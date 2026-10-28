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
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Edit Jabatan</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Perbarui nama jabatan/posisi</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <div class="row">
            <div class="col-lg-8">
                <div class="card wow fadeInUp form-card" data-wow-delay=".3s">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="lni lni-pencil text-primary"></i> Form Edit Jabatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.jabatan.update', ['id' => $jabatan['id']]) }}" id="formEditJabatan">
                            @csrf
                            <input type="hidden" name="id" value="{{ $jabatan['id'] }}">
                            <div class="mb-4">
                                <label for="nama_jabatan" class="form-label form-label-custom">
                                    <i class="lni lni-tag"></i> Nama Jabatan <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg form-input-custom"
                                    id="nama_jabatan"
                                    name="nama_jabatan"
                                    value="{{ old('nama_jabatan', $jabatan['nama_jabatan']) }}"
                                    placeholder="Contoh: Ketua Lab, Wakil Ketua, Dosen Pengampu"
                                    required
                                    minlength="3"
                                    maxlength="100"
                                    autofocus>
                                <div class="form-text">
                                    <i class="lni lni-information"></i> Masukkan nama jabatan atau posisi di laboratorium (minimal 3 karakter)
                                </div>
                                <div class="invalid-feedback" id="nama_jabatan_error"></div>
                            </div>

                            @if($jumlahAnggota > 0)
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="lni lni-warning me-2 fs-5"></i>
                                <div>
                                    <strong>Perhatian:</strong> Jabatan ini sedang digunakan oleh <strong>{{ $jumlahAnggota }} anggota</strong>.
                                    Perubahan nama jabatan akan mempengaruhi semua anggota yang menggunakan jabatan ini.
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="lni lni-bulb me-2 fs-5"></i>
                                <div>
                                    <strong>Info:</strong> Jabatan ini belum digunakan oleh anggota. Anda dapat mengubah nama jabatan dengan bebas.
                                </div>
                            </div>
                            @endif

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-4" id="btnUpdate">
                                    <i class="lni lni-save me-2"></i> Update Jabatan
                                </button>
                                <a href="{{ route('admin.jabatan.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="lni lni-arrow-left me-2"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card wow fadeInUp mb-3" data-wow-delay=".4s" style="border-left: 4px solid #667eea;">
                    <div class="card-body">
                        <h6 class="card-title text-primary mb-3">
                            <i class="lni lni-file me-2"></i> Data Saat Ini
                        </h6>
                        <div class="mb-2">
                            <small class="text-muted d-block">Nama Jabatan</small>
                            <strong>{{ $jabatan['nama_jabatan'] }}</strong>
                        </div>
                        @if(!empty($jabatan['created_at']))
                        <div class="mb-2">
                            <small class="text-muted d-block"><i class="lni lni-calendar me-1"></i> Dibuat</small>
                            <small>{{ date('d M Y', strtotime($jabatan['created_at'])) }}</small>
                        </div>
                        @endif
                        @if($jumlahAnggota > 0)
                        <div>
                            <small class="text-muted d-block">Digunakan Oleh</small>
                            <span class="badge bg-success">{{ $jumlahAnggota }} Anggota</span>
                        </div>
                        @else
                        <div>
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-secondary">Belum Digunakan</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
