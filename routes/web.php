<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController, SopController, SiteMapController, 
    SuratBebasLabController, InventarisController, 
    PeminjamanController, PostController, UserController
};
use App\Http\Controllers\Auth\GoogleController;

/*
|--------------------------------------------------------------------------
| 1. AUTHENTICATION (Google & Default)
|--------------------------------------------------------------------------
*/
Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('google.login');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

// Route untuk keluar dari mode impersonasi (Bisa diakses selama session ada)
Route::get('/admin/stop-impersonate', [UserController::class, 'stopImpersonate'])
    ->name('admin.stop-impersonate')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| 2. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [PostController::class, 'welcome'])->name('welcome');
Route::get('/about', function () { return view('about'); })->name('about');

// Blog & SOP
Route::controller(PostController::class)->group(function () {
    Route::get('/blog', 'index')->name('blog.index');
    Route::get('/blog/{slug}', 'show')->name('blog.show');
});

Route::controller(SopController::class)->group(function () {
    Route::get('/sop', 'index')->name('sop.index');
    Route::get('/sop/{slug}', 'show')->name('sop.show');
});

// Katalog Inventaris Publik
Route::controller(InventarisController::class)->group(function () {
    Route::get('/katalog', 'katalog')->name('katalog.index');
    Route::get('/katalog/{id}', 'show')->name('katalog.show');
});

// Bebas Lab Form (Public/Student)
Route::controller(SuratBebasLabController::class)->group(function () {
    Route::get('/bebas-lab', 'create')->name('bebas-lab.form');
    Route::post('/bebas-lab', 'store')->name('bebas-lab.store');
    Route::get('/bebas-lab/verify/{id}', 'verifyEmail')->name('bebas-lab.verify');
});

// SEO & Tools
Route::get('/sitemap.xml', [SiteMapController::class, 'index']);
Route::get('/peta-situs', [SiteMapController::class, 'visual']);

// Simulator & Apps
Route::name('simulator.')->group(function () {
    Route::view('/simulasigerbanglogika', 'simulator')->name('logic');
    Route::view('/trainer-digital', 'trainer')->name('trainer');
});
Route::view('/apps', 'apps.index')->name('apps.index');

/*
|--------------------------------------------------------------------------
| 3. PROTECTED ROUTES (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [SuratBebasLabController::class, 'dashboardAdmin'])->name('dashboard');

    // User Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Peminjaman (Student Side)
    Route::controller(PeminjamanController::class)->group(function () {
        Route::get('/peminjaman', 'index')->name('peminjaman.index');
        Route::post('/peminjaman', 'store')->name('peminjaman.store');
    });

    /*
    |--------------------------------------------------------------------------
    | 4. ADMIN AREA (Superadmin / PLP)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', [SuratBebasLabController::class, 'dashboardAdmin'])->name('dashboard');

        // Admin: Blog Management
        Route::controller(PostController::class)->prefix('posts')->name('posts.')->group(function () {
            Route::get('/guide', 'guide')->name('guide');
            Route::get('/', 'indexAdmin')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        // Admin: Inventaris Management
        Route::controller(InventarisController::class)->prefix('inventaris')->name('inventaris.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah', 'create')->name('create');
            Route::post('/simpan', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        // Admin: Bebas Lab & Peminjaman
        Route::post('/bebas-lab/{id}/update', [SuratBebasLabController::class, 'updateStatus'])->name('bebas-lab.update');
        Route::get('/bebas-lab', [SuratBebasLabController::class, 'indexAdmin'])->name('bebas-lab.index');
        
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::patch('/peminjaman/{id}/update-status', [PeminjamanController::class, 'updateStatus'])->name('peminjaman.update');

        // Admin: SOP Management
        Route::controller(SopController::class)->prefix('sop')->name('sop.')->group(function () {
            Route::get('/tambah', 'create')->name('create');
            Route::post('/simpan', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        // Admin: User Management & Impersonation
        Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::patch('/{id}/role', 'updateRole')->name('update-role');
            Route::post('/{id}/impersonate', 'impersonate')->name('impersonate');
        });

    }); // End Admin
}); // End Auth

require __DIR__.'/auth.php';