<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\SuratBebasLabController;
use Illuminate\Support\Facades\Route;

// Halaman Publik
Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

// Route Dashboard utama melewati SuratBebasLabController
Route::get('/dashboard', [SuratBebasLabController::class, 'dashboardAdmin'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route SOP Publik (Index harus di atas, Detail/Slug harus di paling bawah)
Route::get('/sop', [SopController::class, 'index'])->name('sop.index');

// --- AREA ADMIN SOP ---
// Harus diletakkan SEBELUM route {slug} agar tidak bentrok
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/sop/tambah', [SopController::class, 'create'])->name('sop.create');
    Route::post('/sop/simpan', [SopController::class, 'store'])->name('sop.store');
    Route::get('/sop/{id}/edit', [SopController::class, 'edit'])->name('sop.edit');
    Route::put('/sop/{id}', [SopController::class, 'update'])->name('sop.update');
    Route::delete('/sop/{id}', [SopController::class, 'destroy'])->name('sop.destroy');
});

// Detail SOP (Wajib di bawah route /tambah)
Route::get('/sop/{slug}', [SopController::class, 'show'])->name('sop.show');

// Profile & Auth
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk Mahasiswa (Bebas Lab)
Route::get('/bebas-lab', [SuratBebasLabController::class, 'create'])->name('bebas-lab.form');
Route::post('/bebas-lab', [SuratBebasLabController::class, 'store'])->name('bebas-lab.store');
Route::get('/bebas-lab/verify/{id}', [SuratBebasLabController::class, 'verifyEmail'])->name('bebas-lab.verify');

// Route khusus Admin/PLP (Prefix Admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [SuratBebasLabController::class, 'dashboardAdmin'])->name('admin.dashboard');
    Route::get('/bebas-lab', [SuratBebasLabController::class, 'indexAdmin'])->name('admin.bebas-lab.index');
    Route::post('/bebas-lab/{id}/update', [SuratBebasLabController::class, 'updateStatus'])->name('admin.bebas-lab.update');
});

// Sitemap
Route::get('/sitemap.xml', [SiteMapController::class, 'index']);
Route::get('/peta-situs', [SiteMapController::class, 'visual']);

require __DIR__.'/auth.php';