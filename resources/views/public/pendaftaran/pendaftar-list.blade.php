@extends('layouts.public')

@push('styles')
<style>
.pendaftar-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 30px 25px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid #f0f0f0;
    position: relative;
    overflow: hidden;
}
.pendaftar-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2d5aa0 0%, #4a90e2 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}
.pendaftar-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 30px rgba(45, 90, 160, 0.15);
    border-color: #2d5aa0;
}
.pendaftar-card:hover::before {
    transform: scaleX(1);
}
.pendaftar-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 20px;
    border: 4px solid #e8f0f8;
    box-shadow: 0 4px 15px rgba(45, 90, 160, 0.1);
}
.pendaftar-initial {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2d5aa0 0%, #4a90e2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 700;
    margin: 0 auto 20px;
    border: 4px solid #e8f0f8;
    box-shadow: 0 4px 15px rgba(45, 90, 160, 0.1);
}
.pendaftar-name {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 12px;
    text-align: center;
}
.pendaftar-nim {
    background: linear-gradient(135deg, #f0f4f8 0%, #e8f0f8 100%);
    color: #2d5aa0;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    display: inline-block;
    margin: 0 auto 15px;
    font-family: 'Courier New', monospace;
    border: 1px solid #d0e0f0;
}
.pendaftar-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 15px;
}
.pendaftar-info-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
    color: #555;
}
.pendaftar-info-item i {
    color: #2d5aa0;
    width: 20px;
    margin-top: 2px;
}
.pendaftar-date {
    text-align: center;
    font-size: 12px;
    color: #888;
    margin-top: auto;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
    font-weight: 500;
}
.stats-card {
    background: linear-gradient(135deg, #2d5aa0 0%, #4a90e2 100%);
    color: white;
    border-radius: 20px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 8px 30px rgba(45, 90, 160, 0.2);
}
.stats-label {
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
}
.stats-number {
    font-size: 48px;
    font-weight: 800;
    margin: 0;
}
.search-box {
    background: #ffffff;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 40px;
    border: 1px solid #f0f0f0;
}
.pendaftar-section {
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    min-height: 80vh;
}
</style>
@endpush

@section('content')
<section class="feature-section feature-style-2 pt-150 pb-100 pendaftar-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="section-title text-center mb-60">
                    <h3 class="mb-15 wow fadeInUp gradient-text" data-wow-delay=".2s">Daftar Pendaftar</h3>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Daftar mahasiswa yang telah mendaftar ke Laboratorium Software Engineering</p>
                </div>
            </div>
        </div>

        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="stats-card wow fadeInUp" data-wow-delay=".2s" style="max-width: 400px; margin: 0 auto;">
                    <div class="stats-label">Total Pendaftar</div>
                    <div class="stats-number">{{ number_format($totalAllPendaftar, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="search-box wow fadeInUp" data-wow-delay=".2s">
                    <form method="GET" action="{{ route('pendaftaran.list') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 250px;">
                            <input type="text" name="search" value="{{ $search }}"
                                   placeholder="Cari nama, NIM, atau email..."
                                   style="width: 100%; padding: 12px 20px; border: 2px solid #e0e0e0; border-radius: 50px; font-size: 14px;">
                        </div>
                        <div>
                            <button type="submit" class="button radius-10" style="padding: 12px 30px;">
                                <i class="lni lni-search"></i> Cari
                            </button>
                            @if (!empty($search))
                                <a href="{{ route('pendaftaran.list') }}" class="button radius-10 border-button" style="padding: 12px 30px; margin-left: 10px;">
                                    <i class="lni lni-reload"></i> Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            @if (count($pendaftarList) > 0)
                @foreach ($pendaftarList as $p)
                    @php
                        $fotoRaw = $p['foto'] ?? '';
                        $fotoSrc = $fotoRaw
                            ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset(ltrim(str_replace('../../', '', $fotoRaw), '/')))
                            : '';
                        $initial = strtoupper(substr($p['nama'] ?? 'P', 0, 1));
                        $dateFormatted = !empty($p['tanggal_daftar'])
                            ? date('d M Y', strtotime($p['tanggal_daftar']))
                            : '-';
                    @endphp
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="pendaftar-card wow fadeInUp" data-wow-delay=".3s">
                            <div style="text-align: center;">
                                @if ($fotoSrc)
                                    <img src="{{ $fotoSrc }}"
                                         alt="{{ $p['nama'] }}"
                                         class="pendaftar-avatar"
                                         onerror="this.onerror=null; this.style.display='none'; var fallback = this.nextElementSibling; if(fallback) fallback.style.display='flex';">
                                    <div class="pendaftar-initial" style="display: none;">{{ $initial }}</div>
                                @else
                                    <div class="pendaftar-initial">{{ $initial }}</div>
                                @endif

                                <div class="pendaftar-name">{{ $p['nama'] }}</div>
                                <div style="text-align: center; margin-bottom: 15px;">
                                    <span class="pendaftar-nim">{{ $p['nim'] }}</span>
                                </div>
                            </div>

                            <div class="pendaftar-info">
                                @if (!empty($p['email']))
                                    <div class="pendaftar-info-item">
                                        <i class="lni lni-envelope"></i>
                                        <span style="word-break: break-word;">{{ $p['email'] }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="pendaftar-date">
                                <i class="lni lni-calendar"></i> {{ $dateFormatted }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-lg-12">
                    <div class="empty-state wow fadeInUp" data-wow-delay=".3s" style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 20px;">
                        <i class="lni lni-inbox" style="font-size: 64px; color: #d0d0d0; margin-bottom: 20px; display: block;"></i>
                        <h4 style="color: #999; margin-bottom: 10px;">
                            @if (!empty($search))
                                Tidak ada pendaftar yang ditemukan dengan kata kunci "{{ $search }}"
                            @else
                                Belum ada data pendaftar
                            @endif
                        </h4>
                    </div>
                </div>
            @endif
        </div>

        @if ($totalPages > 1)
            <div class="row mt-30">
                <div class="col-lg-12 text-center">
                    <div style="display: inline-flex; gap: 8px; flex-wrap: wrap;">
                        @for ($i = 1; $i <= $totalPages; $i++)
                            <a href="{{ route('pendaftaran.list', ['search' => $search, 'page' => $i]) }}"
                               class="button button-sm radius-10 {{ $i === $currentPage ? '' : 'border-button' }}"
                               style="min-width: 40px;">
                                {{ $i }}
                            </a>
                        @endfor
                    </div>
                </div>
            </div>
        @endif

        <div class="row mt-40">
            <div class="col-lg-12 text-center">
                <a href="{{ route('pendaftaran.daftar') }}" class="button radius-10" style="padding: 15px 40px; font-size: 16px;">
                    <i class="lni lni-plus"></i> Daftar Menjadi Anggota
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
