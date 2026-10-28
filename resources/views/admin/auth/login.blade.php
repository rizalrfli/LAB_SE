<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laboratorium Software Engineering</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-5.0.0-alpha-2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/LineIcons.2.0.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}" />
</head>
<body class="login-page-wrapper">
    <div class="row justify-content-center w-100">
        <div class="login-card">
            <div class="text-center">
                <h2 class="login-title">Login</h2>
                <p class="login-subtitle">Akses ke Sistem Laboratorium</p>
            </div>

            @include('partials.flash-message')

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="mb-4 input-icon-group">
                    <label for="username" class="form-label visually-hidden">Nama Pengguna</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Nama Pengguna" value="{{ old('username') }}" required>
                </div>

                <div class="mb-4 input-icon-group">
                    <label for="password" class="form-label visually-hidden">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Kata Sandi" required>
                </div>

                <button type="submit" class="btn btn-main w-100">LOGIN</button>

                <div class="back-link-container">
                    <a href="{{ route('home') }}">← Kembali ke Website Utama</a>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('assets/js/bootstrap.5.0.0.alpha-2-min.js') }}"></script>
</body>
</html>
