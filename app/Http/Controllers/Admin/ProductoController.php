<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return redirect()
                ->route('admin.suministros.index')
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'productos')
                ->with('open_modal', 'crear-producto');
        }

        Producto::create([
            'nombre'       => $request->nombre,
            'modelo'       => $request->modelo,
            'medida'       => $request->medida,
            'color'        => $request->color,
            'acabado'      => $request->acabado,
            'presentacion' => $request->presentacion,
        ]);

        return redirect()
            ->route('admin.suministros.index')
            ->with('success', 'Producto creado correctamente.')
            ->with('active_tab', 'productos');
    }

    public function update(Request $request, Producto $producto)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return redirect()
                ->route('admin.suministros.index')
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'productos')
                ->with('open_modal', 'editar-producto')
                ->with('edit_id', $producto->id);
        }

        $producto->update([
            'nombre'       => $request->nombre,
            'modelo'       => $request->modelo,
            'medida'       => $request->medida,
            'color'        => $request->color,
            'acabado'      => $request->acabado,
            'presentacion' => $request->presentacion,
        ]);

        return redirect()
            ->route('admin.suministros.index')
            ->with('success', 'Producto actualizado correctamente.')
            ->with('active_tab', 'productos');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()
            ->route('admin.suministros.index')
            ->with('success', 'Producto eliminado correctamente.')
            ->with('active_tab', 'productos');
    }

    /**
     * Reglas de validación.
     */
    private function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'min:2', 'max:255'],
            'modelo'       => ['nullable', 'string', 'max:100'],
            'medida'       => ['required', 'string', 'min:2', 'max:100', 'regex:/^[0-9a-zA-Z\sxX×\.\,]+$/u'],
            'color'        => ['nullable', 'string', 'max:80', 'regex:/^[\pL\s\.\-]+$/u'],
            'acabado'      => ['nullable', 'string', 'max:80'],
            'presentacion' => ['nullable', 'string', 'max:120'],
        ];
    }

    /**
     * Mensajes personalizados en español.
     */
    private function messages(): array
    {
        return [
            // Nombre
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.min'      => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max'      => 'El nombre no puede superar los 255 caracteres.',

            // Modelo
            'modelo.string' => 'El modelo debe ser texto.',
            'modelo.max'    => 'El modelo no puede superar los 100 caracteres.',

            // Medida
            'medida.required' => 'La medida es obligatoria.',
            'medida.string'   => 'La medida debe ser texto.',
            'medida.min'      => 'La medida debe tener al menos 2 caracteres.',
            'medida.max'      => 'La medida no puede superar los 100 caracteres.',
            'medida.regex'    => 'La medida solo puede contener números, letras, x y separadores (ej: 60x60 cm).',

            // Color
            'color.string' => 'El color debe ser texto.',
            'color.max'    => 'El color no puede superar los 80 caracteres.',
            'color.regex'  => 'El color solo puede contener letras, espacios, puntos y guiones.',

            // Acabado
            'acabado.string' => 'El acabado debe ser texto.',
            'acabado.max'    => 'El acabado no puede superar los 80 caracteres.',

            // Presentación
            'presentacion.string' => 'La presentación debe ser texto.',
            'presentacion.max'    => 'La presentación no puede superar los 120 caracteres.',
        ];
    }
}