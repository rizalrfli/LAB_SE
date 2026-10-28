@extends('layouts.public')

@section('content')
<section class="pt-150 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center mb-30">
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Tentang <span>Kami</span></h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Mengenal Laboratorium Software Engineering lebih dekat</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="tentang-kami-content" class="pt-100 pb-70">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="about-card wow fadeInUp" data-wow-delay=".2s">
          <div class="about-header">
            <div class="about-icon">
              <i class="lni lni-information"></i>
            </div>
            <h3 class="about-title">
              {{ !empty($tentangKami['title']) ? $tentangKami['title'] : 'Tentang Kami' }}
            </h3>
          </div>

          <p class="about-text">
            {!! nl2br(e($tentangKami['content'] ?? '')) !!}
          </p>

          <div class="about-features">
            <div class="feature-item wow fadeInUp" data-wow-delay=".3s">
              <div class="feature-icon">
                <i class="lni lni-code-alt"></i>
              </div>
              <div class="feature-content">
                <h5>{{ $features['rekayasa']['title'] ?? 'Rekayasa Perangkat Lunak' }}</h5>
                <p>{{ $features['rekayasa']['content'] ?? '' }}</p>
              </div>
            </div>

            <div class="feature-item wow fadeInUp" data-wow-delay=".4s">
              <div class="feature-icon">
                <i class="lni lni-book"></i>
              </div>
              <div class="feature-content">
                <h5>{{ $features['publikasi']['title'] ?? 'Publikasi Ilmiah' }}</h5>
                <p>{{ $features['publikasi']['content'] ?? '' }}</p>
              </div>
            </div>

            <div class="feature-item wow fadeInUp" data-wow-delay=".5s">
              <div class="feature-icon">
                <i class="lni lni-handshake"></i>
              </div>
              <div class="feature-content">
                <h5>{{ $features['kemitraan']['title'] ?? 'Kemitraan Industri' }}</h5>
                <p>{{ $features['kemitraan']['content'] ?? '' }}</p>
              </div>
            </div>

            <div class="feature-item wow fadeInUp" data-wow-delay=".6s">
              <div class="feature-icon">
                <i class="lni lni-graduation"></i>
              </div>
              <div class="feature-content">
                <h5>{{ $features['pembinaan']['title'] ?? 'Pembinaan Kompetensi' }}</h5>
                <p>{{ $features['pembinaan']['content'] ?? '' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
