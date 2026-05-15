<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Nama layout utama (app.blade.php).
     */
    protected $rootView = 'app'; //

    /**
     * Menentukan versi asset (penting agar data tidak 'kosong' karena miss-match).
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Data yang dibagikan secara global ke Vue (Pinia).
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(), //
            ],
            // Pastikan ini tidak menyebabkan error null
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
        ]);
    }
}