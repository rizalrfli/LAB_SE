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
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Kelola Penelitian</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Kelola data proyek dan aktivitas penelitian laboratorium</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <!-- Stat Cards -->
        <div class="row mb-30">
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".2s">
                    <div class="stat-card-icon stat-card-icon-primary"><i class="lni lni-microscope"></i></div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $totalPenelitian }}</h3>
                        <p class="stat-card-label">Total Penelitian</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".3s">
                    <div class="stat-card-icon stat-card-icon-info"><i class="lni lni-reload"></i></div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $ongoingCount }}</h3>
                        <p class="stat-card-label">Ongoing</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".4s">
                    <div class="stat-card-icon stat-card-icon-success"><i class="lni lni-checkmark-circle"></i></div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $completedCount }}</h3>
                        <p class="stat-card-label">Completed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row mb-20">
            <div class="col-lg-12">
                <form method="GET" action="{{ route('admin.penelitian.index') }}" class="jabatan-search-box wow fadeInUp" data-wow-delay=".2s">
                    <i class="lni lni-search"></i>
                    <input type="text" name="search" class="jabatan-search-input" placeholder="Cari judul penelitian, peneliti, atau kategori..." value="{{ $search }}">
                    @if(!empty($search))
                        <a href="{{ route('admin.penelitian.index') }}" class="jabatan-search-clear" title="Hapus pencarian"><i class="lni lni-close"></i></a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="row mb-30">
            <div class="col-lg-12">
                <a href="{{ route('admin.penelitian.create') }}" class="btn btn-primary btn-lg wow fadeInUp" data-wow-delay=".3s">
                    <i class="lni lni-plus me-2"></i> Tambah Penelitian
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="row">
            <div class="col-lg-12">
                @if(count($penelitianList) > 0)
                    <div class="table-responsive wow fadeInUp" data-wow-delay=".3s">
                        <table class="table table-striped table-hover admin-table">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Judul Penelitian</th>
                                    <th>Peneliti Utama</th>
                                    <th>Tahun</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penelitianList as $idx => $p)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>
                                            <strong>{{ $p['judul'] }}</strong>
                                            @if(!empty($p['deskripsi']))
                                                <br><small class="text-muted">{{ Str::limit($p['deskripsi'], 60) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $p['peneliti_utama'] ?? '-' }}</td>
                                        <td><span class="badge bg-secondary">{{ $p['tahun'] }}</span></td>
                                        <td>{{ $p['kategori'] ?? '-' }}</td>
                                        <td>
                                            @if(($p['status'] ?? 'ongoing') === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Ongoing ({{ (int) ($p['progress'] ?? 0) }}%)</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.penelitian.edit', ['id' => $p['id']]) }}" class="btn btn-primary btn-sm">
                                                    <i class="lni lni-pencil me-1"></i> Edit
                                                </a>
                                                <a href="{{ route('admin.penelitian.index', ['hapus' => 1, 'id' => $p['id']]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus penelitian ini?')">
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
                        <i class="lni lni-microscope empty-state-icon"></i>
                        <h3 class="empty-state-title">Belum Ada Data Penelitian</h3>
                        <p class="empty-state-message">Mulai dengan menambahkan data penelitian pertama.</p>
                        <a href="{{ route('admin.penelitian.create') }}" class="empty-state-action">
                            <i class="lni lni-plus"></i> Tambah Penelitian Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
