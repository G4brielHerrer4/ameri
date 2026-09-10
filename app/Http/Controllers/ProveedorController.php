<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::orderBy('id', 'desc')->get();

        return view('admin.proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('admin.proveedores.create');
    }

   public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'nit' => 'required|numeric|digits_between:1,20|unique:proveedores,nit',
            'telefono' => 'required|numeric|digits_between:7,20',
            'ciudad' => 'required|string|max:100',
            'direccion' => 'required|string|max:255',
        ]);

        Proveedor::create([
            'nombre' => $request->nombre,
            'nit' => $request->nit,
            'telefono' => $request->telefono,
            'ciudad' => $request->ciudad,
            'direccion' => $request->direccion,
            'estado' => true,
        ]);

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);

        return view('admin.proveedores.edit', compact('proveedor'));
    }

   public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:150',
            'nit' => 'required|numeric|digits_between:1,20|unique:proveedores,nit,' . $proveedor->id,
            'telefono' => 'required|numeric|digits_between:7,20',
            'ciudad' => 'required|string|max:100',
            'direccion' => 'required|string|max:255',
        ]);

        $proveedor->update([
            'nombre' => $request->nombre,
            'nit' => $request->nit,
            'telefono' => $request->telefono,
            'ciudad' => $request->ciudad,
            'direccion' => $request->direccion,
        ]);

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
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
            ->route('proveedores.index')
            ->with('success', $mensaje);
    }
}