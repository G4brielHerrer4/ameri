<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'nit',
        'telefono',
        'ciudad',
        'direccion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    //COMRPAS
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
}