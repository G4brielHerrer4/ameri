<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('marca', 100)
                ->nullable()
                ->after('nombre');

            $table->decimal('peso', 10, 2)
                ->nullable()
                ->after('m2_por_caja');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'marca',
                'peso',
            ]);
        });
    }
};