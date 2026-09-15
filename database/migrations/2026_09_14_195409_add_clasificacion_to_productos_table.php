<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('tipo_producto_id')
                ->nullable()
                ->after('id')
                ->constrained('tipos_productos')
                ->nullOnDelete();

            $table->integer('piezas_por_caja')
                ->nullable()
                ->after('medida');

            $table->decimal('m2_por_caja', 10, 2)
                ->nullable()
                ->after('piezas_por_caja');

            $table->string('medida')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['tipo_producto_id']);
            $table->dropColumn([
                'tipo_producto_id',
                'piezas_por_caja',
                'm2_por_caja',
            ]);

            $table->string('medida')
                ->nullable(false)
                ->change();
        });
    }
};
