<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categoriaDecorativos = DB::table('categorias_productos')
            ->where('nombre', 'Decorativos')
            ->value('id');

        /*
        |--------------------------------------------------------------------------
        | Eliminar tipos que ya no forman parte del sistema
        |--------------------------------------------------------------------------
        */

        DB::table('tipos_productos')
            ->whereIn('nombre', [
                'Cemento',
                'Esquinero',
                'Otro',
            ])
            ->delete();


        /*
        |--------------------------------------------------------------------------
        | Agregar Pastinas
        |--------------------------------------------------------------------------
        */

        DB::table('tipos_productos')->updateOrInsert(
            [
                'categoria_id' => $categoriaDecorativos,
                'nombre' => 'Pastinas',
            ],
            [
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Agregar Esquinero de aluminio
        |--------------------------------------------------------------------------
        */

        DB::table('tipos_productos')->updateOrInsert(
            [
                'categoria_id' => $categoriaDecorativos,
                'nombre' => 'Esquinero de aluminio',
            ],
            [
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Agregar Esquinero de goma
        |--------------------------------------------------------------------------
        */

        DB::table('tipos_productos')->updateOrInsert(
            [
                'categoria_id' => $categoriaDecorativos,
                'nombre' => 'Esquinero de goma',
            ],
            [
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        $categoriaDecorativos = DB::table('categorias_productos')
            ->where('nombre', 'Decorativos')
            ->value('id');

        DB::table('tipos_productos')
            ->where('categoria_id', $categoriaDecorativos)
            ->whereIn('nombre', [
                'Pastinas',
                'Esquinero de aluminio',
                'Esquinero de goma',
            ])
            ->delete();
    }
};