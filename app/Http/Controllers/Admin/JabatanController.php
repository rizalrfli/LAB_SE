<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', $request->query('q', '')));
        $totalJabatan = 0;
        $jabatanTerpakai = 0;
        $jabatanList = [];

        try {
            $totalJabatan = Jabatan::count();
            $jabatanTerpakai = Anggota::whereNotNull('jabatan')
                ->where('jabatan', '!=', '')
                ->distinct('jabatan')
                ->count('jabatan');

            $sql = "
                SELECT j.id, j.nama_jabatan, j.created_at, COUNT(a.id) as jumlah_anggota
                FROM jabatan j
                LEFT JOIN anggota a ON a.jabatan = j.nama_jabatan AND a.jabatan IS NOT NULL AND a.jabatan != ''
            ";
            $bindings = [];
            if ($search !== '') {
                $sql .= " WHERE j.nama_jabatan ILIKE ?";
                $bindings[] = "%{$search}%";
            }
            $sql .= " GROUP BY j.id, j.nama_jabatan, j.created_at ORDER BY j.id DESC";

            $rows = DB::select($sql, $bindings);
            $jabatanList = array_map(fn($r) => (array) $r, $rows);
        } catch (Throwable $e) {
            // Keep defaults
        }

        return view('admin.jabatan.index', [
            'current_page' => 'jabatan',
            'page_title' => 'Kelola Jabatan - Admin Lab SE',
            'search' => $search,
            'totalJabatan' => $totalJabatan,
            'jabatanTerpakai' => $jabatanTerpakai,
            'jabatanKosong' => max(0, $totalJabatan - $jabatanTerpakai),
            'jabatanList' => $jabatanList,
        ]);
    }

    public function create()
    {
        return view('admin.jabatan.tambah', [
            'current_page' => 'jabatan',
            'page_title' => 'Tambah Jabatan - Admin Lab SE',
        ]);
    }

    public function store(Request $request)
    {
        $namaJabatan = trim((string) $request->input('nama_jabatan', ''));
        if ($namaJabatan === '' || mb_strlen($namaJabatan) < 3) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Nama jabatan wajib diisi (minimal 3 karakter)!')
                ->with('tipe_pesan', 'error');
        }

        $exists = Jabatan::whereRaw('LOWER(nama_jabatan) = LOWER(?)', [$namaJabatan])->exists();
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Nama jabatan sudah ada!')
                ->with('tipe_pesan', 'warning');
        }

        Jabatan::create(['nama_jabatan' => $namaJabatan]);

        return redirect()->route('admin.jabatan.index')
            ->with('pesan', 'Jabatan berhasil ditambahkan!')
            ->with('tipe_pesan', 'success');
    }

    public function edit(Request $request, $id = null)
    {
        $jabatanId = (int) ($id ?? $request->query('id', 0));
        $jabatan = Jabatan::find($jabatanId);
        if (!$jabatan) {
            return redirect()->route('admin.jabatan.index')
                ->with('pesan', 'Data jabatan tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        $jumlahAnggota = Anggota::where('jabatan', $jabatan->nama_jabatan)->count();

        return view('admin.jabatan.edit', [
            'current_page' => 'jabatan',
            'page_title' => 'Edit Jabatan - Admin Lab SE',
            'jabatan' => $jabatan->toArray(),
            'jumlahAnggota' => $jumlahAnggota,
        ]);
    }

    public function update(Request $request, $id = null)
    {
        $jabatanId = (int) ($id ?? $request->input('id', $request->query('id', 0)));
        $jabatan = Jabatan::find($jabatanId);
        if (!$jabatan) {
            return redirect()->route('admin.jabatan.index')
                ->with('pesan', 'Data jabatan tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        $namaJabatan = trim((string) $request->input('nama_jabatan', ''));
        if ($namaJabatan === '' || mb_strlen($namaJabatan) < 3) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Nama jabatan wajib diisi (minimal 3 karakter)!')
                ->with('tipe_pesan', 'error');
        }

        $exists = Jabatan::whereRaw('LOWER(nama_jabatan) = LOWER(?)', [$namaJabatan])
            ->where('id', '!=', $jabatanId)
            ->exists();
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Nama jabatan sudah digunakan!')
                ->with('tipe_pesan', 'warning');
        }

        $oldName = $jabatan->nama_jabatan;
        $jabatan->update(['nama_jabatan' => $namaJabatan]);
        if ($oldName !== $namaJabatan) {
            Anggota::where('jabatan', $oldName)->update(['jabatan' => $namaJabatan]);
        }

        return redirect()->route('admin.jabatan.index')
            ->with('pesan', 'Jabatan berhasil diperbarui!')
            ->with('tipe_pesan', 'success');
    }

    public function destroy(Request $request, $id = null)
    {
        $jabatanId = (int) ($id ?? $request->query('id', $request->input('id', 0)));
        $jabatan = Jabatan::find($jabatanId);

        if (!$jabatan) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Data jabatan tidak ditemukan!'], 404);
            }
            return redirect()->route('admin.jabatan.index')
                ->with('pesan', 'Data jabatan tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        $nama = $jabatan->nama_jabatan;
        $jabatan->delete();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Jabatan \"{$nama}\" berhasil dihapus!",
                'id' => $jabatanId,
                'nama' => $nama,
            ]);
        }

        return redirect()->route('admin.jabatan.index')
            ->with('pesan', "Jabatan \"{$nama}\" berhasil dihapus!")
            ->with('tipe_pesan', 'success');
    }
}
