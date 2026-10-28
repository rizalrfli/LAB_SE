<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontenPage;
use App\Models\RoadmapItem;
use App\Models\RoadmapPeriode;
use Illuminate\Http\Request;

class ProfilAdminController extends Controller
{
    protected function upsertKonten(string $pageKey, ?string $title, string $content): void
    {
        KontenPage::updateOrCreate(
            ['page_key' => $pageKey],
            [
                'title' => $title,
                'content' => $content,
                'last_updated' => now(),
            ]
        );
    }

    public function index(Request $request)
    {
        if ($request->has('hapus_misi')) {
            $misiKey = (string) $request->query('hapus_misi');
            KontenPage::where('page_key', $misiKey)->delete();
            return redirect()->route('admin.profil.index')
                ->with('pesan', 'Item misi berhasil dihapus!')
                ->with('tipe_pesan', 'success');
        }

        $allKonten = KontenPage::all()->keyBy('page_key');

        $tentangKami = [
            'title' => $allKonten['tentang_kami']->title ?? 'Tentang Lab SE',
            'content' => $allKonten['tentang_kami']->content ?? '',
        ];

        $features = [
            'tentang_kami_feature_rekayasa' => [
                'title' => $allKonten['tentang_kami_feature_rekayasa']->title ?? 'Rekayasa Perangkat Lunak',
                'content' => $allKonten['tentang_kami_feature_rekayasa']->content ?? '',
                'icon' => 'lni lni-code-alt',
            ],
            'tentang_kami_feature_publikasi' => [
                'title' => $allKonten['tentang_kami_feature_publikasi']->title ?? 'Publikasi Ilmiah',
                'content' => $allKonten['tentang_kami_feature_publikasi']->content ?? '',
                'icon' => 'lni lni-book',
            ],
            'tentang_kami_feature_kemitraan' => [
                'title' => $allKonten['tentang_kami_feature_kemitraan']->title ?? 'Kemitraan Industri',
                'content' => $allKonten['tentang_kami_feature_kemitraan']->content ?? '',
                'icon' => 'lni lni-handshake',
            ],
            'tentang_kami_feature_pembinaan' => [
                'title' => $allKonten['tentang_kami_feature_pembinaan']->title ?? 'Pembinaan Kompetensi',
                'content' => $allKonten['tentang_kami_feature_pembinaan']->content ?? '',
                'icon' => 'lni lni-graduation',
            ],
        ];

        $focusDefaults = [
            'focus_qa' => ['title' => 'Quality Assurance', 'content' => 'Pengujian perangkat lunak berbasis otomatisasi.', 'icon' => 'lni lni-shield'],
            'focus_hci' => ['title' => 'Human-Computer Interaction', 'content' => 'Riset pengalaman pengguna (UX) dan desain antarmuka.', 'icon' => 'lni lni-network'],
            'focus_ml' => ['title' => 'Machine Learning for SE', 'content' => 'Penerapan AI dalam rekayasa perangkat lunak.', 'icon' => 'lni lni-stats-up'],
        ];
        $focusItems = [];
        foreach ($focusDefaults as $fKey => $def) {
            if (isset($allKonten[$fKey])) {
                $decoded = json_decode($allKonten[$fKey]->content, true);
                if (is_array($decoded)) {
                    $focusItems[$fKey] = [
                        'title' => $decoded['title'] ?? $def['title'],
                        'content' => $decoded['content'] ?? $def['content'],
                        'icon' => $decoded['icon'] ?? $def['icon'],
                    ];
                } else {
                    $focusItems[$fKey] = [
                        'title' => $allKonten[$fKey]->title ?: $def['title'],
                        'content' => $allKonten[$fKey]->content ?: $def['content'],
                        'icon' => $def['icon'],
                    ];
                }
            } else {
                $focusItems[$fKey] = $def;
            }
        }

        $visiContent = $allKonten['visi_deskripsi']->content ?? '';
        $misiItems = KontenPage::where('page_key', 'like', 'misi_item_%')
            ->orderBy('page_key', 'asc')
            ->get()
            ->toArray();

        $navbarLogo = $allKonten['navbar_logo']->content ?? 'assets/img/logo/logo.png';
        $footerDeskripsi = $allKonten['footer_deskripsi']->content ?? 'Laboratorium Software Engineering merupakan wadah pengembangan dan riset di bidang Rekayasa Perangkat Lunak.';
        $footerCopyright = $allKonten['footer_copyright']->content ?? '© {YEAR} Laboratorium Software Engineering. All Rights Reserved.';

        $footerSocialLinks = isset($allKonten['footer_social_links']) ? (json_decode($allKonten['footer_social_links']->content, true) ?: []) : [];
        $footerTautanCepat = isset($allKonten['footer_tautan_cepat']) ? (json_decode($allKonten['footer_tautan_cepat']->content, true) ?: []) : [];
        $footerLayanan = isset($allKonten['footer_layanan']) ? (json_decode($allKonten['footer_layanan']->content, true) ?: []) : [];
        $footerInformasi = isset($allKonten['footer_informasi']) ? (json_decode($allKonten['footer_informasi']->content, true) ?: []) : [];

        return view('admin.profil.index', [
            'current_page' => 'profil',
            'page_title' => 'Kelola Profil & Konten - Admin Lab SE',
            'tentangKami' => $tentangKami,
            'features' => $features,
            'focusItems' => $focusItems,
            'visiContent' => $visiContent,
            'misiItems' => $misiItems,
            'navbarLogo' => $navbarLogo,
            'footerDeskripsi' => $footerDeskripsi,
            'footerCopyright' => $footerCopyright,
            'footerSocialLinks' => $footerSocialLinks,
            'footerTautanCepat' => $footerTautanCepat,
            'footerLayanan' => $footerLayanan,
            'footerInformasi' => $footerInformasi,
        ]);
    }

    public function handlePost(Request $request)
    {
        if ($request->has('update_tentang_kami')) {
            $this->upsertKonten(
                'tentang_kami',
                trim((string) $request->input('tentang_kami_title', 'Tentang Lab SE')),
                (string) $request->input('tentang_kami_content', '')
            );
            return redirect()->route('admin.profil.index')
                ->with('pesan', 'Konten Tentang Kami berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('update_feature')) {
            $key = (string) $request->input('feature_key', '');
            if ($key !== '') {
                $this->upsertKonten(
                    $key,
                    trim((string) $request->input('feature_title', '')),
                    trim((string) $request->input('feature_content', ''))
                );
            }
            return redirect()->route('admin.profil.index')
                ->with('pesan', 'Fitur Tentang Kami berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('update_focus_scope')) {
            $key = (string) $request->input('focus_key', '');
            if (in_array($key, ['focus_qa', 'focus_hci', 'focus_ml'], true)) {
                $title = trim((string) $request->input('focus_title', ''));
                $content = trim((string) $request->input('focus_content', ''));
                $icon = trim((string) $request->input('focus_icon', 'lni lni-cog'));
                $json = json_encode(['title' => $title, 'content' => $content, 'icon' => $icon]);
                $this->upsertKonten($key, $title, $json);
            }
            return redirect()->route('admin.profil.index')
                ->with('pesan', 'Focus & Scope berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('update_visi')) {
            $this->upsertKonten(
                'visi_deskripsi',
                'Visi Kami',
                trim((string) $request->input('visi_content', ''))
            );
            return redirect()->route('admin.profil.index')
                ->with('pesan', 'Visi berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('tambah_misi')) {
            $existing = KontenPage::where('page_key', 'like', 'misi_item_%')->pluck('page_key')->toArray();
            $maxNum = 0;
            foreach ($existing as $k) {
                $num = (int) str_replace('misi_item_', '', $k);
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
            $nextKey = 'misi_item_' . ($maxNum + 1);
            $this->upsertKonten($nextKey, 'Misi ' . ($maxNum + 1), trim((string) $request->input('misi_content', '')));
            return redirect()->route('admin.profil.index')
                ->with('pesan', 'Item misi baru berhasil ditambahkan!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('update_misi')) {
            $misiKey = (string) $request->input('misi_key', '');
            if ($misiKey !== '') {
                $this->upsertKonten($misiKey, 'Misi', trim((string) $request->input('misi_content', '')));
            }
            return redirect()->route('admin.profil.index')
                ->with('pesan', 'Item misi berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('update_footer_deskripsi')) {
            $this->upsertKonten('footer_deskripsi', 'Deskripsi Footer', trim((string) $request->input('footer_deskripsi', '')));
            return redirect()->route('admin.profil.index')
                ->with('pesan', 'Deskripsi footer berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('update_footer_copyright')) {
            $this->upsertKonten('footer_copyright', 'Copyright Footer', trim((string) $request->input('footer_copyright', '')));
            return redirect()->route('admin.profil.index')
                ->with('pesan', 'Copyright footer berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('update_footer_links')) {
            $linkType = (string) $request->input('link_type', '');
            $linksJson = (string) $request->input('links_json', '[]');
            if (in_array($linkType, ['social_links', 'tautan_cepat', 'layanan', 'informasi'], true)) {
                $this->upsertKonten('footer_' . $linkType, 'Footer ' . $linkType, $linksJson);
            }
            return redirect()->route('admin.profil.index')
                ->with('pesan', 'Tautan footer berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        }

        return redirect()->route('admin.profil.index');
    }

    public function uploadGambarNavbar(Request $request)
    {
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return response()->json(['success' => false, 'message' => 'File tidak valid'], 400);
        }

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true)) {
            return response()->json(['success' => false, 'message' => 'Format file tidak didukung'], 400);
        }
        if ($file->getSize() > 2 * 1024 * 1024) {
            return response()->json(['success' => false, 'message' => 'Ukuran maksimal 2MB'], 400);
        }

        $uploadDir = public_path('assets/img/logo');
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }
        $newFileName = 'navbar_logo_' . time() . '.' . $ext;
        $file->move($uploadDir, $newFileName);

        $relativePath = 'assets/img/logo/' . $newFileName;
        $this->upsertKonten('navbar_logo', 'Navbar Logo', $relativePath);

        return response()->json([
            'success' => true,
            'message' => 'Logo navbar berhasil diperbarui!',
            'path' => $relativePath,
            'url' => asset($relativePath),
        ]);
    }

    public function uploadGambar(Request $request)
    {
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return response('Error: File tidak valid', 400);
        }
        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            return response('Error: Format tidak didukung', 400);
        }
        $uploadDir = public_path('assets/img/konten');
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }
        $newFileName = uniqid('img_', true) . '.' . $ext;
        $file->move($uploadDir, $newFileName);

        return response(asset('assets/img/konten/' . $newFileName), 200);
    }

    public function browseFile()
    {
        $dir = public_path('assets/img/konten');
        $files = [];
        if (is_dir($dir)) {
            foreach (scandir($dir) as $f) {
                if ($f !== '.' && $f !== '..' && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f)) {
                    $files[] = asset('assets/img/konten/' . $f);
                }
            }
        }
        return view('admin.profil.browse_file', compact('files'));
    }

    public function kelolaRoadmap(Request $request)
    {
        if ($request->has('hapus_periode')) {
            $idPeriode = (int) $request->query('hapus_periode');
            RoadmapItem::where('id_periode', $idPeriode)->delete();
            RoadmapPeriode::where('id', $idPeriode)->delete();
            return redirect()->route('admin.roadmap.index')
                ->with('pesan', 'Periode roadmap berhasil dihapus!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('hapus_tugas')) {
            $idTugas = (int) $request->query('hapus_tugas');
            $kelolaTugas = (int) $request->query('kelola_tugas', 0);
            RoadmapItem::where('id', $idTugas)->delete();
            return redirect()->route('admin.roadmap.index', ['kelola_tugas' => $kelolaTugas])
                ->with('pesan', 'Tugas roadmap berhasil dihapus!')
                ->with('tipe_pesan', 'success');
        }

        $periodes = RoadmapPeriode::orderBy('urutan', 'asc')->get()->toArray();
        $selectedPeriodeId = (int) $request->query('kelola_tugas', 0);
        $selectedPeriode = null;
        $tugasList = [];

        if ($selectedPeriodeId > 0) {
            $pModel = RoadmapPeriode::find($selectedPeriodeId);
            if ($pModel) {
                $selectedPeriode = $pModel->toArray();
                $tugasList = RoadmapItem::where('id_periode', $selectedPeriodeId)
                    ->orderBy('urutan', 'asc')
                    ->get()
                    ->toArray();
            }
        }

        return view('admin.profil.kelola_roadmap', [
            'current_page' => 'roadmap',
            'page_title' => 'Kelola Roadmap - Admin Lab SE',
            'periodes' => $periodes,
            'selectedPeriode' => $selectedPeriode,
            'tugasList' => $tugasList,
        ]);
    }

    public function handleRoadmapPost(Request $request)
    {
        if ($request->has('tambah_periode')) {
            RoadmapPeriode::create([
                'tahun_periode' => trim((string) $request->input('tahun_periode', '')),
                'judul_fase' => trim((string) $request->input('judul_fase', '')),
                'urutan' => (int) $request->input('urutan', 1),
            ]);
            return redirect()->route('admin.roadmap.index')
                ->with('pesan', 'Periode roadmap berhasil ditambahkan!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('edit_periode')) {
            $id = (int) $request->input('id_periode', 0);
            RoadmapPeriode::where('id', $id)->update([
                'tahun_periode' => trim((string) $request->input('tahun_periode', '')),
                'judul_fase' => trim((string) $request->input('judul_fase', '')),
                'urutan' => (int) $request->input('urutan', 1),
            ]);
            return redirect()->route('admin.roadmap.index')
                ->with('pesan', 'Periode roadmap berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('tambah_tugas')) {
            $idPeriode = (int) $request->input('id_periode', 0);
            RoadmapItem::create([
                'id_periode' => $idPeriode,
                'deskripsi_tugas' => trim((string) $request->input('deskripsi_tugas', '')),
                'urutan' => (int) $request->input('urutan', 1),
            ]);
            return redirect()->route('admin.roadmap.index', ['kelola_tugas' => $idPeriode])
                ->with('pesan', 'Tugas roadmap berhasil ditambahkan!')
                ->with('tipe_pesan', 'success');
        }

        if ($request->has('edit_tugas')) {
            $idItem = (int) $request->input('id_item', 0);
            $idPeriode = (int) $request->input('id_periode', 0);
            RoadmapItem::where('id', $idItem)->update([
                'deskripsi_tugas' => trim((string) $request->input('deskripsi_tugas', '')),
                'urutan' => (int) $request->input('urutan', 1),
            ]);
            return redirect()->route('admin.roadmap.index', ['kelola_tugas' => $idPeriode])
                ->with('pesan', 'Tugas roadmap berhasil diperbarui!')
                ->with('tipe_pesan', 'success');
        }

        return redirect()->route('admin.roadmap.index');
    }
}
