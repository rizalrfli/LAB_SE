<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Throwable;

class MaterializedViewService
{
    public function viewExists(string $viewName): bool
    {
        try {
            $res = DB::selectOne(
                "SELECT 1 FROM pg_matviews WHERE matviewname = ? LIMIT 1",
                [$viewName]
            );
            return $res !== null;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function getStatsComplete(): array
    {
        try {
            if ($this->viewExists('mv_stats_complete')) {
                $row = DB::selectOne("SELECT * FROM mv_stats_complete LIMIT 1");
                if ($row) {
                    return (array) $row;
                }
            }
        } catch (Throwable $e) {
            // Fallback below
        }

        try {
            return [
                'total_anggota' => (int) DB::table('anggota')->count(),
                'total_pendaftar' => (int) DB::table('pendaftaran')->count(),
                'pendaftar_pending' => (int) DB::table('pendaftaran')->where('status', 'pending')->count(),
                'pendaftar_accepted' => (int) DB::table('pendaftaran')->where('status', 'accepted')->count(),
                'pendaftar_rejected' => (int) DB::table('pendaftaran')->where('status', 'rejected')->count(),
                'total_jabatan' => (int) DB::table('jabatan')->count(),
                'total_blog_published' => (int) DB::table('blog')->where('is_published', true)->count(),
                'total_blog_draft' => (int) DB::table('blog')->where('is_published', false)->count(),
                'total_blog_views' => (int) DB::table('blog')->where('is_published', true)->sum('views'),
                'total_user' => (int) DB::table('users')->count(),
                'total_admin' => (int) DB::table('users')->where('role', 'admin')->count(),
                'total_mahasiswa' => (int) DB::table('users')->where('role', 'mahasiswa')->count(),
            ];
        } catch (Throwable $e) {
            return [
                'total_anggota' => 0,
                'total_pendaftar' => 0,
                'pendaftar_pending' => 0,
                'pendaftar_accepted' => 0,
                'pendaftar_rejected' => 0,
                'total_jabatan' => 0,
                'total_blog_published' => 0,
                'total_blog_draft' => 0,
                'total_blog_views' => 0,
                'total_user' => 0,
                'total_admin' => 0,
                'total_mahasiswa' => 0,
            ];
        }
    }

    public function getPendaftarTerbaru(int $limit = 20): array
    {
        try {
            if ($this->viewExists('mv_pendaftar_terbaru_complete')) {
                $rows = DB::select("SELECT * FROM mv_pendaftar_terbaru_complete LIMIT ?", [$limit]);
                if (!empty($rows)) {
                    return array_map(fn($r) => (array) $r, $rows);
                }
            }
            $rows = DB::table('pendaftaran')
                ->orderByDesc('tanggal_daftar')
                ->limit($limit)
                ->get();
            return $rows->map(fn($r) => (array) $r)->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getBlogPublished(int $limit = 10, ?string $category = null, string $orderBy = 'published_at'): array
    {
        try {
            if ($this->viewExists('mv_blog_published_complete')) {
                $sql = "SELECT * FROM mv_blog_published_complete";
                $bindings = [];
                if ($category) {
                    $sql .= " WHERE LOWER(category) = LOWER(?)";
                    $bindings[] = $category;
                }
                $sql .= $orderBy === 'views'
                    ? " ORDER BY views DESC"
                    : " ORDER BY COALESCE(published_at, created_at) DESC";
                $sql .= " LIMIT ?";
                $bindings[] = $limit;
                $rows = DB::select($sql, $bindings);
                if (!empty($rows)) {
                    return array_map(fn($r) => (array) $r, $rows);
                }
            }

            $q = DB::table('blog')->where('is_published', true);
            if ($category) {
                $q->whereRaw('LOWER(category) = LOWER(?)', [$category]);
            }
            if ($orderBy === 'views') {
                $q->orderByDesc('views');
            } else {
                $q->orderByRaw('COALESCE(published_at, created_at) DESC');
            }
            return $q->limit($limit)->get()->map(fn($r) => (array) $r)->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getPendaftarPerStatus(): array
    {
        $stats = ['pending' => 0, 'accepted' => 0, 'rejected' => 0];
        try {
            if ($this->viewExists('mv_pendaftar_per_status')) {
                $rows = DB::select("SELECT status, jumlah FROM mv_pendaftar_per_status");
                foreach ($rows as $row) {
                    $stats[$row->status] = (int) $row->jumlah;
                }
                return $stats;
            }
            $rows = DB::table('pendaftaran')
                ->selectRaw('status, COUNT(*) as jumlah')
                ->groupBy('status')
                ->get();
            foreach ($rows as $row) {
                $stats[$row->status] = (int) $row->jumlah;
            }
        } catch (Throwable $e) {
            // keep default 0s
        }
        return $stats;
    }

    public function getPendaftarPerBulan(): array
    {
        try {
            if ($this->viewExists('mv_pendaftar_per_bulan')) {
                $rows = DB::select("SELECT bulan_format, bulan_label, jumlah FROM mv_pendaftar_per_bulan ORDER BY bulan DESC");
                if (!empty($rows)) {
                    return array_map(fn($r) => [
                        'bulan' => $r->bulan_format,
                        'label' => $r->bulan_label,
                        'jumlah' => (int) $r->jumlah,
                    ], $rows);
                }
            }

            $rows = DB::select("
                SELECT 
                    DATE_TRUNC('month', tanggal_daftar) as bulan,
                    TO_CHAR(tanggal_daftar, 'YYYY-MM') as bulan_format,
                    TO_CHAR(tanggal_daftar, 'Mon YYYY') as bulan_label,
                    COUNT(*) as jumlah
                FROM pendaftaran
                WHERE tanggal_daftar >= DATE_TRUNC('month', CURRENT_DATE - INTERVAL '6 months')
                GROUP BY DATE_TRUNC('month', tanggal_daftar), TO_CHAR(tanggal_daftar, 'YYYY-MM'), TO_CHAR(tanggal_daftar, 'Mon YYYY')
                ORDER BY bulan DESC
            ");
            return array_map(fn($r) => [
                'bulan' => $r->bulan_format,
                'label' => $r->bulan_label,
                'jumlah' => (int) $r->jumlah,
            ], $rows);
        } catch (Throwable $e) {
            return [];
        }
    }

    public function refreshViewsComplete(): bool
    {
        $views = [
            'mv_stats_complete',
            'mv_pendaftar_terbaru_complete',
            'mv_blog_published_complete',
            'mv_pendaftar_per_status',
            'mv_anggota_per_jabatan',
            'mv_blog_per_kategori',
            'mv_pendaftar_per_bulan',
            'mv_blog_populer',
            'mv_riwayat_status_terbaru',
            'mv_anggota_lengkap',
            'mv_blog_per_bulan',
            'mv_pendaftar_pending',
        ];

        $success = true;
        foreach ($views as $view) {
            try {
                if (!$this->viewExists($view)) {
                    continue;
                }
                DB::statement("REFRESH MATERIALIZED VIEW {$view}");
            } catch (Throwable $e) {
                $success = false;
            }
        }
        return $success;
    }
}
