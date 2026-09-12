<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProveedorController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return redirect()
                ->route('admin.suministros.index')
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'proveedores')
                ->with('open_modal', 'crear-proveedor');
        }

        Proveedor::create([
            'nombre'    => $request->nombre,
            'nit'       => $request->nit,
            'telefono'  => $request->telefono,
            'ciudad'    => $request->ciudad,
            'direccion' => $request->direccion,
            'estado'    => true,
        ]);

        return redirect()
            ->route('admin.suministros.index')
            ->with('success', 'Proveedor registrado correctamente.')
            ->with('active_tab', 'proveedores');
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($proveedor->id),
            $this->messages()
        );

        if ($validator->fails()) {
            return redirect()
                ->route('admin.suministros.index')
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'proveedores')
                ->with('open_modal', 'editar-proveedor')
                ->with('edit_id', $proveedor->id);
        }

        $proveedor->update([
            'nombre'    => $request->nombre,
            'nit'       => $request->nit,
            'telefono'  => $request->telefono,
            'ciudad'    => $request->ciudad,
            'direccion' => $request->direccion,
        ]);

        return redirect()
            ->route('admin.suministros.index')
            ->with('success', 'Proveedor actualizado correctamente.')
            ->with('active_tab', 'proveedores');
    }

    public function cambiarEstado($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->estado = !$proveedor->estado;
        $proveedor->save();

        $mensaje = $proveedor->estado
            ? 'Proveedor activado correctamente.'
            : 'Proveedor inactivado correctamente.';

        return redirect()
            ->route('admin.suministros.index')
            ->with('success', $mensaje)
            ->with('active_tab', 'proveedores');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()
            ->route('admin.suministros.index')
            ->with('success', 'Proveedor eliminado correctamente.')
            ->with('active_tab', 'proveedores');
    }

    /**
     * Reglas de validación.
     */
    private function rules(?int $ignoreId = null): array
    {
        return [
            'nombre'    => ['required', 'string', 'min:3', 'max:150', 'regex:/^[0-9\pL\s\.\-\&]+$/u'],
            'nit'       => ['required', 'string', 'min:5', 'max:20', 'regex:/^[0-9\-]+$/', 'unique:proveedores,nit' . ($ignoreId ? ',' . $ignoreId : '')],
            'telefono'  => ['required', 'string', 'min:7', 'max:20', 'regex:/^[0-9\+\-\s]+$/'],
            'ciudad'    => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\pL\s\.\-]+$/u'],
            'direccion' => ['required', 'string', 'min:5', 'max:255'],
        ];
    }

    /**
     * Mensajes personalizados en español.
     */
    private function messages(): array
    {
        return [
            // Nombre
            'nombre.required' => 'El nombre del proveedor es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.min'      => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max'      => 'El nombre no puede superar los 150 caracteres.',
            'nombre.regex'    => 'El nombre solo puede contener letras, espacios, puntos, guiones y el símbolo &.',

            // NIT
            'nit.required' => 'El NIT es obligatorio.',
            'nit.string'   => 'El NIT debe ser texto.',
            'nit.min'      => 'El NIT debe tener al menos 5 caracteres.',
            'nit.max'      => 'El NIT no puede superar los 20 caracteres.',
            'nit.regex'    => 'El NIT solo puede contener números y guiones.',
            'nit.unique'   => 'Este NIT ya está registrado con otro proveedor.',

            // Teléfono
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.string'   => 'El teléfono debe ser texto.',
            'telefono.min'      => 'El teléfono debe tener al menos 7 dígitos.',
            'telefono.max'      => 'El teléfono no puede superar los 20 caracteres.',
            'telefono.regex'    => 'El teléfono solo puede contener números, +, - y espacios.',

            // Ciudad
            'ciudad.required' => 'La ciudad es obligatoria.',
            'ciudad.string'   => 'La ciudad debe ser texto.',
            'ciudad.min'      => 'La ciudad debe tener al menos 3 caracteres.',
            'ciudad.max'      => 'La ciudad no puede superar los 100 caracteres.',
            'ciudad.regex'    => 'La ciudad solo puede contener letras, espacios, puntos y guiones.',

            // Dirección
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.string'   => 'La dirección debe ser texto.',
            'direccion.min'      => 'La dirección debe tener al menos 5 caracteres.',
            'direccion.max'      => 'La dirección no puede superar los 255 caracteres.',
        ];
    }
}