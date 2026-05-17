<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            
            // PENTING: Paksa email menjadi lowercase untuk mencocokkan data database dan standardisasi
            $email = strtolower($googleUser->getEmail());

            // 1. Cek apakah akun dengan email ini SUDAH TERDAFTAR di database
            // (Meliputi Akun Admin, Akun Buatan Manual untuk Dosen/Staf, atau Mahasiswa Lama)
            $user = User::where('email', $email)->first();

            if ($user) {
                // Sambungkan akun ke Google ID dan perbarui data profil sosialnya jika sukses SSO
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    // Menggunakan '??' agar NIP dosen/staf yang sudah Anda input manual di Admin tidak tertimpa
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);

                Auth::login($user);
            } else {
                // 2. JIKA BELUM TERDAFTAR: Terapkan aturan registrasi otomatis mandiri
                // Hanya mengizinkan domain mahasiswa (@student.untan.ac.id) atau email testing Anda
                if (str_ends_with($email, '@student.untan.ac.id') || $email == 'filzabp2@gmail.com') {
                    
                    // Ekstrak NIM secara otomatis dari email mahasiswa
                    $detectedNim = $this->extractNimFromEmail($email);

                    $newUser = User::create([
                        'name'              => $googleUser->getName(),
                        'email'             => $email,
                        'google_id'         => $googleUser->getId(),
                        'avatar'            => $googleUser->getAvatar(),
                        'nim_nip'           => $detectedNim, // Menyimpan string NIM hasil ekstraksi
                        'password'          => Hash::make(Str::random(16)), 
                        'email_verified_at' => now(),
                    ]);

                    // Berikan hak akses default sebagai mahasiswa
                    $newUser->assignRole('mahasiswa');

                    Auth::login($newUser);
                } else {
                    // Jika ada email dosen/staf (@untan.ac.id) yang belum Anda daftarkan di menu Admin, akses otomatis dikunci
                    return redirect('/login')->with('error', 'Akses ditolak. Akun staf/dosen Anda belum terdaftar di sistem laboratorium. Silakan hubungi Admin.');
                }
            }

            // Jika sukses melompati semua gerbang keamanan, arahkan ke dashboard utama
            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            dd($e->getMessage()); 
        }
    }

    /**
     * Fungsi Pembantu: Ekstraksi NIM otomatis jika berformat email mahasiswa FMIPA UNTAN
     */
    private function extractNimFromEmail(string $email): ?string
    {
        $emailPrefix = Str::before($email, '@');

        // Regex mendeteksi prefix email berawalan H (H101, h109, dst) khas FMIPA UNTAN
        if (preg_match('/^[Hh]\d+/', $emailPrefix)) {
            return strtoupper($emailPrefix);
        }

        return null;
    }
}