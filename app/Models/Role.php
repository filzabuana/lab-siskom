<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    // Ditambahkan guard_name jika skema database Anda membutuhkannya (bawaan Spatie)
    protected $fillable = ['name', 'guard_name'];

    /**
     * Relasi ke Master Permissions (Many-to-Many)
     * Menghubungkan tabel roles -> role_has_permissions -> permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class, 
            'role_has_permissions', 
            'role_id', 
            'permission_id'
        );
    }

    /**
     * Relasi ke Users (Many-to-Many)
     * Menghubungkan tabel roles -> model_has_roles -> users
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class, 
            'model_has_roles', 
            'role_id', 
            'model_id'
        );
    }
}