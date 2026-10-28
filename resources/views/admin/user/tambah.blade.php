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
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Tambah Admin</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Tambahkan akun admin baru ke dalam sistem</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <div class="row">
            <div class="col-lg-8">
                <div class="card wow fadeInUp form-card" data-wow-delay=".3s">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="lni lni-user text-primary"></i> Form Tambah Admin
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.user.store') }}" id="formTambahUser">
                            @csrf
                            <div class="mb-4">
                                <label for="username" class="form-label form-label-custom">
                                    <i class="lni lni-user"></i> Username <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg form-input-custom"
                                    id="username"
                                    name="username"
                                    value="{{ old('username') }}"
                                    placeholder="Contoh: admin, admin_lab, ketua_lab"
                                    required
                                    minlength="3"
                                    maxlength="50"
                                    autofocus>
                                <div class="form-text">
                                    <i class="lni lni-information"></i> Username untuk login ke sistem (minimal 3 karakter, maksimal 50 karakter)
                                </div>
                                <div class="invalid-feedback" id="username_error"></div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label form-label-custom">
                                    <i class="lni lni-lock"></i> Password <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg form-input-custom"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan password"
                                    required
                                    minlength="5">
                                <div class="form-text">
                                    <i class="lni lni-information"></i> Password untuk login (minimal 5 karakter)
                                </div>
                                <div class="invalid-feedback" id="password_error"></div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirm" class="form-label form-label-custom">
                                    <i class="lni lni-lock-alt"></i> Konfirmasi Password <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg form-input-custom"
                                    id="password_confirm"
                                    name="password_confirm"
                                    placeholder="Masukkan ulang password"
                                    required>
                                <div class="form-text">
                                    <i class="lni lni-information"></i> Masukkan password yang sama untuk konfirmasi
                                </div>
                                <div class="invalid-feedback" id="password_confirm_error"></div>
                            </div>

                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="lni lni-bulb me-2 fs-5"></i>
                                <div>
                                    <strong>Tips:</strong> Pastikan username unik dan password kuat. Simpan informasi login dengan aman.
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-4" id="btnSimpan">
                                    <i class="lni lni-save me-2"></i> Simpan Admin
                                </button>
                                <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-lg px-4">
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
                            <i class="lni lni-information me-2"></i> Informasi
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="lni lni-checkmark-circle text-success me-2"></i><small>Username harus unik</small></li>
                            <li class="mb-2"><i class="lni lni-checkmark-circle text-success me-2"></i><small>Minimal 3 karakter</small></li>
                            <li class="mb-2"><i class="lni lni-checkmark-circle text-success me-2"></i><small>Maksimal 50 karakter</small></li>
                            <li class="mb-2"><i class="lni lni-checkmark-circle text-success me-2"></i><small>Password minimal 5 karakter</small></li>
                            <li><i class="lni lni-checkmark-circle text-success me-2"></i><small>Dapat diedit nanti</small></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
