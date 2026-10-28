@extends('layouts.public')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/blog-detail.css') }}" />
@endpush

@section('content')
<section class="pt-150 pb-100" style="margin-top: 80px; background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9" id="blog-detail-container">
          @if ($blog)
            @php
                $dateStr = !empty($blog['published_at'])
                    ? date('d F Y', strtotime($blog['published_at']))
                    : date('d F Y', strtotime($blog['created_at'] ?? 'now'));
                $imgRaw = $blog['featured_image'] ?? '';
                $imgSrc = $imgRaw
                    ? (str_starts_with($imgRaw, 'http') ? $imgRaw : asset(ltrim(str_replace('../../', '', $imgRaw), '/')))
                    : asset('assets/img/blog/blog-1-1.jpg');
                $safeContent = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $blog['content'] ?? '');
            @endphp
            <div class="section-title mb-40">
                <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">{{ $blog['title'] }}</h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">Dipublikasikan pada: {{ $dateStr }}</p>
            </div>

            <div class="blog-meta-info wow fadeInUp" data-wow-delay=".3s">
                <div class="meta-item">
                    <i class="lni lni-calendar"></i>
                    <span>{{ $dateStr }}</span>
                </div>
                @if (!empty($blog['views']))
                <div class="meta-item">
                    <i class="lni lni-eye"></i>
                    <span>{{ number_format((int) $blog['views']) }} views</span>
                </div>
                @endif
                @if (!empty($blog['category']))
                <div class="meta-item">
                    <i class="lni lni-tag"></i>
                    <span>{{ ucfirst($blog['category']) }}</span>
                </div>
                @endif
            </div>

            <div class="wow fadeInUp" data-wow-delay=".6s">
                <img src="{{ $imgSrc }}"
                     alt="{{ $blog['title'] }}"
                     class="img-fluid mb-40 blog-featured-image"
                     onerror="this.src='{{ asset('assets/img/blog/blog-1-1.jpg') }}';">
            </div>

            <div class="article-content wow fadeInUp" data-wow-delay=".8s">
                {!! $safeContent !!}
            </div>

            @if (!empty($blog['tags_array']))
                <div class="blog-tags wow fadeInUp" data-wow-delay=".9s">
                    <strong>Tags:</strong>
                    @foreach ($blog['tags_array'] as $tag)
                        <span>{{ trim($tag) }}</span>
                    @endforeach
                </div>
            @endif
          @else
            <div class="section-title mb-40">
                <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">Artikel Tidak Ditemukan</h2>
                <p class="wow fadeInUp" data-wow-delay=".4s" style="color: #585978;">Artikel yang Anda cari tidak ditemukan atau belum dipublikasikan.</p>
            </div>
          @endif
          <hr class="my-5">
          <a href="{{ route('home') }}#blog" class="button radius-10 border-button mt-20 wow fadeInUp" data-wow-delay="1s">← Kembali ke Daftar Blog</a>
        </div>
      </div>
    </div>
</section>
@endsection
