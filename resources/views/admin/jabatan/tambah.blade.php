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
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Tambah Jabatan</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Masukkan nama jabatan/posisi</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <div class="row">
            <!-- Form Section -->
            <div class="col-lg-8">
                <div class="card wow fadeInUp form-card" data-wow-delay=".3s">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="lni lni-briefcase text-primary"></i> Form Tambah Jabatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.jabatan.store') }}" id="formTambahJabatan">
                            @csrf
                            <div class="mb-4">
                                <label for="nama_jabatan" class="form-label form-label-custom">
                                    <i class="lni lni-tag"></i> Nama Jabatan <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg form-input-custom"
                                    id="nama_jabatan"
                                    name="nama_jabatan"
                                    value="{{ old('nama_jabatan') }}"
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

                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="lni lni-bulb me-2 fs-5"></i>
                                <div>
                                    <strong>Tips:</strong> Pastikan nama jabatan unik dan jelas. Jabatan ini akan digunakan untuk mengelompokkan anggota laboratorium.
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-4" id="btnSimpan">
                                    <i class="lni lni-save me-2"></i> Simpan Jabatan
                                </button>
                                <a href="{{ route('admin.jabatan.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="lni lni-arrow-left me-2"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="col-lg-4">
                <div class="card wow fadeInUp mb-3" data-wow-delay=".4s" style="border-left: 4px solid #667eea;">
                    <div class="card-body">
                        <h6 class="card-title text-primary mb-3">
                            <i class="lni lni-information me-2"></i> Informasi
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="lni lni-checkmark-circle text-success me-2"></i>
                                <small>Nama jabatan harus unik</small>
                            </li>
                            <li class="mb-2">
                                <i class="lni lni-checkmark-circle text-success me-2"></i>
                                <small>Minimal 3 karakter</small>
                            </li>
                            <li class="mb-2">
                                <i class="lni lni-checkmark-circle text-success me-2"></i>
                                <small>Maksimal 100 karakter</small>
                            </li>
                            <li>
                                <i class="lni lni-checkmark-circle text-success me-2"></i>
                                <small>Dapat diedit nanti</small>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card wow fadeInUp mb-3" data-wow-delay=".5s" style="border-left: 4px solid #28a745;">
                    <div class="card-body">
                        <h6 class="card-title text-success mb-3">
                            <i class="lni lni-lightbulb me-2"></i> Contoh Jabatan
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark">Ketua Lab</span>
                            <span class="badge bg-light text-dark">Wakil Ketua</span>
                            <span class="badge bg-light text-dark">Dosen Pengampu</span>
                            <span class="badge bg-light text-dark">Koordinator</span>
                            <span class="badge bg-light text-dark">Sekretaris</span>
                            <span class="badge bg-light text-dark">Bendahara</span>
                        </div>
                    </div>
                </div>

                <div class="card wow fadeInUp" data-wow-delay=".6s" style="border-left: 4px solid #ffc107;">
                    <div class="card-body">
                        <h6 class="card-title text-warning mb-3">
                            <i class="lni lni-question-circle me-2"></i> Butuh Bantuan?
                        </h6>
                        <p class="card-text small mb-2">
                            Jika Anda bingung menentukan nama jabatan, lihat contoh di atas atau hubungi administrator.
                        </p>
                        <a href="{{ route('admin.jabatan.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="lni lni-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/classes/ToastNotification.js') }}"></script>
<script src="{{ asset('assets/js/classes/LoadingState.js') }}"></script>
@endpush
