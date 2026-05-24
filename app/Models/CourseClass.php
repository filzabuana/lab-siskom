<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseClass extends Model
{
    protected $fillable = [
        'subject_id', 
        'teacher_id', 
        'teacher2_id', // Tambah ini
        'assistant_id', // Tambah ini
        'assistant2_id', // Tambah ini
        'name', 
        'academic_year'
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function teacher2()
    {
        return $this->belongsTo(User::class, 'teacher2_id');
    }
    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }

    public function assistant2()
    {
        return $this->belongsTo(User::class, 'assistant2_id');
    }


    // Relasi ke Mahasiswa (Many to Many via Pivot)
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_class_user', 'course_class_id', 'user_id')
                    ->withTimestamps();
    }

    public function attendanceSessions(): HasMany
    {
        return $this->hasMany(AttendanceSession::class);
    }
}
