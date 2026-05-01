<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\SuratBebasLabController;

// Halaman yang bisa dilihat semua orang (Mahasiswa, Dosen, Umum)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/peta-situs', [SiteMapController::class, 'index'])->name('peta.situs');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/sop', [SopController::class, 'index'])->name('sop.index');

// Halaman yang HANYA bisa diakses jika sudah LOGIN (Admin/PLP)
Route::middleware(['auth'])->group(function () {
    Route::get('/sop/tambah', [SopController::class, 'create'])->name('sop.create');
    Route::post('/sop/simpan', [SopController::class, 'store'])->name('sop.store');
    Route::delete('/sop/{id}', [SopController::class, 'destroy'])->name('sop.destroy');
    Route::get('/sop/{id}/edit', [SopController::class, 'edit'])->name('sop.edit');
    Route::put('/sop/{id}', [SopController::class, 'update'])->name('sop.update');
});
Route::get('/sop/{slug}', [SopController::class, 'show'])->name('sop.show');


require __DIR__.'/auth.php';


// ... rute lainnya ...

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk Mahasiswa
Route::get('/bebas-lab', [SuratBebasLabController::class, 'create'])->name('bebas-lab.form');
Route::post('/bebas-lab', [SuratBebasLabController::class, 'store'])->name('bebas-lab.store');
Route::get('/bebas-lab/verify/{id}', [SuratBebasLabController::class, 'verifyEmail'])->name('bebas-lab.verify');

// Gunakan dua middleware sekaligus
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/bebas-lab', [SuratBebasLabController::class, 'indexAdmin'])->name('admin.bebas-lab.index');
    Route::post('/bebas-lab/{id}/update', [SuratBebasLabController::class, 'updateStatus'])->name('admin.bebas-lab.update');
});