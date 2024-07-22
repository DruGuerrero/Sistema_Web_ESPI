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
        'id_year',
    ];

    // Relación uno a uno con MediaFile (un curso puede tener un archivo y un archivo pertenece a un curso)
    public function mediaFile()
    {
        return $this->hasOne(MediaFile::class, 'id_course');
    }

    // Relación muchos a uno con Year (un curso pertenece a un año y un año puede tener varios cursos)
    public function year()
    {
        return $this->belongsTo(Year::class, 'id_year');
    }

    // Relación muchos a uno con User (Docente) (muchos cursos pueden tener un solo docente, pero un curso solo puede tener un docente asignado)
    public function docente()
    {
        return $this->belongsTo(User::class, 'id_docente');
    }
}