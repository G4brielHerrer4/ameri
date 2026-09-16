<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'user_id',
        'producto_id',
        'cantidad',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public static function incrementar(int $userId, int $productoId, float $cantidad): void
    {
        $stock = static::firstOrCreate(
            [
                'user_id'     => $userId,
                'producto_id' => $productoId,
            ],
            ['cantidad' => 0]
        );

        $stock->increment('cantidad', $cantidad);
    }

    public static function decrementar(int $userId, int $productoId, float $cantidad): void
    {
        $stock = static::where('user_id', $userId)
            ->where('producto_id', $productoId)
            ->first();

        if ($stock && $stock->cantidad >= $cantidad) {
            $stock->decrement('cantidad', $cantidad);
        }
    }
}