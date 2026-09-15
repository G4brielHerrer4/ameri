<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'tipo_producto_id',
        'nombre',
        'marca',
        'modelo',
        'medida',
        'piezas_por_caja',
        'm2_por_caja',
        'peso',
        'color',
        'acabado',
        'presentacion',
    ];

    protected $casts = [
        'piezas_por_caja' => 'integer',
        'm2_por_caja' => 'decimal:2',
        'peso' => 'decimal:2',
    ];

    public function tipoProducto(): BelongsTo
    {
        return $this->belongsTo(
            TipoProducto::class,
            'tipo_producto_id'
        );
    }
}