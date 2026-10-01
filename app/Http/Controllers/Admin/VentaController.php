<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\VentaMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    /* ============================================================
     * INDEX
     * ============================================================ */
    public function index()
    {
        $ventas = Venta::with(['user', 'detalles'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.ventas.index', compact('ventas'));
    }

    /* ============================================================
     * CREATE
     * ============================================================ */
    public function create()
    {
        $userId = auth()->id();

        // Stock propio del admin logueado
        $stocks = Stock::with(['producto.tipoProducto.categoria'])
            ->where('user_id', $userId)
            ->where('cantidad', '>', 0)
            ->get()
            ->filter(fn($s) => $s->producto !== null)
            ->values();

        return view('admin.ventas.create', compact('stocks'));
    }

    /* ============================================================
     * AJAX: consultar stock de un producto en todos los admins
     * ============================================================ */
    public function stockProducto(Producto $producto)
    {
        $stocks = Stock::with('user')
            ->where('producto_id', $producto->id)
            ->where('cantidad', '>', 0)
            ->whereHas('user.role', fn($q) => $q->where('slug', 'administrador'))
            ->orderBy('cantidad', 'desc')
            ->get()
            ->map(function ($s) {
                return [
                    'user_id'    => $s->user_id,
                    'user_name'  => $s->user->name ?? 'Admin',
                    'cantidad'   => (float) $s->cantidad,
                    'es_propio'  => $s->user_id === auth()->id(),
                ];
            });

        return response()->json([
            'producto' => [
                'id'          => $producto->id,
                'nombre'      => $producto->nombre,
                'unidad_base' => $producto->unidad_base,
            ],
            'stocks' => $stocks,
        ]);
    }

    /* ============================================================
     * STORE — guarda venta como pendiente
     * ============================================================ */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return redirect()
                ->route('admin.ventas.create')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $venta = Venta::create([
                'user_id'           => auth()->id(),
                'numero_venta'      => Venta::generarNumeroVenta(),
                'cliente_nombre'    => $request->cliente_nombre,
                'cliente_documento' => $request->cliente_documento,
                'cliente_telefono'  => $request->cliente_telefono,
                'fecha_venta'       => $request->fecha_venta,
                'subtotal'          => 0,
                'descuento'         => $request->descuento ?? 0,
                'total'             => 0,
                'estado'            => 'pendiente',
                'observaciones'     => $request->observaciones,
            ]);

            $subtotal = 0;

            foreach ($request->items as $item) {
                $cantidad     = (float) $item['cantidad'];
                $precioUnit   = (float) $item['precio_unitario'];
                $lineSubtotal = $cantidad * $precioUnit;
                $subtotal    += $lineSubtotal;

                $detalle = VentaDetalle::create([
                    'venta_id'        => $venta->id,
                    'producto_id'     => $item['producto_id'],
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precioUnit,
                    'subtotal'        => $lineSubtotal,
                ]);

                // Guardar los movimientos (de qué admins se va a tomar el stock)
                if (!empty($item['movimientos'])) {
                    foreach ($item['movimientos'] as $mov) {
                        $cantMov = (float) $mov['cantidad'];

                        if ($cantMov <= 0) continue;

                        VentaMovimiento::create([
                            'venta_detalle_id' => $detalle->id,
                            'stock_user_id'    => $mov['stock_user_id'],
                            'cantidad'         => $cantMov,
                        ]);
                    }
                }
            }

            $venta->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal - ($request->descuento ?? 0),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.ventas.index')
                ->with('success', 'Venta registrada como pendiente. Revísala y confírmala para descontar stock.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.ventas.create')
                ->withInput()
                ->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    /* ============================================================
     * SHOW
     * ============================================================ */
    public function show(Venta $venta)
    {
        $venta->load([
            'user',
            'detalles.producto.tipoProducto.categoria',
            'detalles.movimientos.stockUser',
        ]);

        return view('admin.ventas.show', compact('venta'));
    }

    /* ============================================================
     * EDIT — solo si está pendiente
     * ============================================================ */
    public function edit(Venta $venta)
    {
        if ($venta->estado !== 'pendiente') {
            return redirect()
                ->route('admin.ventas.index')
                ->with('error', 'Solo se pueden editar ventas en estado pendiente.');
        }

        if ($venta->user_id !== auth()->id()) {
            abort(403, 'No puedes editar ventas de otros administradores.');
        }

        $venta->load('detalles.movimientos');

        $userId = auth()->id();

        $stocks = Stock::with(['producto.tipoProducto.categoria'])
            ->where('user_id', $userId)
            ->where('cantidad', '>', 0)
            ->get()
            ->filter(fn($s) => $s->producto !== null)
            ->values();

        return view('admin.ventas.edit', compact('venta', 'stocks'));
    }

    /* ============================================================
     * UPDATE
     * ============================================================ */
    public function update(Request $request, Venta $venta)
    {
        if ($venta->estado !== 'pendiente') {
            return redirect()
                ->route('admin.ventas.index')
                ->with('error', 'Solo se pueden editar ventas en estado pendiente.');
        }

        if ($venta->user_id !== auth()->id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return redirect()
                ->route('admin.ventas.edit', $venta)
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $venta->update([
                'cliente_nombre'    => $request->cliente_nombre,
                'cliente_documento' => $request->cliente_documento,
                'cliente_telefono'  => $request->cliente_telefono,
                'fecha_venta'       => $request->fecha_venta,
                'descuento'         => $request->descuento ?? 0,
                'observaciones'     => $request->observaciones,
            ]);

            // Borrar detalles y movimientos previos
            $venta->detalles()->delete();

            $subtotal = 0;

            foreach ($request->items as $item) {
                $cantidad     = (float) $item['cantidad'];
                $precioUnit   = (float) $item['precio_unitario'];
                $lineSubtotal = $cantidad * $precioUnit;
                $subtotal    += $lineSubtotal;

                $detalle = VentaDetalle::create([
                    'venta_id'        => $venta->id,
                    'producto_id'     => $item['producto_id'],
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precioUnit,
                    'subtotal'        => $lineSubtotal,
                ]);

                if (!empty($item['movimientos'])) {
                    foreach ($item['movimientos'] as $mov) {
                        $cantMov = (float) $mov['cantidad'];

                        if ($cantMov <= 0) continue;

                        VentaMovimiento::create([
                            'venta_detalle_id' => $detalle->id,
                            'stock_user_id'    => $mov['stock_user_id'],
                            'cantidad'         => $cantMov,
                        ]);
                    }
                }
            }

            $venta->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal - ($request->descuento ?? 0),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.ventas.index')
                ->with('success', 'Venta actualizada correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.ventas.edit', $venta)
                ->withInput()
                ->with('error', 'Error al actualizar la venta: ' . $e->getMessage());
        }
    }

    /* ============================================================
     * CONFIRMAR — descuenta stock + notifica
     * ============================================================ */
    public function confirmar(Venta $venta)
    {
        if ($venta->estado !== 'pendiente') {
            return back()->with('error', 'Solo se pueden confirmar ventas pendientes.');
        }

        if ($venta->user_id !== auth()->id()) {
            abort(403);
        }

        DB::beginTransaction();

        try {
            $venta->load('detalles.movimientos.stockUser');

            // 1. Validar que todos los admins tengan stock suficiente
            foreach ($venta->detalles as $detalle) {
                foreach ($detalle->movimientos as $mov) {
                    $stock = Stock::where('user_id', $mov->stock_user_id)
                        ->where('producto_id', $detalle->producto_id)
                        ->first();

                    $disponible = $stock ? (float) $stock->cantidad : 0;

                    if ($disponible < (float) $mov->cantidad) {
                        throw new \Exception(
                            'El admin ' . ($mov->stockUser->name ?? '') .
                            ' ya no tiene suficiente stock de ' .
                            ($detalle->producto->nombre ?? '') .
                            '. Disponible: ' . $disponible .
                            ', requerido: ' . $mov->cantidad
                        );
                    }
                }
            }

            // 2. Descontar stock y notificar
            $notificados = [];

            foreach ($venta->detalles as $detalle) {
                foreach ($detalle->movimientos as $mov) {
                    $stock = Stock::where('user_id', $mov->stock_user_id)
                        ->where('producto_id', $detalle->producto_id)
                        ->first();

                    if ($stock) {
                        $stock->decrement('cantidad', (float) $mov->cantidad);
                    }

                    // Notificar solo si es de otro admin y no lo hemos notificado ya
                    if ($mov->stock_user_id !== $venta->user_id) {
                        $key = $mov->stock_user_id . '-' . $detalle->producto_id;

                        if (!isset($notificados[$key])) {
                            Notificacion::create([
                                'user_id'      => $mov->stock_user_id,
                                'from_user_id' => $venta->user_id,
                                'tipo'         => 'stock_prestado',
                                'titulo'       => 'Se tomó stock de tu inventario',
                                'mensaje'      => auth()->user()->name . ' usó ' . $mov->cantidad . ' ' .
                                                  ($detalle->producto->unidad_base ?? 'unidades') . ' de ' .
                                                  $detalle->producto->nombre . ' para la venta ' .
                                                  $venta->numero_venta . '.',
                                'venta_id'     => $venta->id,
                            ]);

                            $notificados[$key] = true;
                        }
                    }
                }
            }

            $venta->update(['estado' => 'vendida']);

            DB::commit();

            return redirect()
                ->route('admin.ventas.index')
                ->with('success', 'Venta confirmada. El stock fue descontado correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /* ============================================================
     * CANCELAR — devuelve stock si estaba vendida
     * ============================================================ */
    public function cancelar(Venta $venta)
    {
        if ($venta->estado === 'cancelada') {
            return back()->with('error', 'Esta venta ya está cancelada.');
        }

        if ($venta->user_id !== auth()->id()) {
            abort(403);
        }

        DB::beginTransaction();

        try {
            // Si estaba vendida → devolver stock
            if ($venta->estado === 'vendida') {
                $venta->load('detalles.movimientos.stockUser');

                $notificados = [];

                foreach ($venta->detalles as $detalle) {
                    foreach ($detalle->movimientos as $mov) {
                        Stock::incrementar(
                            $mov->stock_user_id,
                            $detalle->producto_id,
                            (float) $mov->cantidad
                        );

                        if ($mov->stock_user_id !== $venta->user_id) {
                            $key = $mov->stock_user_id . '-' . $detalle->producto_id;

                            if (!isset($notificados[$key])) {
                                Notificacion::create([
                                    'user_id'      => $mov->stock_user_id,
                                    'from_user_id' => $venta->user_id,
                                    'tipo'         => 'stock_devuelto',
                                    'titulo'       => 'Se devolvió stock a tu inventario',
                                    'mensaje'      => 'La venta ' . $venta->numero_venta . ' fue cancelada. Se te devolvieron ' .
                                                      $mov->cantidad . ' ' . ($detalle->producto->unidad_base ?? 'unidades') .
                                                      ' de ' . $detalle->producto->nombre . '.',
                                    'venta_id'     => $venta->id,
                                ]);

                                $notificados[$key] = true;
                            }
                        }
                    }
                }
            }

            $venta->update(['estado' => 'cancelada']);

            DB::commit();

            $mensaje = $venta->estado === 'vendida'
                ? 'Venta cancelada. El stock fue devuelto a los administradores.'
                : 'Venta cancelada.';

            return redirect()
                ->route('admin.ventas.index')
                ->with('success', $mensaje);

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Error al cancelar la venta: ' . $e->getMessage());
        }
    }

    /* ============================================================
     * DESTROY — solo si está pendiente o cancelada
     * ============================================================ */
    public function destroy(Venta $venta)
    {
        if ($venta->estado === 'vendida') {
            return redirect()
                ->route('admin.ventas.index')
                ->with('error', 'No se puede eliminar una venta ya confirmada. Cancélala primero.');
        }

        if ($venta->user_id !== auth()->id()) {
            abort(403);
        }

        $venta->delete();

        return redirect()
            ->route('admin.ventas.index')
            ->with('success', 'Venta eliminada correctamente.');
    }

    /* ============================================================
     * VALIDACIÓN
     * ============================================================ */
    private function rules(): array
    {
        return [
            'cliente_nombre'    => ['required', 'string', 'max:150'],
            'cliente_documento' => ['required', 'string', 'max:30'],
            'cliente_telefono'  => ['nullable', 'string', 'max:30'],
            'fecha_venta'       => ['required', 'date'],
            'descuento'         => ['nullable', 'numeric', 'min:0'],
            'observaciones'     => ['nullable', 'string', 'max:1000'],

            'items'                          => ['required', 'array', 'min:1'],
            'items.*.producto_id'            => ['required', 'integer', 'exists:productos,id'],
            'items.*.cantidad'               => ['required', 'numeric', 'min:0.01'],
            'items.*.precio_unitario'        => ['required', 'numeric', 'min:0.01'],
            'items.*.movimientos'            => ['required', 'array', 'min:1'],
            'items.*.movimientos.*.stock_user_id' => ['required', 'integer', 'exists:users,id'],
            'items.*.movimientos.*.cantidad'      => ['required', 'numeric', 'min:0.01'],
        ];
    }

    private function messages(): array
    {
        return [
            'cliente_nombre.required'    => 'El nombre del cliente es obligatorio.',
            'cliente_documento.required' => 'El CI/NIT del cliente es obligatorio.',
            'fecha_venta.required'       => 'La fecha de venta es obligatoria.',

            'items.required' => 'Debes agregar al menos un producto a la venta.',
            'items.min'      => 'Debes agregar al menos un producto a la venta.',

            'items.*.producto_id.required' => 'Selecciona un producto en cada línea.',
            'items.*.producto_id.exists'   => 'El producto seleccionado no es válido.',

            'items.*.cantidad.required' => 'La cantidad es obligatoria en cada línea.',
            'items.*.cantidad.min'      => 'La cantidad debe ser mayor a 0.',

            'items.*.precio_unitario.required' => 'El precio unitario es obligatorio.',
            'items.*.precio_unitario.min'      => 'El precio unitario debe ser mayor a 0.',

            'items.*.movimientos.required' => 'Debes especificar de qué admin(es) se tomará el stock.',
            'items.*.movimientos.min'      => 'Debes especificar al menos un origen de stock.',
        ];
    }
}