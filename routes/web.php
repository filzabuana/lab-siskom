<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController, SopController, SiteMapController, 
    SuratBebasLabController, InventarisController, 
    PeminjamanController, PostController, UserController,
    DashboardController
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

    /*
    |--------------------------------------------------------------------------
    | 4. AREA MANAGERIAL & STAF LAB (Murni Berbasis Permission / PBAC)
    |--------------------------------------------------------------------------
    | Menggunakan standar kebab-case (-) agar sinkron dengan seeder.
    | */
    Route::middleware(['can:access-admin-area'])->prefix('admin')->name('admin.')->group(function () {
        
        // Manajemen Peminjaman (Disinkronkan dengan permission 'review-peminjaman')
        Route::middleware(['can:review-peminjaman'])->prefix('peminjaman')->name('peminjaman.')->group(function () {
            Route::get('/', [PeminjamanController::class, 'indexAdmin'])->name('index');
            Route::patch('/{id}/status', [PeminjamanController::class, 'updateStatus'])->name('update-status');
            Route::delete('/detail/{detail_id}', [PeminjamanController::class, 'destroyDetail'])->name('destroy-detail');
        });

        // Manajemen Inventaris Struktural (Tulis, Ubah, Hapus)
        Route::middleware(['can:manage-inventaris'])->group(function () {
            Route::get('inventaris/create', [InventarisController::class, 'create'])->name('inventaris.create');
            Route::post('inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
            Route::get('inventaris/{inventari}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
            Route::put('inventaris/{inventari}', [InventarisController::class, 'update'])->name('inventaris.update');
            Route::delete('inventaris/{inventari}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');
        });

        // Manajemen Inventaris Standar (Hanya Baca / View)
        Route::middleware(['can:view-inventaris'])->group(function () {
            Route::get('inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
            Route::get('inventaris/{inventari}', [InventarisController::class, 'show'])->name('inventaris.show');
        });

        // Manajemen Bebas Lab (Admin Side)
        Route::middleware(['can:manage-bebas-lab'])->group(function () {
            Route::get('/bebas-lab', [SuratBebasLabController::class, 'indexAdmin'])->name('bebas-lab.index');
            Route::patch('/bebas-lab/{id}', [SuratBebasLabController::class, 'updateStatus'])->name('bebas-lab.update');
        });

        // Manajemen User & Role (Eksklusif Superadmin / Kepala Unit dengan permission 'manage-users')
        Route::middleware(['can:manage-users'])->group(function () {
            Route::resource('users', UserController::class)->only(['index', 'show', 'create', 'store', 'destroy']);
            Route::post('users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
            Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');

            // ROUTE KHUSUS MANAJEMEN ROLE
            Route::get('/roles', [RoleManagementController::class, 'index'])->name('roles.index');
            Route::post('/roles', [RoleManagementController::class, 'store'])->name('roles.store');
            Route::put('/roles/{role}', [RoleManagementController::class, 'update'])->name('roles.update');
        });

        // Konten Blog (Admin Side - Full Inertia)
        Route::middleware(['can:manage-posts'])->group(function () {
            Route::get('posts', [PostController::class, 'indexAdmin'])->name('posts.index');
            Route::resource('posts', PostController::class)->except(['index']);
        });
        
        // SOP Admin
        Route::middleware(['can:manage-sop'])->group(function () {
            Route::resource('sop', SopController::class);
        });

    });
});

require __DIR__.'/auth.php';