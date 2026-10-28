<?php

namespace App\Providers;

use App\Models\KontenPage;
use App\Services\MaterializedViewService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (
            $this->app->environment('production') ||
            isset($_ENV['VERCEL']) ||
            isset($_SERVER['VERCEL']) ||
            getenv('VERCEL') ||
            request()->header('x-forwarded-proto') === 'https'
        ) {
            URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();

        View::composer('layouts.public', function ($view) {
            $navbarLogo = asset('assets/img/logo/logo.png');
            $stats = ['anggota' => 0, 'pendaftar' => 0, 'blog' => 0];

            $footerDeskripsi = 'Laboratorium Software Engineering merupakan wadah pengembangan dan riset di bidang Rekayasa Perangkat Lunak.';
            $footerCopyright = '© ' . date('Y') . ' Laboratorium Software Engineering. All Rights Reserved.';
            $footerSocialLinks = [
                ['icon' => 'lni lni-facebook-filled', 'url' => '#0', 'label' => 'Facebook'],
                ['icon' => 'lni lni-twitter-filled', 'url' => '#0', 'label' => 'Twitter'],
                ['icon' => 'lni lni-instagram-filled', 'url' => '#0', 'label' => 'Instagram'],
                ['icon' => 'lni lni-linkedin-original', 'url' => '#0', 'label' => 'LinkedIn'],
            ];
            $footerTautanCepat = [
                ['label' => 'Beranda', 'url' => url('/') . '#home'],
                ['label' => 'Tentang Kami', 'url' => route('profil.tentang-kami')],
                ['label' => 'Personil', 'url' => route('profil.personil')],
            ];
            $footerLayanan = [
                ['label' => 'Visi & Misi', 'url' => route('profil.visi-misi')],
                ['label' => 'Roadmap', 'url' => route('profil.roadmap')],
                ['label' => 'Blog', 'url' => url('/') . '#blog'],
                ['label' => 'Dosen Pengampu', 'url' => url('/') . '#galeri-dosen'],
            ];
            $footerInformasi = [
                ['label' => 'Pendaftaran', 'url' => route('pendaftaran.daftar')],
                ['label' => 'Anggota Lab', 'url' => route('profil.personil')],
                ['label' => 'Admin', 'url' => route('admin.login')],
                ['label' => 'Kontak', 'url' => url('/') . '#contact'],
            ];

            try {
                $mvService = app(MaterializedViewService::class);
                $mvStats = $mvService->getStatsComplete();
                $stats = [
                    'anggota' => (int) ($mvStats['total_anggota'] ?? 0),
                    'pendaftar' => (int) ($mvStats['total_pendaftar'] ?? 0),
                    'blog' => (int) ($mvStats['total_blog_published'] ?? 0),
                ];

                $keys = [
                    'navbar_logo',
                    'footer_deskripsi',
                    'footer_copyright',
                    'footer_social_links',
                    'footer_tautan_cepat',
                    'footer_layanan',
                    'footer_informasi',
                ];
                $kontenMap = KontenPage::whereIn('page_key', $keys)
                    ->pluck('content', 'page_key')
                    ->toArray();

                if (!empty($kontenMap['navbar_logo'])) {
                    $logoPath = ltrim($kontenMap['navbar_logo'], '/');
                    $navbarLogo = str_starts_with($logoPath, 'http') ? $logoPath : asset($logoPath);
                }
                if (!empty($kontenMap['footer_deskripsi'])) {
                    $footerDeskripsi = $kontenMap['footer_deskripsi'];
                }
                if (!empty($kontenMap['footer_copyright'])) {
                    $footerCopyright = str_replace('{YEAR}', date('Y'), $kontenMap['footer_copyright']);
                }
                if (!empty($kontenMap['footer_social_links'])) {
                    $decoded = json_decode($kontenMap['footer_social_links'], true);
                    if (is_array($decoded) && !empty($decoded)) {
                        $footerSocialLinks = $decoded;
                    }
                }
                if (!empty($kontenMap['footer_tautan_cepat'])) {
                    $decoded = json_decode($kontenMap['footer_tautan_cepat'], true);
                    if (is_array($decoded) && !empty($decoded)) {
                        $footerTautanCepat = $decoded;
                    }
                }
                if (!empty($kontenMap['footer_layanan'])) {
                    $decoded = json_decode($kontenMap['footer_layanan'], true);
                    if (is_array($decoded) && !empty($decoded)) {
                        $footerLayanan = $decoded;
                    }
                }
                if (!empty($kontenMap['footer_informasi'])) {
                    $decoded = json_decode($kontenMap['footer_informasi'], true);
                    if (is_array($decoded) && !empty($decoded)) {
                        $footerInformasi = $decoded;
                    }
                }
            } catch (Throwable $e) {
                // Keep defaults when DB is unreachable
            }

            $view->with([
                'navbarLogo' => $navbarLogo,
                'stats' => $stats,
                'footerDeskripsi' => $footerDeskripsi,
                'footerCopyright' => $footerCopyright,
                'footerSocialLinks' => $footerSocialLinks,
                'footerTautanCepat' => $footerTautanCepat,
                'footerLayanan' => $footerLayanan,
                'footerInformasi' => $footerInformasi,
            ]);
        });
    }
}
