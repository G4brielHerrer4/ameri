<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with(['user', 'proveedor'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.compras.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = Proveedor::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $admins = User::whereHas('role', function ($q) {
            $q->where('slug', 'administrador');
        })->orderBy('name')->get();

        $productos = Producto::with(['tipoProducto.categoria'])
            ->orderBy('nombre')
            ->get();

        return view('admin.compras.create', compact(
            'proveedores',
            'admins',
            'productos'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return redirect()
                ->route('admin.compras.create')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $compra = Compra::create([
                'user_id'       => $request->user_id,
                'proveedor_id'  => $request->proveedor_id,
                'numero_orden'  => Compra::generarNumeroOrden(),
                'fecha_compra'  => $request->fecha_compra,
                'subtotal'      => 0,
                'descuento'     => $request->descuento ?? 0,
                'total'         => 0,
                'estado'        => 'pendiente',
                'observaciones' => $request->observaciones,
            ]);

            $subtotal = 0;

            foreach ($request->items as $item) {
                $cantidad      = (float) $item['cantidad'];
                $precioUnit    = (float) $item['precio_unitario'];
                $unidadCompra  = $item['unidad_compra'] ?? 'unidad';
                $upp           = (float) ($item['unidades_por_paquete'] ?? 1);
                $cantidadTotal = $cantidad * $upp;
                $lineSubtotal  = $cantidad * $precioUnit;
                $subtotal     += $lineSubtotal;

                CompraDetalle::create([
                    'compra_id'            => $compra->id,
                    'producto_id'          => $item['producto_id'],
                    'cantidad'             => $cantidad,
                    'unidad_compra'        => $unidadCompra,
                    'unidades_por_paquete' => $upp,
                    'cantidad_total'       => $cantidadTotal,
                    'precio_unitario'      => $precioUnit,
                    'subtotal'             => $lineSubtotal,
                ]);
            }

            $compra->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal - ($request->descuento ?? 0),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.compras.index')
                ->with('success', 'Compra registrada correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.compras.create')
                ->withInput()
                ->with('error', 'Error al registrar la compra: ' . $e->getMessage());
        }
    }

    public function show(Compra $compra)
    {
        $compra->load([
            'user',
            'proveedor',
            'detalles.producto.tipoProducto.categoria',
        ]);

        return view('admin.compras.show', compact('compra'));
    }

    public function edit(Compra $compra)
    {
        if ($compra->estado === 'recibida') {
            return redirect()
                ->route('admin.compras.index')
                ->with('error', 'No se puede editar una compra ya recibida.');
        }

        $compra->load('detalles');

        $proveedores = Proveedor::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $admins = User::whereHas('role', function ($q) {
            $q->where('slug', 'administrador');
        })->orderBy('name')->get();

        $productos = Producto::with(['tipoProducto.categoria'])
            ->orderBy('nombre')
            ->get();

        return view('admin.compras.edit', compact(
            'compra',
            'proveedores',
            'admins',
            'productos'
        ));
    }

    public function update(Request $request, Compra $compra)
    {
        if ($compra->estado === 'recibida') {
            return redirect()
                ->route('admin.compras.index')
                ->with('error', 'No se puede modificar una compra ya recibida.');
        }

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return redirect()
                ->route('admin.compras.edit', $compra)
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $compra->update([
                'user_id'       => $request->user_id,
                'proveedor_id'  => $request->proveedor_id,
                'fecha_compra'  => $request->fecha_compra,
                'descuento'     => $request->descuento ?? 0,
                'observaciones' => $request->observaciones,
            ]);

            // Borrar detalles previos y recrear
            $compra->detalles()->delete();

            $subtotal = 0;

            foreach ($request->items as $item) {
                $cantidad      = (float) $item['cantidad'];
                $precioUnit    = (float) $item['precio_unitario'];
                $unidadCompra  = $item['unidad_compra'] ?? 'unidad';
                $upp           = (float) ($item['unidades_por_paquete'] ?? 1);
                $cantidadTotal = $cantidad * $upp;
                $lineSubtotal  = $cantidad * $precioUnit;
                $subtotal     += $lineSubtotal;

                CompraDetalle::create([
                    'compra_id'            => $compra->id,
                    'producto_id'          => $item['producto_id'],
                    'cantidad'             => $cantidad,
                    'unidad_compra'        => $unidadCompra,
                    'unidades_por_paquete' => $upp,
                    'cantidad_total'       => $cantidadTotal,
                    'precio_unitario'      => $precioUnit,
                    'subtotal'             => $lineSubtotal,
                ]);
            }

            $compra->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal - ($request->descuento ?? 0),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.compras.index')
                ->with('success', 'Compra actualizada correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.compras.edit', $compra)
                ->withInput()
                ->with('error', 'Error al actualizar la compra: ' . $e->getMessage());
        }
    }

    public function cambiarEstado(Compra $compra)
    {
        DB::beginTransaction();

        try {
            $estadoAnterior = $compra->estado;
            $nuevoEstado    = request('estado');

            if (!in_array($nuevoEstado, ['pendiente', 'recibida', 'cancelada'])) {
                return back()->with('error', 'Estado no válido.');
            }

            // Si estaba recibida y sale de recibida → restar stock
            if ($estadoAnterior === 'recibida' && $nuevoEstado !== 'recibida') {
                foreach ($compra->detalles as $detalle) {
                    Stock::decrementar(
                        $compra->user_id,
                        $detalle->producto_id,
                        (float) $detalle->cantidad_total
                    );
                }
            }

            // Si no estaba recibida y ahora sí → sumar stock
            if ($estadoAnterior !== 'recibida' && $nuevoEstado === 'recibida') {
                foreach ($compra->detalles as $detalle) {
                    Stock::incrementar(
                        $compra->user_id,
                        $detalle->producto_id,
                        (float) $detalle->cantidad_total
                    );
                }
            }

            $compra->update(['estado' => $nuevoEstado]);

            DB::commit();

            return redirect()
                ->route('admin.compras.index')
                ->with('success', 'Estado actualizado correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.compras.index')
                ->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function destroy(Compra $compra)
    {
        if ($compra->estado === 'recibida') {
            return redirect()
                ->route('admin.compras.index')
                ->with('error', 'No se puede eliminar una compra recibida. Cámbiala a cancelada primero.');
        }

        $compra->delete();

        return redirect()
            ->route('admin.compras.index')
            ->with('success', 'Compra eliminada correctamente.');
    }

    private function rules(): array
    {
        return [
            'user_id'       => ['required', 'integer', 'exists:users,id'],
            'proveedor_id'  => ['required', 'integer', 'exists:proveedores,id'],
            'fecha_compra'  => ['required', 'date'],
            'descuento'     => ['nullable', 'numeric', 'min:0'],
            'observaciones' => ['nullable', 'string', 'max:1000'],

            'items'                          => ['required', 'array', 'min:1'],
            'items.*.producto_id'            => ['required', 'integer', 'exists:productos,id'],
            'items.*.cantidad'               => ['required', 'numeric', 'min:0.01'],
            'items.*.unidad_compra'          => ['required', 'string', 'in:caja,bolsa,pieza,unidad,kg,m2'],
            'items.*.unidades_por_paquete'   => ['required', 'numeric', 'min:0.01'],
            'items.*.precio_unitario'        => ['required', 'numeric', 'min:0.01'],
        ];
    }

    private function messages(): array
    {
        return [
            'user_id.required'      => 'Debes seleccionar el administrador que registra la compra.',
            'user_id.exists'        => 'El administrador seleccionado no es válido.',
            'proveedor_id.required' => 'Debes seleccionar un proveedor.',
            'proveedor_id.exists'   => 'El proveedor seleccionado no es válido.',
            'fecha_compra.required' => 'La fecha de compra es obligatoria.',
            'fecha_compra.date'     => 'La fecha de compra no es válida.',

            'items.required' => 'Debes agregar al menos un producto a la compra.',
            'items.min'      => 'Debes agregar al menos un producto a la compra.',

            'items.*.producto_id.required' => 'Selecciona un producto en cada línea.',
            'items.*.producto_id.exists'   => 'El producto seleccionado no es válido.',

            'items.*.cantidad.required' => 'La cantidad es obligatoria en cada línea.',
            'items.*.cantidad.min'      => 'La cantidad debe ser mayor a 0.',

            'items.*.unidad_compra.required' => 'La unidad de compra es obligatoria.',
            'items.*.unidad_compra.in'       => 'La unidad de compra no es válida.',

            'items.*.unidades_por_paquete.required' => 'Las unidades por paquete son obligatorias.',
            'items.*.unidades_por_paquete.min'      => 'Las unidades por paquete deben ser mayor a 0.',

            'items.*.precio_unitario.required' => 'El precio unitario es obligatorio.',
            'items.*.precio_unitario.min'      => 'El precio unitario debe ser mayor a 0.',
        ];
    }
}