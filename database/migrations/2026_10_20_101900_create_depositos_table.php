<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('depositos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        // Seed mínimo: depósitos base del sistema
        DB::table('depositos')->insert([
            [
                'nombre'      => 'Tienda 1',
                'descripcion' => 'Priemra tienda',
                'estado'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Tienda 2',
                'descripcion' => 'Segunda tienda',
                'estado'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Tienda 3',
                'descripcion' => 'Tercera Tienda',
                'estado'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Tienda 4',
                'descripcion' => 'Cuarta Tienda',
                'estado'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('depositos');
    }
};