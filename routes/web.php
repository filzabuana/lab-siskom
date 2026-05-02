<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\SuratBebasLabController;
use Illuminate\Support\Facades\Route;

// Halaman yang bisa dilihat semua orang (Mahasiswa, Dosen, Umum)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

// --- PERUBAHAN DI SINI ---
// Route Dashboard utama sekarang melewati SuratBebasLabController
Route::get('/dashboard', [SuratBebasLabController::class, 'dashboardAdmin'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
// -------------------------

Route::get('/sop', [SopController::class, 'index'])->name('sop.index');
Route::get('/sop/{slug}', [SopController::class, 'show'])->name('sop.show');

// Halaman yang HANYA bisa diakses jika sudah LOGIN
Route::middleware(['auth'])->group(function () {
    Route::get('/sop/tambah', [SopController::class, 'create'])->name('sop.create');
    Route::post('/sop/simpan', [SopController::class, 'store'])->name('sop.store');
    Route::delete('/sop/{id}', [SopController::class, 'destroy'])->name('sop.destroy');
    Route::get('/sop/{id}/edit', [SopController::class, 'edit'])->name('sop.edit');
    Route::put('/sop/{id}', [SopController::class, 'update'])->name('sop.update');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk Mahasiswa (Bebas Lab)
Route::get('/bebas-lab', [SuratBebasLabController::class, 'create'])->name('bebas-lab.form');
Route::post('/bebas-lab', [SuratBebasLabController::class, 'store'])->name('bebas-lab.store');
Route::get('/bebas-lab/verify/{id}', [SuratBebasLabController::class, 'verifyEmail'])->name('bebas-lab.verify');

// Route khusus Admin/PLP
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Karena /dashboard sudah dihandle di atas, ini tetap ada sebagai alias atau akses langsung
    Route::get('/dashboard', [SuratBebasLabController::class, 'dashboardAdmin'])->name('admin.dashboard');
    Route::get('/bebas-lab', [SuratBebasLabController::class, 'indexAdmin'])->name('admin.bebas-lab.index');
    Route::post('/bebas-lab/{id}/update', [SuratBebasLabController::class, 'updateStatus'])->name('admin.bebas-lab.update');
});

// Sitemap
Route::get('/sitemap.xml', [SiteMapController::class, 'index']);
Route::get('/peta-situs', [SiteMapController::class, 'visual']);

require __DIR__.'/auth.php';