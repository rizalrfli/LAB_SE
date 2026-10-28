<?php

use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\PendaftarController;
use App\Http\Controllers\Admin\PenelitianAdminController;
use App\Http\Controllers\Admin\ProfilAdminController;
use App\Http\Controllers\Admin\PublikasiAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/index.php', [HomeController::class, 'index']);

// Profil Routes
Route::get('/profil/tentang-kami', [ProfilController::class, 'tentangKami'])->name('profil.tentang-kami');
Route::get('/pages/profil/tentang-kami.php', [ProfilController::class, 'tentangKami']);

Route::get('/profil/visi-misi', [ProfilController::class, 'visiMisi'])->name('profil.visi-misi');
Route::get('/pages/profil/visi-misi.php', [ProfilController::class, 'visiMisi']);

Route::get('/profil/roadmap', [ProfilController::class, 'roadmap'])->name('profil.roadmap');
Route::get('/pages/profil/roadmap.php', [ProfilController::class, 'roadmap']);

Route::get('/profil/personil', [ProfilController::class, 'personil'])->name('profil.personil');
Route::get('/pages/profil/personil.php', [ProfilController::class, 'personil']);

Route::get('/profil/personil/{id?}', [ProfilController::class, 'detailPersonil'])->name('profil.detail-personil');
Route::get('/pages/profil/detail-personil.php', [ProfilController::class, 'detailPersonil']);

Route::get('/profil/penelitian-dan-publikasi', [ProfilController::class, 'penelitianPublikasi'])->name('profil.penelitian-publikasi');
Route::get('/pages/profil/penelitian-dan-publikasi.php', [ProfilController::class, 'penelitianPublikasi']);

// Blog Public Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/pages/blog/blog-list.php', [BlogController::class, 'index']);

Route::get('/blog/detail/{slug?}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/pages/blog/blog-detail.php', [BlogController::class, 'show']);

// Pendaftaran Public Routes
Route::get('/pendaftaran/daftar', [PendaftaranController::class, 'showForm'])->name('pendaftaran.daftar');
Route::post('/pendaftaran/daftar', [PendaftaranController::class, 'submitForm'])->name('pendaftaran.submit');
Route::get('/pages/pendaftaran/daftar.php', [PendaftaranController::class, 'showForm']);
Route::post('/pages/pendaftaran/daftar.php', [PendaftaranController::class, 'submitForm']);

Route::get('/pendaftaran/list', [PendaftaranController::class, 'listPendaftar'])->name('pendaftaran.list');
Route::get('/pages/pendaftaran/pendaftar-list.php', [PendaftaranController::class, 'listPendaftar']);

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::match(['get', 'post'], '/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Legacy Auth URL aliases
Route::get('/module/auth/login.php', [AuthController::class, 'showLogin']);
Route::post('/module/auth/login.php', [AuthController::class, 'login']);
Route::match(['get', 'post'], '/module/auth/logout.php', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| Protected Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['labse.auth'])->group(function () {
    // Dashboard
    Route::get('/admin', fn () => redirect()->route('admin.dashboard'));
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/module/admin/dashboard.php', [DashboardController::class, 'index']);

    // Anggota
    Route::get('/admin/anggota', [AnggotaController::class, 'index'])->name('admin.anggota.index');
    Route::get('/admin/anggota/tambah', [AnggotaController::class, 'create'])->name('admin.anggota.create');
    Route::post('/admin/anggota/tambah', [AnggotaController::class, 'store'])->name('admin.anggota.store');
    Route::get('/admin/anggota/edit/{id?}', [AnggotaController::class, 'edit'])->name('admin.anggota.edit');
    Route::post('/admin/anggota/edit/{id?}', [AnggotaController::class, 'update'])->name('admin.anggota.update');
    Route::match(['get', 'post', 'delete'], '/admin/anggota/hapus/{id?}', [AnggotaController::class, 'destroy'])->name('admin.anggota.destroy');

    // Legacy Anggota URLs
    Route::get('/module/anggota/index.php', [AnggotaController::class, 'index']);
    Route::get('/module/anggota/tambah.php', [AnggotaController::class, 'create']);
    Route::post('/module/anggota/tambah.php', [AnggotaController::class, 'store']);
    Route::get('/module/anggota/edit.php', [AnggotaController::class, 'edit']);
    Route::post('/module/anggota/edit.php', [AnggotaController::class, 'update']);
    Route::match(['get', 'post', 'delete'], '/module/anggota/hapus.php', [AnggotaController::class, 'destroy']);

    // Jabatan
    Route::get('/admin/jabatan', [JabatanController::class, 'index'])->name('admin.jabatan.index');
    Route::get('/admin/jabatan/tambah', [JabatanController::class, 'create'])->name('admin.jabatan.create');
    Route::post('/admin/jabatan/tambah', [JabatanController::class, 'store'])->name('admin.jabatan.store');
    Route::get('/admin/jabatan/edit/{id?}', [JabatanController::class, 'edit'])->name('admin.jabatan.edit');
    Route::post('/admin/jabatan/edit/{id?}', [JabatanController::class, 'update'])->name('admin.jabatan.update');
    Route::match(['get', 'post', 'delete'], '/admin/jabatan/hapus/{id?}', [JabatanController::class, 'destroy'])->name('admin.jabatan.destroy');

    // Legacy Jabatan URLs
    Route::get('/module/jabatan/index.php', [JabatanController::class, 'index']);
    Route::get('/module/jabatan/tambah.php', [JabatanController::class, 'create']);
    Route::post('/module/jabatan/tambah.php', [JabatanController::class, 'store']);
    Route::get('/module/jabatan/edit/index.php', [JabatanController::class, 'edit']);
    Route::post('/module/jabatan/edit/index.php', [JabatanController::class, 'update']);
    Route::match(['get', 'post', 'delete'], '/module/jabatan/hapus.php', [JabatanController::class, 'destroy']);

    // Pendaftar
    Route::get('/admin/pendaftar', [PendaftarController::class, 'index'])->name('admin.pendaftar.index');
    Route::post('/admin/pendaftar', [PendaftarController::class, 'handleAction'])->name('admin.pendaftar.action');
    Route::get('/admin/pendaftar/history-log', [PendaftarController::class, 'historyLog'])->name('admin.pendaftar.history');

    // Legacy Pendaftar URLs
    Route::get('/module/pendaftar/index.php', [PendaftarController::class, 'index']);
    Route::post('/module/pendaftar/index.php', [PendaftarController::class, 'handleAction']);
    Route::get('/module/pendaftar/history_log.php', [PendaftarController::class, 'historyLog']);

    // Blog Admin
    Route::get('/admin/blog', [BlogAdminController::class, 'index'])->name('admin.blog.index');
    Route::post('/admin/blog', [BlogAdminController::class, 'handlePost'])->name('admin.blog.post');
    Route::post('/admin/blog/upload-gambar', [BlogAdminController::class, 'uploadGambar'])->name('admin.blog.upload');

    // Legacy Blog Admin URLs
    Route::get('/module/blog/index.php', [BlogAdminController::class, 'index']);
    Route::post('/module/blog/index.php', [BlogAdminController::class, 'handlePost']);
    Route::post('/module/blog/upload_gambar.php', [BlogAdminController::class, 'uploadGambar']);

    // Profil & Roadmap Admin
    Route::get('/admin/profil', [ProfilAdminController::class, 'index'])->name('admin.profil.index');
    Route::post('/admin/profil', [ProfilAdminController::class, 'handlePost'])->name('admin.profil.post');
    Route::post('/admin/profil/upload-gambar-navbar', [ProfilAdminController::class, 'uploadGambarNavbar'])->name('admin.profil.upload-navbar');
    Route::post('/admin/profil/upload-gambar', [ProfilAdminController::class, 'uploadGambar'])->name('admin.profil.upload-gambar');
    Route::get('/admin/profil/browse-file', [ProfilAdminController::class, 'browseFile'])->name('admin.profil.browse-file');

    Route::get('/admin/roadmap', [ProfilAdminController::class, 'kelolaRoadmap'])->name('admin.roadmap.index');
    Route::post('/admin/roadmap', [ProfilAdminController::class, 'handleRoadmapPost'])->name('admin.roadmap.post');

    // Legacy Profil & Roadmap URLs
    Route::get('/module/profil/index.php', [ProfilAdminController::class, 'index']);
    Route::post('/module/profil/index.php', [ProfilAdminController::class, 'handlePost']);
    Route::post('/module/profil/upload_gambar_navbar.php', [ProfilAdminController::class, 'uploadGambarNavbar']);
    Route::post('/module/profil/upload_gambar.php', [ProfilAdminController::class, 'uploadGambar']);
    Route::get('/module/profil/browse_file.php', [ProfilAdminController::class, 'browseFile']);
    Route::get('/module/profil/kelola_roadmap.php', [ProfilAdminController::class, 'kelolaRoadmap']);
    Route::post('/module/profil/kelola_roadmap.php', [ProfilAdminController::class, 'handleRoadmapPost']);

    // Penelitian Admin
    Route::get('/admin/penelitian', [PenelitianAdminController::class, 'index'])->name('admin.penelitian.index');
    Route::get('/admin/penelitian/tambah', [PenelitianAdminController::class, 'create'])->name('admin.penelitian.create');
    Route::post('/admin/penelitian/tambah', [PenelitianAdminController::class, 'store'])->name('admin.penelitian.store');
    Route::get('/admin/penelitian/edit/{id?}', [PenelitianAdminController::class, 'edit'])->name('admin.penelitian.edit');
    Route::post('/admin/penelitian/edit/{id?}', [PenelitianAdminController::class, 'update'])->name('admin.penelitian.update');

    // Legacy Penelitian URLs
    Route::get('/module/penelitian/index.php', [PenelitianAdminController::class, 'index']);
    Route::get('/module/penelitian/tambah.php', [PenelitianAdminController::class, 'create']);
    Route::post('/module/penelitian/tambah.php', [PenelitianAdminController::class, 'store']);
    Route::get('/module/penelitian/edit.php', [PenelitianAdminController::class, 'edit']);
    Route::post('/module/penelitian/edit.php', [PenelitianAdminController::class, 'update']);

    // Publikasi Admin
    Route::get('/admin/publikasi', [PublikasiAdminController::class, 'index'])->name('admin.publikasi.index');
    Route::get('/admin/publikasi/tambah', [PublikasiAdminController::class, 'create'])->name('admin.publikasi.create');
    Route::post('/admin/publikasi/tambah', [PublikasiAdminController::class, 'store'])->name('admin.publikasi.store');
    Route::get('/admin/publikasi/edit/{id?}', [PublikasiAdminController::class, 'edit'])->name('admin.publikasi.edit');
    Route::post('/admin/publikasi/edit/{id?}', [PublikasiAdminController::class, 'update'])->name('admin.publikasi.update');

    // Legacy Publikasi URLs
    Route::get('/module/publikasi/index.php', [PublikasiAdminController::class, 'index']);
    Route::get('/module/publikasi/tambah.php', [PublikasiAdminController::class, 'create']);
    Route::post('/module/publikasi/tambah.php', [PublikasiAdminController::class, 'store']);
    Route::get('/module/publikasi/edit.php', [PublikasiAdminController::class, 'edit']);
    Route::post('/module/publikasi/edit.php', [PublikasiAdminController::class, 'update']);

    // User Management (Requires role === 'admin')
    Route::middleware(['labse.admin'])->group(function () {
        Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');
        Route::get('/admin/user/tambah', [UserController::class, 'create'])->name('admin.user.create');
        Route::post('/admin/user/tambah', [UserController::class, 'store'])->name('admin.user.store');
        Route::get('/admin/user/edit/{id?}', [UserController::class, 'edit'])->name('admin.user.edit');
        Route::post('/admin/user/edit/{id?}', [UserController::class, 'update'])->name('admin.user.update');
        Route::match(['get', 'post', 'delete'], '/admin/user/hapus/{id?}', [UserController::class, 'destroy'])->name('admin.user.destroy');

        // Legacy User URLs
        Route::get('/module/user/index.php', [UserController::class, 'index']);
        Route::get('/module/user/tambah.php', [UserController::class, 'create']);
        Route::post('/module/user/tambah.php', [UserController::class, 'store']);
        Route::get('/module/user/edit.php', [UserController::class, 'edit']);
        Route::post('/module/user/edit.php', [UserController::class, 'update']);
        Route::match(['get', 'post', 'delete'], '/module/user/hapus.php', [UserController::class, 'destroy']);
    });
});
