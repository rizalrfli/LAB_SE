@extends('layouts.admin')

@section('content')
<section class="pt-50 pb-50 admin-section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb-50">
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">
                        <i class="lni lni-history"></i> History Log Aktivitas Pendaftar
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">
                        Riwayat lengkap semua perubahan status dan aktivitas pada data pendaftar
                    </p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <!-- Filter Section -->
        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="wow fadeInUp filter-advanced-card" data-wow-delay=".3s" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <form method="GET" action="{{ route('admin.pendaftar.history') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-9 mb-3">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50; font-size: 13px;">
                                    <i class="lni lni-search"></i> Pencarian
                                </label>
                                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pendaftar, NIM, catatan, atau admin..." style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px;">
                            </div>
                            <div class="col-md-3 mb-3" style="display: flex; align-items: flex-end; gap: 10px;">
                                <button type="submit" class="button radius-10 btn-edit" style="flex: 1; padding: 10px;">
                                    <i class="lni lni-search"></i> Cari
                                </button>
                                @if(!empty($search))
                                <a href="{{ route('admin.pendaftar.history') }}" class="button radius-10 border-button" style="padding: 10px;">
                                    Reset
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="wow fadeInUp" data-wow-delay=".2s" style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                        <span style="color: #666; font-size: 14px;">
                            <i class="lni lni-list"></i> Total Log: <strong>{{ number_format($totalLogs) }}</strong>
                        </span>
                        <a href="{{ route('admin.pendaftar.index') }}" class="button button-sm radius-10 border-button">
                            <i class="lni lni-arrow-left"></i> Kembali ke Pendaftar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Timeline -->
        <div class="row">
            <div class="col-lg-12">
                <div class="wow fadeInUp" data-wow-delay=".3s" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    @if(count($logs) > 0)
                        <div class="log-timeline">
                            @foreach($logs as $log)
                                <div class="log-item">
                                    <div class="log-item-header">
                                        <div class="log-icon-wrapper">
                                            <div class="log-icon" style="background: #2F80ED20; color: #2F80ED;">
                                                <i class="lni lni-sync"></i>
                                            </div>
                                        </div>
                                        <div class="log-content">
                                            <div class="log-title">
                                                <strong>Perubahan Status ({{ strtoupper($log['status_lama'] ?? '-') }} &rarr; {{ strtoupper($log['status_baru'] ?? '-') }})</strong>
                                                @if(!empty($log['nama']))
                                                    <span class="log-pendaftar">
                                                        - <a href="{{ route('admin.pendaftar.index', ['search' => $log['nim'] ?? '']) }}" style="color: #2F80ED; text-decoration: none;">
                                                            {{ $log['nama'] }} ({{ $log['nim'] ?? '-' }})
                                                        </a>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="log-description">
                                                {{ $log['catatan'] ?? '-' }}
                                            </div>
                                            <div class="log-meta">
                                                <span class="log-time">
                                                    <i class="lni lni-calendar"></i>
                                                    {{ !empty($log['changed_at']) ? date('d/m/Y H:i:s', strtotime($log['changed_at'])) . ' WIB' : '-' }}
                                                </span>
                                                @if(!empty($log['changed_by_username']))
                                                    <span class="log-user">
                                                        <i class="lni lni-user"></i> {{ $log['changed_by_username'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center" style="padding: 40px;">
                            <i class="lni lni-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                            <p style="color: #999; font-size: 16px;">Belum ada log aktivitas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.log-timeline { position: relative; padding-left: 40px; }
.log-timeline::before { content: ''; position: absolute; left: 15px; top: 0; bottom: 0; width: 2px; background: #e0e0e0; }
.log-item { position: relative; margin-bottom: 25px; padding-left: 20px; }
.log-item:last-child { margin-bottom: 0; }
.log-item-header { display: flex; gap: 15px; align-items: flex-start; }
.log-icon-wrapper { position: absolute; left: -25px; top: 0; z-index: 1; }
.log-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.log-content { flex: 1; background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 3px solid #2F80ED; }
.log-title { font-size: 15px; color: #2c3e50; margin-bottom: 8px; }
.log-pendaftar { color: #666; font-weight: normal; }
.log-description { font-size: 14px; color: #555; margin-bottom: 10px; line-height: 1.5; }
.log-meta { display: flex; gap: 15px; flex-wrap: wrap; font-size: 12px; color: #999; }
.log-meta span { display: flex; align-items: center; gap: 5px; }
</style>
@endsection
