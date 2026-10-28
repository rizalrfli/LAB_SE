<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Jabatan;
use App\Services\MaterializedViewService;
use Illuminate\Http\Request;
use Throwable;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', $request->query('q', '')));
        $totalAnggota = 0;
        $anggotaBerjabatan = 0;
        $anggotaList = [];

        try {
            $totalAnggota = Anggota::count();
            $anggotaBerjabatan = Anggota::whereNotNull('jabatan')->where('jabatan', '!=', '')->count();

            $query = Anggota::query();
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'ILIKE', "%{$search}%")
                      ->orWhere('jabatan', 'ILIKE', "%{$search}%");
                });
            }
            $anggotaList = $query->orderByDesc('id')->get()->toArray();
        } catch (Throwable $e) {
            // Keep defaults
        }

        return view('admin.anggota.index', [
            'current_page' => 'anggota',
            'page_title' => 'Kelola Anggota - Admin Lab SE',
            'search' => $search,
            'totalAnggota' => $totalAnggota,
            'anggotaBerjabatan' => $anggotaBerjabatan,
            'anggotaTanpaJabatan' => max(0, $totalAnggota - $anggotaBerjabatan),
            'anggotaList' => $anggotaList,
        ]);
    }

    public function create()
    {
        $jabatanList = [];
        try {
            $jabatanList = Jabatan::orderBy('nama_jabatan', 'asc')->pluck('nama_jabatan')->toArray();
        } catch (Throwable $e) {
            // Keep empty
        }

        return view('admin.anggota.tambah', [
            'current_page' => 'anggota',
            'page_title' => 'Tambah Anggota - Admin Lab SE',
            'jabatanList' => $jabatanList,
        ]);
    }

    public function store(Request $request, MaterializedViewService $mvService)
    {
        $nama = trim((string) $request->input('nama', ''));
        $jabatan = trim((string) $request->input('jabatan', ''));
        $deskripsi = trim((string) $request->input('deskripsi', ''));
        $instagram = trim((string) $request->input('instagram', ''));
        $linkedin = trim((string) $request->input('linkedin', ''));
        $nip = trim((string) $request->input('nip', ''));
        $sintaUrl = trim((string) $request->input('sinta_url', ''));
        $urutan = (int) $request->input('urutan', 0);

        if ($nama === '' || mb_strlen($nama) < 3) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Nama anggota wajib diisi (minimal 3 karakter)!')
                ->with('tipe_pesan', 'error');
        }

        $fotoRelativePath = null;
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                return redirect()->back()
                    ->withInput()
                    ->with('pesan', 'Format foto tidak didukung! Gunakan JPG, JPEG, PNG, atau WEBP.')
                    ->with('tipe_pesan', 'error');
            }
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()
                    ->withInput()
                    ->with('pesan', 'Ukuran foto maksimal 2MB!')
                    ->with('tipe_pesan', 'error');
            }

            $uploadDir = public_path('uploads/anggota');
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0775, true);
            }
            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName) ?: 'anggota';
            $newName = $safeBase . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            $file->move($uploadDir, $newName);
            $fotoRelativePath = 'uploads/anggota/' . $newName;
        }

        try {
            Anggota::create([
                'nama' => $nama,
                'jabatan' => $jabatan !== '' ? $jabatan : null,
                'nip' => $nip !== '' ? $nip : null,
                'sinta_url' => $sintaUrl !== '' ? $sintaUrl : null,
                'urutan' => $urutan,
                'deskripsi' => $deskripsi !== '' ? $deskripsi : null,
                'instagram' => $instagram !== '' ? $instagram : null,
                'linkedin' => $linkedin !== '' ? $linkedin : null,
                'foto' => $fotoRelativePath,
            ]);
            $mvService->refreshViewsComplete();

            return redirect()->route('admin.anggota.index')
                ->with('pesan', 'Anggota berhasil ditambahkan!')
                ->with('tipe_pesan', 'success');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Gagal menambahkan anggota: ' . $e->getMessage())
                ->with('tipe_pesan', 'error');
        }
    }

    public function edit(Request $request, $id = null)
    {
        $anggotaId = (int) ($id ?? $request->query('id', 0));
        $anggota = Anggota::find($anggotaId);
        if (!$anggota) {
            return redirect()->route('admin.anggota.index')
                ->with('pesan', 'Data anggota tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        $jabatanList = Jabatan::orderBy('nama_jabatan', 'asc')->pluck('nama_jabatan')->toArray();

        return view('admin.anggota.edit', [
            'current_page' => 'anggota',
            'page_title' => 'Edit Anggota - Admin Lab SE',
            'anggota' => $anggota->toArray(),
            'jabatanList' => $jabatanList,
        ]);
    }

    public function update(Request $request, MaterializedViewService $mvService, $id = null)
    {
        $anggotaId = (int) ($id ?? $request->input('id', $request->query('id', 0)));
        $anggota = Anggota::find($anggotaId);
        if (!$anggota) {
            return redirect()->route('admin.anggota.index')
                ->with('pesan', 'Data anggota tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        $nama = trim((string) $request->input('nama', ''));
        $jabatan = trim((string) $request->input('jabatan', ''));
        $deskripsi = trim((string) $request->input('deskripsi', ''));
        $instagram = trim((string) $request->input('instagram', ''));
        $linkedin = trim((string) $request->input('linkedin', ''));
        $nip = trim((string) $request->input('nip', ''));
        $sintaUrl = trim((string) $request->input('sinta_url', ''));
        $urutan = (int) $request->input('urutan', 0);

        if ($nama === '' || mb_strlen($nama) < 3) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Nama anggota wajib diisi (minimal 3 karakter)!')
                ->with('tipe_pesan', 'error');
        }

        $fotoRelativePath = $anggota->foto;
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                return redirect()->back()
                    ->withInput()
                    ->with('pesan', 'Format foto tidak didukung! Gunakan JPG, JPEG, PNG, atau WEBP.')
                    ->with('tipe_pesan', 'error');
            }
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()
                    ->withInput()
                    ->with('pesan', 'Ukuran foto maksimal 2MB!')
                    ->with('tipe_pesan', 'error');
            }

            $uploadDir = public_path('uploads/anggota');
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0775, true);
            }
            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName) ?: 'anggota';
            $newName = $safeBase . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            $file->move($uploadDir, $newName);
            $fotoRelativePath = 'uploads/anggota/' . $newName;
        }

        try {
            $anggota->update([
                'nama' => $nama,
                'jabatan' => $jabatan !== '' ? $jabatan : null,
                'nip' => $nip !== '' ? $nip : null,
                'sinta_url' => $sintaUrl !== '' ? $sintaUrl : null,
                'urutan' => $urutan,
                'deskripsi' => $deskripsi !== '' ? $deskripsi : null,
                'instagram' => $instagram !== '' ? $instagram : null,
                'linkedin' => $linkedin !== '' ? $linkedin : null,
                'foto' => $fotoRelativePath,
            ]);
            $mvService->refreshViewsComplete();

            return redirect()->route('admin.anggota.index')
                ->with('pesan', 'Data anggota berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Gagal memperbarui anggota: ' . $e->getMessage())
                ->with('tipe_pesan', 'error');
        }
    }

    public function destroy(Request $request, MaterializedViewService $mvService, $id = null)
    {
        $anggotaId = (int) ($id ?? $request->query('id', $request->input('id', 0)));
        $anggota = Anggota::find($anggotaId);

        if (!$anggota) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Data anggota tidak ditemukan!'], 404);
            }
            return redirect()->route('admin.anggota.index')
                ->with('pesan', 'Data anggota tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        $nama = $anggota->nama;
        $anggota->delete();
        $mvService->refreshViewsComplete();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Anggota \"{$nama}\" berhasil dihapus!",
                'id' => $anggotaId,
                'nama' => $nama,
            ]);
        }

        return redirect()->route('admin.anggota.index')
            ->with('pesan', "Anggota \"{$nama}\" berhasil dihapus!")
            ->with('tipe_pesan', 'success');
    }
}
