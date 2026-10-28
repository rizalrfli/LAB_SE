@extends('layouts.public')

@section('content')
<section class="pt-150 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center mb-30">
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s"><span>Roadmap</span></h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Perjalanan dan Rencana Pengembangan Laboratorium Software Engineering</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="roadmap-content" class="pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if (empty($periodes))
                    <p class="text-center alert alert-warning">Roadmap belum diatur di Admin Panel.</p>
                @endif

                @foreach ($periodes as $periode)
                    @php
                        $parts = explode(' ', $periode['tahun_periode'] ?? '', 2);
                        $tahun = $parts[0] ?? '';
                        $kuartal = $parts[1] ?? '';
                        $itemsTugas = $periode['items'] ?? [];
                    @endphp
                    <div class="roadmap-card">
                        <div class="roadmap-header">
                            <h3>{{ $tahun }}</h3>
                            <div class="roadmap-year">
                                <h4>{{ $kuartal }}</h4>
                            </div>
                        </div>
                        <div class="roadmap-content">
                            <h5>{{ $periode['judul_fase'] }}</h5>
                            @if (!empty($itemsTugas))
                                <ul>
                                    @foreach ($itemsTugas as $tugas)
                                        <li>
                                            <i class="lni lni-checkmark-circle"></i>
                                            {{ $tugas }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Tugas untuk fase ini belum diatur.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
