<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venta_movimientos', function (Blueprint $table) {
            $table->foreignId('deposito_id')
                ->after('stock_user_id')
                ->nullable() // Lo dejamos nullable por si hay ventas viejas
                ->constrained('depositos')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('venta_movimientos', function (Blueprint $table) {
            $table->dropForeign(['deposito_id']);
            $table->dropColumn('deposito_id');
        });
    }
};