<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('stock_minimo', 12, 2)
                ->default(0)
                ->after('presentacion');

            $table->decimal('stock_maximo', 12, 2)
                ->default(0)
                ->after('stock_minimo');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'stock_minimo',
                'stock_maximo',
            ]);
        });
    }
};