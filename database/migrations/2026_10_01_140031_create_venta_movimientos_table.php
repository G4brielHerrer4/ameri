<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venta_movimientos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('venta_detalle_id')
                ->constrained('venta_detalles')
                ->cascadeOnDelete();

            $table->foreignId('stock_user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('Admin de quién se tomó el stock');

            $table->decimal('cantidad', 12, 2);

            $table->timestamps();

            $table->index('stock_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta_movimientos');
    }
};