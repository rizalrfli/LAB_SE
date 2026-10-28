@extends('layouts.admin')

@section('content')
<main class="admin-main-content">
<section class="pt-50 pb-50 admin-section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb-50">
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Kelola Anggota</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Kelola data anggota/personil laboratorium</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <!-- Stat Cards -->
        <div class="row mb-30">
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".2s">
                    <div class="stat-card-icon stat-card-icon-primary">
                        <i class="lni lni-users"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $totalAnggota }}</h3>
                        <p class="stat-card-label">Total Anggota</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".3s">
                    <div class="stat-card-icon stat-card-icon-success">
                        <i class="lni lni-briefcase"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $anggotaBerjabatan }}</h3>
                        <p class="stat-card-label">Anggota Berjabatan</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-20">
                <div class="stat-card wow fadeInUp" data-wow-delay=".4s">
                    <div class="stat-card-icon stat-card-icon-info">
                        <i class="lni lni-user"></i>
                    </div>
                    <div class="stat-card-content">
                        <h3 class="stat-card-number">{{ $anggotaTanpaJabatan }}</h3>
                        <p class="stat-card-label">Tanpa Jabatan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row mb-20">
            <div class="col-lg-12">
                <div class="jabatan-search-box wow fadeInUp" data-wow-delay=".2s">
                    <i class="lni lni-search"></i>
                    <input type="text" id="searchAnggota" class="jabatan-search-input" placeholder="Cari anggota berdasarkan nama atau jabatan..." value="{{ $search }}">
                    @if (!empty($search))
                        <a href="{{ route('admin.anggota.index') }}" class="jabatan-search-clear" title="Hapus pencarian">
                            <i class="lni lni-close"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="row mb-30">
            <div class="col-lg-12">
                <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary btn-lg wow fadeInUp" data-wow-delay=".3s">
                    <i class="lni lni-plus me-2"></i> Tambah Anggota
                </a>
            </div>
        </div>

        @if ($totalAnggota > 0)
        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="info-box wow fadeInUp" data-wow-delay=".3s">
                    <div class="info-box-icon">
                        <i class="lni lni-information"></i>
                    </div>
                    <div class="info-box-content">
                        <h4 class="info-box-title">Tips Mengelola Anggota</h4>
                        <p class="info-box-text">
                            Pastikan setiap anggota memiliki foto dan informasi lengkap. Anggota tanpa jabatan dapat ditambahkan jabatan melalui menu edit.
                            @if ($anggotaTanpaJabatan > 0)
                                <br><strong>Anda memiliki {{ $anggotaTanpaJabatan }} anggota yang belum memiliki jabatan.</strong>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                @if (count($anggotaList) > 0)
                <div class="table-responsive wow fadeInUp" data-wow-delay=".3s">
                    <table class="table table-striped table-hover admin-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anggotaList as $idx => $a)
                                @php
                                    $fotoRaw = $a['foto'] ?? '';
                                    $fotoSrc = $fotoRaw
                                        ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset(ltrim(str_replace('../../', '', $fotoRaw), '/')))
                                        : '';
                                    $initial = strtoupper(substr($a['nama'] ?? 'A', 0, 1));
                                @endphp
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="flex-center">
                                            @if ($fotoSrc)
                                                <img src="{{ $fotoSrc }}" alt="{{ $a['nama'] }}" class="avatar-circle">
                                            @else
                                                <div class="initial-circle">{{ $initial }}</div>
                                            @endif
                                            <strong>{{ $a['nama'] }}</strong>
                                        </div>
                                    </td>
                                    <td>&nbsp;{{ $a['jabatan'] ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-info btn-sm" onclick="openPreviewAnggota({{ (int) $a['id'] }})">
                                                <i class="lni lni-eye me-1"></i> Preview
                                            </button>
                                            <a href="{{ route('admin.anggota.edit', ['id' => $a['id']]) }}" class="btn btn-primary btn-sm">
                                                <i class="lni lni-pencil me-1"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteAnggota({{ (int) $a['id'] }}, '{{ addslashes($a['nama']) }}')">
                                                <i class="lni lni-trash me-1"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="anggota-card-view"></div>
                @else
                <div class="empty-state wow fadeInUp" data-wow-delay=".3s">
                    <i class="lni lni-users empty-state-icon"></i>
                    <h3 class="empty-state-title">Belum Ada Anggota</h3>
                    <p class="empty-state-message">Mulai dengan menambahkan anggota pertama ke dalam sistem.</p>
                    <a href="{{ route('admin.anggota.create') }}" class="empty-state-action">
                        <i class="lni lni-plus"></i> Tambah Anggota Pertama
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
</main>

@php
    $anggotaMap = [];
    foreach ($anggotaList as $row) {
        $fotoRaw = $row['foto'] ?? '';
        $fotoResolved = $fotoRaw
            ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset(ltrim(str_replace('../../', '', $fotoRaw), '/')))
            : '';
        $anggotaMap[(string) $row['id']] = [
            'id' => (int) $row['id'],
            'nama' => $row['nama'] ?? '',
            'jabatan' => $row['jabatan'] ?? '',
            'deskripsi' => $row['deskripsi'] ?? '',
            'foto' => $row['foto'] ?? '',
            'foto_resolved' => $fotoResolved,
            'instagram' => $row['instagram'] ?? '',
            'linkedin' => $row['linkedin'] ?? '',
        ];
    }
@endphp

<!-- Modal Preview Anggota -->
<div id="anggotaPreviewOverlay" onclick="if(event.target===this){closePreviewAnggota()}">
    <div class="anggota-modal-bootstrap">
        <div class="anggota-modal-header" style="background: linear-gradient(135deg, #2F80ED 0%, #1E5FCC 100%) !important;">
            <h5 class="anggota-modal-title">
                <i class="lni lni-user me-2"></i> Preview Anggota
            </h5>
            <button type="button" class="anggota-modal-close" onclick="closePreviewAnggota()" aria-label="Close">
                <i class="lni lni-close"></i>
            </button>
        </div>
        <div class="anggota-modal-body">
            <div class="anggota-preview-content">
                <div class="anggota-preview-photo-section">
                    <div class="anggota-preview-photo-wrapper">
                        <img id="apFoto" class="anggota-preview-photo" src="" alt="Foto">
                        <div id="apInitial" class="anggota-preview-initial">?</div>
                    </div>
                </div>
                <div class="anggota-preview-info">
                    <h3 id="apNama" class="anggota-preview-nama">-</h3>
                    <div class="anggota-preview-item">
                        <div class="anggota-preview-label">
                            <i class="lni lni-briefcase"></i> Jabatan
                        </div>
                        <div id="apJabatan" class="anggota-preview-value">
                            <span class="badge bg-secondary">Belum ada jabatan</span>
                        </div>
                    </div>
                    <div class="anggota-preview-item">
                        <div class="anggota-preview-label">
                            <i class="lni lni-text-format"></i> Deskripsi
                        </div>
                        <div id="apDeskripsi" class="anggota-preview-value">
                            <p class="text-muted mb-0 fst-italic">Tidak ada deskripsi</p>
                        </div>
                    </div>
                    <div class="anggota-preview-item">
                        <div class="anggota-preview-label">
                            <i class="lni lni-share"></i> Media Sosial
                        </div>
                        <div id="apSosmed" class="anggota-preview-value">
                            <small class="text-muted">Tidak ada media sosial</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="anggota-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closePreviewAnggota()">
                <i class="lni lni-close me-1"></i> Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.anggotaData = @json($anggotaMap);
</script>
<script src="{{ asset('assets/js/classes/ToastNotification.js') }}"></script>
<script src="{{ asset('assets/js/classes/LoadingState.js') }}"></script>
<script src="{{ asset('assets/js/classes/AnggotaConfirmModal.js') }}"></script>
<script src="{{ asset('assets/js/classes/AnggotaViewManager.js') }}"></script>
<script src="{{ asset('assets/js/classes/AnggotaPreviewModal.js') }}"></script>
<script>
if (typeof AnggotaPreviewModal !== 'undefined' && window.anggotaData) {
    window.anggotaPreviewModal = new AnggotaPreviewModal(window.anggotaData);
}

function confirmDeleteAnggota(id, nama) {
    const deleteUrl = "{{ route('admin.anggota.destroy') }}?id=" + id;
    if (window.anggotaConfirmModal) {
        window.anggotaConfirmModal.confirmDelete(nama, function() {
            if (window.loading) window.loading.show('Menghapus anggota...');
            fetch(deleteUrl, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (window.loading) window.loading.hide();
                if (data.success) {
                    if (window.toast) window.toast.success(data.message || 'Anggota berhasil dihapus!', 'Berhasil');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    if (window.toast) window.toast.error(data.message || 'Gagal menghapus anggota.', 'Error');
                }
            })
            .catch(() => {
                if (window.loading) window.loading.hide();
                window.location.href = deleteUrl;
            });
        });
    } else if (confirm('Yakin ingin menghapus anggota "' + nama + '"?')) {
        window.location.href = deleteUrl;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchAnggota');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                window.location.href = query.length > 0
                    ? "{{ route('admin.anggota.index') }}?search=" + encodeURIComponent(query)
                    : "{{ route('admin.anggota.index') }}";
            }
        });
    }
});
</script>
@endpush
