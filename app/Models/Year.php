<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_career',
    ];

    // Relación uno a muchos con Course
    public function courses()
    {
        return $this->hasMany(Course::class, 'id_year');
    }

    // Relación muchos a uno con Career
    public function career()
    {
        return $this->belongsTo(Career::class, 'id_career');
    }
}