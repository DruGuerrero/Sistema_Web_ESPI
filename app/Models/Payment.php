<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_student',
        'id_product',
        'fecha',
        'monto_pagado',
        'id_debt',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function debt()
    {
        return $this->belongsTo(Debt::class, 'id_debt');
    }
}