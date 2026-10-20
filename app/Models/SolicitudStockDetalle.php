<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudStockDetalle extends Model
{
    protected $table = 'solicitud_stock_detalles';

    protected $fillable = [
        'solicitud_id',
        'producto_id',
        'cantidad',
        'deposito_origen_id',
        'deposito_destino_id',
        'precio_acordado'
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_acordado' => 'decimal:2',
    ];

    // Relación con la solicitud (por si la necesitas desde el detalle)
    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudStock::class, 'solicitud_id');
    }

    // Relación con el producto
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación con la tienda de donde sale el producto (Admin B)
    public function depositoOrigen(): BelongsTo
    {
        return $this->belongsTo(Deposito::class, 'deposito_origen_id');
    }

    // Relación con la tienda a donde llega el producto (Admin A)
    public function depositoDestino(): BelongsTo
    {
        return $this->belongsTo(Deposito::class, 'deposito_destino_id');
    }
}