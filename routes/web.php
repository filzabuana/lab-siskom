<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController, SopController, SiteMapController, 
    SuratBebasLabController, InventarisController, 
    PeminjamanController, PostController, UserController,
    DashboardController, AttendanceController,
};
use App\Http\Controllers\Admin\RoleManagementController; // Impor Controller Baru Terdaftar
use App\Http\Controllers\Auth\GoogleController;

/*
|--------------------------------------------------------------------------
| 1. AUTHENTICATION (Google & Global)
|--------------------------------------------------------------------------
*/
Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('google.login');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::middleware('auth')->group(function () {
    // Stop Impersonate ditaruh di sini agar bisa diakses saat sedang "menyamar"
    Route::get('/admin/stop-impersonate', [UserController::class, 'stopImpersonate'])->name('admin.stop-impersonate');
});

/*
|--------------------------------------------------------------------------
| 2. PUBLIC ROUTES (Blade Only - Visitor & Guest Accessible)
|--------------------------------------------------------------------------
*/
Route::get('/', [PostController::class, 'welcome'])->name('welcome');
Route::get('/about', fn() => view('about'))->name('about');

// Blog (Publik - Read Only Bersetting Blade)
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('blog.show');

// Katalog Publik Khusus Berbasis BLADE
Route::get('/katalog', [InventarisController::class, 'katalogPublicIndex'])->name('katalog.index');
Route::get('/katalog/{id}', [InventarisController::class, 'katalogPublicShow'])->name('katalog.show');

// Public Bebas Lab Form
Route::controller(SuratBebasLabController::class)->group(function () {
    Route::get('/bebas-lab', 'create')->name('bebas-lab.form');
    Route::post('/bebas-lab', 'store')->name('bebas-lab.store');
    Route::get('/bebas-lab/verify/{id}', 'verifyEmail')->name('bebas-lab.verify');
});

// Simulator & Apps
Route::name('simulator.')->group(function () {
    Route::view('/simulasigerbanglogika', 'simulator')->name('logic');
    Route::view('/trainer-digital', 'trainer')->name('trainer');
});
Route::view('/apps', 'apps.index')->name('apps.index');

Route::controller(SopController::class)->group(function () {
    Route::get('/sop', 'index')->name('sop.index');
    Route::get('/sop/{slug}', 'show')->name('sop.show');
});

/*
|--------------------------------------------------------------------------
| 3. PROTECTED ROUTES (Inertia - Login Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fitur Peminjaman Mahasiswa (Pure Inertia System)
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/katalog', [PeminjamanController::class, 'indexKatalog'])->name('katalog');
        Route::get('/keranjang', [PeminjamanController::class, 'viewCart'])->name('cart.view');
        Route::post('/keranjang/add', [PeminjamanController::class, 'addToCart'])->name('cart.add');
        Route::patch('/keranjang/{id}', [PeminjamanController::class, 'updateCart'])->name('cart.update');
        Route::delete('/keranjang/{id}', [PeminjamanController::class, 'destroyCart'])->name('cart.destroy');
        Route::post('/checkout', [PeminjamanController::class, 'checkout'])->name('checkout');
        Route::get('/riwayat', [PeminjamanController::class, 'history'])->name('history');
        Route::get('/katalog/{id}', [PeminjamanController::class, 'showItem'])->name('show');
    });

    // Scan Presensi Mahasiswa
    Route::get('/scan-presensi', [AttendanceController::class, 'showScanner'])->name('attendance.scan');
    Route::post('/scan-presensi', [AttendanceController::class, 'scan'])->name('attendance.scan.process');

    /*
    |--------------------------------------------------------------------------
    | 4. AREA MANAGERIAL & STAF LAB (Murni Berbasis Permission / PBAC)
    |--------------------------------------------------------------------------
    | */
    Route::middleware(['auth', 'can:access-admin-area'])->prefix('admin')->name('admin.')->group(function () {
        
        // Manajemen Peminjaman
        Route::middleware(['can:review-peminjaman'])->prefix('peminjaman')->name('peminjaman.')->group(function () {
            Route::get('/', [PeminjamanController::class, 'indexAdmin'])->name('index');
            Route::patch('/{id}/status', [PeminjamanController::class, 'updateStatus'])->name('update-status');
            Route::delete('/detail/{detail_id}', [PeminjamanController::class, 'destroyDetail'])->name('destroy-detail');
        });

        // Manajemen Inventaris
        Route::middleware(['can:manage-inventaris'])->group(function () {
            Route::resource('inventaris', InventarisController::class)->except(['index', 'show']);
        });
        Route::middleware(['can:view-inventaris'])->group(function () {
            Route::get('inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
            Route::get('inventaris/{inventari}', [InventarisController::class, 'show'])->name('inventaris.show');
        });

        // Manajemen Bebas Lab (Admin)
        Route::middleware(['can:manage-bebas-lab'])->group(function () {
            Route::get('/bebas-lab', [SuratBebasLabController::class, 'indexAdmin'])->name('bebas-lab.index');
            Route::patch('/bebas-lab/{id}', [SuratBebasLabController::class, 'updateStatus'])->name('bebas-lab.update');
        });

        // Manajemen User & Role (PBAC: manage-users)
        Route::middleware(['can:manage-users'])->group(function () {
            // User Management
            Route::resource('users', UserController::class);
            Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
            
            // Impersonation
            Route::post('users-impersonate/{user}', [UserController::class, 'impersonate'])->name('users.impersonate');
            // Catatan: stop-impersonate sudah ada di middleware auth global di atas sesuai file Anda

            // Role Management (Menggunakan controller di folder Admin)
            Route::get('roles', [RoleManagementController::class, 'index'])->name('roles.index');
            Route::post('roles', [RoleManagementController::class, 'store'])->name('roles.store');
            Route::put('roles/{role}', [RoleManagementController::class, 'update'])->name('roles.update');
        });

        // Konten Blog & SOP
        Route::middleware(['can:manage-posts'])->group(function() {
            Route::get('posts', [PostController::class, 'indexAdmin'])->name('posts.index');
            Route::resource('posts', PostController::class)->except(['index']);
            });
        Route::middleware(['can:manage-sop'])->resource('sop', SopController::class);

        // Presensi Praktikum
        Route::middleware(['can:create-presensi'])->prefix('attendance')->name('attendance.')->group(function () {
            // --- Manajemen Kelas (CRUD) ---
            Route::get('/', [AttendanceController::class, 'index'])->name('index');
            Route::post('/store-class', [AttendanceController::class, 'storeClass'])->name('store-class'); // Tambahkan ini
            Route::put('/update-class/{courseClass}', [AttendanceController::class, 'updateClass'])->name('update-class'); // Tambahkan ini
            Route::delete('/destroy-class/{courseClass}', [AttendanceController::class, 'destroyClass'])->name('destroy-class'); // Tambahkan ini

            // --- Manajemen Sesi Presensi ---
            Route::get('/session/{courseClass}', [AttendanceController::class, 'showSession'])->name('session');
            Route::post('/start/{courseClass}', [AttendanceController::class, 'startSession'])->name('start');
            Route::patch('session/{session}/toggle', [AttendanceController::class, 'toggleSession'])->name('toggle');
            
            // --- Laporan & Import ---
            Route::get('/report/{courseClass}', [AttendanceController::class, 'report'])->name('report');
            Route::get('/export-template', [AttendanceController::class, 'exportTemplate'])->name('export-template');
            Route::post('/{classId}/import', [AttendanceController::class, 'import'])->name('import');
        });
    });
});

require __DIR__.'/auth.php';