<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('compra_detalles', function (Blueprint $table) {
            $table->foreignId('deposito_id')
                ->after('producto_id')
                ->nullable()
                ->constrained('depositos')
                ->restrictOnDelete()
                ->comment('Depósito destino del producto');
        });
    }

    public function down(): void
    {
        Schema::table('compra_detalles', function (Blueprint $table) {
            $table->dropForeign(['deposito_id']);
            $table->dropColumn('deposito_id');
        });
    }
};