<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->decimal('cantidad_venta', 12, 2)
                ->nullable()
                ->after('cantidad_stock');

            $table->string('unidad_venta', 20)
                ->nullable()
                ->after('cantidad_venta');
        });
    }

    public function down(): void
    {
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->dropColumn([
                'cantidad_venta',
                'unidad_venta',
            ]);
        });
    }
};