@extends('layouts.public')

@section('content')
<section id="penelitian-publikasi-content" class="pb-70" style="padding-top: 130px;">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="research-tabs-container wow fadeInUp" data-wow-delay=".2s">
          <div class="tab-buttons" id="researchTabs" role="tablist">
            <button class="tab-btn" id="penelitian-tab" data-bs-toggle="tab" data-bs-target="#penelitian" type="button" role="tab" aria-controls="penelitian" aria-selected="false">
              <i class="lni lni-microscope"></i>
              <span>Penelitian</span>
            </button>
            <button class="tab-btn active" id="publikasi-tab" data-bs-toggle="tab" data-bs-target="#publikasi" type="button" role="tab" aria-controls="publikasi" aria-selected="true">
              <i class="lni lni-book"></i>
              <span>Publikasi</span>
            </button>
          </div>
        </div>

        <div class="tab-content-container wow fadeInUp" data-wow-delay=".3s">
          <div class="tab-content" id="researchTabsContent">
            <!-- Penelitian Tab -->
            <div class="tab-pane fade" id="penelitian" role="tabpanel" aria-labelledby="penelitian-tab">
              <div class="tab-content-inner">
                <h2 class="tab-title">Penelitian</h2>
                <p class="tab-intro">Daftar penelitian yang sedang dan telah dilakukan oleh Laboratorium Software Engineering:</p>

                @if (count($penelitianList) > 0)
                  <div class="research-list">
                    @foreach ($penelitianList as $p)
                      <div class="research-item-card">
                        <div class="research-header">
                          <span class="research-year-badge">{{ $p['tahun'] }}</span>
                          <span class="research-status-badge {{ ($p['status'] ?? '') === 'ongoing' ? 'status-ongoing' : 'status-completed' }}">
                            {{ ($p['status'] ?? '') === 'ongoing' ? 'Sedang Berlangsung' : 'Selesai' }}
                          </span>
                        </div>
                        <h3 class="research-title-main">{{ $p['judul'] }}</h3>
                        @if (!empty($p['deskripsi']))
                          <p class="research-description-text">{!! nl2br(e($p['deskripsi'])) !!}</p>
                        @endif
                        <div class="research-details-grid">
                          @if (!empty($p['peneliti_utama']))
                            <div class="research-detail-item">
                              <i class="lni lni-user"></i>
                              <span>{{ $p['peneliti_utama'] }}</span>
                            </div>
                          @endif
                          @if (!empty($p['kategori']))
                            <div class="research-detail-item">
                              <i class="lni lni-folder"></i>
                              <span>{{ $p['kategori'] }}</span>
                            </div>
                          @endif
                          @if (!empty($p['jumlah_tim']) && $p['jumlah_tim'] > 0)
                            <div class="research-detail-item">
                              <i class="lni lni-users"></i>
                              <span>{{ (int) $p['jumlah_tim'] }} Peneliti</span>
                            </div>
                          @endif
                          @if (!empty($p['progress']))
                            <div class="research-detail-item">
                              <i class="lni lni-bar-chart"></i>
                              <span>Progress: {{ (int) $p['progress'] }}%</span>
                            </div>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="empty-state" style="text-align: center; padding: 60px 20px;">
                    <i class="lni lni-microscope" style="font-size: 64px; color: #dee2e6; margin-bottom: 20px;"></i>
                    <h3 style="color: #6c757d; margin-bottom: 10px;">Belum Ada Data Penelitian</h3>
                    <p style="color: #adb5bd;">Data penelitian akan ditampilkan di sini setelah ditambahkan melalui admin panel.</p>
                  </div>
                @endif
              </div>
            </div>

            <!-- Publikasi Tab -->
            <div class="tab-pane fade show active" id="publikasi" role="tabpanel" aria-labelledby="publikasi-tab">
              <div class="tab-content-inner">
                <h2 class="tab-title">Publikasi</h2>
                <p class="tab-intro">Berikut ini adalah daftar publikasi di laboratorium Rekayasa Perangkat Lunak:</p>

                @if (count($publikasiList) > 0)
                  <div class="publications-grid">
                    @foreach ($publikasiList as $index => $pub)
                      <div class="pub-item-card">
                        <div class="pub-number-badge">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="pub-content">
                          <h3 class="pub-title-main">{{ $pub['judul'] }}</h3>
                          @if (!empty($pub['nama_journal_conference']))
                            <p class="pub-journal">
                              <i class="lni lni-book"></i>
                              {{ $pub['nama_journal_conference'] }}
                              @if (!empty($pub['volume'])) <span class="pub-volume">Vol. {{ $pub['volume'] }}</span> @endif
                              @if (!empty($pub['issue'])) <span class="pub-issue">No. {{ $pub['issue'] }}</span> @endif
                              @if (!empty($pub['halaman'])) <span class="pub-pages">pp. {{ $pub['halaman'] }}</span> @endif
                            </p>
                          @endif
                          @if (!empty($pub['penulis_utama']))
                            <div class="pub-author">
                              <i class="lni lni-user"></i>
                              <span>{{ $pub['penulis_utama'] }}</span>
                            </div>
                          @endif
                          <div class="pub-meta-tags">
                            @if (!empty($pub['tipe']))
                              <span class="pub-type-badge type-{{ $pub['tipe'] === 'journal' ? 'journal' : 'conference' }}">
                                <i class="lni {{ $pub['tipe'] === 'journal' ? 'lni-book' : 'lni-presentation' }}"></i>
                                {{ ucfirst($pub['tipe']) }}
                              </span>
                            @endif
                            @if (!empty($pub['quartile']))
                              <span class="pub-quartile-badge">{{ $pub['quartile'] }}</span>
                            @endif
                            @if (!empty($pub['scopus_indexed']))
                              <span class="pub-scopus-badge">
                                <i class="lni lni-checkmark-circle"></i> Scopus
                              </span>
                            @endif
                            <span class="pub-year-badge">
                              <i class="lni lni-calendar"></i>
                              {{ $pub['tahun'] }}
                            </span>
                            <span class="pub-year-badge">
                              <i class="lni lni-quote-right"></i>
                              {{ (int) ($pub['sitasi'] ?? 0) }} sitasi
                            </span>
                          </div>
                          @if (!empty($pub['tautan_scholar']))
                            <div class="pub-doi-link">
                              <a href="{{ $pub['tautan_scholar'] }}" target="_blank" rel="noopener noreferrer" class="doi-link">
                                <i class="lni lni-link"></i>
                                <span>Lihat di Google Scholar</span>
                                <i class="lni lni-arrow-right"></i>
                              </a>
                            </div>
                          @endif
                          @if (!empty($pub['doi']))
                            <div class="pub-doi-link">
                              <a href="https://doi.org/{{ $pub['doi'] }}" target="_blank" class="doi-link">
                                <i class="lni lni-link"></i>
                                <span>DOI: {{ $pub['doi'] }}</span>
                                <i class="lni lni-arrow-right"></i>
                              </a>
                            </div>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="empty-state" style="text-align: center; padding: 60px 20px;">
                    <i class="lni lni-book" style="font-size: 64px; color: #dee2e6; margin-bottom: 20px;"></i>
                    <h3 style="color: #6c757d; margin-bottom: 10px;">Belum Ada Data Publikasi</h3>
                    <p style="color: #adb5bd;">Data publikasi akan ditampilkan di sini setelah ditambahkan melalui admin panel.</p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    function resetTabs() {
        tabButtons.forEach(function(btn) {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
        });
        tabPanes.forEach(function(pane) {
            pane.classList.remove('show', 'active');
        });
    }

    function activateTab(button) {
        const targetId = button.getAttribute('data-bs-target');
        const targetPane = document.querySelector(targetId);

        resetTabs();
        button.classList.add('active');
        button.setAttribute('aria-selected', 'true');

        if (targetPane) {
            setTimeout(function() {
                targetPane.classList.add('show', 'active');
            }, 50);
        }
    }

    tabButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            activateTab(this);
        });
    });

    const activeTab = document.querySelector('.tab-btn.active');
    if (activeTab) {
        activateTab(activeTab);
    }
});
</script>
@endpush
