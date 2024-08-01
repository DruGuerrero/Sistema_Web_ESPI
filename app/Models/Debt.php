<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_student',
        'id_product',
        'monto_pendiente',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_debt');
    }
}