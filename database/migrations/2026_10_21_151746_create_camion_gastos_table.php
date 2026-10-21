
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camion_gastos', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');

            $table->enum('tipo_gasto', [
                'combustible',
                'aceite',
                'mantenimiento',
                'repuestos',
                'otros'
            ]);

            $table->enum('tipo_combustible', [
                'gasolina',
                'gnv'
            ])->nullable();

            $table->string('descripcion', 255)->nullable();
            $table->decimal('monto', 12, 2);

            $table->unsignedInteger('kilometraje')->nullable();

            // Ruta de la fotografía o PDF del comprobante
            $table->string('comprobante')->nullable();

            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camion_gastos');
    }
};
