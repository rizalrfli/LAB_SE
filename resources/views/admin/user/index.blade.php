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
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Kelola User</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Kelola data akun admin</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <!-- Stat Cards -->
        <div class="row mb-30">
            <div class="col-lg-6 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".2s">
                    <div class="stat-card-icon stat-card-icon-primary">
                        <i class="lni lni-user"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ count($users) }}</h3>
                        <p class="stat-card-label">Total Admin</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".3s">
                    <div class="stat-card-icon stat-card-icon-danger">
                        <i class="lni lni-shield"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $totalAdmin }}</h3>
                        <p class="stat-card-label">Admin Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row mb-20">
            <div class="col-lg-12">
                <div class="jabatan-search-box wow fadeInUp" data-wow-delay=".2s">
                    <i class="lni lni-search"></i>
                    <input type="text" id="searchUser" class="jabatan-search-input" placeholder="Cari admin berdasarkan username..." value="{{ $search }}">
                    @if(!empty($search))
                        <a href="{{ route('admin.user.index') }}" class="jabatan-search-clear" title="Hapus pencarian">
                            <i class="lni lni-close"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="row mb-30">
            <div class="col-lg-12">
                <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-lg wow fadeInUp" data-wow-delay=".3s">
                    <i class="lni lni-plus me-2"></i> Tambah User
                </a>
            </div>
        </div>

        <!-- Info Box -->
        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="info-box wow fadeInUp" data-wow-delay=".3s">
                    <div class="info-box-icon">
                        <i class="lni lni-information"></i>
                    </div>
                    <div class="info-box-content">
                        <h4 class="info-box-title">Tips Mengelola User</h4>
                        <p class="info-box-text">
                            Halaman ini hanya dapat diakses oleh ADMIN. Anda dapat mengelola semua akun admin di sini.
                            @if($totalAdmin > 0)
                                <br><strong>Total: {{ $totalAdmin }} admin</strong>
                            @endif
                            <br><small class="text-muted">Anda tidak dapat menghapus user yang sedang aktif (user yang sedang login).</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive wow fadeInUp" data-wow-delay=".3s">
                    <table class="table table-striped table-hover admin-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Username</th>
                                <th>Tanggal Dibuat</th>
                                <th>Terakhir Diupdate</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $currentUserId = (int) (auth()->id() ?? session('user_id', 0));
                            @endphp
                            @forelse($users as $idx => $user)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td><strong>{{ $user['username'] }}</strong></td>
                                    <td>{{ !empty($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : '-' }}</td>
                                    <td>{{ !empty($user['updated_at']) ? date('d/m/Y H:i', strtotime($user['updated_at'])) : '-' }}</td>
                                    <td style="text-align: center;">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.user.edit', ['id' => $user['id']]) }}" class="btn btn-primary btn-sm">
                                                <i class="lni lni-pencil me-1"></i> Edit
                                            </a>
                                            @if((int) $user['id'] !== $currentUserId)
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteUser({{ (int) $user['id'] }}, '{{ addslashes($user['username']) }}')">
                                                    <i class="lni lni-trash me-1"></i> Hapus
                                                </button>
                                            @else
                                                <span class="badge bg-secondary">User Aktif</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="empty-state wow fadeInUp" data-wow-delay=".3s">
                                            <i class="lni lni-user empty-state-icon"></i>
                                            <h3 class="empty-state-title">Belum Ada User</h3>
                                            <p class="empty-state-message">Mulai dengan menambahkan user pertama ke dalam sistem.</p>
                                            <a href="{{ route('admin.user.create') }}" class="empty-state-action">
                                                <i class="lni lni-plus"></i> Tambah User Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/classes/ToastNotification.js') }}"></script>
<script src="{{ asset('assets/js/classes/LoadingState.js') }}"></script>
<script src="{{ asset('assets/js/classes/AnggotaConfirmModal.js') }}"></script>
<script>
const userIndexUrl = @json(route('admin.user.index'));
const userDestroyUrl = @json(route('admin.user.destroy'));

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchUser');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            searchTimeout = setTimeout(function() {
                if (query.length > 0 || window.location.search.includes('search=')) {
                    window.location.href = userIndexUrl + (query.length > 0 ? '?search=' + encodeURIComponent(query) : '');
                }
            }, 500);
        });
    }

    if (typeof ToastNotification !== 'undefined') window.toast = new ToastNotification();
    if (typeof LoadingState !== 'undefined') window.loading = new LoadingState();
    if (typeof AnggotaConfirmModal !== 'undefined') window.userConfirmModal = new AnggotaConfirmModal();
});

function confirmDeleteUser(id, username) {
    if (window.userConfirmModal) {
        window.userConfirmModal.confirmDelete('User "' + username + '"', function() {
            if (window.loading) window.loading.show('Menghapus user...');

            fetch(userDestroyUrl + '?id=' + id, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (window.loading) window.loading.hide();
                if (data.success) {
                    if (window.toast) window.toast.success(data.message || 'User berhasil dihapus!', 'Berhasil');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    if (window.toast) window.toast.error(data.message || 'Gagal menghapus user.', 'Error');
                }
            })
            .catch(() => {
                if (window.loading) window.loading.hide();
                if (window.toast) window.toast.error('Terjadi kesalahan. Silakan coba lagi.', 'Error');
            });
        });
    } else if (confirm('Apakah Anda yakin ingin menghapus user "' + username + '"?')) {
        window.location.href = userDestroyUrl + '?id=' + id;
    }
}
</script>
@endpush
