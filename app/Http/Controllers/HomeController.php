<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Blog;
use App\Models\KontenPage;
use App\Models\RoadmapItem;
use App\Models\RoadmapPeriode;
use App\Services\MaterializedViewService;
use Illuminate\Support\Facades\DB;
use Throwable;

class HomeController extends Controller
{
    public function index(MaterializedViewService $mvService)
    {
        $tentangKami = ['title' => 'Tentang Lab SE', 'content' => ''];
        $focusItems = [
            'focus_qa' => [
                'title' => 'Quality Assurance',
                'content' => 'Pengujian perangkat lunak berbasis otomatisasi, audit kualitas, serta pengembangan standar mutu pengembangan aplikasi.',
                'icon' => 'lni lni-shield',
            ],
            'focus_hci' => [
                'title' => 'Human-Computer Interaction',
                'content' => 'Riset pengalaman pengguna (UX), desain antarmuka adaptif, dan evaluasi kegunaan pada berbagai platform digital.',
                'icon' => 'lni lni-network',
            ],
            'focus_ml' => [
                'title' => 'Machine Learning for SE',
                'content' => 'Penerapan kecerdasan buatan untuk estimasi effort, deteksi bug otomatis, dan rekomendasi arsitektur perangkat lunak.',
                'icon' => 'lni lni-stats-up',
            ],
        ];
        $visi = ['title' => 'Visi', 'content' => ''];
        $misiItems = [];
        $periodePreview = [];
        $roadmapTasks = [];
        $dosenList = [];
        $totalDosen = 0;
        $blogList = [];
        $totalBlogPublished = 0;

        try {
            // Tentang Kami
            $tk = KontenPage::where('page_key', 'tentang_kami')->first();
            if ($tk) {
                $tentangKami = [
                    'title' => $tk->title ?: 'Tentang Lab SE',
                    'content' => mb_substr($tk->content ?? '', 0, 15000),
                ];
            }

            // Focus & Scope
            $focusRows = KontenPage::whereIn('page_key', ['focus_qa', 'focus_hci', 'focus_ml'])->get();
            foreach ($focusRows as $row) {
                $decoded = json_decode($row->content ?? '', true);
                if (is_array($decoded)) {
                    $focusItems[$row->page_key] = [
                        'title' => $decoded['title'] ?? $row->title ?? $focusItems[$row->page_key]['title'],
                        'content' => $decoded['content'] ?? $focusItems[$row->page_key]['content'],
                        'icon' => $decoded['icon'] ?? $focusItems[$row->page_key]['icon'],
                    ];
                } else {
                    $focusItems[$row->page_key]['title'] = $row->title ?: $focusItems[$row->page_key]['title'];
                    $focusItems[$row->page_key]['content'] = $row->content ?: $focusItems[$row->page_key]['content'];
                }
            }

            // Visi & Misi
            $visiRow = KontenPage::where('page_key', 'visi_deskripsi')->first();
            if ($visiRow) {
                $visi = [
                    'title' => $visiRow->title ?: 'Visi',
                    'content' => $visiRow->content ?? '',
                ];
            }
            $misiItems = KontenPage::where('page_key', 'like', 'misi_item_%')
                ->orderBy('page_key', 'asc')
                ->limit(5)
                ->pluck('content')
                ->toArray();

            // Roadmap Preview (first 2 periods, up to 3 items each)
            $periodes = RoadmapPeriode::orderBy('urutan', 'asc')->limit(2)->get();
            foreach ($periodes as $p) {
                $periodePreview[] = $p->toArray();
                $roadmapTasks[$p->id] = RoadmapItem::where('id_periode', $p->id)
                    ->orderBy('urutan', 'asc')
                    ->limit(3)
                    ->pluck('deskripsi_tugas')
                    ->toArray();
            }

            // Stats & Dosen Pengampu
            $mvStats = $mvService->getStatsComplete();
            $totalDosen = (int) ($mvStats['total_anggota'] ?? 0);
            if ($totalDosen === 0) {
                $totalDosen = Anggota::whereNotNull('jabatan')->count();
            }

            $dosenRows = DB::select("
                SELECT * FROM anggota 
                WHERE jabatan IS NOT NULL 
                AND (jabatan LIKE '%Dosen%' OR jabatan LIKE '%Ketua%' OR jabatan LIKE '%Kepala%' OR jabatan LIKE '%Koordinator%' OR jabatan LIKE '%Pembimbing%' OR jabatan LIKE '%Peneliti%')
                ORDER BY CASE jabatan
                    WHEN 'Kepala Laboratorium' THEN 1
                    WHEN 'Ketua Lab' THEN 1
                    WHEN 'Wakil Ketua Lab' THEN 2
                    WHEN 'Koordinator Penelitian' THEN 3
                    WHEN 'Dosen Pembimbing' THEN 4
                    WHEN 'Dosen Pengampu' THEN 5
                    ELSE 6
                END, urutan ASC, id ASC 
                LIMIT 6
            ");
            if (empty($dosenRows)) {
                $dosenRows = Anggota::orderBy('urutan')->orderBy('id')->limit(6)->get()->toArray();
            } else {
                $dosenRows = array_map(fn($r) => (array) $r, $dosenRows);
            }
            $dosenList = $dosenRows;

            // Blog section
            $totalBlogPublished = (int) ($mvStats['total_blog_published'] ?? 0);
            $blogList = Blog::where('is_published', true)
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->limit(6)
                ->get()
                ->toArray();
        } catch (Throwable $e) {
            // Keep safe defaults
        }

        return view('public.home', compact(
            'tentangKami',
            'focusItems',
            'visi',
            'misiItems',
            'periodePreview',
            'roadmapTasks',
            'dosenList',
            'totalDosen',
            'blogList',
            'totalBlogPublished'
        ));
    }
}
