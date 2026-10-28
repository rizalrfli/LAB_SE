@extends('layouts.public')

@section('content')
<!-- ========================= hero-2 start ========================= -->
<section id="home" class="hero-section hero-style-2" style="padding-top: 120px; padding-bottom: 60px;">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-12 col-lg-10">
        <div class="row align-items-center">
          <div class="col-lg-6 d-flex align-items-center">
            <div class="text-center w-100">
              <h4 style="color: #2F80ED !important;">Selamat Datang</h4>
              <h2 style="background: linear-gradient(135deg, #2F80ED 0%, #1E5FCC 100%) !important; -webkit-background-clip: text !important; -webkit-text-fill-color: transparent !important; background-clip: text !important; color: transparent !important;">
                Web Profile Laboratorium<br>Software Engineering
              </h2>
            </div>
          </div>
          <div class="col-lg-6 d-flex align-items-center">
            <div class="hero-mascot wow fadeInRight w-100" data-wow-delay=".4s">
              <div class="mascot-container">
                <div class="mascot-bg-circle"></div>
                <img src="{{ asset('assets/img/LAB SE_Outline.png') }}" class="mascot-illustration" alt="Lab SE Mascot">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ========================= hero-2 end ========================= -->

<!-- ========================= feature style-2 start ========================= -->
<section id="services" class="feature-section feature-style-2 pt-100 pb-70">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="row justify-content-center">
          <div class="col-xl-10 col-lg-12 col-md-12">
            <div class="section-surface mb-60 text-center">
              <div class="section-title mb-0">
                <h3 class="mb-15 wow fadeInUp gradient-text" data-wow-delay=".2s">
                  {{ $tentangKami['title'] ?? 'Tentang Lab SE' }}
                </h3>
                <p class="wow fadeInUp" data-wow-delay=".4s">
                  {!! nl2br(e($tentangKami['content'] ?? '')) !!}
                </p>
                <div class="mt-4 wow fadeInUp" data-wow-delay=".5s">
                  <a href="{{ route('profil.tentang-kami') }}" class="btn btn-primary btn-sm radius-10 btn-primary-custom">
                    Selengkapnya <i class="lni lni-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================= Focus & Scope Section ========================= -->
<section id="focus" class="feature-section feature-style-2 pt-100 pb-70">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-title text-center mb-60">
          <h3 class="mb-15 wow fadeInUp gradient-text" data-wow-delay=".2s">Focus &amp; Scope</h3>
          <p class="wow fadeInUp" data-wow-delay=".4s">Fokus penelitian dan pengembangan di Laboratorium Software Engineering</p>
        </div>
      </div>
    </div>
    <div class="row">
      @foreach (['focus_qa' => 0.2, 'focus_hci' => 0.3, 'focus_ml' => 0.4] as $fKey => $delay)
        @php $focus = $focusItems[$fKey] ?? ['title' => '', 'content' => '', 'icon' => 'lni-cog']; @endphp
        <div class="col-lg-4 col-md-6">
          <div class="single-feature wow fadeInUp" data-wow-delay="{{ $delay }}s">
            <div class="icon">
              <i class="lni {{ $focus['icon'] }}"></i>
            </div>
            <div class="content">
              <h5>{{ $focus['title'] }}</h5>
              <p>{{ $focus['content'] }}</p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ========================= Visi & Misi Preview Section ========================= -->
<section id="visi" class="feature-section feature-style-2 pt-100 pb-70">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="section-title text-center mb-60">
          <h3 class="mb-15 wow fadeInUp gradient-text" data-wow-delay=".2s">Visi &amp; Misi</h3>
        </div>
        <div class="row">
          <div class="col-lg-6 mb-4">
            <div class="single-feature wow fadeInUp" data-wow-delay=".2s">
              <h5>Visi</h5>
              <p>{!! nl2br(e($visi['content'] ?? '')) !!}</p>
            </div>
          </div>
          <div class="col-lg-6 mb-4">
            <div class="single-feature wow fadeInUp" data-wow-delay=".3s">
              <h5>Misi</h5>
              @if (!empty($misiItems))
                <ul>
                  @foreach ($misiItems as $misiText)
                  <li>
                    <i class="lni lni-checkmark-circle"></i>
                    {{ $misiText }}
                  </li>
                  @endforeach
                </ul>
              @else
                <p class="empty-state">Konten misi belum diatur di Admin Panel.</p>
              @endif
            </div>
          </div>
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('profil.visi-misi') }}" class="button button-sm radius-10">
            Lihat Selengkapnya
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================= Roadmap Preview Section ========================= -->
<section id="roadmap" class="feature-section feature-style-2 pt-100 pb-70">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="section-title text-center mb-60">
          <h3 class="mb-15 wow fadeInUp gradient-text" data-wow-delay=".2s">Roadmap</h3>
          <p class="wow fadeInUp" data-wow-delay=".4s">Perjalanan dan Rencana Pengembangan Laboratorium</p>
        </div>
        <div class="row">
          @if (!empty($periodePreview))
            @foreach ($periodePreview as $index => $periode)
              @php $tasks = $roadmapTasks[$periode['id']] ?? []; @endphp
              <div class="col-lg-6 mb-4">
                <div class="single-feature wow fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                  <h5>{{ $periode['judul_fase'] }}</h5>
                  <p>{{ $periode['tahun_periode'] }}</p>
                  @if (!empty($tasks))
                    <ul>
                      @foreach ($tasks as $t)
                        <li>
                          <i class="lni lni-checkmark-circle"></i>
                          {{ $t }}
                        </li>
                      @endforeach
                    </ul>
                  @else
                    <p class="empty-state">Belum ada tugas yang ditambahkan untuk periode ini.</p>
                  @endif
                </div>
              </div>
            @endforeach
          @else
            <div class="col-12">
              <div class="text-center empty-state-box">
                <p class="empty-state-message">
                  Roadmap belum diatur di Admin Panel.
                  <br><small>Silakan tambahkan roadmap melalui <a href="{{ route('admin.login') }}">admin panel</a>.</small>
                </p>
              </div>
            </div>
          @endif
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('profil.roadmap') }}" class="button button-sm radius-10">Lihat Selengkapnya</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================= Galeri Dosen Section ========================= -->
<section id="galeri-dosen" class="team-style-1 pt-100 pb-70">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-title text-center mb-60">
          <h3 class="mb-15 wow fadeInUp gradient-text" data-wow-delay=".2s">Dosen Pengampu</h3>
          <p class="wow fadeInUp" data-wow-delay=".4s">Tim dosen yang membimbing dan mengembangkan Laboratorium Software Engineering</p>
        </div>
      </div>
    </div>
    <div class="row justify-content-center">
      @if (!empty($dosenList))
        @foreach ($dosenList as $dosen)
          @php
            $fotoRaw = $dosen['foto'] ?? '';
            $fotoUrl = $fotoRaw
                ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset(ltrim(str_replace('../../', '', $fotoRaw), '/')))
                : 'https://via.placeholder.com/300';
          @endphp
          <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="single-team wow fadeInUp" data-wow-delay=".2s">
              <div class="image">
                <img src="{{ $fotoUrl }}" alt="{{ $dosen['nama'] }}" onerror="this.src='https://via.placeholder.com/300'">
              </div>
              <div class="info">
                <h6>{{ $dosen['nama'] }}</h6>
                <p>{{ $dosen['jabatan'] ?? 'Dosen' }}</p>
                @if (!empty($dosen['nip']))
                  <p style="font-size: 13px;">NIP. {{ $dosen['nip'] }}</p>
                @endif
                @if (!empty($dosen['sinta_url']))
                  <p style="font-size: 13px;"><a href="{{ $dosen['sinta_url'] }}" target="_blank" rel="noopener noreferrer">Profil SINTA</a></p>
                @endif
                <a href="{{ route('profil.detail-personil', ['id' => $dosen['id']]) }}" class="button button-sm radius-10 border-button mt-20">Lihat Profil</a>
              </div>
            </div>
          </div>
        @endforeach
      @else
        <div class="col-12 text-center">
          <p class="empty-state-message">
            Belum ada data anggota di database.
          </p>
          <a href="{{ route('profil.personil') }}" class="button button-sm radius-10 mt-3">Lihat Semua Personil</a>
        </div>
      @endif
    </div>
  </div>
</section>

<!-- ========================= Blog Section ========================= -->
<section id="blog" class="feature-section feature-style-2 pt-100 pb-70">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-title text-center mb-60">
          <h3 class="mb-15 wow fadeInUp gradient-text" data-wow-delay=".2s">Blog &amp; Artikel</h3>
          <p class="wow fadeInUp" data-wow-delay=".4s">Artikel dan berita terkini dari Laboratorium Software Engineering</p>
        </div>
      </div>
    </div>
    <div class="row">
      @if (!empty($blogList))
        @foreach ($blogList as $blogItem)
          @php
            $blogLink = !empty($blogItem['slug'])
                ? route('blog.show', ['slug' => $blogItem['slug']])
                : route('blog.show', ['id' => $blogItem['id']]);
            $imgRaw = $blogItem['featured_image'] ?? '';
            $blogImage = $imgRaw
                ? (str_starts_with($imgRaw, 'http') ? $imgRaw : asset(ltrim(str_replace('../../', '', $imgRaw), '/')))
                : asset('assets/img/blog/blog-1-1.jpg');
            $blogExcerpt = !empty($blogItem['excerpt']) ? \Illuminate\Support\Str::limit($blogItem['excerpt'], 100) : '';
            $blogDate = !empty($blogItem['published_at'])
                ? date('d F Y', strtotime($blogItem['published_at']))
                : date('d F Y', strtotime($blogItem['created_at'] ?? 'now'));
          @endphp
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="single-feature blog-card-item wow fadeInUp" data-wow-delay=".2s">
              <div class="image">
                <img src="{{ $blogImage }}" alt="{{ $blogItem['title'] }}" onerror="this.src='{{ asset('assets/img/blog/blog-1-1.jpg') }}';">
              </div>
              <div class="content">
                @if (!empty($blogItem['category']))
                  <span class="category-badge">{{ $blogItem['category'] }}</span>
                @endif
                <h5>
                  <a href="{{ $blogLink }}">{{ $blogItem['title'] }}</a>
                </h5>
                @if ($blogExcerpt)
                  <p>{{ $blogExcerpt }}</p>
                @endif
                <div class="blog-meta">
                  <span><i class="lni lni-calendar"></i> {{ $blogDate }}</span>
                  <a href="{{ $blogLink }}" class="button button-sm radius-10">Baca</a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      @else
        <div class="col-12 text-center">
          <p class="empty-state-message">
            Belum ada artikel blog yang dipublikasikan.
            <br><small>Silakan tambahkan blog melalui admin panel dan pastikan statusnya "Published".</small>
          </p>
        </div>
      @endif
    </div>
    @if ($totalBlogPublished > 6)
      <div class="row mt-4">
        <div class="col-12 text-center">
          <a href="{{ route('blog.index') }}" class="button radius-10">Lihat Semua Artikel ({{ $totalBlogPublished }})</a>
        </div>
      </div>
    @endif
  </div>
</section>

<!-- ========================= Contact Section ========================= -->
<section id="contact" class="contact-section contact-style-6 pt-150 pb-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-title text-center mb-60">
          <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">Bergabung dengan Kami</h2>
          <p class="wow fadeInUp" data-wow-delay=".4s">Daftarkan diri Anda untuk menjadi bagian dari Laboratorium Software Engineering</p>
        </div>
        <div class="contact-form-wrapper wow fadeInUp" data-wow-delay=".6s">
          <a href="{{ route('pendaftaran.daftar') }}" class="button radius-10 w-100 text-center" style="display: block; padding: 15px; font-size: 1.1rem;">Formulir Pendaftaran</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
