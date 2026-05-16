<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // PENTING: Jangan sampai baris ini tertinggal

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Mengambil data user dari Google
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();

            // 1. Cek apakah user sudah terdaftar di database (untuk Admin/PLP)
            $user = User::where('email', $email)->first();

            if ($user) {
                // Update data jika perlu
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                Auth::login($user);
            } else {
                // 2. Jika belum terdaftar, cek apakah domainnya @student.untan.ac.id
                if (str_ends_with($email, '@student.untan.ac.id') || $email == 'filzabp2@gmail.com') {
                    $newUser = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $email,
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => Hash::make(Str::random(16)), 
                    ]);

                    // Berikan role default mahasiswa (Pastikan Spatie Role sudah terpasang)
                    $newUser->assignRole('mahasiswa');

                    Auth::login($newUser);
                } else {
                    return redirect('/login')->with('error', 'Akses ditolak. Gunakan email institusi UNTAN.');
                }
            }

            // Jika sukses, arahkan ke dashboard
            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            // Jika putih polos tadi, kemungkinan errornya ada di sini.
            // Coba aktifkan dd() di bawah ini untuk melihat error aslinya:
            dd($e->getMessage()); 
        }
    }
}