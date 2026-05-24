<?php

namespace App\Imports;

use App\Models\User;
use App\Models\CourseClass;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    protected $courseClassId;

    public function __construct($courseClassId)
    {
        $this->courseClassId = $courseClassId;
    }

    public function model(array $row)
    {
        // 1. Cari atau Buat User (Upsert)
        // Kita gunakan email sebagai kunci unik utama untuk Google Auth
        $user = User::updateOrCreate(
            ['email' => $row['email']], 
            [
                'name'    => $row['name'],
                'nim_nip' => $row['nim_nip'],
                // Password acak karena mereka akan login via Google
                'password' => Hash::make(Str::random(16)), 
            ]
        );

        // 2. Hubungkan Mahasiswa ke Kelas (Pivot Table)
        // Pastikan Anda punya relasi 'students' di model CourseClass
        $courseClass = CourseClass::find($this->courseClassId);
        
        // syncWithoutDetaching agar tidak menghapus mahasiswa yang sudah ada di kelas tersebut
        $courseClass->students()->syncWithoutDetaching([$user->id]);

        return $user;
    }
}