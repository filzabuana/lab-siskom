<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\SuratBebasLabController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\PeminjamanController;
use App\Models\Inventaris;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // Ambil data alat yang bisa dipinjam untuk ditampilkan di Welcome Page
    $inventaris = Inventaris::where('tipe_peminjaman', 'Bisa Dipinjam')->get();
    return view('welcome', compact('inventaris'));
});

// Katalog Publik
Route::get('/katalog', [InventarisController::class, 'katalog'])->name('katalog.index');
Route::get('/katalog/{id}', [InventarisController::class, 'show'])->name('katalog.show');

Route::get('/about', function () {
    return view('about');
})->name('about');

// SOP Publik
Route::get('/sop', [SopController::class, 'index'])->name('sop.index');
Route::get('/sop/{slug}', [SopController::class, 'show'])->name('sop.show');

// Bebas Lab (Input Mahasiswa)
Route::get('/bebas-lab', [SuratBebasLabController::class, 'create'])->name('bebas-lab.form');
Route::post('/bebas-lab', [SuratBebasLabController::class, 'store'])->name('bebas-lab.store');
Route::get('/bebas-lab/verify/{id}', [SuratBebasLabController::class, 'verifyEmail'])->name('bebas-lab.verify');

// Sitemap & SEO
Route::get('/sitemap.xml', [SiteMapController::class, 'index']);
Route::get('/peta-situs', [SiteMapController::class, 'visual']);


/*
|--------------------------------------------------------------------------
| 2. HALAMAN TERPROTEKSI (Wajib Login - Mahasiswa & Admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard (Dialihkan sesuai role oleh Controller)
    Route::get('/dashboard', [SuratBebasLabController::class, 'dashboardAdmin'])->name('dashboard');

    // Profile User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Peminjaman Alat (Role Mahasiswa/User)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');

    /*
    |--------------------------------------------------------------------------
    | 3. AREA KHUSUS ADMIN / PLP (Middleware: auth & admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [SuratBebasLabController::class, 'dashboardAdmin'])->name('admin.dashboard');

        // Manajemen Bebas Lab
        Route::get('/bebas-lab', [SuratBebasLabController::class, 'indexAdmin'])->name('admin.bebas-lab.index');
        Route::post('/bebas-lab/{id}/update', [SuratBebasLabController::class, 'updateStatus'])->name('admin.bebas-lab.update');

        // Manajemen Inventaris (CRUD)
        Route::get('/inventaris', [InventarisController::class, 'index'])->name('admin.inventaris.index');
        Route::get('/inventaris/tambah', [InventarisController::class, 'create'])->name('admin.inventaris.create');
        Route::post('/inventaris/simpan', [InventarisController::class, 'store'])->name('admin.inventaris.store');
        Route::get('/inventaris/{id}', [InventarisController::class, 'show'])->name('admin.inventaris.show');
        Route::get('/inventaris/{id}/edit', [InventarisController::class, 'edit'])->name('admin.inventaris.edit');
        Route::put('/inventaris/{id}', [InventarisController::class, 'update'])->name('admin.inventaris.update');
        Route::delete('/inventaris/{id}', [InventarisController::class, 'destroy'])->name('admin.inventaris.destroy');

        // Manajemen Peminjaman (Approve/Reject)
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('admin.peminjaman.index');
        Route::patch('/peminjaman/{id}/update-status', [PeminjamanController::class, 'updateStatus'])->name('admin.peminjaman.update');

        // Manajemen SOP
        Route::get('/sop/tambah', [SopController::class, 'create'])->name('sop.create');
        Route::post('/sop/simpan', [SopController::class, 'store'])->name('sop.store');
        Route::get('/sop/{id}/edit', [SopController::class, 'edit'])->name('sop.edit');
        Route::put('/sop/{id}', [SopController::class, 'update'])->name('sop.update');
        Route::delete('/sop/{id}', [SopController::class, 'destroy'])->name('sop.destroy');
        // Manajemen User
        Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('admin.users.show');
    });
});

require __DIR__.'/auth.php';