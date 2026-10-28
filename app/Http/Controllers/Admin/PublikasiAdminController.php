<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publikasi;
use App\Services\MaterializedViewService;
use App\Services\StoredProcedureService;
use Illuminate\Http\Request;

class PublikasiAdminController extends Controller
{
    public function index(Request $request, StoredProcedureService $spService, MaterializedViewService $mvService)
    {
        if ($request->has('hapus') && $request->has('id')) {
            $id = (int) $request->query('id', 0);
            $spService->deletePublikasi($id);
            $mvService->refreshViewsComplete();
            return redirect()->route('admin.publikasi.index')
                ->with('pesan', 'Data publikasi berhasil dihapus!')
                ->with('tipe_pesan', 'success');
        }

        $search = trim((string) $request->query('search', $request->query('q', '')));
        $totalPublikasi = Publikasi::count();
        $journalCount = Publikasi::where('tipe', 'journal')->count();
        $conferenceCount = max(0, $totalPublikasi - $journalCount);

        $query = Publikasi::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'ILIKE', "%{$search}%")
                  ->orWhere('penulis_utama', 'ILIKE', "%{$search}%")
                  ->orWhere('nama_journal_conference', 'ILIKE', "%{$search}%");
            });
        }

        $publikasiList = $query->orderByDesc('tahun')->orderByDesc('id')->get()->toArray();

        return view('admin.publikasi.index', [
            'current_page' => 'publikasi',
            'page_title' => 'Kelola Publikasi - Admin Lab SE',
            'search' => $search,
            'totalPublikasi' => $totalPublikasi,
            'journalCount' => $journalCount,
            'conferenceCount' => $conferenceCount,
            'publikasiList' => $publikasiList,
        ]);
    }

    public function create()
    {
        return view('admin.publikasi.tambah', [
            'current_page' => 'publikasi',
            'page_title' => 'Tambah Publikasi - Admin Lab SE',
        ]);
    }

    public function store(Request $request, StoredProcedureService $spService, MaterializedViewService $mvService)
    {
        $judul = trim((string) $request->input('judul', ''));
        $tipe = $request->input('tipe', 'journal');
        $tahun = (int) $request->input('tahun', date('Y'));

        if ($judul === '' || mb_strlen($judul) < 5) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Judul publikasi wajib diisi (minimal 5 karakter)!')
                ->with('tipe_pesan', 'error');
        }

        $data = [
            'judul' => $judul,
            'tipe' => $tipe,
            'tahun' => $tahun,
            'penulis_utama' => trim((string) $request->input('penulis_utama', '')) ?: null,
            'daftar_penulis' => trim((string) $request->input('daftar_penulis', '')) ?: null,
            'nama_journal_conference' => trim((string) $request->input('nama_journal_conference', '')) ?: null,
            'volume' => trim((string) $request->input('volume', '')) ?: null,
            'issue' => trim((string) $request->input('issue', '')) ?: null,
            'halaman' => trim((string) $request->input('halaman', '')) ?: null,
            'doi' => trim((string) $request->input('doi', '')) ?: null,
            'abstract' => trim((string) $request->input('abstract', '')) ?: null,
            'keywords' => trim((string) $request->input('keywords', '')) ?: null,
            'link_url' => trim((string) $request->input('link_url', '')) ?: null,
            'status' => $request->input('status', 'published'),
            'scopus_indexed' => $request->has('scopus_indexed'),
            'quartile' => trim((string) $request->input('quartile', '')) ?: null,
            'tanggal_publikasi' => $request->input('tanggal_publikasi') ?: null,
            'sitasi' => (int) $request->input('sitasi', 0),
            'tautan_scholar' => trim((string) $request->input('tautan_scholar', '')) ?: null,
        ];

        $spService->insertPublikasi($data);
        $mvService->refreshViewsComplete();

        return redirect()->route('admin.publikasi.index')
            ->with('pesan', 'Data publikasi berhasil ditambahkan!')
            ->with('tipe_pesan', 'success');
    }

    public function edit(Request $request, $id = null)
    {
        $publikasiId = (int) ($id ?? $request->query('id', 0));
        $publikasi = Publikasi::find($publikasiId);
        if (!$publikasi) {
            return redirect()->route('admin.publikasi.index')
                ->with('pesan', 'Data publikasi tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        return view('admin.publikasi.edit', [
            'current_page' => 'publikasi',
            'page_title' => 'Edit Publikasi - Admin Lab SE',
            'publikasi' => $publikasi->toArray(),
        ]);
    }

    public function update(Request $request, StoredProcedureService $spService, MaterializedViewService $mvService, $id = null)
    {
        $publikasiId = (int) ($id ?? $request->input('id', $request->query('id', 0)));
        $judul = trim((string) $request->input('judul', ''));
        $tipe = $request->input('tipe', 'journal');
        $tahun = (int) $request->input('tahun', date('Y'));

        if ($judul === '' || mb_strlen($judul) < 5) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Judul publikasi wajib diisi (minimal 5 karakter)!')
                ->with('tipe_pesan', 'error');
        }

        $data = [
            'judul' => $judul,
            'tipe' => $tipe,
            'tahun' => $tahun,
            'penulis_utama' => trim((string) $request->input('penulis_utama', '')) ?: null,
            'daftar_penulis' => trim((string) $request->input('daftar_penulis', '')) ?: null,
            'nama_journal_conference' => trim((string) $request->input('nama_journal_conference', '')) ?: null,
            'volume' => trim((string) $request->input('volume', '')) ?: null,
            'issue' => trim((string) $request->input('issue', '')) ?: null,
            'halaman' => trim((string) $request->input('halaman', '')) ?: null,
            'doi' => trim((string) $request->input('doi', '')) ?: null,
            'abstract' => trim((string) $request->input('abstract', '')) ?: null,
            'keywords' => trim((string) $request->input('keywords', '')) ?: null,
            'link_url' => trim((string) $request->input('link_url', '')) ?: null,
            'status' => $request->input('status', 'published'),
            'scopus_indexed' => $request->has('scopus_indexed'),
            'quartile' => trim((string) $request->input('quartile', '')) ?: null,
            'tanggal_publikasi' => $request->input('tanggal_publikasi') ?: null,
            'sitasi' => (int) $request->input('sitasi', 0),
            'tautan_scholar' => trim((string) $request->input('tautan_scholar', '')) ?: null,
        ];

        $spService->updatePublikasi($publikasiId, $data);
        $mvService->refreshViewsComplete();

        return redirect()->route('admin.publikasi.index')
            ->with('pesan', 'Data publikasi berhasil diperbarui!')
            ->with('tipe_pesan', 'success');
    }
}
