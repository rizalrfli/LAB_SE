@extends('layouts.admin')

@section('content')
@php
    $loggedUsername = auth()->user()?->username ?? session('username', 'Admin');
@endphp
<main class="admin-main-content">
<section class="pt-50 pb-50 admin-section-bg section-transparent">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb-50">
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Admin Dashboard</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Selamat Datang, <strong>{{ $loggedUsername }}</strong>. Kelola semua aspek laboratorium dari sini.</p>
                </div>
                @include('partials.flash-message')
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-50">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <a href="{{ route('admin.anggota.index') }}" style="text-decoration: none; display: block;">
                    <div class="stat-card blue wow fadeInUp" data-wow-delay=".2s" style="cursor: pointer;">
                        <div class="stat-icon"><i class="lni lni-users"></i></div>
                        <div class="stat-number">{{ $stats['total_anggota'] ?? 0 }}</div>
                        <div class="stat-label">Total Anggota</div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <a href="{{ route('admin.pendaftar.index') }}" style="text-decoration: none; display: block;">
                    <div class="stat-card green wow fadeInUp" data-wow-delay=".3s" style="cursor: pointer;">
                        <div class="stat-icon"><i class="lni lni-graduation"></i></div>
                        <div class="stat-number">{{ $stats['total_pendaftar'] ?? 0 }}</div>
                        <div class="stat-label">Total Pendaftar</div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <a href="{{ route('admin.pendaftar.index', ['status' => 'pending']) }}" style="text-decoration: none; display: block;">
                    <div class="stat-card orange wow fadeInUp" data-wow-delay=".4s" style="cursor: pointer;">
                        <div class="stat-icon"><i class="lni lni-alarm"></i></div>
                        <div class="stat-number">{{ $stats['pendaftar_pending'] ?? 0 }}</div>
                        <div class="stat-label">Pending Review</div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <a href="{{ route('admin.jabatan.index') }}" style="text-decoration: none; display: block;">
                    <div class="stat-card purple wow fadeInUp" data-wow-delay=".5s" style="cursor: pointer;">
                        <div class="stat-icon"><i class="lni lni-briefcase"></i></div>
                        <div class="stat-number">{{ $stats['total_jabatan'] ?? 0 }}</div>
                        <div class="stat-label">Jabatan Tersedia</div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <a href="{{ route('admin.blog.index') }}" style="text-decoration: none; display: block;">
                    <div class="stat-card blue wow fadeInUp" data-wow-delay=".6s" style="cursor: pointer; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="stat-icon"><i class="lni lni-text-format"></i></div>
                        <div class="stat-number">{{ $stats['total_blog_published'] ?? 0 }}</div>
                        <div class="stat-label">Blog Published</div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <a href="{{ route('admin.blog.index') }}" style="text-decoration: none; display: block;">
                    <div class="stat-card orange wow fadeInUp" data-wow-delay=".7s" style="cursor: pointer; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="stat-icon"><i class="lni lni-file"></i></div>
                        <div class="stat-number">{{ $stats['total_blog_draft'] ?? 0 }}</div>
                        <div class="stat-label">Blog Draft</div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <a href="{{ route('admin.user.index') }}" style="text-decoration: none; display: block;">
                    <div class="stat-card green wow fadeInUp" data-wow-delay=".8s" style="cursor: pointer; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="stat-icon"><i class="lni lni-user"></i></div>
                        <div class="stat-number">{{ $stats['total_user'] ?? 0 }}</div>
                        <div class="stat-label">Total User</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 col-lg-8 mb-30">
                <div class="recent-activity wow fadeInUp" data-wow-delay=".6s" style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); margin-bottom: 30px;">
                    <h4 style="color: #2c3e50; margin-bottom: 25px; font-weight: 600;">
                        <i class="lni lni-list mr-2" style="color: #2F80ED;"></i> Pendaftar Terbaru
                    </h4>
                    @if (count($recentPendaftar) > 0)
                        @foreach ($recentPendaftar as $pendaftar)
                            <div class="activity-item" style="padding: 15px; border-left: 3px solid #2F80ED; margin-bottom: 15px; background: #f8f9fa; border-radius: 5px;">
                                <div class="activity-name" style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">
                                    {{ $pendaftar['nama'] }}
                                    @if (($pendaftar['status'] ?? '') === 'pending')
                                        <span class="status-badge status-badge-pending status-badge-small" style="background: #F2994A; color: white; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-left: 8px;">Pending</span>
                                    @endif
                                </div>
                                <div class="activity-meta" style="font-size: 13px; color: #6c757d;">
                                    <i class="lni lni-id-card mr-1"></i> NIM: {{ $pendaftar['nim'] }} |
                                    <i class="lni lni-calendar mr-1"></i> {{ !empty($pendaftar['tanggal_daftar']) ? date('d/m/Y', strtotime($pendaftar['tanggal_daftar'])) : '-' }}
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center mt-20">
                            <a href="{{ route('admin.pendaftar.index') }}" class="button button-sm radius-10">Lihat Semua Pendaftar</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="lni lni-inbox admin-table-empty-icon" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
                            <p class="mt-3 text-muted">Belum ada data pendaftar</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 mb-30">
                <div class="recent-activity wow fadeInUp" data-wow-delay=".7s" style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                    <h4 style="color: #2c3e50; margin-bottom: 25px; font-weight: 600;">
                        <i class="lni lni-bolt mr-2" style="color: #F2994A;"></i> Quick Actions
                    </h4>
                    <div class="activity-item activity-item-green" style="padding: 15px; border-left: 3px solid #27AE60; margin-bottom: 15px; background: #f8f9fa; border-radius: 5px;">
                        <div class="activity-name" style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">
                            <i class="lni lni-plus-circle mr-2" style="color: #27AE60;"></i> Tambah Anggota Baru
                        </div>
                        <div class="activity-meta" style="font-size: 13px; color: #6c757d;">
                            <a href="{{ route('admin.anggota.create') }}" style="color: #27AE60; text-decoration: none; font-weight: 500;">Klik untuk menambah →</a>
                        </div>
                    </div>
                    <div class="activity-item activity-item-orange" style="padding: 15px; border-left: 3px solid #F2994A; margin-bottom: 15px; background: #f8f9fa; border-radius: 5px;">
                        <div class="activity-name" style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">
                            <i class="lni lni-eye mr-2" style="color: #F2994A;"></i> Review Pendaftar
                        </div>
                        <div class="activity-meta" style="font-size: 13px; color: #6c757d;">
                            <a href="{{ route('admin.pendaftar.index') }}" style="color: #F2994A; text-decoration: none; font-weight: 500;">{{ $stats['pendaftar_pending'] ?? 0 }} pending →</a>
                        </div>
                    </div>
                    <div class="activity-item activity-item-purple" style="padding: 15px; border-left: 3px solid #9B59B6; margin-bottom: 15px; background: #f8f9fa; border-radius: 5px;">
                        <div class="activity-name" style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">
                            <i class="lni lni-briefcase mr-2" style="color: #9B59B6;"></i> Kelola Jabatan
                        </div>
                        <div class="activity-meta" style="font-size: 13px; color: #6c757d;">
                            <a href="{{ route('admin.jabatan.index') }}" style="color: #9B59B6; text-decoration: none; font-weight: 500;">Klik untuk mengelola →</a>
                        </div>
                    </div>
                    <div class="activity-item" style="padding: 15px; border-left: 3px solid #2F80ED; margin-bottom: 15px; background: #f8f9fa; border-radius: 5px;">
                        <div class="activity-name" style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">
                            <i class="lni lni-text-format mr-2" style="color: #2F80ED;"></i> Kelola Blog
                        </div>
                        <div class="activity-meta" style="font-size: 13px; color: #6c757d;">
                            <a href="{{ route('admin.blog.index') }}" style="color: #2F80ED; text-decoration: none; font-weight: 500;">Klik untuk mengelola →</a>
                        </div>
                    </div>
                    <div class="activity-item" style="padding: 15px; border-left: 3px solid #667eea; margin-bottom: 15px; background: #f8f9fa; border-radius: 5px;">
                        <div class="activity-name" style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">
                            <i class="lni lni-cog mr-2" style="color: #667eea;"></i> Kelola Profil &amp; Roadmap
                        </div>
                        <div class="activity-meta" style="font-size: 13px; color: #6c757d;">
                            <a href="{{ route('admin.profil.index') }}" style="color: #667eea; text-decoration: none; font-weight: 500;">Klik untuk mengelola →</a>
                        </div>
                    </div>
                    <div class="activity-item" style="padding: 15px; border-left: 3px solid #4facfe; margin-bottom: 0; background: #f8f9fa; border-radius: 5px;">
                        <div class="activity-name" style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">
                            <i class="lni lni-user mr-2" style="color: #4facfe;"></i> Kelola User
                        </div>
                        <div class="activity-meta" style="font-size: 13px; color: #6c757d;">
                            <a href="{{ route('admin.user.index') }}" style="color: #4facfe; text-decoration: none; font-weight: 500;">Klik untuk mengelola →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (!empty($recentBlog))
        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="recent-activity wow fadeInUp" data-wow-delay=".8s" style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                    <h4 style="color: #2c3e50; margin-bottom: 25px; font-weight: 600;">
                        <i class="lni lni-text-format mr-2" style="color: #667eea;"></i> Blog Terbaru
                    </h4>
                    @foreach ($recentBlog as $blog)
                        <div class="activity-item" style="padding: 15px; border-left: 3px solid #667eea; margin-bottom: 15px; background: #f8f9fa; border-radius: 5px;">
                            <div class="activity-name" style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">
                                <a href="{{ route('admin.blog.index', ['action' => 'edit', 'id' => $blog['id']]) }}" style="color: #2c3e50; text-decoration: none;">
                                    {{ $blog['title'] }}
                                </a>
                            </div>
                            <div class="activity-meta" style="font-size: 13px; color: #6c757d;">
                                <i class="lni lni-calendar mr-1"></i>
                                @php $pubDate = $blog['published_at'] ?? $blog['created_at'] ?? ''; @endphp
                                {{ $pubDate ? date('d/m/Y', strtotime($pubDate)) : 'Belum dipublish' }}
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center mt-20">
                        <a href="{{ route('admin.blog.index') }}" class="button button-sm radius-10">Lihat Semua Blog</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Chart Section -->
<section class="pt-50 pb-50 admin-section-bg" style="background: transparent; padding-top: 20px !important;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="chart-container wow fadeInUp" data-wow-delay=".2s">
                    <div class="chart-header">
                        <h4><i class="lni lni-bar-chart mr-2"></i> Grafik Pendaftaran Mahasiswa</h4>
                        <p>Statistik jumlah pendaftar laboratorium dalam 6 bulan terakhir</p>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="pendaftaranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
@endsection

@push('scripts')
<script>
const chartData = @json(['labels' => $chartLabels, 'data' => $chartData]);
</script>
<script src="{{ asset('assets/js/classes/ChartManager.js') }}"></script>
<script>
(function() {
    function initChartManager() {
        if (typeof Chart === 'undefined' || typeof ChartManager === 'undefined') {
            setTimeout(initChartManager, 100);
            return;
        }
        const canvas = document.getElementById('pendaftaranChart');
        if (!canvas) return;
        try {
            window.chartManager = new ChartManager('pendaftaranChart', chartData);
        } catch (error) {}
    }
    if (document.readyState === 'complete') {
        setTimeout(initChartManager, 100);
    } else {
        window.addEventListener('load', function() {
            setTimeout(initChartManager, 100);
        });
    }
})();
</script>
@endpush
