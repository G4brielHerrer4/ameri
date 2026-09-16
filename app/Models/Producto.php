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
        'm2_por_caja'     => 'decimal:2',
        'peso'            => 'decimal:2',
    ];

    /* ============================================================
     * RELACIONES
     * ============================================================ */

    public function tipoProducto(): BelongsTo
    {
        return $this->belongsTo(
            TipoProducto::class,
            'tipo_producto_id'
        );
    }

    public function compraDetalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /* ============================================================
     * ACCESSORS — Unidad de compra según tipo de producto
     * ============================================================ */

    /**
     * Unidad en la que se compra este producto.
     * Ej: 'caja', 'bolsa', 'pieza', 'unidad'
     */
    public function getUnidadCompraAttribute(): string
    {
        $tipo = strtolower($this->tipoProducto?->nombre ?? '');

        return match (true) {
            in_array($tipo, ['cerámica', 'porcelanato']) => 'caja',
            $tipo === 'cemento cola'                     => 'bolsa',
            in_array($tipo, ['listelo', 'randa decorativa', 'pastinas']) => 'pieza',
            in_array($tipo, ['esquinero de aluminio', 'esquinero de goma']) => 'pieza',
            default                                       => 'unidad',
        };
    }

    /**
     * Cuántas unidades base trae cada unidad de compra.
     * Ej: 4 piezas por caja → 4
     */
    public function getUnidadesPorPaqueteAttribute(): float
    {
        $tipo = strtolower($this->tipoProducto?->nombre ?? '');

        return match (true) {
            in_array($tipo, ['cerámica', 'porcelanato'])
                => (float) ($this->piezas_por_caja ?? 1),

            in_array($tipo, ['listelo', 'randa decorativa', 'pastinas'])
                => (float) ($this->piezas_por_caja ?? 1),

            in_array($tipo, ['esquinero de aluminio', 'esquinero de goma'])
                => (float) ($this->piezas_por_caja ?? 1),

            // Cemento cola: 1 bolsa = 1 unidad base
            default => 1,
        };
    }

    /**
     * Nombre legible de la unidad base (plural).
     * Ej: 'piezas', 'bolsas', 'unidades'
     */
    public function getUnidadBaseAttribute(): string
    {
        $tipo = strtolower($this->tipoProducto?->nombre ?? '');

        return match (true) {
            in_array($tipo, ['cerámica', 'porcelanato']) => 'piezas',
            $tipo === 'cemento cola'                     => 'bolsas',
            in_array($tipo, ['listelo', 'randa decorativa', 'pastinas']) => 'piezas',
            in_array($tipo, ['esquinero de aluminio', 'esquinero de goma']) => 'piezas',
            default                                       => 'unidades',
        };
    }
}