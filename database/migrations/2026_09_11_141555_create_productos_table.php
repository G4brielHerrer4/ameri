<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');                    // Nombre del producto
            $table->string('modelo')->nullable();        // Modelo / código interno
            $table->string('medida');                    // Ej: 60x60 cm
            $table->string('color')->nullable();         // Ej: Blanco, Gris
            $table->string('acabado')->nullable();       // Ej: Mate, Brillante, Pulido
            $table->string('presentacion')->nullable();  // Ej: Caja x 4 piezas, Pallet, Bolsa 25kg

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};