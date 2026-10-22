<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CamionGasto extends Model
{
    protected $table = 'camion_gastos';

   protected $fillable = [
        'user_id',
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

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
