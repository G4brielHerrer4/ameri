
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camion_viajes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chofer_id')
                ->constrained('camion_choferes')
                ->restrictOnDelete();

            $table->date('fecha');
            $table->string('origen', 200);
            $table->string('destino', 200);
            $table->text('mercaderia')->nullable();

            $table->decimal('monto_cobrado', 12, 2)->default(0);
            $table->enum('estado_pago', ['pagado', 'pendiente'])
                ->default('pendiente');

            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camion_viajes');
    }
};
