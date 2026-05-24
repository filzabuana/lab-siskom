<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    // Tambahkan description ke fillable
    protected $fillable = ['name', 'guard_name', 'description'];
}