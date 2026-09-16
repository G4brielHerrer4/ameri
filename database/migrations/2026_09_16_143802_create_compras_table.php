<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Admin que registró la compra');

            $table->foreignId('proveedor_id')
                ->constrained('proveedores')
                ->restrictOnDelete()
                ->comment('Proveedor al que se le compró');

            $table->string('numero_orden', 30)->unique()
                ->comment('Ej: AMERI-20260916-0001');

            $table->date('fecha_compra');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->enum('estado', ['pendiente', 'recibida', 'cancelada'])
                ->default('pendiente');

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index('fecha_compra');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};