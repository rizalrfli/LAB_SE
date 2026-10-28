@extends('layouts.admin')

@section('content')
<main class="admin-main-content">
<section class="pt-50 pb-50 admin-section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb-50">
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Edit Anggota</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Perbarui data anggota/personil</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <div class="row">
            <div class="col-lg-8">
                <div class="card wow fadeInUp form-card" data-wow-delay=".3s">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="lni lni-pencil text-primary"></i> Form Edit Anggota
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.anggota.update', ['id' => $anggota['id']]) }}" enctype="multipart/form-data" id="formEditAnggota">
                            @csrf
                            <input type="hidden" name="id" value="{{ $anggota['id'] }}">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="nama" class="form-label form-label-custom">
                                        <i class="lni lni-user"></i> Nama <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg form-input-custom"
                                        id="nama"
                                        name="nama"
                                        value="{{ old('nama', $anggota['nama'] ?? '') }}"
                                        placeholder="Masukkan nama lengkap"
                                        required
                                        minlength="3"
                                        maxlength="100"
                                        autofocus>
                                </div>
                                <div class="col-lg-6">
                                    <label for="jabatan" class="form-label form-label-custom">
                                        <i class="lni lni-briefcase"></i> Jabatan
                                    </label>
                                    <select class="form-select form-select-lg form-select-custom" id="jabatan" name="jabatan">
                                        <option value="">Pilih Jabatan (Opsional)</option>
                                        @foreach ($jabatanList as $j)
                                            <option value="{{ $j }}" {{ old('jabatan', $anggota['jabatan'] ?? '') === $j ? 'selected' : '' }}>{{ $j }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label form-label-custom">
                                    <i class="lni lni-text-format"></i> Deskripsi
                                </label>
                                <textarea
                                    class="form-control form-input-custom"
                                    id="deskripsi"
                                    name="deskripsi"
                                    rows="4"
                                    placeholder="Masukkan deskripsi atau biografi singkat anggota (opsional)"
                                    maxlength="500">{{ old('deskripsi', $anggota['deskripsi'] ?? '') }}</textarea>
                                <small class="text-muted float-end">
                                    <span id="deskripsi_count">{{ strlen($anggota['deskripsi'] ?? '') }}</span>/500 karakter
                                </small>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="instagram" class="form-label form-label-custom">
                                        <i class="lni lni-instagram"></i> Link Instagram
                                    </label>
                                    <input
                                        type="url"
                                        class="form-control form-control-lg form-input-custom"
                                        id="instagram"
                                        name="instagram"
                                        value="{{ old('instagram', $anggota['instagram'] ?? '') }}"
                                        placeholder="https://instagram.com/username">
                                </div>
                                <div class="col-lg-6">
                                    <label for="linkedin" class="form-label form-label-custom">
                                        <i class="lni lni-linkedin"></i> Link LinkedIn
                                    </label>
                                    <input
                                        type="url"
                                        class="form-control form-control-lg form-input-custom"
                                        id="linkedin"
                                        name="linkedin"
                                        value="{{ old('linkedin', $anggota['linkedin'] ?? '') }}"
                                        placeholder="https://www.linkedin.com/in/username">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label form-label-custom">
                                    <i class="lni lni-image"></i> Foto (Opsional)
                                </label>
                                @php
                                    $fotoRaw = $anggota['foto'] ?? '';
                                    $fotoResolved = $fotoRaw
                                        ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset(ltrim(str_replace('../../', '', $fotoRaw), '/')))
                                        : '';
                                @endphp
                                @if ($fotoResolved)
                                    <div class="mb-3">
                                        <p class="small text-muted mb-2">Foto Saat Ini:</p>
                                        <img src="{{ $fotoResolved }}" alt="Foto" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                @endif
                                <input
                                    type="file"
                                    class="form-control form-control-lg form-file-custom"
                                    id="foto"
                                    name="foto"
                                    accept=".jpg,.jpeg,.png,.webp">
                                <div class="form-text">
                                    <i class="lni lni-information"></i> Maks 2MB. Format: JPG, JPEG, PNG, WEBP. Kosongkan jika tidak ingin mengubah foto.
                                </div>
                                <div id="previewFoto" class="preview-container mt-3" style="display: none;">
                                    <p class="small text-muted mb-2">Preview Foto Baru:</p>
                                    <img id="previewImg" src="#" alt="Preview" class="preview-image img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-4" id="btnUpdate">
                                    <i class="lni lni-save me-2"></i> Update Anggota
                                </button>
                                <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-secondary btn-lg px-4">
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
                            <small class="text-muted d-block">Nama</small>
                            <strong>{{ $anggota['nama'] }}</strong>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Jabatan</small>
                            <span>{{ !empty($anggota['jabatan']) ? $anggota['jabatan'] : 'Belum ada' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/classes/ImagePreview.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ImagePreview !== 'undefined') {
        window.imagePreview = new ImagePreview({
            inputId: 'foto',
            previewContainerId: 'previewFoto',
            previewImgId: 'previewImg'
        });
    }
    const textareaDeskripsi = document.getElementById('deskripsi');
    const deskripsiCount = document.getElementById('deskripsi_count');
    if (textareaDeskripsi && deskripsiCount) {
        textareaDeskripsi.addEventListener('input', function() {
            deskripsiCount.textContent = this.value.length;
        });
    }
});
</script>
@endpush
