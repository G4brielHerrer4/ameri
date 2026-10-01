<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'from_user_id',
        'tipo',
        'titulo',
        'mensaje',
        'leida',
        'venta_id',
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }
}