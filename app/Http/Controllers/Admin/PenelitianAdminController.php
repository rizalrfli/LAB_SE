<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penelitian;
use App\Services\MaterializedViewService;
use App\Services\StoredProcedureService;
use Illuminate\Http\Request;

class PenelitianAdminController extends Controller
{
    public function index(Request $request, StoredProcedureService $spService, MaterializedViewService $mvService)
    {
        if ($request->has('hapus') && $request->has('id')) {
            $id = (int) $request->query('id', 0);
            $spService->deletePenelitian($id);
            $mvService->refreshViewsComplete();
            return redirect()->route('admin.penelitian.index')
                ->with('pesan', 'Data penelitian berhasil dihapus!')
                ->with('tipe_pesan', 'success');
        }

        $search = trim((string) $request->query('search', $request->query('q', '')));
        $totalPenelitian = Penelitian::count();
        $ongoingCount = Penelitian::where('status', 'ongoing')->count();
        $completedCount = max(0, $totalPenelitian - $ongoingCount);

        $query = Penelitian::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'ILIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'ILIKE', "%{$search}%")
                  ->orWhere('peneliti_utama', 'ILIKE', "%{$search}%")
                  ->orWhere('kategori', 'ILIKE', "%{$search}%");
            });
        }

        $penelitianList = $query->orderByDesc('tahun')->orderByDesc('id')->get()->toArray();

        return view('admin.penelitian.index', [
            'current_page' => 'penelitian',
            'page_title' => 'Kelola Penelitian - Admin Lab SE',
            'search' => $search,
            'totalPenelitian' => $totalPenelitian,
            'ongoingCount' => $ongoingCount,
            'completedCount' => $completedCount,
            'penelitianList' => $penelitianList,
        ]);
    }

    public function create()
    {
        return view('admin.penelitian.tambah', [
            'current_page' => 'penelitian',
            'page_title' => 'Tambah Penelitian - Admin Lab SE',
        ]);
    }

    public function store(Request $request, StoredProcedureService $spService, MaterializedViewService $mvService)
    {
        $judul = trim((string) $request->input('judul', ''));
        $tahun = (int) $request->input('tahun', date('Y'));

        if ($judul === '' || mb_strlen($judul) < 5) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Judul penelitian wajib diisi (minimal 5 karakter)!')
                ->with('tipe_pesan', 'error');
        }

        $data = [
            'judul' => $judul,
            'deskripsi' => trim((string) $request->input('deskripsi', '')) ?: null,
            'tahun' => $tahun,
            'status' => $request->input('status', 'ongoing'),
            'jumlah_tim' => (int) $request->input('jumlah_tim', 0),
            'peneliti_utama' => trim((string) $request->input('peneliti_utama', '')) ?: null,
            'anggota_tim' => trim((string) $request->input('anggota_tim', '')) ?: null,
            'dosen_pembimbing' => trim((string) $request->input('dosen_pembimbing', '')) ?: null,
            'kategori' => trim((string) $request->input('kategori', '')) ?: null,
            'metodologi' => trim((string) $request->input('metodologi', '')) ?: null,
            'tanggal_mulai' => $request->input('tanggal_mulai') ?: null,
            'tanggal_selesai' => $request->input('tanggal_selesai') ?: null,
            'progress' => max(0, min(100, (int) $request->input('progress', 0))),
            'keywords' => trim((string) $request->input('keywords', '')) ?: null,
            'link_dokumen' => trim((string) $request->input('link_dokumen', '')) ?: null,
            'output_hasil' => trim((string) $request->input('output_hasil', '')) ?: null,
        ];

        $spService->insertPenelitian($data);
        $mvService->refreshViewsComplete();

        return redirect()->route('admin.penelitian.index')
            ->with('pesan', 'Data penelitian berhasil ditambahkan!')
            ->with('tipe_pesan', 'success');
    }

    public function edit(Request $request, $id = null)
    {
        $penelitianId = (int) ($id ?? $request->query('id', 0));
        $penelitian = Penelitian::find($penelitianId);
        if (!$penelitian) {
            return redirect()->route('admin.penelitian.index')
                ->with('pesan', 'Data penelitian tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        return view('admin.penelitian.edit', [
            'current_page' => 'penelitian',
            'page_title' => 'Edit Penelitian - Admin Lab SE',
            'penelitian' => $penelitian->toArray(),
        ]);
    }

    public function update(Request $request, StoredProcedureService $spService, MaterializedViewService $mvService, $id = null)
    {
        $penelitianId = (int) ($id ?? $request->input('id', $request->query('id', 0)));
        $judul = trim((string) $request->input('judul', ''));
        $tahun = (int) $request->input('tahun', date('Y'));

        if ($judul === '' || mb_strlen($judul) < 5) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Judul penelitian wajib diisi (minimal 5 karakter)!')
                ->with('tipe_pesan', 'error');
        }

        $data = [
            'judul' => $judul,
            'deskripsi' => trim((string) $request->input('deskripsi', '')) ?: null,
            'tahun' => $tahun,
            'status' => $request->input('status', 'ongoing'),
            'jumlah_tim' => (int) $request->input('jumlah_tim', 0),
            'peneliti_utama' => trim((string) $request->input('peneliti_utama', '')) ?: null,
            'anggota_tim' => trim((string) $request->input('anggota_tim', '')) ?: null,
            'dosen_pembimbing' => trim((string) $request->input('dosen_pembimbing', '')) ?: null,
            'kategori' => trim((string) $request->input('kategori', '')) ?: null,
            'metodologi' => trim((string) $request->input('metodologi', '')) ?: null,
            'tanggal_mulai' => $request->input('tanggal_mulai') ?: null,
            'tanggal_selesai' => $request->input('tanggal_selesai') ?: null,
            'progress' => max(0, min(100, (int) $request->input('progress', 0))),
            'keywords' => trim((string) $request->input('keywords', '')) ?: null,
            'link_dokumen' => trim((string) $request->input('link_dokumen', '')) ?: null,
            'output_hasil' => trim((string) $request->input('output_hasil', '')) ?: null,
        ];

        $spService->updatePenelitian($penelitianId, $data);
        $mvService->refreshViewsComplete();

        return redirect()->route('admin.penelitian.index')
            ->with('pesan', 'Data penelitian berhasil diperbarui!')
            ->with('tipe_pesan', 'success');
    }
}
