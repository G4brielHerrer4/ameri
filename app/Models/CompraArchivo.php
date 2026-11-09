<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraArchivo extends Model
{
    protected $table = 'compra_archivos';

    protected $fillable = [
        'compra_id',
        'ruta',
        'nombre_original',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
}