<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CamionChofer extends Model
{
    protected $table = 'camion_choferes';

    protected $fillable = [
        'nombre',
        'telefono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function viajes(): HasMany
    {
        return $this->hasMany(CamionViaje::class, 'chofer_id');
    }
}
