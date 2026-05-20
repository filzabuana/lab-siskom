<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles; // WAJIB untuk Spatie Laravel-Permission

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles; // HasRoles aktif di sini
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
        'google_id', // Ditambahkan untuk Socialite
        'avatar',    // Ditambahkan untuk Socialite
        'nim_nip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'google_id' => 'string',
        ];
    }

    /**
     * Relasi One-to-Many: Satu user bisa punya banyak riwayat peminjaman
     * Digunakan untuk menghitung tanggungan alat di Dashboard Admin
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    /**
     * Relasi ke Surat Bebas Lab
     */
    public function suratBebasLab(): HasMany
    {
        return $this->hasMany(SuratBebasLab::class, 'user_id');
    }

    // FUNGSI roles() MANUAL DI SINI SUDAH DIHAPUS AGAR TIDAK BENTROK DENGAN TRAIT HASROLES SPATIE
}