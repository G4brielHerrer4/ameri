<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudStock extends Model
{
    protected $table = 'solicitudes_stock';
    protected $fillable = ['user_id', 'proveedor_user_id', 'estado', 'nota'];

    public function solicitante() { return $this->belongsTo(User::class, 'user_id'); }
    public function proveedor() { return $this->belongsTo(User::class, 'proveedor_user_id'); }
    public function detalles() { return $this->hasMany(SolicitudStockDetalle::class, 'solicitud_id'); }
}