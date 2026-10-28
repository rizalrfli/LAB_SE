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
                    <h2 class="mb-15">Edit Penelitian</h2>
                    <p>Perbarui data proyek penelitian laboratorium</p>
                </div>
            </div>
        </div>

        @include('partials.flash-message')

        <div class="row">
            <div class="col-lg-10">
                <div class="card form-card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0"><i class="lni lni-pencil text-primary"></i> Form Edit Penelitian</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.penelitian.update', ['id' => $penelitian['id']]) }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $penelitian['id'] }}">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Judul Penelitian <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" class="form-control" value="{{ old('judul', $penelitian['judul']) }}" required minlength="5">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tahun <span class="text-danger">*</span></label>
                                    <input type="number" name="tahun" class="form-control" value="{{ old('tahun', $penelitian['tahun']) }}" required min="2000" max="2100">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="ongoing" {{ old('status', $penelitian['status'] ?? 'ongoing') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="completed" {{ old('status', $penelitian['status'] ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Kategori</label>
                                    <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $penelitian['kategori'] ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Progress (%)</label>
                                    <input type="number" name="progress" class="form-control" value="{{ old('progress', $penelitian['progress'] ?? 0) }}" min="0" max="100">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Peneliti Utama</label>
                                    <input type="text" name="peneliti_utama" class="form-control" value="{{ old('peneliti_utama', $penelitian['peneliti_utama'] ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Dosen Pembimbing</label>
                                    <input type="text" name="dosen_pembimbing" class="form-control" value="{{ old('dosen_pembimbing', $penelitian['dosen_pembimbing'] ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jumlah Tim</label>
                                    <input type="number" name="jumlah_tim" class="form-control" value="{{ old('jumlah_tim', $penelitian['jumlah_tim'] ?? 1) }}" min="0">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Anggota Tim</label>
                                    <textarea name="anggota_tim" class="form-control" rows="2">{{ old('anggota_tim', $penelitian['anggota_tim'] ?? '') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', !empty($penelitian['tanggal_mulai']) ? substr($penelitian['tanggal_mulai'], 0, 10) : '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', !empty($penelitian['tanggal_selesai']) ? substr($penelitian['tanggal_selesai'], 0, 10) : '') }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Deskripsi Penelitian</label>
                                    <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $penelitian['deskripsi'] ?? '') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Metodologi</label>
                                    <textarea name="metodologi" class="form-control" rows="3">{{ old('metodologi', $penelitian['metodologi'] ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Output / Hasil</label>
                                    <textarea name="output_hasil" class="form-control" rows="3">{{ old('output_hasil', $penelitian['output_hasil'] ?? '') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Keywords</label>
                                    <input type="text" name="keywords" class="form-control" value="{{ old('keywords', $penelitian['keywords'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Link Dokumen / Repository</label>
                                    <input type="url" name="link_dokumen" class="form-control" value="{{ old('link_dokumen', $penelitian['link_dokumen'] ?? '') }}">
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="lni lni-save me-2"></i> Update Penelitian
                                </button>
                                <a href="{{ route('admin.penelitian.index') }}" class="btn btn-outline-secondary btn-lg px-4">
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
