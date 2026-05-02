<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    // Tambahkan baris ini
    protected $table = 'peminjamans'; 

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function inventaris() {
        return $this->belongsTo(Inventaris::class);
    }
}