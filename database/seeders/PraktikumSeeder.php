<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\CourseClass;
use App\Models\User;
use Illuminate\Database\Seeder;

class PraktikumSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['name' => 'Pemrograman Berorientasi Objek', 'code' => 'SK4102'],
            ['name' => 'Arsitektur Komputer', 'code' => 'SK4105'],
            ['name' => 'Jaringan Komputer', 'code' => 'SK4201'],
        ];

        $teacher = User::role('asisten_praktikum')->first() 
                   ?? User::role('dosen')->first() 
                   ?? User::first();

        // Data Asisten/Dosen tambahan untuk tes tampilan Card
        $assistant2 = User::role('asisten_praktikum')->skip(1)->first();

        foreach ($subjects as $index => $sub) {
            $subject = Subject::updateOrCreate(
                ['code' => $sub['code']],
                ['name' => $sub['name']]
            );

            $classes = ['Kelas A', 'Kelas B'];
            
            // Variasi Tahun: Indeks 0 & 1 ke 2024/2025, sisanya 2025/2026
            $year = ($index < 1) ? '2024/2025' : '2025/2026';

            foreach ($classes as $className) {
                $courseClass = CourseClass::updateOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'name' => $className,
                        'academic_year' => $year
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'assistant_id' => $assistant2->id ?? null, // Tes munculkan asisten di card
                    ]
                );

                $students = User::role('mahasiswa')->limit(5)->get();
                if ($students->count() > 0) {
                    $courseClass->students()->sync($students->pluck('id'));
                }
            }
        }
    }
}