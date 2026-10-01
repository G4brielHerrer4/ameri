<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaMovimiento extends Model
{
    use HasFactory;

    protected $table = 'venta_movimientos';

    protected $fillable = [
        'venta_detalle_id',
        'stock_user_id',
        'cantidad',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
    ];

    public function detalle()
    {
        return $this->belongsTo(VentaDetalle::class, 'venta_detalle_id');
    }

    public function stockUser()
    {
        return $this->belongsTo(User::class, 'stock_user_id');
    }
}