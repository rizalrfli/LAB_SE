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
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Edit Admin</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Perbarui data akun admin</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <div class="row">
            <div class="col-lg-8">
                <div class="card wow fadeInUp form-card" data-wow-delay=".3s">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="lni lni-pencil text-primary"></i> Form Edit Admin
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.user.update', ['id' => $user['id']]) }}" id="formEditUser">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user['id'] }}">
                            <div class="mb-4">
                                <label for="username" class="form-label form-label-custom">
                                    <i class="lni lni-user"></i> Username <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg form-input-custom"
                                    id="username"
                                    name="username"
                                    value="{{ old('username', $user['username']) }}"
                                    required
                                    minlength="3"
                                    maxlength="50"
                                    autofocus>
                                <div class="form-text">
                                    <i class="lni lni-information"></i> Username untuk login ke sistem (minimal 3 karakter, maksimal 50 karakter)
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label form-label-custom">
                                    <i class="lni lni-lock"></i> Password Baru (Opsional)
                                </label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg form-input-custom"
                                    id="password"
                                    name="password"
                                    placeholder="Kosongkan jika tidak ingin mengubah password"
                                    minlength="5">
                                <div class="form-text">
                                    <i class="lni lni-information"></i> Kosongkan jika tidak ingin mengubah password
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirm" class="form-label form-label-custom">
                                    <i class="lni lni-lock-alt"></i> Konfirmasi Password Baru
                                </label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg form-input-custom"
                                    id="password_confirm"
                                    name="password_confirm"
                                    placeholder="Masukkan ulang password baru">
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-4" id="btnUpdate">
                                    <i class="lni lni-save me-2"></i> Update Admin
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
                            <i class="lni lni-database me-2"></i> Data Saat Ini
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong>Username:</strong><br>
                                <span class="text-muted">{{ $user['username'] }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Role:</strong><br>
                                <span class="badge bg-danger">Admin</span>
                            </li>
                            <li class="mb-2">
                                <strong>Tanggal Dibuat:</strong><br>
                                <span class="text-muted">{{ !empty($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : '-' }}</span>
                            </li>
                            <li>
                                <strong>Terakhir Diupdate:</strong><br>
                                <span class="text-muted">{{ !empty($user['updated_at']) ? date('d/m/Y H:i', strtotime($user['updated_at'])) : '-' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
