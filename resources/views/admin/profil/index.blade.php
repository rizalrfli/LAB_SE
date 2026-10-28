@extends('layouts.admin')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.profil-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); padding: 24px; margin-bottom: 24px; border: 1px solid #eef0f5; }
.profil-card h5 { color: #2c3e50; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
</style>
@endpush

@section('content')
<section class="pt-50 pb-50 admin-section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title mb-40">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="mb-10">Kelola Profil & Konten</h2>
                            <p>Kelola konten halaman Tentang Kami, Visi & Misi, Focus & Scope, Navbar Logo, dan Footer</p>
                        </div>
                        <a href="{{ route('admin.roadmap.index') }}" class="btn btn-primary">
                            <i class="lni lni-map-marker me-1"></i> Kelola Roadmap
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <div class="row">
            <!-- Tentang Kami -->
            <div class="col-lg-12">
                <div class="profil-card">
                    <h5><i class="lni lni-information text-primary"></i> Tentang Kami</h5>
                    <form method="POST" action="{{ route('admin.profil.post') }}" id="formTentangKami">
                        @csrf
                        <input type="hidden" name="update_tentang_kami" value="1">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul</label>
                            <input type="text" name="tentang_kami_title" class="form-control" value="{{ $tentangKami['title'] ?? 'Tentang Lab SE' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Konten Deskripsi</label>
                            <div id="quillTentangKami" style="min-height: 220px; background: #fff;">{!! $tentangKami['content'] ?? '' !!}</div>
                            <textarea name="tentang_kami_content" id="tentangKamiContent" style="display:none;">{{ $tentangKami['content'] ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="lni lni-save me-1"></i> Simpan Tentang Kami</button>
                    </form>
                </div>
            </div>

            <!-- 4 Fitur Tentang Kami -->
            <div class="col-lg-12">
                <div class="profil-card">
                    <h5><i class="lni lni-grid-alt text-success"></i> Fitur Tentang Kami (4 Pilar)</h5>
                    <div class="row">
                        @foreach($features as $fKey => $feat)
                            <div class="col-md-6 mb-3">
                                <form method="POST" action="{{ route('admin.profil.post') }}" class="p-3 border rounded bg-light">
                                    @csrf
                                    <input type="hidden" name="update_feature" value="1">
                                    <input type="hidden" name="feature_key" value="{{ $fKey }}">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Judul Fitur</label>
                                        <input type="text" name="feature_title" class="form-control form-control-sm" value="{{ $feat['title'] }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Deskripsi</label>
                                        <textarea name="feature_content" class="form-control form-control-sm" rows="2">{{ $feat['content'] }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success"><i class="lni lni-save me-1"></i> Update Fitur</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Focus & Scope -->
            <div class="col-lg-12">
                <div class="profil-card">
                    <h5><i class="lni lni-target text-info"></i> Focus & Scope</h5>
                    <div class="row">
                        @foreach($focusItems as $focusKey => $fItem)
                            <div class="col-md-4 mb-3">
                                <form method="POST" action="{{ route('admin.profil.post') }}" class="p-3 border rounded bg-light">
                                    @csrf
                                    <input type="hidden" name="update_focus_scope" value="1">
                                    <input type="hidden" name="focus_key" value="{{ $focusKey }}">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Judul Focus</label>
                                        <input type="text" name="focus_title" class="form-control form-control-sm" value="{{ $fItem['title'] }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Icon Class (LineIcons)</label>
                                        <input type="text" name="focus_icon" class="form-control form-control-sm" value="{{ $fItem['icon'] }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Deskripsi</label>
                                        <textarea name="focus_content" class="form-control form-control-sm" rows="3">{{ $fItem['content'] }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-info text-white"><i class="lni lni-save me-1"></i> Simpan</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Visi & Misi -->
            <div class="col-lg-6">
                <div class="profil-card">
                    <h5><i class="lni lni-eye text-primary"></i> Visi Laboratorium</h5>
                    <form method="POST" action="{{ route('admin.profil.post') }}">
                        @csrf
                        <input type="hidden" name="update_visi" value="1">
                        <div class="mb-3">
                            <textarea name="visi_content" class="form-control" rows="5" required>{{ $visiContent }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="lni lni-save me-1"></i> Simpan Visi</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="profil-card">
                    <h5><i class="lni lni-bullhorn text-warning"></i> Misi Laboratorium</h5>
                    <form method="POST" action="{{ route('admin.profil.post') }}" class="mb-3 d-flex gap-2">
                        @csrf
                        <input type="hidden" name="tambah_misi" value="1">
                        <input type="text" name="misi_content" class="form-control" placeholder="Tambah poin misi baru..." required>
                        <button type="submit" class="btn btn-success"><i class="lni lni-plus"></i> Tambah</button>
                    </form>
                    <div style="max-height: 280px; overflow-y: auto;">
                        @forelse($misiItems as $misi)
                            <form method="POST" action="{{ route('admin.profil.post') }}" class="d-flex gap-2 mb-2 align-items-center">
                                @csrf
                                <input type="hidden" name="update_misi" value="1">
                                <input type="hidden" name="misi_key" value="{{ $misi['page_key'] }}">
                                <input type="text" name="misi_content" class="form-control form-control-sm" value="{{ $misi['content'] }}" required>
                                <button type="submit" class="btn btn-sm btn-primary" title="Simpan"><i class="lni lni-save"></i></button>
                                <a href="{{ route('admin.profil.index', ['hapus_misi' => $misi['page_key']]) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus poin misi ini?')" title="Hapus"><i class="lni lni-trash"></i></a>
                            </form>
                        @empty
                            <p class="text-muted small">Belum ada item misi.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Navbar & Footer Settings -->
            <div class="col-lg-6">
                <div class="profil-card">
                    <h5><i class="lni lni-image text-primary"></i> Logo Navbar</h5>
                    <div class="mb-3">
                        <img id="navbarLogoPreview" src="{{ preg_match('/^https?:\/\//', $navbarLogo) ? $navbarLogo : asset(ltrim($navbarLogo, '/')) }}" alt="Logo" style="max-height: 60px; background: #f8f9fa; padding: 8px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <div class="mb-3">
                        <input type="file" id="navbarLogoInput" class="form-control" accept="image/*">
                    </div>
                    <button type="button" onclick="uploadNavbarLogo()" class="btn btn-primary btn-sm"><i class="lni lni-upload me-1"></i> Upload Logo Baru</button>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="profil-card">
                    <h5><i class="lni lni-layout text-secondary"></i> Pengaturan Footer</h5>
                    <form method="POST" action="{{ route('admin.profil.post') }}" class="mb-3">
                        @csrf
                        <input type="hidden" name="update_footer_deskripsi" value="1">
                        <label class="form-label small fw-bold">Deskripsi Footer</label>
                        <textarea name="footer_deskripsi" class="form-control form-control-sm mb-2" rows="2">{{ $footerDeskripsi }}</textarea>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan Deskripsi</button>
                    </form>
                    <form method="POST" action="{{ route('admin.profil.post') }}">
                        @csrf
                        <input type="hidden" name="update_footer_copyright" value="1">
                        <label class="form-label small fw-bold">Teks Copyright (gunakan {YEAR} untuk tahun otomatis)</label>
                        <input type="text" name="footer_copyright" class="form-control form-control-sm mb-2" value="{{ $footerCopyright }}">
                        <button type="submit" class="btn btn-sm btn-primary">Simpan Copyright</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quillEl = document.getElementById('quillTentangKami');
    const hiddenEl = document.getElementById('tentangKamiContent');
    const form = document.getElementById('formTentangKami');
    if (quillEl && hiddenEl && typeof Quill !== 'undefined') {
        const q = new Quill('#quillTentangKami', { theme: 'snow' });
        form.addEventListener('submit', function() {
            hiddenEl.value = q.root.innerHTML;
        });
    }
});

function uploadNavbarLogo() {
    const input = document.getElementById('navbarLogoInput');
    if (!input.files || !input.files[0]) {
        alert('Pilih file gambar terlebih dahulu!');
        return;
    }
    const fd = new FormData();
    fd.append('file', input.files[0]);
    fetch(@json(route('admin.profil.upload-navbar')), {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('navbarLogoPreview').src = data.url;
            alert(data.message || 'Logo berhasil diperbarui!');
        } else {
            alert(data.message || 'Gagal mengunggah logo.');
        }
    });
}
</script>
@endpush
