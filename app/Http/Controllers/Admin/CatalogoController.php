<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatalogoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('tipoProducto.categoria')
            ->orderBy('nombre')
            ->get();

        return view('admin.catalogo.index', compact('productos'));
    }

    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'imagen_catalogo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ]);

        $imagenAnterior = $producto->imagen_catalogo;
        $imagenNueva = null;

        if ($request->hasFile('imagen_catalogo')) {
            $imagenNueva = $request->file('imagen_catalogo')
                ->store('catalogo', 'public');

            $datos['imagen_catalogo'] = $imagenNueva;
        } else {
            unset($datos['imagen_catalogo']);
        }

        try {
            $producto->update($datos);
        } catch (\Throwable $e) {
            if ($imagenNueva) {
                Storage::disk('public')->delete($imagenNueva);
            }

            throw $e;
        }

        if ($imagenNueva && $imagenAnterior) {
            Storage::disk('public')->delete($imagenAnterior);
        }

        return back()->with(
            'success',
            'Foto del producto actualizada correctamente.'
        );
    }

    public function cambiarVisibilidad(Producto $producto)
    {
        $producto->update([
            'visible_catalogo' => !$producto->visible_catalogo,
        ]);

        return back()->with(
            'success',
            'Visibilidad del producto actualizada correctamente.'
        );
    }
}
