<?php
// app/Http/Controllers/Admin/CatalogoController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CatalogoController extends Controller
{
    public function __construct()
    {

    }

    // ==================== VISTA PRINCIPAL ====================
    public function index()
    {
        $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();
        $productos = Producto::with(['categoria', 'colores'])->orderBy('created_at', 'desc')->get();
        return view('admin.catalogos.index', compact('categorias', 'productos'));
    }

    // ==================== CATEGORÍAS ====================
    public function storeCategoria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->only(['nombre', 'descripcion', 'estado']);
            $data['estado'] = $request->estado ?? 1;
            $data['created_by'] = auth()->id();

            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('categorias', 'public');
                $data['imagen'] = $path;
            }

            $categoria = Categoria::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Categoría creada exitosamente',
                'data' => $categoria
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateCategoria(Request $request, Categoria $categoria)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->only(['nombre', 'descripcion', 'estado']);
            $data['updated_by'] = auth()->id();

            if ($request->hasFile('imagen')) {
                if ($categoria->imagen && Storage::disk('public')->exists($categoria->imagen)) {
                    Storage::disk('public')->delete($categoria->imagen);
                }
                $path = $request->file('imagen')->store('categorias', 'public');
                $data['imagen'] = $path;
            }

            $categoria->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada exitosamente',
                'data' => $categoria
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroyCategoria(Categoria $categoria)
    {
        try {
            if ($categoria->imagen && Storage::disk('public')->exists($categoria->imagen)) {
                Storage::disk('public')->delete($categoria->imagen);
            }
            $categoria->delete();
            return response()->json(['success' => true, 'message' => 'Categoría eliminada exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // ==================== PRODUCTOS ====================
    public function storeProducto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'medida' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:100',
            'precio_base' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'estado' => 'boolean',
            'destacado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->only(['categoria_id', 'nombre', 'descripcion', 'medida', 'material', 'precio_base', 'stock', 'estado', 'destacado']);
            $data['estado'] = $request->estado ?? 1;
            $data['destacado'] = $request->destacado ?? 0;
            $data['created_by'] = auth()->id();

            if ($request->hasFile('imagen_principal')) {
                $path = $request->file('imagen_principal')->store('productos', 'public');
                $data['imagen_principal'] = $path;
            }

            $producto = Producto::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Producto creado exitosamente',
                'data' => $producto->load('colores')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateProducto(Request $request, Producto $producto)
    {
        $validator = Validator::make($request->all(), [
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'medida' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:100',
            'precio_base' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'estado' => 'boolean',
            'destacado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->only(['categoria_id', 'nombre', 'descripcion', 'medida', 'material', 'precio_base', 'stock', 'estado', 'destacado']);
            $data['updated_by'] = auth()->id();

            if ($request->hasFile('imagen_principal')) {
                if ($producto->imagen_principal && Storage::disk('public')->exists($producto->imagen_principal)) {
                    Storage::disk('public')->delete($producto->imagen_principal);
                }
                $path = $request->file('imagen_principal')->store('productos', 'public');
                $data['imagen_principal'] = $path;
            }

            $producto->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'data' => $producto->load('colores')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroyProducto(Producto $producto)
    {
        try {
            if ($producto->imagen_principal && Storage::disk('public')->exists($producto->imagen_principal)) {
                Storage::disk('public')->delete($producto->imagen_principal);
            }
            
            foreach ($producto->colores as $color) {
                if ($color->imagen && Storage::disk('public')->exists($color->imagen)) {
                    Storage::disk('public')->delete($color->imagen);
                }
            }
            
            $producto->delete();
            return response()->json(['success' => true, 'message' => 'Producto eliminado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // ==================== INFO PRODUCTO ====================
    public function getProductoInfo(Producto $producto)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio_base' => $producto->precio_base,
                'stock' => $producto->stock,
                'stock_restante' => $producto->stock_restante
            ]
        ]);
    }

    // ==================== COLORES ====================
    public function storeColor(Request $request, Producto $producto)
    {
        $validator = Validator::make($request->all(), [
            'nombre_color' => 'required|string|max:50',
            'codigo_color' => 'nullable|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio_adicional' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'disponible' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validar stock
        $stockRestante = $producto->stock_restante;
        if ($request->stock > $stockRestante) {
            return response()->json([
                'errors' => ['stock' => ['El stock no puede exceder el stock restante del producto. Disponible: ' . $stockRestante]]
            ], 422);
        }

        try {
            $data = $request->only(['nombre_color', 'codigo_color', 'precio_adicional', 'stock', 'disponible']);
            $data['producto_id'] = $producto->id;
            $data['disponible'] = $request->disponible ?? 1;

            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('productos/colores', 'public');
                $data['imagen'] = $path;
            }

            $color = ProductoColor::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Color agregado exitosamente',
                'data' => $color
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateColor(Request $request, ProductoColor $color)
    {
        $producto = $color->producto;
        
        $validator = Validator::make($request->all(), [
            'nombre_color' => 'required|string|max:50',
            'codigo_color' => 'nullable|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio_adicional' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'disponible' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Calcular stock disponible (incluyendo el stock actual del color)
        $stockColoresOtros = $producto->colores()->where('id', '!=', $color->id)->sum('stock');
        $stockDisponible = $producto->stock - $stockColoresOtros;
        
        if ($request->stock > $stockDisponible) {
            return response()->json([
                'errors' => ['stock' => ['El stock no puede exceder el stock disponible. Disponible: ' . $stockDisponible]]
            ], 422);
        }

        try {
            $data = $request->only(['nombre_color', 'codigo_color', 'precio_adicional', 'stock', 'disponible']);

            if ($request->hasFile('imagen')) {
                if ($color->imagen && Storage::disk('public')->exists($color->imagen)) {
                    Storage::disk('public')->delete($color->imagen);
                }
                $path = $request->file('imagen')->store('productos/colores', 'public');
                $data['imagen'] = $path;
            }

            $color->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Color actualizado exitosamente',
                'data' => $color
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroyColor(ProductoColor $color)
    {
        try {
            if ($color->imagen && Storage::disk('public')->exists($color->imagen)) {
                Storage::disk('public')->delete($color->imagen);
            }
            $color->delete();
            return response()->json(['success' => true, 'message' => 'Color eliminado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}