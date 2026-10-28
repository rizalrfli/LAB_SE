<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\KontenPage;
use App\Models\Penelitian;
use App\Models\Publikasi;
use App\Models\RoadmapItem;
use App\Models\RoadmapPeriode;
use Illuminate\Http\Request;
use Throwable;

class ProfilController extends Controller
{
    public function tentangKami()
    {
        $tentangKami = [
            'title' => 'Tentang Lab SE',
            'content' => 'Laboratorium Software Engineering merupakan wadah kolaborasi mahasiswa dan dosen untuk penelitian dan pengembangan.',
        ];
        $features = [
            'rekayasa' => [
                'title' => 'Rekayasa Perangkat Lunak',
                'content' => 'Fokus pada pengembangan perangkat lunak berkualitas tinggi dengan metodologi terkini',
            ],
            'publikasi' => [
                'title' => 'Publikasi Ilmiah',
                'content' => 'Mendorong penelitian dan publikasi hasil riset di bidang software engineering',
            ],
            'kemitraan' => [
                'title' => 'Kemitraan Industri',
                'content' => 'Membangun kerjasama dengan industri untuk implementasi teknologi nyata',
            ],
            'pembinaan' => [
                'title' => 'Pembinaan Kompetensi',
                'content' => 'Membina mahasiswa melalui proyek nyata dan pengembangan skill praktis',
            ],
        ];

        try {
            $keys = [
                'tentang_kami',
                'tentang_kami_feature_rekayasa',
                'tentang_kami_feature_publikasi',
                'tentang_kami_feature_kemitraan',
                'tentang_kami_feature_pembinaan',
            ];
            $rows = KontenPage::whereIn('page_key', $keys)->get()->keyBy('page_key');

            if (isset($rows['tentang_kami'])) {
                $tentangKami = [
                    'title' => $rows['tentang_kami']->title ?: $tentangKami['title'],
                    'content' => $rows['tentang_kami']->content ?: $tentangKami['content'],
                ];
            }
            foreach (['rekayasa', 'publikasi', 'kemitraan', 'pembinaan'] as $fKey) {
                $dbKey = 'tentang_kami_feature_' . $fKey;
                if (isset($rows[$dbKey])) {
                    $features[$fKey] = [
                        'title' => $rows[$dbKey]->title ?: $features[$fKey]['title'],
                        'content' => $rows[$dbKey]->content ?: $features[$fKey]['content'],
                    ];
                }
            }
        } catch (Throwable $e) {
            // Keep defaults
        }

        return view('public.profil.tentang-kami', [
            'tentangKami' => $tentangKami,
            'features' => $features,
            'pageCss' => 'tentang-kami.css',
        ]);
    }

    public function visiMisi()
    {
        $visi = [
            'title' => 'Visi Kami',
            'content' => 'Menjadi pusat penelitian software engineering yang relevan dan berdaya guna bagi pengembangan teknologi informasi di Indonesia.',
        ];
        $misiList = [];

        try {
            $visiRow = KontenPage::where('page_key', 'visi_deskripsi')->first();
            if ($visiRow) {
                $visi = [
                    'title' => $visiRow->title ?: 'Visi Kami',
                    'content' => $visiRow->content ?: $visi['content'],
                ];
            }
            $misiList = KontenPage::where('page_key', 'like', 'misi_item_%')
                ->orderBy('page_key', 'asc')
                ->get()
                ->toArray();
        } catch (Throwable $e) {
            // Keep defaults
        }

        return view('public.profil.visi-misi', [
            'visi' => $visi,
            'misiList' => $misiList,
            'pageCss' => 'visi-misi.css',
        ]);
    }

    public function roadmap()
    {
        $periodes = [];
        try {
            $rows = RoadmapPeriode::orderBy('urutan', 'asc')->get();
            foreach ($rows as $row) {
                $items = RoadmapItem::where('id_periode', $row->id)
                    ->orderBy('urutan', 'asc')
                    ->pluck('deskripsi_tugas')
                    ->toArray();
                $periodes[] = [
                    'id' => $row->id,
                    'tahun_periode' => $row->tahun_periode,
                    'judul_fase' => $row->judul_fase,
                    'items' => $items,
                ];
            }
        } catch (Throwable $e) {
            // Keep defaults
        }

        return view('public.profil.roadmap', [
            'periodes' => $periodes,
            'pageCss' => 'roadmap.css',
        ]);
    }

    public function personil()
    {
        $personilList = [];
        try {
            $personilList = Anggota::orderByDesc('id')->get()->toArray();
        } catch (Throwable $e) {
            // Keep defaults
        }

        return view('public.profil.personil', [
            'personilList' => $personilList,
            'pageCss' => 'personil.css',
        ]);
    }

    public function detailPersonil(Request $request, $id = null)
    {
        $personilId = (int) ($id ?? $request->query('id', 0));
        if ($personilId <= 0) {
            return redirect()->route('profil.personil');
        }

        try {
            $personil = Anggota::find($personilId);
            if (!$personil) {
                return redirect()->route('profil.personil');
            }
            $personilData = $personil->toArray();
        } catch (Throwable $e) {
            return redirect()->route('profil.personil');
        }

        return view('public.profil.detail-personil', [
            'personil' => $personilData,
            'pageCss' => 'detail-personil.css',
        ]);
    }

    public function penelitianPublikasi()
    {
        $penelitianList = [];
        $publikasiList = [];

        try {
            $penelitianList = Penelitian::orderByDesc('tahun')
                ->orderByDesc('id')
                ->get()
                ->toArray();

            $publikasiList = Publikasi::where('status', 'published')
                ->orderByDesc('tahun')
                ->orderByDesc('id')
                ->get()
                ->toArray();
        } catch (Throwable $e) {
            // Keep defaults
        }

        return view('public.profil.penelitian-dan-publikasi', [
            'penelitianList' => $penelitianList,
            'publikasiList' => $publikasiList,
            'pageCss' => 'penelitian-publikasi.css',
        ]);
    }
}
