<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'user_id',
        'numero_venta',
        'cliente_nombre',
        'cliente_documento',
        'cliente_telefono',
        'fecha_venta',
        'subtotal',
        'descuento',
        'total',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_venta' => 'date',
        'subtotal'    => 'decimal:2',
        'descuento'   => 'decimal:2',
        'total'       => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public static function generarNumeroVenta(): string
    {
        $year = now()->year;

        $ultimo = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $consecutivo = $ultimo
            ? ((int) substr($ultimo->numero_venta, -4)) + 1
            : 1;

        return sprintf('V-%d-%04d', $year, $consecutivo);
    }
}