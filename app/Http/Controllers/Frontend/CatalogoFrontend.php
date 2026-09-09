<?php
// app/Http/Controllers/Frontend/CatalogoFrontend.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class CatalogoFrontend extends Controller
{
    /**
     * Muestra la página de productos del frontend
     */
    public function index()
    {
        // Categorías activas con conteo de productos
        $categorias = Categoria::withCount(['productos' => function($query) {
            $query->where('estado', true);
        }])->where('estado', true)
          ->orderBy('nombre')
          ->get();
        
        // Productos activos
        $productos = Producto::with(['categoria', 'colores' => function($query) {
            $query->where('disponible', true);
        }])
        ->where('estado', true)
        ->orderBy('destacado', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('frontend.frontend_productos', compact('categorias', 'productos'));
    }

    /**
     * Obtener productos por categoría (para AJAX)
     */
    public function getProductosByCategoria($categoriaId = null)
    {
        $query = Producto::with(['categoria', 'colores'])
            ->where('estado', true);
        
        if ($categoriaId && $categoriaId !== 'all') {
            $query->where('categoria_id', $categoriaId);
        }
        
        $productos = $query->orderBy('destacado', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $productos
            ]);
        }
        
        return redirect()->route('frontend.productos');
    }

    /**
     * Obtener detalle de un producto específico
     */
    public function show($slug)
    {
        $producto = Producto::with(['categoria', 'colores'])
            ->where('slug', $slug)
            ->where('estado', true)
            ->firstOrFail();
        
        // Obtener productos relacionados (misma categoría)
        $relacionados = Producto::with(['categoria', 'colores'])
            ->where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->where('estado', true)
            ->limit(4)
            ->get();
        
        return view('frontend.frontend_producto_detalle', compact('producto', 'relacionados'));
    }

    /**
     * Filtrar productos por precio (AJAX)
     */
    public function filtrarPorPrecio(Request $request)
    {
        $min = $request->get('min', 0);
        $max = $request->get('max', 999999);
        
        $productos = Producto::with(['categoria', 'colores'])
            ->where('estado', true)
            ->whereBetween('precio_base', [$min, $max])
            ->orderBy('destacado', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $productos
        ]);
    }

    /**
     * Obtener detalle de producto para modal (JSON)
     */
    public function getProductoDetalle($id)
    {
        $producto = Producto::with(['categoria', 'colores'])
            ->where('id', $id)
            ->where('estado', true)
            ->first();
        
        if (!$producto) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'imagen_principal_url' => $producto->imagen_principal_url,
                'medida' => $producto->medida,
                'material' => $producto->material,
                'precio_base' => $producto->precio_base,
                'precio_base_formateado' => $producto->precio_base_formateado,
                'categoria' => $producto->categoria ? [
                    'id' => $producto->categoria->id,
                    'nombre' => $producto->categoria->nombre
                ] : null,
                'colores' => $producto->colores->map(function($color) {
                    return [
                        'id' => $color->id,
                        'nombre_color' => $color->nombre_color,
                        'codigo_color' => $color->codigo_color,
                        'stock' => $color->stock,
                        'precio_adicional' => $color->precio_adicional,
                        'disponible' => $color->disponible
                    ];
                })
            ]
        ]);
    }
}