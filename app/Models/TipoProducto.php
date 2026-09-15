<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoProducto extends Model
{
    use HasFactory;

    protected $table = 'tipos_productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(
            CategoriaProducto::class,
            'categoria_id'
        );
    }

    public function productos(): HasMany
    {
        return $this->hasMany(
            Producto::class,
            'tipo_producto_id'
        );
    }
}