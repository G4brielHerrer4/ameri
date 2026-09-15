<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_productos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categoria_id')
                ->constrained('categorias_productos')
                ->cascadeOnDelete();

            $table->string('nombre', 100);

            $table->timestamps();

            $table->unique(['categoria_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_productos');
    }
};