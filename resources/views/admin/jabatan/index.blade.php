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
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Kelola Jabatan</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Kelola daftar jabatan/posisi di laboratorium</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <!-- Stat Cards -->
        <div class="row mb-30">
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".2s">
                    <div class="stat-card-icon stat-card-icon-primary">
                        <i class="lni lni-briefcase"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $totalJabatan }}</h3>
                        <p class="stat-card-label">Total Jabatan</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".3s">
                    <div class="stat-card-icon stat-card-icon-success">
                        <i class="lni lni-users"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $jabatanTerpakai }}</h3>
                        <p class="stat-card-label">Jabatan Terpakai</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".4s">
                    <div class="stat-card-icon stat-card-icon-info">
                        <i class="lni lni-empty-file"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $jabatanKosong }}</h3>
                        <p class="stat-card-label">Jabatan Kosong</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row mb-20">
            <div class="col-lg-12">
                <div class="jabatan-search-box wow fadeInUp" data-wow-delay=".2s">
                    <i class="lni lni-search"></i>
                    <input type="text" id="searchJabatan" class="jabatan-search-input" placeholder="Cari jabatan..." value="{{ $search }}">
                    @if(!empty($search))
                        <a href="{{ route('admin.jabatan.index') }}" class="jabatan-search-clear" title="Hapus pencarian">
                            <i class="lni lni-close"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="row mb-30">
            <div class="col-lg-12">
                <a href="{{ route('admin.jabatan.create') }}" class="button button-sm radius-10 wow fadeInUp" data-wow-delay=".3s">
                    <i class="lni lni-plus"></i> Tambah Jabatan
                </a>
            </div>
        </div>

        <!-- Info Box -->
        @if($totalJabatan > 0)
        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="info-box wow fadeInUp" data-wow-delay=".3s">
                    <div class="info-box-icon">
                        <i class="lni lni-information"></i>
                    </div>
                    <div class="info-box-content">
                        <h4 class="info-box-title">Tips Mengelola Jabatan</h4>
                        <p class="info-box-text">
                            Jabatan yang sudah digunakan oleh anggota tidak dapat dihapus. Pastikan untuk menghapus atau mengubah jabatan anggota terlebih dahulu sebelum menghapus jabatan.
                            @if($jabatanKosong > 0)
                                <br><strong>Anda memiliki {{ $jabatanKosong }} jabatan yang belum digunakan.</strong>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                @if(count($jabatanList) > 0)
                    <div class="table-responsive wow fadeInUp" data-wow-delay=".3s">
                        <table class="table table-striped table-hover admin-table">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Nama Jabatan</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jabatanList as $idx => $jabatan)
                                    @php
                                        $jumlahAnggota = (int) ($jabatan['jumlah_anggota'] ?? 0);
                                        $isTerpakai = $jumlahAnggota > 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div class="jabatan-icon-badge">
                                                    <i class="lni lni-briefcase"></i>
                                                </div>
                                                <div style="flex: 1;">
                                                    <strong>{{ $jabatan['nama_jabatan'] }}</strong>
                                                    @if($isTerpakai)
                                                        <div class="jabatan-usage-badge">
                                                            <i class="lni lni-users"></i> Digunakan oleh {{ $jumlahAnggota }} anggota
                                                        </div>
                                                    @else
                                                        <div class="jabatan-usage-badge jabatan-usage-empty">
                                                            <i class="lni lni-empty-file"></i> Belum digunakan
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.jabatan.edit', ['id' => $jabatan['id']]) }}" class="btn btn-primary btn-sm">
                                                    <i class="lni lni-pencil me-1"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDeleteJabatan({{ (int) $jabatan['id'] }}, '{{ addslashes($jabatan['nama_jabatan']) }}', {{ $isTerpakai ? 'true' : 'false' }}, {{ $jumlahAnggota }})"
                                                    {{ $isTerpakai ? 'disabled title="Jabatan sedang digunakan, tidak dapat dihapus"' : '' }}>
                                                    <i class="lni lni-trash me-1"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state wow fadeInUp" data-wow-delay=".3s">
                        <i class="lni lni-briefcase empty-state-icon"></i>
                        <h3 class="empty-state-title">Belum Ada Jabatan</h3>
                        <p class="empty-state-message">Mulai dengan menambahkan jabatan pertama ke dalam sistem untuk mengorganisir posisi di laboratorium.</p>
                        <a href="{{ route('admin.jabatan.create') }}" class="empty-state-action">
                            <i class="lni lni-plus"></i> Tambah Jabatan Pertama
                        </a>
                    </div>
                @endif
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
const jabatanIndexUrl = @json(route('admin.jabatan.index'));
const jabatanDestroyUrl = @json(route('admin.jabatan.destroy'));

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchJabatan');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length >= 2 || query.length === 0) {
                searchTimeout = setTimeout(function() {
                    window.location.href = query.length > 0
                        ? jabatanIndexUrl + '?search=' + encodeURIComponent(query)
                        : jabatanIndexUrl;
                }, 500);
            }
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                window.location.href = query.length > 0
                    ? jabatanIndexUrl + '?search=' + encodeURIComponent(query)
                    : jabatanIndexUrl;
            }
        });
    }
});

function confirmDeleteJabatan(id, nama, isTerpakai, jumlahAnggota) {
    if (isTerpakai) {
        if (window.toast) {
            window.toast.warning('Jabatan ini sedang digunakan oleh ' + jumlahAnggota + ' anggota. Hapus atau ubah jabatan anggota terlebih dahulu.', 'Tidak Dapat Dihapus');
        }
        return;
    }

    if (window.anggotaConfirmModal) {
        window.anggotaConfirmModal.confirmDelete(nama, function() {
            if (window.loading) {
                window.loading.show('Menghapus jabatan...');
            }

            fetch(jabatanDestroyUrl + '?id=' + id, {
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
                    if (window.toast) {
                        window.toast.success(data.message || 'Jabatan berhasil dihapus!', 'Berhasil');
                    }
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    if (window.toast) {
                        window.toast.error(data.message || 'Gagal menghapus jabatan.', 'Error');
                    }
                }
            })
            .catch(() => {
                if (window.loading) window.loading.hide();
                if (window.toast) {
                    window.toast.error('Terjadi kesalahan. Silakan coba lagi.', 'Error');
                }
            });
        });
    } else if (confirm('Yakin ingin menghapus jabatan "' + nama + '"?')) {
        window.location.href = jabatanDestroyUrl + '?id=' + id;
    }
}
</script>
@endpush
