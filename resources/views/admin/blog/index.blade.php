@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/anggota-ui.css') }}">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.ql-container { font-size: 16px; min-height: 400px; }
.ql-editor { min-height: 400px; }
</style>
@endpush

@section('content')
<section class="pt-50 pb-50 admin-section-bg">
    <div class="container">
        @if($action === 'list')
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-50">
                        <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Kelola Blog</h2>
                        <p class="wow fadeInUp" data-wow-delay=".4s">Kelola artikel blog laboratorium. Blog dengan status <strong>Published</strong> akan tampil di landing page, sedangkan blog dengan status <strong>Draft</strong> hanya dapat dilihat melalui admin panel.</p>
                    </div>
                </div>
            </div>

            @include('partials.flash-message')

            <!-- Stat Cards -->
            <div class="row mb-30">
                <div class="col-lg-4 col-md-6 mb-20">
                    <div class="stat-card wow fadeInUp" data-wow-delay=".2s">
                        <div class="stat-card-icon stat-card-icon-primary">
                            <i class="lni lni-text-format"></i>
                        </div>
                        <div class="stat-card-content">
                            <h3 class="stat-card-number">{{ $total_blog }}</h3>
                            <p class="stat-card-label">Total Blog</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-20">
                    <div class="stat-card wow fadeInUp" data-wow-delay=".3s">
                        <div class="stat-card-icon stat-card-icon-success">
                            <i class="lni lni-checkmark-circle"></i>
                        </div>
                        <div class="stat-card-content">
                            <h3 class="stat-card-number">{{ $published_blog }}</h3>
                            <p class="stat-card-label">Blog Published</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-20">
                    <div class="stat-card wow fadeInUp" data-wow-delay=".4s">
                        <div class="stat-card-icon stat-card-icon-info">
                            <i class="lni lni-file"></i>
                        </div>
                        <div class="stat-card-content">
                            <h3 class="stat-card-number">{{ $draft_blog }}</h3>
                            <p class="stat-card-label">Blog Draft</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="row mb-20">
                <div class="col-lg-12">
                    <div class="jabatan-search-box wow fadeInUp" data-wow-delay=".2s">
                        <i class="lni lni-search"></i>
                        <input type="text" id="searchBlog" class="jabatan-search-input" placeholder="Cari blog berdasarkan judul, kategori, atau excerpt..." value="{{ $search_query }}">
                        @if(!empty($search_query))
                            <a href="{{ route('admin.blog.index') }}" class="jabatan-search-clear" title="Hapus pencarian">
                                <i class="lni lni-close"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="row mb-30">
                <div class="col-lg-12">
                    <a href="{{ route('admin.blog.index', ['action' => 'tambah']) }}" class="btn btn-primary btn-lg wow fadeInUp" data-wow-delay=".3s">
                        <i class="lni lni-plus me-2"></i> Tambah Blog
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    @if(count($blogs) > 0)
                        <div class="table-responsive wow fadeInUp" data-wow-delay=".3s">
                            <table class="table table-striped table-hover admin-table">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Views</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($blogs as $idx => $blogItem)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>
                                            <strong>{{ $blogItem['title'] }}</strong>
                                            @if(!empty($blogItem['excerpt']))
                                                <br><small class="text-muted">{{ Str::limit($blogItem['excerpt'], 50) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ !empty($blogItem['category']) ? $blogItem['category'] : '-' }}</td>
                                        <td>
                                            @if(!empty($blogItem['is_published']))
                                                <span class="status-badge status-badge-published">Published</span>
                                            @else
                                                <span class="status-badge status-badge-draft">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ !empty($blogItem['created_at']) ? date('d/m/Y', strtotime($blogItem['created_at'])) : '-' }}</td>
                                        <td>{{ (int) ($blogItem['views'] ?? 0) }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @if(!empty($blogItem['is_published']))
                                                    <a href="{{ route('blog.detail', ['id' => $blogItem['id']]) }}" target="_blank" class="btn btn-info btn-sm" title="Lihat di Website">
                                                        <i class="lni lni-eye me-1"></i> Preview
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.blog.index', ['action' => 'edit', 'id' => $blogItem['id']]) }}" class="btn btn-primary btn-sm">
                                                    <i class="lni lni-pencil me-1"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteBlog({{ (int) $blogItem['id'] }}, '{{ addslashes($blogItem['title']) }}')">
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
                            <i class="lni lni-text-format empty-state-icon"></i>
                            <h3 class="empty-state-title">Belum Ada Blog</h3>
                            <p class="empty-state-message">Mulai dengan menambahkan blog pertama ke dalam sistem.</p>
                            <a href="{{ route('admin.blog.index', ['action' => 'tambah']) }}" class="empty-state-action">
                                <i class="lni lni-plus"></i> Tambah Blog Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @elseif($action === 'tambah' || ($action === 'edit' && $blog_edit))
            @php
                $isEdit = $action === 'edit' && $blog_edit;
                $b = $blog_edit ?? [];
            @endphp
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-50">
                        <h2 class="mb-15 wow fadeInUp" data-wow-delay=".2s">{{ $isEdit ? 'Edit Blog' : 'Tambah Blog' }}</h2>
                        <p class="wow fadeInUp" data-wow-delay=".4s">{{ $isEdit ? 'Edit artikel blog yang sudah ada' : 'Buat artikel blog baru untuk laboratorium' }}</p>
                    </div>
                </div>
            </div>

            @include('partials.flash-message')

            <div class="row">
                <div class="col-lg-8">
                    <div class="card wow fadeInUp form-card" data-wow-delay=".3s">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="lni lni-text-format text-primary"></i> {{ $isEdit ? 'Form Edit Blog' : 'Form Tambah Blog' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.blog.post') }}" id="formBlog" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="action" value="{{ $isEdit ? 'edit' : 'tambah' }}">
                                @if($isEdit)
                                    <input type="hidden" name="id" value="{{ $b['id'] }}">
                                    <input type="hidden" name="featured_image_old" value="{{ $b['featured_image'] ?? '' }}">
                                @endif

                                <div class="mb-4">
                                    <label for="title" class="form-label form-label-custom">
                                        <i class="lni lni-headphone-alt"></i> Judul Blog <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg form-input-custom"
                                        id="title"
                                        name="title"
                                        value="{{ old('title', $b['title'] ?? '') }}"
                                        placeholder="Masukkan judul artikel blog"
                                        required
                                        minlength="5"
                                        maxlength="200"
                                        autofocus>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label for="category" class="form-label form-label-custom">
                                            <i class="lni lni-folder"></i> Kategori
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control form-control-lg form-input-custom"
                                            id="category"
                                            name="category"
                                            value="{{ old('category', $b['category'] ?? '') }}"
                                            placeholder="Contoh: Teknologi, Research, dll"
                                            maxlength="50">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="tags" class="form-label form-label-custom">
                                            <i class="lni lni-tag"></i> Tags
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control form-control-lg form-input-custom"
                                            id="tags"
                                            name="tags"
                                            value="{{ old('tags', $b['tags_string'] ?? '') }}"
                                            placeholder="Pisahkan dengan koma (contoh: php, javascript, web)"
                                            maxlength="200">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="featured_image" class="form-label form-label-custom">
                                        <i class="lni lni-image"></i> Gambar Featured
                                    </label>
                                    @if($isEdit && !empty($b['featured_image']))
                                        <div class="mb-2">
                                            <small class="text-muted">Gambar saat ini:</small><br>
                                            <img src="{{ preg_match('/^https?:\/\//', $b['featured_image']) ? $b['featured_image'] : asset(ltrim($b['featured_image'], '/')) }}"
                                                 alt="Current"
                                                 style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 2px solid #e0e0e0; margin-top: 8px;">
                                        </div>
                                    @endif
                                    <input
                                        type="file"
                                        class="form-control form-control-lg form-input-custom"
                                        id="featured_image"
                                        name="featured_image"
                                        accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                                </div>

                                <div class="mb-4">
                                    <label for="excerpt" class="form-label form-label-custom">
                                        <i class="lni lni-text-format"></i> Excerpt / Ringkasan
                                    </label>
                                    <textarea
                                        class="form-control form-input-custom"
                                        id="excerpt"
                                        name="excerpt"
                                        rows="3"
                                        placeholder="Ringkasan singkat tentang artikel ini..."
                                        maxlength="300">{{ old('excerpt', $b['excerpt'] ?? '') }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label for="content" class="form-label form-label-custom">
                                        <i class="lni lni-file"></i> Konten Blog <span class="text-danger">*</span>
                                    </label>
                                    <div id="content-editor" style="min-height: 400px; margin-bottom: 10px; background: #fff;">{!! old('content', $b['content'] ?? '') !!}</div>
                                    <textarea
                                        class="form-control form-input-custom"
                                        id="content"
                                        name="content"
                                        rows="15"
                                        style="display: none;">{{ old('content', $b['content'] ?? '') }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $b['is_published'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label form-label-custom" for="is_published">
                                            <i class="lni lni-checkmark-circle"></i> Publikasikan sekarang
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-success btn-lg px-4">
                                        <i class="lni lni-save me-2"></i> {{ $isEdit ? 'Update Blog' : 'Simpan Blog' }}
                                    </button>
                                    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                        <i class="lni lni-arrow-left me-2"></i> Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="{{ asset('assets/js/classes/ToastNotification.js') }}"></script>
<script src="{{ asset('assets/js/classes/LoadingState.js') }}"></script>
<script src="{{ asset('assets/js/classes/AnggotaConfirmModal.js') }}"></script>
<script>
const blogIndexUrl = @json(route('admin.blog.index'));

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchBlog');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length >= 2 || query.length === 0) {
                searchTimeout = setTimeout(function() {
                    window.location.href = query.length > 0
                        ? blogIndexUrl + '?search=' + encodeURIComponent(query)
                        : blogIndexUrl;
                }, 500);
            }
        });
    }

    const editorEl = document.getElementById('content-editor');
    const contentTextarea = document.getElementById('content');
    const formBlog = document.getElementById('formBlog');
    if (editorEl && contentTextarea && typeof Quill !== 'undefined') {
        const quill = new Quill('#content-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image', 'blockquote', 'code-block'],
                    ['clean']
                ]
            }
        });
        if (formBlog) {
            formBlog.addEventListener('submit', function() {
                contentTextarea.value = quill.root.innerHTML;
            });
        }
    }
});

function confirmDeleteBlog(id, title) {
    if (confirm('Apakah Anda yakin ingin menghapus blog "' + title + '"?')) {
        window.location.href = blogIndexUrl + '?action=hapus&id=' + id;
    }
}
</script>
@endpush
