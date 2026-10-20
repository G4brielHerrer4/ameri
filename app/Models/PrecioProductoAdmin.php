<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrecioProductoAdmin extends Model
{
    use HasFactory;

    protected $table = 'precios_producto_admin';

    protected $fillable = [
        'user_id',
        'producto_id',
        'precio_venta_caja',
        'precio_venta_m2',
        'precio_venta_pieza',
        'precio_venta_bolsa',
        'stock_minimo',
        'stock_maximo',
        'estado',
    ];

    protected $casts = [
        'precio_venta_caja'  => 'decimal:2',
        'precio_venta_m2'    => 'decimal:2',
        'precio_venta_pieza' => 'decimal:2',
        'precio_venta_bolsa' => 'decimal:2',
        'stock_minimo'       => 'decimal:2',
        'stock_maximo'       => 'decimal:2',
        'estado'             => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}