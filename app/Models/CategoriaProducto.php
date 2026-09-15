<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaProducto extends Model
{
    use HasFactory;

    protected $table = 'categorias_productos';

    protected $fillable = [
        'nombre',
    ];

    public function tipos(): HasMany
    {
        return $this->hasMany(TipoProducto::class, 'categoria_id');
    }
}