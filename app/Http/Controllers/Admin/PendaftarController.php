<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\RiwayatPendaftaran;
use App\Services\MaterializedViewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PendaftarController extends Controller
{
    public function index(Request $request, MaterializedViewService $mvService)
    {
        if ($request->query('action') === 'get_riwayat' && $request->has('id')) {
            return $this->getRiwayatJson((int) $request->query('id'));
        }

        $statusFilter = $request->query('status', 'all');
        if (!in_array($statusFilter, ['all', 'pending', 'accepted', 'rejected'], true)) {
            $statusFilter = 'all';
        }
        $search = trim((string) $request->query('search', ''));
        $tanggalDari = trim((string) $request->query('tanggal_dari', ''));
        $tanggalSampai = trim((string) $request->query('tanggal_sampai', ''));
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }
        $currentPage = max(1, (int) $request->query('page', 1));
        $offset = ($currentPage - 1) * $perPage;

        $stats = $mvService->getPendaftarPerStatus();
        $totalAll = array_sum($stats);

        $query = Pendaftaran::query();
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'ILIKE', "%{$search}%")
                  ->orWhere('nim', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }
        if ($tanggalDari !== '') {
            $query->whereDate('tanggal_daftar', '>=', $tanggalDari);
        }
        if ($tanggalSampai !== '') {
            $query->whereDate('tanggal_daftar', '<=', $tanggalSampai);
        }

        $totalRecords = (clone $query)->count();
        $totalPages = $totalRecords > 0 ? (int) ceil($totalRecords / $perPage) : 0;

        $pendaftarList = $query->orderByDesc('tanggal_daftar')
            ->offset($offset)
            ->limit($perPage)
            ->get()
            ->toArray();

        return view('admin.pendaftar.index', [
            'current_page' => 'pendaftar',
            'page_title' => 'Kelola Pendaftar - Admin Lab SE',
            'stats' => $stats,
            'totalAll' => $totalAll,
            'pendaftarList' => $pendaftarList,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'tanggalDari' => $tanggalDari,
            'tanggalSampai' => $tanggalSampai,
            'perPage' => $perPage,
            'currentPage' => $currentPage,
            'totalRecords' => $totalRecords,
            'totalPages' => $totalPages,
        ]);
    }

    public function handleAction(Request $request, MaterializedViewService $mvService)
    {
        $action = $request->input('action', '');
        $userId = (int) (Auth::id() ?? session('user_id', 1));

        if ($action === 'update_status') {
            $id = (int) $request->input('id', 0);
            $newStatus = $request->input('status', 'pending');
            $catatan = trim((string) $request->input('catatan', ''));

            $pendaftar = Pendaftaran::find($id);
            if ($pendaftar && in_array($newStatus, ['pending', 'accepted', 'rejected'], true)) {
                $oldStatus = $pendaftar->status;
                $pendaftar->update([
                    'status' => $newStatus,
                    'tanggal_review' => in_array($newStatus, ['accepted', 'rejected'], true) ? now() : $pendaftar->tanggal_review,
                ]);
                RiwayatPendaftaran::create([
                    'pendaftaran_id' => $id,
                    'status_lama' => $oldStatus,
                    'status_baru' => $newStatus,
                    'catatan' => $catatan !== '' ? $catatan : "Status diubah menjadi {$newStatus}",
                    'changed_by' => $userId ?: null,
                    'changed_at' => now(),
                ]);
                $mvService->refreshViewsComplete();

                return redirect()->back()
                    ->with('pesan', 'Status pendaftar berhasil diperbarui!')
                    ->with('tipe_pesan', 'success');
            }
        }

        if ($action === 'update_pendaftar') {
            $id = (int) $request->input('id', 0);
            $pendaftar = Pendaftaran::find($id);
            if ($pendaftar) {
                $oldStatus = $pendaftar->status;
                $newStatus = $request->input('status', $oldStatus);
                $fotoPath = $request->input('foto_old', $pendaftar->foto);

                if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                    $file = $request->file('foto');
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) && $file->getSize() <= 2 * 1024 * 1024) {
                        $uploadDir = public_path('uploads/pendaftar');
                        if (!is_dir($uploadDir)) {
                            @mkdir($uploadDir, 0775, true);
                        }
                        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName) ?: 'pendaftar';
                        $newName = $safeBase . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                        $file->move($uploadDir, $newName);
                        $fotoPath = 'uploads/pendaftar/' . $newName;
                    }
                }

                $pendaftar->update([
                    'nama' => trim((string) $request->input('nama', $pendaftar->nama)),
                    'nim' => trim((string) $request->input('nim', $pendaftar->nim)),
                    'email' => trim((string) $request->input('email', $pendaftar->email)),
                    'foto' => $fotoPath,
                    'status' => $newStatus,
                    'catatan_admin' => trim((string) $request->input('catatan_admin', '')),
                    'tanggal_review' => in_array($newStatus, ['accepted', 'rejected'], true) ? now() : $pendaftar->tanggal_review,
                ]);

                if ($oldStatus !== $newStatus) {
                    RiwayatPendaftaran::create([
                        'pendaftaran_id' => $id,
                        'status_lama' => $oldStatus,
                        'status_baru' => $newStatus,
                        'catatan' => trim((string) $request->input('catatan_admin', '')) ?: "Status diubah dari {$oldStatus} ke {$newStatus}",
                        'changed_by' => $userId ?: null,
                        'changed_at' => now(),
                    ]);
                }

                $mvService->refreshViewsComplete();
                return redirect()->back()
                    ->with('pesan', 'Data pendaftar berhasil diperbarui!')
                    ->with('tipe_pesan', 'success');
            }
        }

        if ($action === 'delete_pendaftar') {
            $id = (int) $request->input('id', 0);
            $pendaftar = Pendaftaran::find($id);
            if ($pendaftar) {
                $pendaftar->delete();
                $mvService->refreshViewsComplete();
                return redirect()->back()
                    ->with('pesan', 'Data pendaftar berhasil dihapus!')
                    ->with('tipe_pesan', 'success');
            }
        }

        if ($action === 'bulk_action') {
            $bulkType = $request->input('bulk_action_type', '');
            $selectedIds = (array) $request->input('selected_ids', []);
            $ids = array_values(array_filter(array_map('intval', $selectedIds)));

            if (!empty($ids) && in_array($bulkType, ['accept', 'reject', 'delete'], true)) {
                if ($bulkType === 'delete') {
                    Pendaftaran::whereIn('id', $ids)->delete();
                } else {
                    $targetStatus = $bulkType === 'accept' ? 'accepted' : 'rejected';
                    $rows = Pendaftaran::whereIn('id', $ids)->get();
                    foreach ($rows as $row) {
                        $oldStatus = $row->status;
                        $row->update([
                            'status' => $targetStatus,
                            'tanggal_review' => now(),
                        ]);
                        RiwayatPendaftaran::create([
                            'pendaftaran_id' => $row->id,
                            'status_lama' => $oldStatus,
                            'status_baru' => $targetStatus,
                            'catatan' => "Bulk action: {$targetStatus}",
                            'changed_by' => $userId ?: null,
                            'changed_at' => now(),
                        ]);
                    }
                }
                $mvService->refreshViewsComplete();
                return redirect()->back()
                    ->with('pesan', 'Bulk action berhasil dijalankan pada ' . count($ids) . ' data!')
                    ->with('tipe_pesan', 'success');
            }
        }

        return redirect()->back();
    }

    protected function getRiwayatJson(int $id)
    {
        $pendaftar = Pendaftaran::find($id);
        if (!$pendaftar) {
            return response()->json(['success' => false, 'message' => 'Data pendaftar tidak ditemukan']);
        }

        $riwayat = DB::table('riwayat_pendaftaran as r')
            ->leftJoin('users as u', 'r.changed_by', '=', 'u.id')
            ->select('r.*', 'u.username as changed_by_username')
            ->where('r.pendaftaran_id', $id)
            ->orderByDesc('r.changed_at')
            ->get()
            ->map(fn($r) => (array) $r)
            ->all();

        return response()->json([
            'success' => true,
            'pendaftar' => $pendaftar->toArray(),
            'riwayat' => $riwayat,
        ]);
    }

    public function historyLog(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 25);
        $currentPage = max(1, (int) $request->query('page', 1));
        $offset = ($currentPage - 1) * $perPage;

        $logs = [];
        $totalLogs = 0;
        $totalPages = 0;

        try {
            $query = DB::table('riwayat_pendaftaran as r')
                ->leftJoin('pendaftaran as p', 'r.pendaftaran_id', '=', 'p.id')
                ->leftJoin('users as u', 'r.changed_by', '=', 'u.id')
                ->select(
                    'r.id',
                    'r.pendaftaran_id',
                    'p.nama',
                    'p.nim',
                    'r.status_lama',
                    'r.status_baru',
                    'r.catatan',
                    'r.changed_at',
                    'u.username as changed_by_username'
                );

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('p.nama', 'ILIKE', "%{$search}%")
                      ->orWhere('p.nim', 'ILIKE', "%{$search}%")
                      ->orWhere('r.catatan', 'ILIKE', "%{$search}%")
                      ->orWhere('u.username', 'ILIKE', "%{$search}%");
                });
            }

            $totalLogs = (clone $query)->count();
            $totalPages = $totalLogs > 0 ? (int) ceil($totalLogs / $perPage) : 0;

            $logs = $query->orderByDesc('r.changed_at')
                ->offset($offset)
                ->limit($perPage)
                ->get()
                ->map(fn($r) => (array) $r)
                ->all();
        } catch (Throwable $e) {
            // Keep defaults
        }

        return view('admin.pendaftar.history_log', [
            'current_page' => 'pendaftar',
            'page_title' => 'History Log Pendaftar - Admin Lab SE',
            'logs' => $logs,
            'search' => $search,
            'totalLogs' => $totalLogs,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
        ]);
    }
}
