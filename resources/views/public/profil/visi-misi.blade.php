@extends('layouts.public')

@section('content')
<section class="pt-150 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center mb-30">
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Visi &amp; <span>Misi</span></h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Arah dan komitmen Laboratorium Software Engineering</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="visi-misi-content" class="pt-100 pb-70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="vision-card wow fadeInUp" data-wow-delay=".2s">
                    <div class="card-header-custom">
                        <div class="card-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, #2F80ED 0%, #1E5FCC 100%); border-radius: 12px; display: flex !important; align-items: center !important; justify-content: center !important; color: #ffffff !important; font-size: 24px !important; margin-right: 20px !important; box-shadow: 0 4px 15px rgba(47, 128, 237, 0.3) !important; flex-shrink: 0 !important;">
                            <i class="lni lni-rocket" style="font-size: 24px !important; color: #ffffff !important; display: inline-block !important; line-height: 1 !important; font-family: 'LineIcons' !important;" aria-hidden="true"></i>
                        </div>
                        <h3 class="card-title-custom">{{ !empty($visi['title']) ? $visi['title'] : 'Visi' }}</h3>
                    </div>
                    <p class="vision-text">
                        {!! nl2br(e($visi['content'] ?? '')) !!}
                    </p>
                </div>

                <div class="mission-card wow fadeInUp" data-wow-delay=".4s">
                    <div class="card-header-custom">
                        <div class="card-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, #27AE60 0%, #229954 100%); border-radius: 12px; display: flex !important; align-items: center !important; justify-content: center !important; color: #ffffff !important; font-size: 24px !important; margin-right: 20px !important; box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3) !important; flex-shrink: 0 !important;">
                            <i class="lni lni-target" style="font-size: 24px !important; color: #ffffff !important; display: inline-block !important; line-height: 1 !important; font-family: 'LineIcons' !important;" aria-hidden="true"></i>
                        </div>
                        <h3 class="card-title-custom">Misi Kami</h3>
                    </div>

                    @if (!empty($misiList))
                        <ul class="mission-list">
                            @foreach ($misiList as $item)
                                <li>
                                    <p class="mission-text">
                                        {!! nl2br(e($item['content'] ?? '')) !!}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Konten misi belum diatur di Admin Panel.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
