@extends('layouts.admin')

@section('content')
<main class="admin-main-content">
<section class="pt-50 pb-50 admin-section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb-50">
                    <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Tambah Anggota</h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">Tambahkan data anggota/personil</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <div class="row">
            <div class="col-lg-8">
                <div class="card wow fadeInUp form-card" data-wow-delay=".3s">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="lni lni-user text-primary"></i> Form Tambah Anggota
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.anggota.store') }}" enctype="multipart/form-data" id="formTambahAnggota">
                            @csrf
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
                                        value="{{ old('nama') }}"
                                        placeholder="Masukkan nama lengkap"
                                        required
                                        minlength="3"
                                        maxlength="100"
                                        autofocus>
                                    <div class="form-text">
                                        <i class="lni lni-information"></i> Masukkan nama lengkap anggota (minimal 3 karakter)
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="jabatan" class="form-label form-label-custom">
                                        <i class="lni lni-briefcase"></i> Jabatan
                                    </label>
                                    <select class="form-select form-select-lg form-select-custom" id="jabatan" name="jabatan">
                                        <option value="">Pilih Jabatan (Opsional)</option>
                                        @foreach ($jabatanList as $j)
                                            <option value="{{ $j }}" {{ old('jabatan') === $j ? 'selected' : '' }}>{{ $j }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        <i class="lni lni-information"></i> Pilih jabatan anggota di laboratorium
                                    </div>
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
                                    maxlength="500">{{ old('deskripsi') }}</textarea>
                                <div class="form-text">
                                    <i class="lni lni-information"></i> Deskripsi singkat tentang anggota (maksimal 500 karakter)
                                </div>
                                <small class="text-muted float-end">
                                    <span id="deskripsi_count">0</span>/500 karakter
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
                                        value="{{ old('instagram') }}"
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
                                        value="{{ old('linkedin') }}"
                                        placeholder="https://www.linkedin.com/in/username">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label form-label-custom">
                                    <i class="lni lni-image"></i> Foto (Opsional)
                                </label>
                                <input
                                    type="file"
                                    class="form-control form-control-lg form-file-custom"
                                    id="foto"
                                    name="foto"
                                    accept=".jpg,.jpeg,.png,.webp">
                                <div class="form-text">
                                    <i class="lni lni-information"></i> Maks 2MB. Format: JPG, JPEG, PNG, WEBP
                                </div>
                                <div id="previewFoto" class="preview-container mt-3" style="display: none;">
                                    <img id="previewImg" src="#" alt="Preview" class="preview-image img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-4" id="btnSimpan">
                                    <i class="lni lni-save me-2"></i> Simpan Anggota
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
                            <i class="lni lni-information me-2"></i> Informasi
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="lni lni-checkmark-circle text-success me-2"></i><small>Nama wajib diisi (min 3 karakter)</small></li>
                            <li class="mb-2"><i class="lni lni-checkmark-circle text-success me-2"></i><small>Jabatan dapat dipilih atau dikosongkan</small></li>
                            <li class="mb-2"><i class="lni lni-checkmark-circle text-success me-2"></i><small>Foto maksimal 2MB</small></li>
                            <li class="mb-2"><i class="lni lni-checkmark-circle text-success me-2"></i><small>Deskripsi maksimal 500 karakter</small></li>
                        </ul>
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
