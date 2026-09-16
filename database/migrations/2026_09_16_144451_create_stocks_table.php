<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Admin que tiene el stock');

            $table->foreignId('producto_id')
                ->constrained('productos')
                ->cascadeOnDelete();

            // Cantidad en unidades base (piezas, bolsas, etc.)
            $table->decimal('cantidad', 12, 2)->default(0);

            $table->timestamps();

            $table->unique(['user_id', 'producto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};