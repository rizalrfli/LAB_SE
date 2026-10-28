@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/anggota-ui.css') }}">
@endpush

@section('content')
<section class="pt-50 pb-50 admin-section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb-50">
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Kelola Publikasi</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Kelola daftar jurnal dan prosiding konferensi ilmiah laboratorium</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <!-- Stat Cards -->
        <div class="row mb-30">
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".2s">
                    <div class="stat-card-icon stat-card-icon-primary"><i class="lni lni-book"></i></div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $totalPublikasi }}</h3>
                        <p class="stat-card-label">Total Publikasi</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".3s">
                    <div class="stat-card-icon stat-card-icon-success"><i class="lni lni-files"></i></div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $journalCount }}</h3>
                        <p class="stat-card-label">Journal</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".4s">
                    <div class="stat-card-icon stat-card-icon-info"><i class="lni lni-mic"></i></div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $conferenceCount }}</h3>
                        <p class="stat-card-label">Conference</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row mb-20">
            <div class="col-lg-12">
                <form method="GET" action="{{ route('admin.publikasi.index') }}" class="jabatan-search-box wow fadeInUp" data-wow-delay=".2s">
                    <i class="lni lni-search"></i>
                    <input type="text" name="search" class="jabatan-search-input" placeholder="Cari judul publikasi, penulis, atau nama jurnal/konferensi..." value="{{ $search }}">
                    @if(!empty($search))
                        <a href="{{ route('admin.publikasi.index') }}" class="jabatan-search-clear" title="Hapus pencarian"><i class="lni lni-close"></i></a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="row mb-30">
            <div class="col-lg-12">
                <a href="{{ route('admin.publikasi.create') }}" class="btn btn-primary btn-lg wow fadeInUp" data-wow-delay=".3s">
                    <i class="lni lni-plus me-2"></i> Tambah Publikasi
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="row">
            <div class="col-lg-12">
                @if(count($publikasiList) > 0)
                    <div class="table-responsive wow fadeInUp" data-wow-delay=".3s">
                        <table class="table table-striped table-hover admin-table">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Judul Publikasi</th>
                                    <th>Penulis Utama</th>
                                    <th>Tipe</th>
                                    <th>Jurnal / Konferensi</th>
                                    <th>Tahun</th>
                                <th>Sitasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($publikasiList as $idx => $pub)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>
                                            <strong>{{ $pub['judul'] }}</strong>
                                            @if(!empty($pub['scopus_indexed']))
                                                <span class="badge bg-warning text-dark ms-1">Scopus {{ $pub['quartile'] ?? '' }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $pub['penulis_utama'] ?? '-' }}</td>
                                        <td>
                                            @if(!empty($pub['tipe']))
                                                <span class="badge {{ $pub['tipe'] === 'journal' ? 'bg-success' : 'bg-info' }}">
                                                    {{ ucfirst($pub['tipe']) }}
                                                </span>
                                            @else
                                                <span class="text-muted">Tidak dicatat</span>
                                            @endif
                                        </td>
                                        <td>{{ $pub['nama_journal_conference'] ?? '-' }}</td>
                                        <td><span class="badge bg-secondary">{{ $pub['tahun'] }}</span></td>
                                        <td class="text-center">{{ (int) ($pub['sitasi'] ?? 0) }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.publikasi.edit', ['id' => $pub['id']]) }}" class="btn btn-primary btn-sm">
                                                    <i class="lni lni-pencil me-1"></i> Edit
                                                </a>
                                                <a href="{{ route('admin.publikasi.index', ['hapus' => 1, 'id' => $pub['id']]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus publikasi ini?')">
                                                    <i class="lni lni-trash me-1"></i> Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state wow fadeInUp" data-wow-delay=".3s">
                        <i class="lni lni-book empty-state-icon"></i>
                        <h3 class="empty-state-title">Belum Ada Data Publikasi</h3>
                        <p class="empty-state-message">Mulai dengan menambahkan data publikasi ilmiah pertama.</p>
                        <a href="{{ route('admin.publikasi.create') }}" class="empty-state-action">
                            <i class="lni lni-plus"></i> Tambah Publikasi Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
