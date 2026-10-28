@extends('layouts.admin')

@section('content')
<section class="pt-50 pb-50 admin-section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb-40">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="mb-10">Kelola Roadmap Laboratorium</h2>
                            <p>Kelola periode tahun dan daftar fokus/tugas pada setiap fase roadmap</p>
                        </div>
                        <a href="{{ route('admin.profil.index') }}" class="btn btn-outline-secondary">
                            <i class="lni lni-arrow-left me-1"></i> Kembali ke Kelola Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <div class="row">
            <!-- Daftar Periode -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="lni lni-calendar text-primary me-2"></i> Daftar Periode Roadmap</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.roadmap.post') }}" class="mb-4 p-3 bg-light rounded">
                            @csrf
                            <input type="hidden" name="tambah_periode" value="1">
                            <h6 class="fw-bold mb-3">Tambah Periode Baru</h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" name="tahun_periode" class="form-control form-control-sm" placeholder="Tahun (mis. 2024-2025)" required>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="judul_fase" class="form-control form-control-sm" placeholder="Judul Fase" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="urutan" class="form-control form-control-sm" placeholder="Urutan" value="{{ count($periodes) + 1 }}" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success mt-2"><i class="lni lni-plus me-1"></i> Tambah Periode</button>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Urutan</th>
                                        <th>Tahun</th>
                                        <th>Judul Fase</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($periodes as $p)
                                        <tr class="{{ ($selectedPeriode && $selectedPeriode['id'] == $p['id']) ? 'table-primary' : '' }}">
                                            <td>{{ $p['urutan'] }}</td>
                                            <td><strong>{{ $p['tahun_periode'] }}</strong></td>
                                            <td>{{ $p['judul_fase'] }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.roadmap.index', ['kelola_tugas' => $p['id']]) }}" class="btn btn-info text-white" title="Kelola Tugas">
                                                        <i class="lni lni-list"></i> Tugas
                                                    </a>
                                                    <a href="{{ route('admin.roadmap.index', ['hapus_periode' => $p['id']]) }}" class="btn btn-danger" onclick="return confirm('Hapus periode ini beserta seluruh tugasnya?')" title="Hapus">
                                                        <i class="lni lni-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada periode roadmap.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kelola Tugas pada Periode Terpilih -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="lni lni-checkmark-circle text-success me-2"></i>
                            @if($selectedPeriode)
                                Tugas Periode: {{ $selectedPeriode['tahun_periode'] }} ({{ $selectedPeriode['judul_fase'] }})
                            @else
                                Pilih Periode untuk Mengelola Tugas
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($selectedPeriode)
                            <form method="POST" action="{{ route('admin.roadmap.post') }}" class="mb-4 p-3 bg-light rounded">
                                @csrf
                                <input type="hidden" name="tambah_tugas" value="1">
                                <input type="hidden" name="id_periode" value="{{ $selectedPeriode['id'] }}">
                                <h6 class="fw-bold mb-3">Tambah Tugas / Item Roadmap</h6>
                                <div class="row g-2">
                                    <div class="col-md-9">
                                        <input type="text" name="deskripsi_tugas" class="form-control form-control-sm" placeholder="Deskripsi tugas / target..." required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="urutan" class="form-control form-control-sm" placeholder="Urutan" value="{{ count($tugasList) + 1 }}" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-success mt-2"><i class="lni lni-plus me-1"></i> Tambah Tugas</button>
                            </form>

                            <ul class="list-group">
                                @forelse($tugasList as $t)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-secondary me-2">#{{ $t['urutan'] }}</span>
                                            {{ $t['deskripsi_tugas'] }}
                                        </div>
                                        <a href="{{ route('admin.roadmap.index', ['kelola_tugas' => $selectedPeriode['id'], 'hapus_tugas' => $t['id']]) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus tugas ini?')">
                                            <i class="lni lni-trash"></i>
                                        </a>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">Belum ada tugas pada periode ini.</li>
                                @endforelse
                            </ul>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="lni lni-pointer-left fs-1 d-block mb-2"></i>
                                Klik tombol <strong>Tugas</strong> pada salah satu periode di sebelah kiri untuk mengelola item tugasnya.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
