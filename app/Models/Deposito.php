<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deposito extends Model
{
    protected $table = 'depositos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    // Relaciones (las iremos completando en próximos pasos)
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function compraDetalles(): HasMany
    {
        return $this->hasMany(CompraDetalle::class);
    }
    
}