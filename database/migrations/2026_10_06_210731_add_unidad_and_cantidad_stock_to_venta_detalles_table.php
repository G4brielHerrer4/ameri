<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->string('unidad', 20)
                ->default('unidad')
                ->after('cantidad');

            $table->decimal('cantidad_stock', 12, 2)
                ->default(0)
                ->after('unidad');
        });
    }

    public function down(): void
    {
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->dropColumn([
                'unidad',
                'cantidad_stock',
            ]);
        });
    }
};