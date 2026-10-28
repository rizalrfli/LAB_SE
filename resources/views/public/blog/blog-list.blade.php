@extends('layouts.public')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/blog-list.css') }}" />
@endpush

@section('content')
<section class="pt-150 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center mb-30">
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Blog &amp; <span>Artikel</span></h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Artikel dan berita terkini dari Laboratorium Software Engineering</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="blog-list" class="pt-100 pb-70">
    <div class="container">
        <div class="row">
            @if (!empty($blogs))
                @foreach ($blogs as $blog)
                    @php
                        $imgRaw = $blog['featured_image'] ?? '';
                        $imgSrc = $imgRaw
                            ? (str_starts_with($imgRaw, 'http') ? $imgRaw : asset(ltrim(str_replace('../../', '', $imgRaw), '/')))
                            : asset('assets/img/blog/blog-1-1.jpg');
                        $detailLink = !empty($blog['slug'])
                            ? route('blog.show', ['slug' => $blog['slug']])
                            : route('blog.show', ['id' => $blog['id']]);
                        $dateStr = !empty($blog['published_at'])
                            ? date('d M Y', strtotime($blog['published_at']))
                            : date('d M Y', strtotime($blog['created_at'] ?? 'now'));
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="blog-card wow fadeInUp" data-wow-delay=".2s">
                            <div class="blog-image">
                                <img src="{{ $imgSrc }}"
                                     alt="{{ $blog['title'] }}"
                                     onerror="this.src='{{ asset('assets/img/blog/blog-1-1.jpg') }}';">
                                <div class="blog-category">
                                    {{ ucfirst($blog['category'] ?? 'Uncategorized') }}
                                </div>
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span><i class="lni lni-calendar"></i> {{ $dateStr }}</span>
                                    <span><i class="lni lni-eye"></i> {{ number_format((int) ($blog['views'] ?? 0)) }} views</span>
                                </div>
                                <h3 class="blog-title">
                                    <a href="{{ $detailLink }}">{{ $blog['title'] }}</a>
                                </h3>
                                <p class="blog-excerpt">
                                    {{ !empty($blog['excerpt']) ? $blog['excerpt'] : \Illuminate\Support\Str::limit(strip_tags($blog['content'] ?? ''), 150) }}
                                </p>
                                <a href="{{ $detailLink }}" class="read-more">
                                    Baca Selengkapnya <i class="lni lni-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada artikel yang dipublikasi.</p>
                </div>
            @endif
        </div>

        @if ($totalPages > 1)
            <div class="row">
                <div class="col-lg-12">
                    <div class="pagination-wrapper text-center mt-50">
                        <ul class="pagination">
                            @if ($currentPage > 1)
                                <li><a href="?page={{ $currentPage - 1 }}"><i class="lni lni-chevron-left"></i></a></li>
                            @endif

                            @for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                                <li class="{{ $i === $currentPage ? 'active' : '' }}">
                                    <a href="?page={{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($currentPage < $totalPages)
                                <li><a href="?page={{ $currentPage + 1 }}"><i class="lni lni-chevron-right"></i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
