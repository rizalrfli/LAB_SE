<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Services\MaterializedViewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PendaftaranController extends Controller
{
    public function showForm()
    {
        return view('public.pendaftaran.daftar');
    }

    public function submitForm(Request $request, MaterializedViewService $mvService)
    {
        $nama = trim((string) $request->input('name', ''));
        $nim = trim((string) $request->input('nim', ''));
        $email = trim((string) $request->input('email', ''));

        if ($nama === '' || $nim === '' || $email === '') {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Mohon lengkapi semua field yang wajib diisi!')
                ->with('tipe_pesan', 'error');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Format email tidak valid! Mohon masukkan alamat email yang benar.')
                ->with('tipe_pesan', 'error');
        }

        $fotoRelativePath = null;
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $ext = strtolower($file->getClientOriginalExtension());
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $allowedExt, true)) {
                return redirect()->back()
                    ->withInput()
                    ->with('pesan', 'Format foto tidak didukung! Gunakan JPG, JPEG, PNG, atau WEBP.')
                    ->with('tipe_pesan', 'error');
            }

            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()
                    ->withInput()
                    ->with('pesan', 'Ukuran foto terlalu besar! Maksimal 2MB.')
                    ->with('tipe_pesan', 'error');
            }

            $uploadDir = public_path('uploads/pendaftar');
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0775, true);
            }

            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName) ?: 'pendaftar';
            $newName = $safeBase . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;

            $file->move($uploadDir, $newName);
            $fotoRelativePath = 'uploads/pendaftar/' . $newName;
        }

        try {
            $data = [
                'nama' => $nama,
                'nim' => $nim,
                'email' => $email,
                'status' => 'pending',
                'tanggal_daftar' => now(),
            ];
            if ($fotoRelativePath) {
                $data['foto'] = $fotoRelativePath;
            }

            Pendaftaran::create($data);
            $mvService->refreshViewsComplete();

            return redirect()->route('pendaftaran.daftar')
                ->with('pesan', 'Pendaftaran berhasil! Data Anda sedang dalam proses review oleh admin.')
                ->with('tipe_pesan', 'success');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Terjadi kesalahan saat menyimpan pendaftaran: ' . $e->getMessage())
                ->with('tipe_pesan', 'error');
        }
    }

    public function listPendaftar(Request $request, MaterializedViewService $mvService)
    {
        $statusFilter = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 12);
        if (!in_array($perPage, [12, 24, 36, 48], true)) {
            $perPage = 12;
        }
        $currentPage = max(1, (int) $request->query('page', 1));
        $offset = ($currentPage - 1) * $perPage;

        $pendaftarList = [];
        $totalRecords = 0;
        $totalAllPendaftar = 0;
        $totalPages = 0;

        try {
            $mvStats = $mvService->getStatsComplete();
            $totalAllPendaftar = (int) ($mvStats['total_pendaftar'] ?? 0);
            if ($totalAllPendaftar === 0) {
                $totalAllPendaftar = Pendaftaran::count();
            }

            $query = Pendaftaran::query();
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'ILIKE', "%{$search}%")
                      ->orWhere('nim', 'ILIKE', "%{$search}%")
                      ->orWhere('email', 'ILIKE', "%{$search}%");
                });
            }

            $totalRecords = (clone $query)->count();
            $totalPages = $totalRecords > 0 ? (int) ceil($totalRecords / $perPage) : 0;

            $pendaftarList = $query->orderByDesc('tanggal_daftar')
                ->offset($offset)
                ->limit($perPage)
                ->get()
                ->toArray();
        } catch (Throwable $e) {
            // Keep defaults
        }

        return view('public.pendaftaran.pendaftar-list', compact(
            'pendaftarList',
            'totalRecords',
            'totalAllPendaftar',
            'totalPages',
            'currentPage',
            'perPage',
            'search',
            'statusFilter'
        ));
    }
}
