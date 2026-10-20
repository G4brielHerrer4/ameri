<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'user_id',
        'producto_id',
        'deposito_id',  
        'cantidad',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function deposito(): BelongsTo
    {
        return $this->belongsTo(Deposito::class);
    }

    public static function depositoPorDefectoId(): int
    {
        return (int) (Deposito::query()->orderBy('id')->value('id') ?? 1);
    }

    public static function incrementar(
        int $userId,
        int $productoId,
        float $cantidad,
        ?int $depositoId = null
    ): void {
        $depositoId = $depositoId ?: static::depositoPorDefectoId();

        $stock = static::firstOrCreate(
            [
                'user_id'     => $userId,
                'producto_id' => $productoId,
                'deposito_id' => $depositoId,
            ],
            ['cantidad' => 0]
        );

        $stock->increment('cantidad', $cantidad);
    }

    public static function decrementar(
        int $userId,
        int $productoId,
        float $cantidad,
        ?int $depositoId = null
    ): void {
        $depositoId = $depositoId ?: static::depositoPorDefectoId();

        $stock = static::where('user_id', $userId)
            ->where('producto_id', $productoId)
            ->where('deposito_id', $depositoId)
            ->first();

        if ($stock && $stock->cantidad >= $cantidad) {
            $stock->decrement('cantidad', $cantidad);
        }
    }
}