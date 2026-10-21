<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CamionGasto extends Model
{
    protected $table = 'camion_gastos';

    protected $fillable = [
        'fecha',
        'tipo_gasto',
        'tipo_combustible',
        'descripcion',
        'monto',
        'kilometraje',
        'comprobante',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
        'kilometraje' => 'integer',
    ];
}
