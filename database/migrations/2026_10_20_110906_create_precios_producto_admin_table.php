<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('precios_producto_admin', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')->cascadeOnDelete()
                ->comment('Admin dueño del precio y límites');

            $table->foreignId('producto_id')
                ->constrained('productos')->cascadeOnDelete();

            $table->decimal('precio_venta_caja',  12, 2)->nullable();
            $table->decimal('precio_venta_m2',    12, 2)->nullable();
            $table->decimal('precio_venta_pieza', 12, 2)->nullable();
            $table->decimal('precio_venta_bolsa', 12, 2)->nullable();

            $table->decimal('stock_minimo', 12, 2)->default(0);
            $table->decimal('stock_maximo', 12, 2)->default(0);

            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'producto_id'], 'precios_user_producto_unique');
        });

        /* ============================================================
           BACKFILL: copiar los valores actuales de productos
           SOLO a los admins que tienen stock del producto.
           Si no hay ninguno, se asigna al primer admin (id más bajo).
           ============================================================ */
        $productos = DB::table('productos')->get();

        foreach ($productos as $p) {
            // Admins que tienen stock de este producto
            $adminsConStock = DB::table('stocks')
                ->where('producto_id', $p->id)
                ->pluck('user_id')
                ->unique()
                ->values();

            if ($adminsConStock->isEmpty()) {
                // Fallback: admin más antiguo
                $fallback = DB::table('users')->orderBy('id')->value('id');
                if ($fallback) {
                    $adminsConStock = collect([$fallback]);
                }
            }

            foreach ($adminsConStock as $userId) {
                DB::table('precios_producto_admin')->updateOrInsert(
                    [
                        'user_id'     => $userId,
                        'producto_id' => $p->id,
                    ],
                    [
                        'precio_venta_caja'  => $p->precio_venta_caja,
                        'precio_venta_m2'    => $p->precio_venta_m2,
                        'precio_venta_pieza' => $p->precio_venta_pieza,
                        'precio_venta_bolsa' => $p->precio_venta_bolsa,
                        'stock_minimo'       => $p->stock_minimo ?? 0,
                        'stock_maximo'       => $p->stock_maximo ?? 0,
                        'estado'             => true,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('precios_producto_admin');
    }
};