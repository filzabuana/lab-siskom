<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    // Tambahkan description ke fillable
    protected $fillable = ['name', 'guard_name', 'description'];
}