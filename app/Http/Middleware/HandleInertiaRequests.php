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
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'nim_nip'   => $user->nim_nip, 
                    'avatar'    => $user->avatar,
                    'is_admin'  => (bool) $user->is_admin,
                    'google_id' => $user->google_id, 
                    
                    // 1. Array Roles (Dipertahankan untuk text identitas / pajangan di UI)
                    'roles'     => $user->roles->pluck('name'), 

                    // 2. Array Permissions Baru (Mengirim wildcard '*' jika is_admin, jika tidak kirim seluruh permission miliknya)
                    'permissions' => $user->is_admin 
                        ? ['*'] 
                        : $user->getAllPermissions()->pluck('name'),
                ] : null,
                'impersonator' => $request->session()->has('impersonate'),
            ],

            // OPTIMASI LAZY PROPS: Menghitung jumlah item di keranjang mahasiswa
            'cartCount' => fn () => $user && $user->hasRole('mahasiswa')
                ? Keranjang::where('user_id', $user->id)->count()
                : 0,

            // OPTIMASI LAZY PROPS + PBAC: Lonceng notifikasi menyala jika user punya permission review-peminjaman (atau is_admin)
            'pendingCount' => fn () => $user && ($user->is_admin || $user->can('review-peminjaman'))
                ? Peminjaman::where('status', 'Pending')->count()
                : 0,

            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
        ]);
    }
}