<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'id_course',
        'type',
        'file',
    ];

    // Relación muchos a uno con Student (un archivo pertenece a un estudiante)
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Relación muchos a uno con Course (un archivo pertenece a un curso)
    public function course()
    {
        return $this->belongsTo(Course::class, 'id_course');
    }
}
