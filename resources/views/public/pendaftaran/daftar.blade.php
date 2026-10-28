@extends('layouts.public')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/daftar.css') }}" />
@endpush

@section('content')
<section id="daftar" class="contact-section contact-style-6 pt-150 pb-100" style="margin-top: 80px;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="section-title text-center mb-60">
                <h3 class="mb-15 wow fadeInUp gradient-text" data-wow-delay=".2s">Formulir <span>Pendaftaran</span></h3>
                <p class="wow fadeInUp" data-wow-delay=".4s">Isi data di bawah ini untuk mendaftar sebagai anggota Laboratorium Software Engineering.</p>
            </div>

            @include('partials.flash-message')

            <div class="contact-form-wrapper wow fadeInUp" data-wow-delay=".6s">
                <form method="POST" action="{{ route('pendaftaran.submit') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                    <div class="col-md-12">
                      <div class="single-input">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Masukkan Nama Lengkap Anda" value="{{ old('name') }}" required>
                        <i class="lni lni-user"></i>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="single-input">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="nama@email.com" value="{{ old('email') }}" required>
                        <i class="lni lni-envelope"></i>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="single-input">
                        <label for="nim">NIM</label>
                        <input type="text" id="nim" name="nim" class="form-input" placeholder="Masukkan NIM Anda" value="{{ old('nim') }}" required>
                        <i class="lni lni-graduation"></i>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="single-input">
                        <label for="foto">Foto (opsional)</label>
                        <input type="file" id="foto" name="foto" class="form-input" accept=".jpg,.jpeg,.png,.webp">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-button text-center">
                        <button type="submit" class="button radius-10">Kirim Pendaftaran</button>
                      </div>
                    </div>
                  </div>
                </form>
            </div>
        </div>
      </div>
    </div>
</section>
@endsection
