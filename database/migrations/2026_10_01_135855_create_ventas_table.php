<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Admin que registra la venta');

            $table->string('numero_venta', 30)->unique();

            $table->string('cliente_nombre', 150);
            $table->string('cliente_documento', 30)
                ->comment('CI o NIT');
            $table->string('cliente_telefono', 30)->nullable();

            $table->date('fecha_venta');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->enum('estado', ['pendiente', 'vendida', 'cancelada'])
                ->default('pendiente');

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index('fecha_venta');
            $table->index('estado');
            $table->index('cliente_documento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};