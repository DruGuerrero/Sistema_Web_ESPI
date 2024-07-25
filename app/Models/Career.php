<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'cant_estudiantes',
        'id_moodle',
    ];

    // Relación uno a muchos con Year
    public function years()
    {
        return $this->hasMany(Year::class, 'id_career');
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments', 'id_career', 'id_student')->withTimestamps();
    }
}