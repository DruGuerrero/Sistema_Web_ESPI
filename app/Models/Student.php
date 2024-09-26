<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'num_carnet',
        'email',
        'ciudad_domicilio',
        'num_celular',
        'matricula',
        'nombre_tutor',
        'celular_tutor',
        'ciudad_tutor',
        'parentesco',
        'disabled',
        'moodle_user',
        'moodle_pass',
    ];

    protected $casts = [
        'disabled' => 'boolean',
    ];

    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class, 'student_id');
    }

    public function careers()
    {
        return $this->belongsToMany(Career::class, 'enrollments', 'id_student', 'id_career')->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_student');
    }
    
    public function debts()
    {
        return $this->hasMany(Debt::class, 'id_student');
    }
}