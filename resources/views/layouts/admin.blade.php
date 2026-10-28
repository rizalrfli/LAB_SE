<!DOCTYPE html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>{{ $page_title ?? 'Admin - Web Profile Laboratorium SE' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-5.0.0-alpha-2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/LineIcons.2.0.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lindy-uikit.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/admin-layout.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/admin-modules.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/anggota-ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive-fix.css') }}" />
    @if (isset($current_page) && in_array($current_page, ['penelitian', 'publikasi']))
        <link rel="stylesheet" href="{{ asset('assets/css/penelitian-publikasi-admin.css') }}" />
    @endif
    @stack('styles')
</head>
<body>
    <div class="preloader">
      <div class="loader">
        <div class="spinner">
          <div class="spinner-container">
            <div class="spinner-rotator">
              <div class="spinner-left"><div class="spinner-circle"></div></div>
              <div class="spinner-right"><div class="spinner-circle"></div></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <header class="header header-2">
        <div class="navbar-area">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="{{ route('home') }}">
                      <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo" />
                    </a>
                    <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <i class="lni lni-menu"></i>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent2">
                        <ul id="nav2" class="navbar-nav ml-auto">
                            <li class="nav-item">
                              <a class="page-scroll active" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                        </ul>
                      <a href="{{ route('admin.logout') }}" class="button button-sm radius-10 border-button d-none d-lg-flex">Logout</a>
                      <a href="{{ route('admin.logout') }}" class="button button-sm radius-10 border-button d-lg-none mt-2">Logout</a>
                    </div>
                  </nav>
              </div>
            </div>
          </div>
        </div>
    </header>

    @php
        $activePage = $current_page ?? 'dashboard.php';
        $sidebarMenu = [
            'dashboard.php' => ['title' => 'Dashboard', 'icon' => 'lni lni-home', 'link' => route('admin.dashboard'), 'active' => in_array($activePage, ['dashboard.php', 'dashboard'])],
            'anggota' => ['title' => 'Kelola Anggota', 'icon' => 'lni lni-users', 'link' => route('admin.anggota.index'), 'active' => $activePage === 'anggota'],
            'jabatan' => ['title' => 'Kelola Jabatan', 'icon' => 'lni lni-briefcase', 'link' => route('admin.jabatan.index'), 'active' => $activePage === 'jabatan'],
            'user' => ['title' => 'Kelola User', 'icon' => 'lni lni-user', 'link' => route('admin.user.index'), 'active' => $activePage === 'user'],
            'pendaftar' => ['title' => 'Kelola Pendaftar', 'icon' => 'lni lni-graduation', 'link' => route('admin.pendaftar.index'), 'active' => $activePage === 'pendaftar'],
            'blog' => ['title' => 'Kelola Blog', 'icon' => 'lni lni-pencil-alt', 'link' => route('admin.blog.index'), 'active' => $activePage === 'blog'],
            'profil' => ['title' => 'Kelola Profil', 'icon' => 'lni lni-cog', 'link' => route('admin.profil.index'), 'active' => in_array($activePage, ['profil', 'roadmap'])],
            'penelitian' => ['title' => 'Kelola Penelitian', 'icon' => 'lni lni-microscope', 'link' => route('admin.penelitian.index'), 'active' => $activePage === 'penelitian'],
            'publikasi' => ['title' => 'Kelola Publikasi', 'icon' => 'lni lni-book', 'link' => route('admin.publikasi.index'), 'active' => $activePage === 'publikasi'],
        ];
        $loggedUsername = auth()->user()?->username ?? session('username', 'Admin');
    @endphp

    <button class="sidebar-toggle" id="sidebarToggle" style="z-index: 1000000 !important; position: fixed !important; pointer-events: auto !important; cursor: pointer !important;">
        <i class="lni lni-menu"></i>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <h4>Admin Panel</h4>
            <p><i class="lni lni-user"></i> {{ $loggedUsername }}</p>
        </div>
        <ul class="sidebar-menu">
            @foreach ($sidebarMenu as $key => $item)
                <li>
                    <a href="{{ $item['link'] }}" class="{{ $item['active'] ? 'active' : '' }}">
                        <i class="{{ $item['icon'] }}"></i>
                        <span class="menu-text">{{ $item['title'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="sidebar-divider"></div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('home') }}" target="_blank">
                    <i class="lni lni-world"></i>
                    <span class="menu-text">Lihat Website</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.logout') }}" style="color: #e74c3c;">
                    <i class="lni lni-exit"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </li>
        </ul>
    </aside>

    @yield('content')

    <footer class="footer footer-style-1">
        <div class="container">
          <div class="copyright-wrapper wow fadeInUp" data-wow-delay=".2s">
            <p>Admin Panel - Laboratorium Software Engineering</p>
          </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.5.0.0.alpha-2-min.js') }}"></script>
    <script src="{{ asset('assets/js/count-up.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/classes/AdminSidebar.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
