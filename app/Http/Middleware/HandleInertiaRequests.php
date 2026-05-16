<?php

namespace App\Http\Middleware;

use App\Models\Keranjang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Nama layout utama (app.blade.php).
     */
    protected $rootView = 'app';

    /**
     * Menentukan versi asset (penting agar data tidak 'kosong' karena miss-match).
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Data yang dibagikan secara global ke Vue.
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user ? [
                    'id'       => $user->id,
                    'name'     => $user->name,
                    'email'    => $user->email,
                    'nim'      => $user->nim,
                    'avatar'   => $user->avatar, // <--- INI KUNCI UTAMANYA, PAK!
                    'is_admin' => $user->is_admin,
                    'roles'    => $user->getRoleNames(), 
                ] : null,
                'impersonator' => $request->session()->has('impersonate'),
            ],

            /**
             * Menghitung jumlah jenis barang di tabel keranjangs.
             */
            'cartCount' => $user && $user->hasRole('mahasiswa')
                ? Keranjang::where('user_id', $user->id)->count()
                : 0,

            /**
             * Menghitung jumlah pengajuan peminjaman yang berstatus 'Pending'.
             */
            'pendingCount' => $user && ($user->hasRole('plp') || $user->is_admin)
                ? Peminjaman::where('status', 'Pending')->count()
                : 0,

            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
        ]);
    }
}