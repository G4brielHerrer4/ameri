<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('A quién va dirigida');

            $table->foreignId('from_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Quién la origina');

            $table->enum('tipo', [
                'stock_prestado',
                'stock_devuelto',
                'info',
            ])->default('info');

            $table->string('titulo', 150);
            $table->text('mensaje');

            $table->boolean('leida')->default(false);

            $table->foreignId('venta_id')
                ->nullable()
                ->constrained('ventas')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['user_id', 'leida']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};