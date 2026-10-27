<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mensaje_contactos', function (Blueprint $table) {
            $table->string('nombre', 120);
            $table->string('email', 150);
            $table->string('telefono', 30)->nullable();
            $table->text('mensaje');
            $table->boolean('leido')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('mensaje_contactos', function (Blueprint $table) {
            $table->dropColumn([
                'nombre',
                'email',
                'telefono',
                'mensaje',
                'leido',
            ]);
        });
    }
};