<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['name', 'guard_name'];

    /**
     * Relasi balik ke Roles (Many-to-Many)
     * Menghubungkan tabel permissions -> role_has_permissions -> roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class, 
            'role_has_permissions', 
            'permission_id', 
            'role_id'
        );
    }
}