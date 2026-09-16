<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compra_detalles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('compra_id')
                ->constrained('compras')
                ->cascadeOnDelete();

            $table->foreignId('producto_id')
                ->constrained('productos')
                ->restrictOnDelete();

            // Cantidad comprada (en la unidad de compra: cajas, bolsas, piezas)
            $table->decimal('cantidad', 12, 2)
                ->comment('Cantidad de paquetes comprados');

            // Unidad en la que se compra (heredada del producto)
            $table->string('unidad_compra', 20)
                ->default('unidad')
                ->comment('caja, bolsa, pieza, unidad');

            // Factor de conversión: cuántas unidades base trae cada paquete
            $table->decimal('unidades_por_paquete', 10, 2)
                ->default(1)
                ->comment('Ej: 4 piezas por caja');

            // Total en unidades base para el stock
            $table->decimal('cantidad_total', 12, 2)
                ->default(0)
                ->comment('cantidad × unidades_por_paquete');

            $table->decimal('precio_unitario', 12, 2)
                ->comment('Precio por unidad de compra (por caja, por bolsa...)');

            $table->decimal('subtotal', 12, 2)
                ->comment('cantidad × precio_unitario');

            $table->timestamps();

            $table->index('producto_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compra_detalles');
    }
};