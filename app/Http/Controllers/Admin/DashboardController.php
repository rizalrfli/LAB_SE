<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MaterializedViewService;

class DashboardController extends Controller
{
    public function index(MaterializedViewService $mvService)
    {
        $stats = $mvService->getStatsComplete();
        $recentPendaftar = $mvService->getPendaftarTerbaru(5);
        $recentBlog = $mvService->getBlogPublished(3);
        $pendaftaranPerBulan = $mvService->getPendaftarPerBulan();

        $chartLabels = [];
        $chartData = [];
        foreach (array_reverse($pendaftaranPerBulan) as $item) {
            $chartLabels[] = $item['label'];
            $chartData[] = (int) $item['jumlah'];
        }
        if (empty($chartLabels)) {
            $chartLabels = [date('M Y')];
            $chartData = [0];
        }

        return view('admin.dashboard', [
            'current_page' => 'dashboard.php',
            'page_title' => 'Dashboard Admin - Laboratorium Software Engineering',
            'stats' => $stats,
            'recentPendaftar' => $recentPendaftar,
            'recentBlog' => $recentBlog,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}
