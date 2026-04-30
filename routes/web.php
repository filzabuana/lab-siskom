<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SopController;

// Halaman yang bisa dilihat semua orang (Mahasiswa, Dosen, Umum)
Route::get('/', function () {
    return view('welcome');
});
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/sop', [SopController::class, 'index'])->name('sop.index');

// Halaman yang HANYA bisa diakses jika sudah LOGIN (Admin/PLP)
Route::middleware(['auth'])->group(function () {
    Route::get('/sop/tambah', [SopController::class, 'create'])->name('sop.create');
    Route::post('/sop/simpan', [SopController::class, 'store'])->name('sop.store');
    Route::delete('/sop/{id}', [SopController::class, 'destroy'])->name('sop.destroy');
});
Route::get('/sop/{slug}', [SopController::class, 'show'])->name('sop.show');


require __DIR__.'/auth.php';


// ... rute lainnya ...

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});