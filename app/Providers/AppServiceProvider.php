<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate; // <-- Ditambahkan untuk mengaktifkan fitur Gate

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paginator bawaan Anda
        Paginator::useTailwind();

        // TAMBAHKAN INI: Bypass semua pengecekan role/permission Spatie jika user memiliki is_admin = 1
        Gate::before(function ($user, $ability) {
            if ($user->is_admin == 1) {
                return true; // Lolos otomatis dari gerbang Route::middleware(['role:...'])
            }
        });
    }
}