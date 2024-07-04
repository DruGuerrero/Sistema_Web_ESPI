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
    ];

    protected $casts = [
        'disabled' => 'boolean',
    ];
}