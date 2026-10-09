
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('precio_venta_caja', 12, 2)
                ->nullable();

            $table->decimal('precio_venta_m2', 12, 2)
                ->nullable();

            $table->decimal('precio_venta_pieza', 12, 2)
                ->nullable();

            $table->decimal('precio_venta_bolsa', 12, 2)
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'precio_venta_caja',
                'precio_venta_m2',
                'precio_venta_pieza',
                'precio_venta_bolsa',
            ]);
        });
    }
};