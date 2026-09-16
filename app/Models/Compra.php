<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';

    protected $fillable = [
        'user_id',
        'proveedor_id',
        'numero_orden',
        'fecha_compra',
        'subtotal',
        'descuento',
        'total',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'subtotal'     => 'decimal:2',
        'descuento'    => 'decimal:2',
        'total'        => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public static function generarNumeroOrden(): string
    {
        $hoy = now()->format('Ymd');

        $ultimo = static::where('numero_orden', 'like', "AMERI-{$hoy}-%")
            ->orderBy('id', 'desc')
            ->first();

        $consecutivo = $ultimo
            ? ((int) substr($ultimo->numero_orden, -4)) + 1
            : 1;

        return sprintf('AMERI-%s-%04d', $hoy, $consecutivo);
    }
}