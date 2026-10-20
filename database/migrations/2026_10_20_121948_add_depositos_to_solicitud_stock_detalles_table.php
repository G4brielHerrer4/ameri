<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitud_stock_detalles', function (Blueprint $table) {
            $table->foreignId('deposito_origen_id')->nullable()->constrained('depositos')->nullOnDelete();
            $table->foreignId('deposito_destino_id')->nullable()->constrained('depositos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('solicitud_stock_detalles', function (Blueprint $table) {
            $table->dropForeign(['deposito_origen_id']);
            $table->dropForeign(['deposito_destino_id']);
            $table->dropColumn(['deposito_origen_id', 'deposito_destino_id']);
        });
    }
};