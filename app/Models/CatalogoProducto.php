<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogoProducto extends Model
{
    protected $table = 'catalogo_productos';

    protected $fillable = [
        'nombre',
        'categoria',
        'industria',
        'precio',
        'imagen',
        'visible',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'visible' => 'boolean',
    ];

    public const CATEGORIAS = [
        'ceramicas' => 'Cerámicas y porcelanatos',
        'cementos' => 'Cementos y adhesivos',
        'decorativos' => 'Decorativos',
    ];
}
