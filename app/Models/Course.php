<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_docente',
        'id_carrera',
    ];

    // Relación uno a uno con el modelo User
    public function docente()
    {
        return $this->belongsTo(User::class, 'id_docente');
    }
}