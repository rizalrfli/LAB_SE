@extends('layouts.public')

@section('content')
<section class="pt-150 pb-50">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-title text-center mb-30">
            <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Anggota <span>Laboratorium</span></h2>
            <p class="wow fadeInUp" data-wow-delay=".4s">Dosen, Asisten, dan Peneliti di Lab Software Engineering</p>
          </div>
        </div>
      </div>
    </div>
</section>

<section id="team" class="team-style-1 pt-100 pb-70">
    <div class="container">
        <div class="row">
            @if (!empty($personilList))
                @foreach ($personilList as $row)
                    @php
                        $fotoRaw = $row['foto'] ?? '';
                        $fotoSrc = $fotoRaw
                            ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset(ltrim(str_replace('../../', '', $fotoRaw), '/')))
                            : 'https://via.placeholder.com/300';
                        $initial = urlencode(substr($row['nama'] ?? 'A', 0, 1));
                    @endphp
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-team wow fadeInUp" data-wow-delay=".2s">
                            <div class="image">
                                <img src="{{ $fotoSrc }}" alt="{{ $row['nama'] }}" loading="lazy" width="300" height="300" onerror="this.src='https://via.placeholder.com/300?text={{ $initial }}'">
                            </div>
                            <div class="info">
                                <h6>{{ $row['nama'] }}</h6>
                                <p>{{ $row['jabatan'] ?? 'Anggota' }}</p>
                                <ul class="socials">
                                    @if (!empty($row['instagram']))
                                        <li><a href="{{ $row['instagram'] }}" target="_blank" rel="noopener"><i class="lni lni-instagram-filled"></i></a></li>
                                    @endif
                                    @if (!empty($row['linkedin']))
                                        <li><a href="{{ $row['linkedin'] }}" target="_blank" rel="noopener"><i class="lni lni-linkedin-original"></i></a></li>
                                    @endif
                                </ul>
                                <a href="{{ route('profil.detail-personil', ['id' => $row['id']]) }}" class="button button-sm radius-10 border-button mt-20">Lihat Profil</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="text-center" style="color: #717791;">Belum ada data anggota.</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
