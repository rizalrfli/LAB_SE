<!DOCTYPE html>
<html class="no-js" lang="id">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>@yield('title', 'Web Profile Laboratorium Software Engineering')</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover" />

    <style>
    html, body {
        overflow-x: hidden !important;
        max-width: 100vw !important;
        width: 100% !important;
    }
    @media (max-width: 767px) {
        * {
            box-sizing: border-box !important;
        }
        .container,
        .container-fluid {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .row {
            margin-left: -15px !important;
            margin-right: -15px !important;
            max-width: 100% !important;
        }
        [class*="col-"] {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
        img {
            max-width: 100% !important;
            height: auto !important;
        }
        section {
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
        .hero-mascot,
        .hero-mascot .mascot-container,
        .hero-mascot .mascot-illustration {
            max-width: 100% !important;
            width: 100% !important;
        }
    }
    </style>

    <!-- ========================= CSS here ========================= -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-5.0.0-alpha-2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/LineIcons.2.0.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lindy-uikit.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/fixed-navbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dosen-pengampu.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/landing-page.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/footer-modern.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive-fix.css') }}" />

    @if (!empty($pageCss))
        <link rel="stylesheet" href="{{ asset('assets/css/' . $pageCss) }}" />
    @endif
    @stack('styles')
  </head>
  <body>
    <nav id="fixed-navbar" class="fixed-navbar-new">
      <div class="navbar-container-new">
        <div class="container">
          <div class="navbar-content-new">
            <a class="navbar-brand-new" href="{{ route('home') }}">
              <img src="{{ $navbarLogo ?? asset('assets/img/logo/logo.png') }}" alt="Logo Lab SE" onerror="this.src='{{ asset('assets/img/logo/logo.png') }}'" />
            </a>
            <button class="navbar-toggler-new" type="button" id="navbarToggleNew" aria-label="Toggle navigation">
              <span></span>
              <span></span>
              <span></span>
            </button>
            <div class="navbar-menu-new" id="navbarMenuNew">
              <ul id="nav2" class="navbar-nav">
                <li class="nav-item">
                  <a class="page-scroll" href="{{ route('home') }}#home">Home</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="page-scroll dropdown-toggle" href="#" id="navbarDropdownProfile">Profil Kami</a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('profil.tentang-kami') }}">Tentang Kami</a>
                    <a class="dropdown-item" href="{{ route('profil.visi-misi') }}">Visi &amp; Misi</a>
                    <a class="dropdown-item" href="{{ route('profil.roadmap') }}">Roadmap</a>
                  </div>
                </li>
                <li class="nav-item">
                  <a class="page-scroll" href="{{ route('profil.personil') }}">Personil</a>
                </li>
                <li class="nav-item">
                  <a class="page-scroll" href="{{ route('profil.penelitian-publikasi') }}">Penelitian dan Publikasi</a>
                </li>
                <li class="nav-item">
                  <a class="page-scroll" href="{{ route('pendaftaran.daftar') }}">Daftar</a>
                </li>
                <li class="nav-item">
                  <a class="page-scroll" href="{{ route('home') }}#blog">Blog</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="page-scroll dropdown-toggle" href="#" id="navbarDropdownStats">Statistik</a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('profil.personil') }}">Anggota: <strong>{{ $stats['anggota'] ?? 0 }}</strong></a>
                    <a class="dropdown-item" href="{{ route('pendaftaran.list') }}">Pendaftar: <strong>{{ $stats['pendaftar'] ?? 0 }}</strong></a>
                    <a class="dropdown-item" href="{{ route('blog.index') }}">Blog: <strong>{{ $stats['blog'] ?? 0 }}</strong></a>
                  </div>
                </li>
              </ul>
              <a href="{{ route('admin.login') }}" class="btn-admin-new">Admin</a>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <div class="preloader">
      <div class="loader">
        <div class="spinner">
          <div class="spinner-container">
            <div class="spinner-rotator">
              <div class="spinner-left">
                <div class="spinner-circle"></div>
              </div>
              <div class="spinner-right">
                <div class="spinner-circle"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @yield('content')

    @php
        $formatFooterUrl = function ($url) {
            if (empty($url) || $url === '#' || $url === '#0') {
                return $url ?: '#';
            }
            if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                return $url;
            }
            return url($url);
        };
    @endphp

    <!-- ========================= footer style-1 start ========================= -->
    <footer class="footer footer-style-1">
      <div class="container">
        <div class="widget-wrapper">
          <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-6">
              <div class="footer-widget wow fadeInUp" data-wow-delay=".2s">
                <div class="logo">
                  <a href="{{ route('home') }}"> <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo Lab SE"> </a>
                </div>
                <p class="desc">{{ $footerDeskripsi ?? '' }}</p>
                <ul class="socials">
                  @foreach ($footerSocialLinks ?? [] as $social)
                  <li>
                    <a href="{{ $formatFooterUrl($social['url'] ?? '#0') }}" target="_blank" rel="noopener" title="{{ $social['label'] ?? '' }}">
                      <i class="lni {{ $social['icon'] ?? 'lni-link' }}"></i>
                    </a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-xl-2 offset-xl-1 col-lg-2 col-md-6 col-sm-6">
              <div class="footer-widget wow fadeInUp" data-wow-delay=".3s">
                <h6>Tautan Cepat</h6>
                <ul class="links">
                  @foreach ($footerTautanCepat ?? [] as $link)
                  <li>
                    <a href="{{ $formatFooterUrl($link['url'] ?? '#') }}">
                      {{ $link['label'] ?? '' }}
                    </a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
              <div class="footer-widget wow fadeInUp" data-wow-delay=".4s">
                <h6>Layanan</h6>
                <ul class="links">
                  @foreach ($footerLayanan ?? [] as $link)
                  <li>
                    <a href="{{ $formatFooterUrl($link['url'] ?? '#') }}">
                      {{ $link['label'] ?? '' }}
                    </a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
              <div class="footer-widget wow fadeInUp" data-wow-delay=".5s">
                <h6>Informasi</h6>
                <ul class="links">
                  @foreach ($footerInformasi ?? [] as $link)
                  <li>
                    <a href="{{ $formatFooterUrl($link['url'] ?? '#') }}" style="background: transparent !important; border: none !important; padding: 0 !important; border-radius: 0 !important; box-shadow: none !important; color: #6c757d !important; font-weight: 400 !important; font-size: 14px !important;">
                      {{ $link['label'] ?? '' }}
                    </a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="copyright-wrapper wow fadeInUp" data-wow-delay=".2s">
          <p>{{ $footerCopyright ?? '' }}</p>
        </div>
      </div>
    </footer>
    <!-- ========================= footer style-1 end ========================= -->

    <a href="#" class="scroll-top"> <i class="lni lni-chevron-up"></i> </a>

    <!-- ========================= JS here ========================= -->
    <script src="{{ asset('assets/js/bootstrap.5.0.0.alpha-2-min.js') }}"></script>
    <script src="{{ asset('assets/js/count-up.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
    (function() {
        'use strict';
        function forceResponsive() {
            var width = window.innerWidth || document.documentElement.clientWidth;
            if (width <= 767) {
                var cols = document.querySelectorAll('[class*="col-"]');
                cols.forEach(function(col) {
                    if (!col.classList.contains('col-12')) {
                        col.style.flex = '0 0 100%';
                        col.style.maxWidth = '100%';
                        col.style.width = '100%';
                    }
                });
                var containers = document.querySelectorAll('.container, .container-fluid');
                containers.forEach(function(container) {
                    container.style.maxWidth = '100%';
                    container.style.width = '100%';
                    container.style.paddingLeft = '15px';
                    container.style.paddingRight = '15px';
                });
                var images = document.querySelectorAll('img');
                images.forEach(function(img) {
                    if (img.style.maxWidth !== '100%') {
                        img.style.maxWidth = '100%';
                        img.style.height = 'auto';
                    }
                });
                document.documentElement.style.overflowX = 'hidden';
                document.body.style.overflowX = 'hidden';
                document.documentElement.style.maxWidth = '100vw';
                document.body.style.maxWidth = '100vw';
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', forceResponsive);
        } else {
            forceResponsive();
        }
        window.addEventListener('resize', forceResponsive);
        setTimeout(forceResponsive, 100);
        setTimeout(forceResponsive, 500);
    })();
    </script>

    <script>
    (function() {
        var toggleBtn = document.getElementById('navbarToggleNew');
        var navbarMenu = document.getElementById('navbarMenuNew');
        if (toggleBtn && navbarMenu) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                navbarMenu.classList.toggle('active');
                toggleBtn.classList.toggle('active');
            });
            document.addEventListener('click', function(e) {
                if (!toggleBtn.contains(e.target) && !navbarMenu.contains(e.target)) {
                    navbarMenu.classList.remove('active');
                    toggleBtn.classList.remove('active');
                }
            });
            function setupMobileDropdowns() {
                var dropdownToggles = navbarMenu.querySelectorAll('.dropdown-toggle');
                dropdownToggles.forEach(function(toggle) {
                    var newToggle = toggle.cloneNode(true);
                    toggle.parentNode.replaceChild(newToggle, toggle);
                    newToggle.addEventListener('click', function(e) {
                        if (window.innerWidth <= 991) {
                            e.preventDefault();
                            e.stopPropagation();
                            var dropdown = this.closest('.nav-item.dropdown') || this.parentElement;
                            var dropdownMenu = dropdown.querySelector('.dropdown-menu');
                            if (!dropdownMenu) return;
                            var isActive = dropdown.classList.contains('active');
                            if (isActive) {
                                dropdownMenu.style.display = 'none';
                                dropdown.classList.remove('active', 'show');
                            } else {
                                dropdownMenu.style.display = 'block';
                                dropdown.classList.add('active', 'show');
                            }
                        }
                    });
                });
            }
            setupMobileDropdowns();
        }
    })();
    </script>
    @stack('scripts')
  </body>
</html>
