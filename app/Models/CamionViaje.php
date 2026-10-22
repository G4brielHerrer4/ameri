<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CamionViaje extends Model
{
    protected $table = 'camion_viajes';

    protected $fillable = [
        'user_id',
        'chofer_id',
        'fecha',
        'origen',
        'destino',
        'mercaderia',
        'monto_cobrado',
        'estado_pago',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto_cobrado' => 'decimal:2',
    ];

    public function chofer(): BelongsTo
    {
        return $this->belongsTo(CamionChofer::class, 'chofer_id');
    }
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
