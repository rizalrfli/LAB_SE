@extends('layouts.public')

@section('content')
<section class="pt-150 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center mb-30">
                    <h2 class="mb-15 wow fadeInUp gradient-text" data-wow-delay=".2s">Detail <span>Personil</span></h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Informasi lengkap tentang anggota laboratorium</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="detail-personil-content" class="pt-100 pb-70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="detail-card wow fadeInUp" data-wow-delay=".2s">
                    <div class="profile-header">
                        <div class="profile-image-wrapper">
                            @php
                                $fotoRaw = $personil['foto'] ?? '';
                                $fotoSrc = $fotoRaw
                                    ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset(ltrim(str_replace('../../', '', $fotoRaw), '/')))
                                    : 'https://via.placeholder.com/400';
                                $initial = urlencode(substr($personil['nama'] ?? 'A', 0, 1));
                            @endphp
                            <img src="{{ $fotoSrc }}"
                                 alt="{{ $personil['nama'] }}"
                                 class="profile-image"
                                 onerror="this.src='https://via.placeholder.com/400?text={{ $initial }}'">
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name">{{ $personil['nama'] }}</h3>
                            <p class="profile-role">
                                <i class="lni lni-briefcase"></i>
                                {{ $personil['jabatan'] ?? 'Anggota' }}
                            </p>
                            @if (!empty($personil['instagram']) || !empty($personil['linkedin']))
                                <div class="profile-socials">
                                    @if (!empty($personil['instagram']))
                                        <a href="{{ $personil['instagram'] }}" target="_blank" rel="noopener" class="social-icon instagram">
                                            <i class="lni lni-instagram-filled"></i>
                                        </a>
                                    @endif
                                    @if (!empty($personil['linkedin']))
                                        <a href="{{ $personil['linkedin'] }}" target="_blank" rel="noopener" class="social-icon linkedin">
                                            <i class="lni lni-linkedin-original"></i>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="profile-content">
                        @if (!empty($personil['deskripsi']))
                            <div class="info-section">
                                <div class="info-header">
                                    <i class="lni lni-user"></i>
                                    <h4>Tentang</h4>
                                </div>
                                <div class="info-body">
                                    <p>{!! nl2br(e($personil['deskripsi'])) !!}</p>
                                </div>
                            </div>
                        @endif

                        @if (!empty($personil['email']) || !empty($personil['telepon']))
                            <div class="info-section">
                                <div class="info-header">
                                    <i class="lni lni-envelope"></i>
                                    <h4>Informasi Kontak</h4>
                                </div>
                                <div class="info-body">
                                    <ul class="contact-list">
                                        @if (!empty($personil['email']))
                                            <li>
                                                <i class="lni lni-envelope"></i>
                                                <div>
                                                    <span class="label">Email</span>
                                                    <span class="value">{{ $personil['email'] }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        @if (!empty($personil['telepon']))
                                            <li>
                                                <i class="lni lni-phone"></i>
                                                <div>
                                                    <span class="label">Telepon</span>
                                                    <span class="value">{{ $personil['telepon'] }}</span>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="profile-actions">
                        <a href="{{ route('profil.personil') }}" class="button radius-10 border-button">
                            <i class="lni lni-arrow-left"></i> Kembali ke Daftar Personil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
