<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    use HasFactory;

    protected $table = 'compra_detalles';

    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'unidad_compra',
        'unidades_por_paquete',
        'cantidad_total',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'cantidad'             => 'decimal:2',
        'unidades_por_paquete' => 'decimal:2',
        'cantidad_total'       => 'decimal:2',
        'precio_unitario'      => 'decimal:2',
        'subtotal'             => 'decimal:2',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}