<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Crear un índice simple para user_id (para que la FK lo use)
        Schema::table('stocks', function (Blueprint $table) {
            $table->index('user_id');
        });

        // 2) Ahora sí, soltar el unique viejo
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'producto_id']);
        });

        // 3) Agregar deposito_id nullable
        Schema::table('stocks', function (Blueprint $table) {
            $table->foreignId('deposito_id')
                ->after('producto_id')
                ->nullable()
                ->constrained('depositos')
                ->restrictOnDelete();
        });

        // 4) Backfill: TODOS los stocks existentes → Tienda 1 (id = 1)
        $depositoPorDefectoId = DB::table('depositos')->orderBy('id')->value('id');

        if ($depositoPorDefectoId) {
            DB::table('stocks')
                ->whereNull('deposito_id')
                ->update(['deposito_id' => $depositoPorDefectoId]);
        }

        // 5) NOT NULL + nuevo unique
        Schema::table('stocks', function (Blueprint $table) {
            $table->foreignId('deposito_id')->nullable(false)->change();

            $table->unique(
                ['user_id', 'producto_id', 'deposito_id'],
                'stocks_user_producto_deposito_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropUnique('stocks_user_producto_deposito_unique');
            $table->dropForeign(['deposito_id']);
            $table->dropColumn('deposito_id');

            // Restaurar unique viejo
            $table->unique(['user_id', 'producto_id']);

            // Eliminar el índice simple que creamos en up()
            $table->dropIndex(['user_id']);
        });
    }
};