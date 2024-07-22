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
}