<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'metodo_pago',
        'monto_pagado',
        'cambio',
        'estado'
    ];

    // Relación con el usuario que vendió
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con los detalles de la venta
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
