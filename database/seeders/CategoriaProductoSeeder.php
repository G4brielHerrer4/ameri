<?php

namespace Database\Seeders;

use App\Models\CategoriaProducto;
use App\Models\TipoProducto;
use Illuminate\Database\Seeder;

class CategoriaProductoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Cerámicas / Porcelanatos
        |--------------------------------------------------------------------------
        */

        $ceramicas = CategoriaProducto::updateOrCreate(
            [
                'nombre' => 'Cerámicas / Porcelanatos',
            ]
        );

        TipoProducto::updateOrCreate(
            [
                'categoria_id' => $ceramicas->id,
                'nombre' => 'Cerámica',
            ]
        );

        TipoProducto::updateOrCreate(
            [
                'categoria_id' => $ceramicas->id,
                'nombre' => 'Porcelanato',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Cementos / Adhesivos
        |--------------------------------------------------------------------------
        */

        $cementos = CategoriaProducto::updateOrCreate(
            [
                'nombre' => 'Cementos / Adhesivos',
            ]
        );

        TipoProducto::updateOrCreate(
            [
                'categoria_id' => $cementos->id,
                'nombre' => 'Cemento Cola',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Decorativos
        |--------------------------------------------------------------------------
        */

        $decorativos = CategoriaProducto::updateOrCreate(
            [
                'nombre' => 'Decorativos',
            ]
        );

        TipoProducto::updateOrCreate(
            [
                'categoria_id' => $decorativos->id,
                'nombre' => 'Listelo',
            ]
        );

        TipoProducto::updateOrCreate(
            [
                'categoria_id' => $decorativos->id,
                'nombre' => 'Randa decorativa',
            ]
        );

        TipoProducto::updateOrCreate(
            [
                'categoria_id' => $decorativos->id,
                'nombre' => 'Pastinas',
            ]
        );

        TipoProducto::updateOrCreate(
            [
                'categoria_id' => $decorativos->id,
                'nombre' => 'Esquinero de aluminio',
            ]
        );

        TipoProducto::updateOrCreate(
            [
                'categoria_id' => $decorativos->id,
                'nombre' => 'Esquinero de goma',
            ]
        );
    }
}