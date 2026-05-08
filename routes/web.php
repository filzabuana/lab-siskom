<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\SuratBebasLabController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController; // Pastikan ini diimport
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/
Route::get('/', [PostController::class, 'welcome'])->name('welcome');
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('blog.show');

Route::get('/katalog', [InventarisController::class, 'katalog'])->name('katalog.index');
Route::get('/katalog/{id}', [InventarisController::class, 'show'])->name('katalog.show');

Route::get('/about', function () { return view('about'); })->name('about');

Route::get('/sop', [SopController::class, 'index'])->name('sop.index');
Route::get('/sop/{slug}', [SopController::class, 'show'])->name('sop.show');

Route::get('/bebas-lab', [SuratBebasLabController::class, 'create'])->name('bebas-lab.form');
Route::post('/bebas-lab', [SuratBebasLabController::class, 'store'])->name('bebas-lab.store');
Route::get('/bebas-lab/verify/{id}', [SuratBebasLabController::class, 'verifyEmail'])->name('bebas-lab.verify');

Route::get('/sitemap.xml', [SiteMapController::class, 'index']);
Route::get('/peta-situs', [SiteMapController::class, 'visual']);

Route::get('/simulasigerbanglogika', function () {
    return view('simulator');
});

Route::get('/trainer-digital', function () {
    return view('trainer'); 
});


/*
|--------------------------------------------------------------------------
| 2. HALAMAN TERPROTEKSI (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [SuratBebasLabController::class, 'dashboardAdmin'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    // Perbaikan: gunakan POST untuk store
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');

    /*
    |--------------------------------------------------------------------------
    | 3. AREA KHUSUS ADMIN / PLP
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        
        Route::get('/dashboard', [SuratBebasLabController::class, 'dashboardAdmin'])->name('admin.dashboard');

        // Manajemen Blog/Post (PENTING: Guide harus di atas ID)
        Route::get('/posts/guide', [PostController::class, 'guide'])->name('admin.posts.guide');
        Route::get('/posts', [PostController::class, 'indexAdmin'])->name('admin.posts.index');
        Route::get('/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
        Route::post('/posts', [PostController::class, 'store'])->name('admin.posts.store');
        Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
        Route::put('/posts/{id}', [PostController::class, 'update'])->name('admin.posts.update');
        Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('admin.posts.destroy');

        // Manajemen Bebas Lab
        Route::get('/bebas-lab', [SuratBebasLabController::class, 'indexAdmin'])->name('admin.bebas-lab.index');
        Route::post('/bebas-lab/{id}/update', [SuratBebasLabController::class, 'updateStatus'])->name('admin.bebas-lab.update');

        // Manajemen Inventaris
        Route::get('/inventaris', [InventarisController::class, 'index'])->name('admin.inventaris.index');
        Route::get('/inventaris/tambah', [InventarisController::class, 'create'])->name('admin.inventaris.create');
        Route::post('/inventaris/simpan', [InventarisController::class, 'store'])->name('admin.inventaris.store');
        Route::get('/inventaris/{id}', [InventarisController::class, 'show'])->name('admin.inventaris.show');
        Route::get('/inventaris/{id}/edit', [InventarisController::class, 'edit'])->name('admin.inventaris.edit');
        Route::put('/inventaris/{id}', [InventarisController::class, 'update'])->name('admin.inventaris.update');
        Route::delete('/inventaris/{id}', [InventarisController::class, 'destroy'])->name('admin.inventaris.destroy');

        // Manajemen Peminjaman
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('admin.peminjaman.index');
        Route::patch('/peminjaman/{id}/update-status', [PeminjamanController::class, 'updateStatus'])->name('admin.peminjaman.update');

        // Manajemen SOP
        Route::get('/sop/tambah', [SopController::class, 'create'])->name('sop.create');
        Route::post('/sop/simpan', [SopController::class, 'store'])->name('sop.store');
        Route::get('/sop/{id}/edit', [SopController::class, 'edit'])->name('sop.edit');
        Route::put('/sop/{id}', [SopController::class, 'update'])->name('sop.update');
        Route::delete('/sop/{id}', [SopController::class, 'destroy'])->name('sop.destroy');
       
        // Manajemen User
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.users.show');

    }); // End Middleware Admin
}); // End Middleware Auth

require __DIR__.'/auth.php';