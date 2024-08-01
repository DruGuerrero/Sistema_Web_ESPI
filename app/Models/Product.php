<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_product');
    }

    public function debts()
    {
        return $this->hasMany(Debt::class, 'id_product');
    }
}