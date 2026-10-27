<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProducto;
use App\Models\Producto;

class CatalogoPublicoController extends Controller
{
    public function index(?int $categoria = null)
    {
        // Obtener solo los productos habilitados para el catálogo.
        $productos = Producto::with('tipoProducto.categoria')
            ->where('visible_catalogo', true)
            ->when($categoria !== null, function ($query) use ($categoria) {
                $query->whereHas('tipoProducto.categoria', function ($q) use ($categoria) {
                    $q->where('categorias_productos.id', $categoria);
                });
            })
            ->orderBy('nombre')
            ->get();

        // Mostrar las categorías que tienen productos visibles.
        $categorias = CategoriaProducto::query()
            ->whereHas('tipos.productos', function ($query) {
                $query->where('visible_catalogo', true);
            })
            ->with('tipos')
            ->orderBy('nombre')
            ->get();

        // Verificar que la categoría solicitada exista.
        if ($categoria !== null &&
            !CategoriaProducto::whereKey($categoria)->exists()) {
            abort(404);
        }

        return view('catalogo.index', compact(
            'productos',
            'categorias',
            'categoria'
        ));
    }
}
