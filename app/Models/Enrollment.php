<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_student',
        'id_career',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }

    public function career()
    {
        return $this->belongsTo(Career::class, 'id_career');
    }
}