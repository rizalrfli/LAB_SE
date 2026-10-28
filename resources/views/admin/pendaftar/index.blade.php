@extends('layouts.admin')

@section('content')
<section class="pt-50 pb-50 admin-section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb-50">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Kelola Pendaftar</h2>
                            <p class="wow fadeInUp" data-wow-delay=".4s">Daftar mahasiswa yang telah mendaftar ke Laboratorium Software Engineering</p>
                        </div>
                        <div class="wow fadeInUp" data-wow-delay=".3s">
                            <a href="{{ route('admin.pendaftar.history') }}" class="button radius-10" style="background: #6c757d; color: white; border: none; padding: 10px 20px;">
                                <i class="lni lni-history"></i> History Log
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <!-- Statistics Cards -->
        <div class="row mb-50">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <div class="stat-card blue wow fadeInUp" data-wow-delay=".2s">
                    <div class="stat-icon"><i class="lni lni-graduation"></i></div>
                    <div class="stat-number">{{ number_format($totalAll) }}</div>
                    <div class="stat-label">Total Pendaftar</div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <div class="stat-card orange wow fadeInUp" data-wow-delay=".3s">
                    <div class="stat-icon"><i class="lni lni-hourglass"></i></div>
                    <div class="stat-number">{{ number_format($stats['pending'] ?? 0) }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <div class="stat-card green wow fadeInUp" data-wow-delay=".4s">
                    <div class="stat-icon"><i class="lni lni-checkmark-circle"></i></div>
                    <div class="stat-number">{{ number_format($stats['accepted'] ?? 0) }}</div>
                    <div class="stat-label">Diterima</div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                <div class="stat-card purple wow fadeInUp" data-wow-delay=".5s">
                    <div class="stat-icon"><i class="lni lni-close"></i></div>
                    <div class="stat-number">{{ number_format($stats['rejected'] ?? 0) }}</div>
                    <div class="stat-label">Ditolak</div>
                </div>
            </div>
        </div>

        <!-- Filter Status -->
        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="wow fadeInUp filter-card-wrapper" data-wow-delay=".2s">
                    <div class="filter-buttons">
                        <span class="filter-label"><i class="lni lni-filter"></i>Filter Status:</span>
                        <a href="{{ route('admin.pendaftar.index', array_filter(['status' => 'all', 'search' => $search, 'tanggal_dari' => $tanggalDari, 'tanggal_sampai' => $tanggalSampai])) }}" class="button button-sm radius-10 filter-button {{ $statusFilter === 'all' ? 'filter-button-active' : 'border-button' }}">
                            <i class="lni lni-list"></i> Semua
                        </a>
                        <a href="{{ route('admin.pendaftar.index', array_filter(['status' => 'pending', 'search' => $search, 'tanggal_dari' => $tanggalDari, 'tanggal_sampai' => $tanggalSampai])) }}" class="button button-sm radius-10 filter-button {{ $statusFilter === 'pending' ? 'filter-button-pending' : 'border-button' }}">
                            <i class="lni lni-hourglass"></i> Pending
                        </a>
                        <a href="{{ route('admin.pendaftar.index', array_filter(['status' => 'accepted', 'search' => $search, 'tanggal_dari' => $tanggalDari, 'tanggal_sampai' => $tanggalSampai])) }}" class="button button-sm radius-10 filter-button {{ $statusFilter === 'accepted' ? 'filter-button-accepted' : 'border-button' }}">
                            <i class="lni lni-checkmark-circle"></i> Diterima
                        </a>
                        <a href="{{ route('admin.pendaftar.index', array_filter(['status' => 'rejected', 'search' => $search, 'tanggal_dari' => $tanggalDari, 'tanggal_sampai' => $tanggalSampai])) }}" class="button button-sm radius-10 filter-button {{ $statusFilter === 'rejected' ? 'filter-button-rejected' : 'border-button' }}">
                            <i class="lni lni-close"></i> Ditolak
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pencarian dan Filter Lanjutan -->
        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="wow fadeInUp filter-advanced-card" data-wow-delay=".3s" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <form method="GET" action="{{ route('admin.pendaftar.index') }}" id="filterForm">
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50; font-size: 13px;">
                                    <i class="lni lni-search"></i> Pencarian
                                </label>
                                <input type="text" name="search" value="{{ $search }}" placeholder="Nama, NIM, atau Email..." style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px;">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50; font-size: 13px;">
                                    <i class="lni lni-calendar"></i> Tanggal Dari
                                </label>
                                <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px;">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50; font-size: 13px;">
                                    <i class="lni lni-calendar"></i> Tanggal Sampai
                                </label>
                                <input type="date" name="tanggal_sampai" value="{{ $tanggalSampai }}" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px;">
                            </div>
                            <div class="col-md-2 mb-3" style="display: flex; align-items: flex-end;">
                                <button type="submit" class="button radius-10 btn-edit" style="width: 100%; padding: 10px;">
                                    <i class="lni lni-search"></i> Cari
                                </button>
                            </div>
                        </div>
                        @if(!empty($search) || !empty($tanggalDari) || !empty($tanggalSampai))
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <a href="{{ route('admin.pendaftar.index', ['status' => $statusFilter]) }}" class="button button-sm radius-10 border-button">
                                    <i class="lni lni-close"></i> Reset Filter
                                </a>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        @if(count($pendaftarList) > 0)
        <div class="row mb-20" id="bulkActionsBar" style="display: none;">
            <div class="col-lg-12">
                <div class="wow fadeInUp bulk-actions-bar" data-wow-delay=".3s" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e0e0e0;">
                    <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                        <span style="font-weight: 600; color: #2c3e50;">
                            <i class="lni lni-checkmark-circle"></i> <span id="selectedCount">0</span> dipilih
                        </span>
                        <button type="button" onclick="bulkAction('accept')" class="button button-sm radius-10" style="background: #28a745; color: white; border: none;">
                            <i class="lni lni-checkmark-circle"></i> Terima
                        </button>
                        <button type="button" onclick="bulkAction('reject')" class="button button-sm radius-10" style="background: #dc3545; color: white; border: none;">
                            <i class="lni lni-close"></i> Tolak
                        </button>
                        <button type="button" onclick="bulkAction('delete')" class="button button-sm radius-10 border-button" style="color: #dc3545;">
                            <i class="lni lni-trash"></i> Hapus
                        </button>
                        <button type="button" onclick="clearSelection()" class="button button-sm radius-10 border-button">
                            <i class="lni lni-close"></i> Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Table Pendaftar -->
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive wow fadeInUp" data-wow-delay=".3s" style="overflow-x: auto; overflow-y: visible;">
                    <table class="table table-striped table-hover admin-table" style="position: relative;">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" title="Pilih Semua">
                                </th>
                                <th>NO</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>NIM</th>
                                <th>Status</th>
                                <th>Tanggal Daftar</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendaftarList as $idx => $p)
                                @php
                                    $status = $p['status'] ?? 'pending';
                                    $fotoRaw = $p['foto'] ?? '';
                                    $fotoSrc = !empty($fotoRaw) ? (preg_match('/^https?:\/\//', $fotoRaw) ? $fotoRaw : asset(ltrim($fotoRaw, '/'))) : '';
                                    $initial = strtoupper(substr($p['nama'] ?? 'U', 0, 1));
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" class="row-checkbox" value="{{ $p['id'] }}" onchange="updateBulkActionsBar()">
                                    </td>
                                    <td>{{ ($currentPage - 1) * $perPage + $idx + 1 }}</td>
                                    <td>
                                        <div class="flex-center">
                                            @if(!empty($fotoSrc))
                                                <img src="{{ $fotoSrc }}" alt="{{ $p['nama'] }}" class="avatar-circle" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="initial-circle" style="display: none;">{{ $initial }}</div>
                                            @else
                                                <div class="initial-circle">{{ $initial }}</div>
                                            @endif
                                            <strong>{{ $p['nama'] }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if(!empty($p['email']))
                                            <a href="mailto:{{ $p['email'] }}" class="email-link">
                                                <i class="lni lni-envelope"></i>{{ $p['email'] }}
                                            </a>
                                        @else
                                            <span class="text-muted-small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="nim-badge">{{ $p['nim'] }}</span>
                                    </td>
                                    <td>
                                        @if($status === 'accepted')
                                            <span class="status-badge status-badge-accepted">Diterima</span>
                                        @elseif($status === 'rejected')
                                            <span class="status-badge status-badge-rejected">Ditolak</span>
                                        @else
                                            <span class="status-badge status-badge-pending">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ !empty($p['tanggal_daftar']) ? date('d/m/Y H:i', strtotime($p['tanggal_daftar'])) . ' WIB' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown-actions">
                                            <button type="button" class="button button-sm radius-10 border-button dropdown-toggle" onclick="toggleDropdown({{ $p['id'] }}, event)" title="Menu Aksi">
                                                <i class="lni lni-menu"></i> Aksi
                                            </button>
                                            <div class="dropdown-menu" id="dropdown-{{ $p['id'] }}">
                                                <a href="javascript:void(0)" onclick="viewDetail({{ $p['id'] }})" class="dropdown-item">
                                                    <i class="lni lni-eye"></i> Lihat Detail
                                                </a>
                                                <a href="javascript:void(0)" onclick="viewRiwayat({{ $p['id'] }})" class="dropdown-item">
                                                    <i class="lni lni-history"></i> Riwayat
                                                </a>
                                                @if($status === 'pending')
                                                    <a href="javascript:void(0)" onclick="updateStatus({{ $p['id'] }}, 'accepted')" class="dropdown-item text-success">
                                                        <i class="lni lni-checkmark-circle"></i> Terima Pendaftar
                                                    </a>
                                                    <a href="javascript:void(0)" onclick="updateStatus({{ $p['id'] }}, 'rejected')" class="dropdown-item text-danger">
                                                        <i class="lni lni-close"></i> Tolak Pendaftar
                                                    </a>
                                                @endif
                                                <a href="javascript:void(0)" onclick="showEditModal({{ $p['id'] }})" class="dropdown-item">
                                                    <i class="lni lni-pencil"></i> Update Data
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:void(0)" onclick="deletePendaftar({{ $p['id'] }})" class="dropdown-item text-danger">
                                                    <i class="lni lni-trash"></i> Hapus Pendaftar
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center admin-table-empty">
                                        <i class="lni lni-inbox admin-table-empty-icon"></i>
                                        <p class="mt-3 text-muted">Belum ada data pendaftar.</p>
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

<!-- Update Modal for Pendaftar -->
<div id="updatePendaftarOverlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1100; align-items: center; justify-content: center; padding: 20px; overflow-y: auto;" onclick="if(event.target===this){ this.style.display='none'; }">
    <div class="detail-modal" style="max-width: 600px;">
        <div class="detail-header">
            <h4><i class="lni lni-pencil icon-blue"></i> Update Data Pendaftar</h4>
            <button class="detail-close" title="Tutup" onclick="closeUpdatePendaftarModal();">&times;</button>
        </div>
        <div class="detail-body">
            <form id="formUpdatePendaftar" method="POST" action="{{ route('admin.pendaftar.action') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" value="update_pendaftar">
                <input type="hidden" name="id" id="updatePendaftarId">
                <input type="hidden" name="current_filter" value="{{ $statusFilter }}">
                <input type="hidden" name="foto_old" id="updatePendaftarFotoOld">

                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #2c3e50; font-size: 13px; font-weight: 600;">Nama Lengkap <span style="color: #dc3545;">*</span></label>
                        <input type="text" name="nama" id="updatePendaftarNama" required style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #2c3e50; font-size: 13px; font-weight: 600;">NIM <span style="color: #dc3545;">*</span></label>
                        <input type="text" name="nim" id="updatePendaftarNim" required style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #2c3e50; font-size: 13px; font-weight: 600;">Email</label>
                        <input type="email" name="email" id="updatePendaftarEmail" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #2c3e50; font-size: 13px; font-weight: 600;">Foto</label>
                        <div id="fotoPreviewContainer" style="margin-bottom: 10px; display: none;">
                            <img id="fotoPreview" src="" alt="Preview" style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 2px solid #e0e0e0; object-fit: cover;">
                        </div>
                        <input type="file" name="foto" id="updatePendaftarFoto" accept="image/jpeg,image/jpg,image/png,image/webp" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px;" onchange="previewFotoUpdate(this);">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #2c3e50; font-size: 13px; font-weight: 600;">Status</label>
                        <select name="status" id="updatePendaftarStatus" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px;">
                            <option value="pending">Pending</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 6px; color: #2c3e50; font-size: 13px; font-weight: 600;">Catatan Admin</label>
                        <textarea name="catatan_admin" id="updatePendaftarCatatan" rows="3" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px;"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="detail-footer">
            <button type="button" class="button radius-10 border-button" onclick="closeUpdatePendaftarModal();">Tutup</button>
            <button type="button" class="button radius-10 btn-edit" onclick="document.getElementById('formUpdatePendaftar').submit();">Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Riwayat Pendaftar -->
<div id="riwayatOverlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1100; align-items: center; justify-content: center; padding: 20px; overflow-y: auto;" onclick="if(event.target===this){ this.style.display='none'; }">
    <div class="detail-modal" style="max-width: 700px; max-height: 90vh;">
        <div class="detail-header">
            <h4><i class="lni lni-history icon-blue"></i> Riwayat Pendaftar</h4>
            <button class="detail-close" title="Tutup" onclick="closeRiwayatModal();">&times;</button>
        </div>
        <div class="detail-body">
            <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e0e0e0;">
                <div style="display: grid; grid-template-columns: auto 1fr; gap: 10px; align-items: center;">
                    <strong style="color: #2c3e50;">Nama:</strong>
                    <span id="riwayatNama">-</span>
                    <strong style="color: #2c3e50;">NIM:</strong>
                    <span id="riwayatNim">-</span>
                </div>
            </div>
            <div id="riwayatBody" style="max-height: 400px; overflow-y: auto;"></div>
        </div>
        <div class="detail-footer">
            <button type="button" class="button radius-10 border-button" onclick="closeRiwayatModal();">Tutup</button>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailOverlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1100; align-items: center; justify-content: center; padding: 20px;" onclick="if(event.target===this){ this.style.display='none'; }">
    <div class="detail-modal">
        <div class="detail-header">
            <h4><i class="lni lni-eye icon-blue"></i> Detail Pendaftar</h4>
            <button class="detail-close" title="Tutup" onclick="document.getElementById('detailOverlay').style.display='none'">&times;</button>
        </div>
        <div class="detail-body">
            <div class="detail-grid">
                <div class="detail-label">Nama Lengkap</div>
                <div class="detail-value" id="detailNama">-</div>
                <div class="detail-label">NIM</div>
                <div class="detail-value" id="detailNim">-</div>
                <div class="detail-label">Email</div>
                <div class="detail-value"><a id="detailEmail" href="javascript:void(0)">-</a></div>
                <div class="detail-label">Foto</div>
                <div class="detail-value" id="detailFoto">-</div>
                <div class="detail-label">Status</div>
                <div class="detail-value"><span id="detailStatus" class="detail-status-badge status-pending">Pending</span></div>
                <div class="detail-label">Tanggal Daftar</div>
                <div class="detail-value" id="detailTanggalDaftar">-</div>
                <div class="detail-label">Tanggal Review</div>
                <div class="detail-value" id="detailTanggalReview">-</div>
                <div class="detail-label">Catatan Admin</div>
                <div class="detail-value" id="detailCatatan">-</div>
            </div>
        </div>
        <div class="detail-footer">
            <button class="button radius-10 border-button" onclick="document.getElementById('detailOverlay').style.display='none'">Tutup</button>
        </div>
    </div>
</div>

@php
    $pendaftarMap = [];
    foreach ($pendaftarList as $pp) {
        $pendaftarMap[(string) $pp['id']] = [
            'id' => (int) $pp['id'],
            'nama' => $pp['nama'] ?? '',
            'email' => $pp['email'] ?? '',
            'nim' => $pp['nim'] ?? '',
            'foto' => $pp['foto'] ?? '',
            'status' => $pp['status'] ?? 'pending',
            'tanggal_daftar' => !empty($pp['tanggal_daftar']) ? date('d/m/Y H:i', strtotime($pp['tanggal_daftar'])) . ' WIB' : '-',
            'tanggal_review' => !empty($pp['tanggal_review']) ? date('d/m/Y H:i', strtotime($pp['tanggal_review'])) . ' WIB' : '-',
            'catatan_admin' => $pp['catatan_admin'] ?? '',
        ];
    }
@endphp

<style>
.dropdown-actions { position: relative; display: inline-block; }
.dropdown-toggle { cursor: pointer; }
.dropdown-menu { display: none; position: fixed; background: white; border: 1px solid #e0e0e0; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 200px; z-index: 9999; margin-top: 5px; padding: 5px 0; }
.dropdown-menu.show { display: block; }
.dropdown-item { display: block; padding: 10px 20px; color: #333; text-decoration: none; font-size: 14px; }
.dropdown-item:hover { background: #f5f5f5; }
.dropdown-divider { height: 1px; background: #e0e0e0; margin: 5px 0; }
.riwayat-item { display: flex; gap: 15px; padding: 15px 0; border-bottom: 1px solid #f0f0f0; }
.riwayat-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; }
.riwayat-icon.status-accepted { background: #28a745; }
.riwayat-icon.status-rejected { background: #dc3545; }
.riwayat-icon.status-pending { background: #ffc107; }
</style>
@endsection

@push('scripts')
<script>
window.pendaftarData = @json($pendaftarMap);
window.pendaftarDataFull = @json($pendaftarMap);
window.pendaftarActionUrl = @json(route('admin.pendaftar.action'));
window.pendaftarIndexUrl = @json(route('admin.pendaftar.index'));
window.csrfToken = @json(csrf_token());

function toggleDropdown(id, event) {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu.id !== 'dropdown-' + id) menu.classList.remove('show');
    });
    const dropdown = document.getElementById('dropdown-' + id);
    const button = event.target.closest('.dropdown-toggle');
    if (dropdown && button) {
        const isOpen = dropdown.classList.contains('show');
        if (!isOpen) {
            const rect = button.getBoundingClientRect();
            dropdown.style.position = 'fixed';
            dropdown.style.top = (rect.bottom + 5) + 'px';
            dropdown.style.left = Math.max(10, rect.right - 200) + 'px';
            dropdown.classList.add('show');
        } else {
            dropdown.classList.remove('show');
        }
    }
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown-actions')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
    }
});

function viewDetail(id) {
    const d = window.pendaftarData[id];
    if (!d) return;
    document.getElementById('detailNama').textContent = d.nama || '-';
    document.getElementById('detailNim').textContent = d.nim || '-';
    document.getElementById('detailEmail').textContent = d.email || '-';
    document.getElementById('detailStatus').textContent = (d.status || 'pending').toUpperCase();
    document.getElementById('detailTanggalDaftar').textContent = d.tanggal_daftar || '-';
    document.getElementById('detailTanggalReview').textContent = d.tanggal_review || '-';
    document.getElementById('detailCatatan').textContent = d.catatan_admin || '-';
    document.getElementById('detailOverlay').style.display = 'flex';
}

function updateStatus(id, status) {
    const label = status === 'accepted' ? 'menerima' : 'menolak';
    if (!confirm('Apakah Anda yakin ingin ' + label + ' pendaftar ini?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.pendaftarActionUrl;
    form.innerHTML = `
        <input type="hidden" name="_token" value="${window.csrfToken}">
        <input type="hidden" name="action" value="update_status">
        <input type="hidden" name="id" value="${id}">
        <input type="hidden" name="status" value="${status}">
    `;
    document.body.appendChild(form);
    form.submit();
}

function deletePendaftar(id) {
    if (!confirm('Yakin ingin menghapus pendaftar ini? Data tidak bisa dikembalikan.')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.pendaftarActionUrl;
    form.innerHTML = `
        <input type="hidden" name="_token" value="${window.csrfToken}">
        <input type="hidden" name="action" value="delete_pendaftar">
        <input type="hidden" name="id" value="${id}">
    `;
    document.body.appendChild(form);
    form.submit();
}

function showEditModal(id) {
    const data = window.pendaftarData[id];
    if (!data) return;
    document.getElementById('updatePendaftarId').value = data.id;
    document.getElementById('updatePendaftarNama').value = data.nama || '';
    document.getElementById('updatePendaftarNim').value = data.nim || '';
    document.getElementById('updatePendaftarEmail').value = data.email || '';
    document.getElementById('updatePendaftarFotoOld').value = data.foto || '';
    document.getElementById('updatePendaftarStatus').value = data.status || 'pending';
    document.getElementById('updatePendaftarCatatan').value = data.catatan_admin || '';
    document.getElementById('updatePendaftarOverlay').style.display = 'flex';
}

function closeUpdatePendaftarModal() {
    document.getElementById('updatePendaftarOverlay').style.display = 'none';
}

function previewFotoUpdate(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('fotoPreview').src = e.target.result;
            document.getElementById('fotoPreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleSelectAll(checkbox) {
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = checkbox.checked);
    updateBulkActionsBar();
}

function updateBulkActionsBar() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const bulkBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    if (bulkBar && selectedCount) {
        bulkBar.style.display = checkboxes.length > 0 ? 'block' : 'none';
        selectedCount.textContent = checkboxes.length;
    }
}

function clearSelection() {
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
    const selectAll = document.getElementById('selectAll');
    if (selectAll) selectAll.checked = false;
    updateBulkActionsBar();
}

function bulkAction(action) {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    if (checkboxes.length === 0) return;
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    if (!confirm('Jalankan aksi pada ' + selectedIds.length + ' pendaftar terpilih?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.pendaftarActionUrl;
    form.innerHTML = `
        <input type="hidden" name="_token" value="${window.csrfToken}">
        <input type="hidden" name="action" value="bulk_action">
        <input type="hidden" name="bulk_action_type" value="${action}">
        ${selectedIds.map(id => `<input type="hidden" name="selected_ids[]" value="${id}">`).join('')}
    `;
    document.body.appendChild(form);
    form.submit();
}

function viewRiwayat(id) {
    fetch(window.pendaftarIndexUrl + '?action=get_riwayat&id=' + id)
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;
            document.getElementById('riwayatNama').textContent = data.pendaftar.nama || '-';
            document.getElementById('riwayatNim').textContent = data.pendaftar.nim || '-';
            const body = document.getElementById('riwayatBody');
            if (!data.riwayat || data.riwayat.length === 0) {
                body.innerHTML = '<p style="text-align:center;color:#999;padding:20px;">Belum ada riwayat perubahan</p>';
            } else {
                body.innerHTML = data.riwayat.map(r => `
                    <div class="riwayat-item">
                        <div class="riwayat-content">
                            <div><strong>${(r.status_baru || '-').toUpperCase()}</strong> <small style="color:#999;">(${r.changed_at || '-'})</small></div>
                            ${r.catatan ? `<div style="margin-top:4px;">${r.catatan}</div>` : ''}
                        </div>
                    </div>
                `).join('');
            }
            document.getElementById('riwayatOverlay').style.display = 'flex';
        });
}

function closeRiwayatModal() {
    document.getElementById('riwayatOverlay').style.display = 'none';
}
</script>
@endpush
