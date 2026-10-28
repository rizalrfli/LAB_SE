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
                    <h2 class="mb-15">Tambah Publikasi</h2>
                    <p>Tambahkan publikasi jurnal atau konferensi baru</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <div class="row">
            <div class="col-lg-10">
                <div class="card form-card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0"><i class="lni lni-book text-primary"></i> Form Tambah Publikasi</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.publikasi.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Judul Publikasi <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required minlength="5">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Tipe <span class="text-danger">*</span></label>
                                    <select name="tipe" class="form-control">
                                        <option value="journal" {{ old('tipe') === 'journal' ? 'selected' : '' }}>Journal</option>
                                        <option value="conference" {{ old('tipe') === 'conference' ? 'selected' : '' }}>Conference</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Tahun <span class="text-danger">*</span></label>
                                    <input type="number" name="tahun" class="form-control" value="{{ old('tahun', date('Y')) }}" required min="1990" max="2100">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Penulis Utama</label>
                                    <input type="text" name="penulis_utama" class="form-control" value="{{ old('penulis_utama') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Jurnal / Conference</label>
                                    <input type="text" name="nama_journal_conference" class="form-control" value="{{ old('nama_journal_conference') }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Daftar Penulis Lengkap</label>
                                    <input type="text" name="daftar_penulis" class="form-control" value="{{ old('daftar_penulis') }}" placeholder="Pisahkan dengan koma">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Volume</label>
                                    <input type="text" name="volume" class="form-control" value="{{ old('volume') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Issue / Nomor</label>
                                    <input type="text" name="issue" class="form-control" value="{{ old('issue') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Halaman</label>
                                    <input type="text" name="halaman" class="form-control" value="{{ old('halaman') }}" placeholder="Contoh: 120-135">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Quartile</label>
                                    <select name="quartile" class="form-control">
                                        <option value="">- Tidak Ada -</option>
                                        <option value="Q1" {{ old('quartile') === 'Q1' ? 'selected' : '' }}>Q1</option>
                                        <option value="Q2" {{ old('quartile') === 'Q2' ? 'selected' : '' }}>Q2</option>
                                        <option value="Q3" {{ old('quartile') === 'Q3' ? 'selected' : '' }}>Q3</option>
                                        <option value="Q4" {{ old('quartile') === 'Q4' ? 'selected' : '' }}>Q4</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">DOI</label>
                                    <input type="text" name="doi" class="form-control" value="{{ old('doi') }}" placeholder="10.1109/...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Link URL</label>
                                    <input type="url" name="link_url" class="form-control" value="{{ old('link_url') }}" placeholder="https://...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tanggal Publikasi</label>
                                    <input type="date" name="tanggal_publikasi" class="form-control" value="{{ old('tanggal_publikasi') }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Abstrak</label>
                                    <textarea name="abstract" class="form-control" rows="4">{{ old('abstract') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Keywords</label>
                                    <input type="text" name="keywords" class="form-control" value="{{ old('keywords') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="accepted" {{ old('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                        <option value="submitted" {{ old('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="scopus_indexed" id="scopus_indexed" class="form-check-input" value="1" {{ old('scopus_indexed') ? 'checked' : '' }}>
                                        <label for="scopus_indexed" class="form-check-label fw-bold">Terindeks Scopus</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-4">
                                    <i class="lni lni-save me-2"></i> Simpan Publikasi
                                </button>
                                <a href="{{ route('admin.publikasi.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="lni lni-arrow-left me-2"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
