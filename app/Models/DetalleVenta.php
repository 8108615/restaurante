<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación con la venta principal
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
