<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\VentaMovimiento;
use App\Models\User;
use App\Models\Deposito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    public function index()
    {
        $consulta = Venta::with(['user', 'detalles']);

        if (auth()->user()->role?->slug === 'vendedor') {
            $consulta->where('user_id', auth()->id());
        }

        $ventas = $consulta->orderBy('id', 'desc')->get();

        if (auth()->user()->role?->slug === 'vendedor') {
            return view('vendedor.ventas.index', compact('ventas'));
        }

        return view('admin.ventas.index', compact('ventas'));
    }

    public function create()
    {
        $stocks = Stock::with(['producto.tipoProducto.categoria'])
            ->where('cantidad', '>', 0)
            ->whereHas('user.role', function ($q) {
                $q->where('slug', 'administrador');
            })
            ->get()
            ->filter(fn($s) => $s->producto !== null)
            ->groupBy('producto_id')
            ->map(function ($grupo) {
                $primero = $grupo->first();
                $primero->cantidad = $grupo->sum(fn($stock) => (float) $stock->cantidad);
                return $primero;
            })
            ->values();

        $admins = User::whereHas('role', function ($q) {
            $q->where('slug', 'administrador');
        })->where('id', '!=', auth()->id())->orderBy('name')->get();

        if (auth()->user()->role?->slug === 'vendedor') {
            return view('vendedor.ventas.create', compact('stocks', 'admins'));
        }

        return view('admin.ventas.create', compact('stocks', 'admins'));
    }

    public function stockProducto(Producto $producto)
    {
        $producto->load('tipoProducto');
        $unidadesVenta = $this->unidadesVenta($producto);
        $esVendedor = auth()->user()->role?->slug === 'vendedor';

        // 🔥 AGRUPAMOS POR ADMIN Y TIENDA (depósito)
        $stocks = Stock::with(['user', 'deposito'])
            ->where('producto_id', $producto->id)
            ->where('cantidad', '>', 0)
            ->whereHas('user.role', function ($q) {
                $q->where('slug', 'administrador');
            })
            ->get()
            ->groupBy(function($item) {
                return $item->user_id . '-' . $item->deposito_id;
            })
            ->map(function ($grupo) use ($producto, $unidadesVenta, $esVendedor) {
                $primero = $grupo->first();
                $cantidadFisica = $grupo->sum('cantidad');

                $cajasDisponibles = null;
                $m2Disponibles = null;
                $tipo = mb_strtolower(trim($producto->tipoProducto?->nombre ?? ''), 'UTF-8');

                if (in_array($tipo, ['cerámica', 'ceramica', 'porcelanato'], true) && (int) $producto->piezas_por_caja > 0) {
                    $piezasPorCaja = (int) $producto->piezas_por_caja;
                    $cajasDisponibles = floor($cantidadFisica / $piezasPorCaja);
                    if ((float) $producto->m2_por_caja > 0) {
                        $m2Disponibles = ($cantidadFisica / $piezasPorCaja) * (float) $producto->m2_por_caja;
                    }
                }

                return [
                    'user_id'           => $primero->user_id,
                    'user_name'         => $primero->user->name ?? 'Administrador',
                    'deposito_id'       => $primero->deposito_id, // 🔥 NUEVO
                    'deposito_nombre'   => $primero->deposito->nombre ?? 'Tienda', // 🔥 NUEVO
                    'cantidad'          => $cantidadFisica,
                    'unidad_stock'      => $producto->unidad_base,
                    'cajas_disponibles' => $cajasDisponibles,
                    'm2_disponibles'    => $m2Disponibles,
                    'es_propio'         => !$esVendedor && (int) $primero->user_id === (int) auth()->id(),
                ];
            })->values();

        return response()->json([
            'producto' => [
                'id'              => $producto->id,
                'nombre'          => $producto->nombre,
                'tipo'            => $producto->tipoProducto?->nombre,
                'unidad_base'     => $producto->unidad_base,
                'unidades_venta'  => $unidadesVenta,
                'piezas_por_caja' => (int) ($producto->piezas_por_caja ?? 0),
                'm2_por_caja'     => (float) ($producto->m2_por_caja ?? 0),
                'precios_venta' => [
                    'caja' => $producto->precio_venta_caja !== null ? (float) $producto->precio_venta_caja : null,
                    'm2' => $producto->precio_venta_m2 !== null ? (float) $producto->precio_venta_m2 : null,
                    'pieza' => $producto->precio_venta_pieza !== null ? (float) $producto->precio_venta_pieza : null,
                    'bolsa' => $producto->precio_venta_bolsa !== null ? (float) $producto->precio_venta_bolsa : null,
                ],
            ],
            'stocks' => $stocks,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return redirect()->route('admin.ventas.create')->withErrors($validator)->withInput();
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
                $producto = Producto::with('tipoProducto')->findOrFail($item['producto_id']);
                $cantidad = (float) $item['cantidad'];
                $unidad = $item['unidad'];

                $tipo = mb_strtolower(trim((string) ($producto->tipoProducto?->nombre ?? '')), 'UTF-8');
                $esCeramica = in_array($tipo, ['cerámica', 'ceramica', 'porcelanato'], true);

                $conversion = $this->calcularCantidadStock($producto, $cantidad, $unidad);
                $cantidadStock = (float) $conversion['cantidad_stock'];

                if ($esCeramica && $unidad === 'm2') {
                    $m2PorCaja = (float) ($producto->m2_por_caja ?? 0);
                    $piezasPorCaja = (int) ($producto->piezas_por_caja ?? 0);
                    if ($m2PorCaja <= 0 || $piezasPorCaja <= 0) throw new \Exception('Faltan m² o piezas por caja.');
                    $m2PorPieza = $m2PorCaja / $piezasPorCaja;
                    $cantidadStock = (int) ceil(($cantidad / $m2PorPieza) - 0.000000001);
                }

                $movimientos = $item['movimientos'] ?? [];
                foreach ($movimientos as $mov) {
                    $stockUsuario = Stock::where('user_id', $mov['stock_user_id'])
                        ->where('producto_id', $producto->id)
                        ->where('deposito_id', $mov['deposito_id']) // 🔥 Validamos la tienda
                        ->whereHas('user.role', function ($q) { $q->where('slug', 'administrador'); })
                        ->first();

                    if (!$stockUsuario || (float) $stockUsuario->cantidad < (float) $mov['cantidad']) {
                        throw new \Exception('No hay suficiente stock en la tienda seleccionada para ' . $producto->nombre);
                    }
                }

                $totalMovimientos = collect($movimientos)->sum(fn($mov) => (float) ($mov['cantidad'] ?? 0));
                if (abs($totalMovimientos - $cantidadStock) > 0.01) {
                    throw new \Exception('El stock asignado no coincide con la cantidad física requerida.');
                }

                $calculoPrecio = $this->calcularPrecioLinea($producto, $cantidad, $unidad);
                $precioUnit = $calculoPrecio['precio_unitario'];
                $lineSubtotal = $calculoPrecio['subtotal'];

                $cantidadRegistrada = $cantidad;
                $unidadRegistrada = $unidad;
                if ($esCeramica && $unidad === 'm2') { $cantidadRegistrada = $cantidadStock; $unidadRegistrada = 'pieza'; }

                $subtotal += $lineSubtotal;

                $detalle = VentaDetalle::create([
                    'venta_id'        => $venta->id,
                    'producto_id'     => $producto->id,
                    'cantidad'        => $cantidadRegistrada,
                    'unidad'          => $unidadRegistrada,
                    'cantidad_stock'  => $cantidadStock,
                    'precio_unitario' => $precioUnit,
                    'subtotal'        => $lineSubtotal,
                ]);

                foreach ($movimientos as $mov) {
                    $cantMov = (float) ($mov['cantidad'] ?? 0);
                    if ($cantMov <= 0) continue;

                    VentaMovimiento::create([
                        'venta_detalle_id' => $detalle->id,
                        'stock_user_id'    => $mov['stock_user_id'],
                        'deposito_id'      => $mov['deposito_id'], // 🔥 Guardamos la tienda exacta
                        'cantidad'         => $cantMov,
                    ]);
                }
            }

            $descuento = (float) ($request->descuento ?? 0);
            if ($descuento < 0 || $descuento > $subtotal) throw new \Exception('El descuento no puede superar el subtotal.');

            $venta->update([
                'subtotal' => round($subtotal, 2),
                'total'    => round(max(0, $subtotal - $descuento), 2),
            ]);

            DB::commit();
            return redirect()->route($this->rutaVentas('index'))->with('success', 'Venta registrada como pendiente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route($this->rutaVentas('create'))->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show(Venta $venta)
    {
        $rol = auth()->user()->role?->slug;
        if ($rol === 'vendedor' && (int) $venta->user_id !== (int) auth()->id()) abort(403);

        $venta->load([
            'user',
            'detalles.producto.tipoProducto.categoria',
            'detalles.movimientos.stockUser',
            'detalles.movimientos.deposito', // 🔥 Cargamos la tienda
        ]);

        $vista = $rol === 'vendedor' ? 'vendedor.ventas.show' : 'admin.ventas.show';
        return view($vista, compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $venta->load('detalles.movimientos');
        $stocks = Stock::with(['producto.tipoProducto.categoria'])->where('cantidad', '>', 0)->get()->filter(fn($s) => $s->producto !== null)->groupBy('producto_id')->map(function ($grupo) {
            $primero = $grupo->first();
            $primero->cantidad = $grupo->sum(fn($stock) => (float) $stock->cantidad);
            return $primero;
        })->values();

        $admins = User::whereHas('role', function ($q) { $q->where('slug', 'administrador'); })->where('id', '!=', auth()->id())->orderBy('name')->get();

        $lineasExistentes = $venta->detalles->map(function ($detalle) {
            return [
                'producto_id' => (int) $detalle->producto_id,
                'cantidad' => (float) $detalle->cantidad,
                'unidad' => $detalle->unidad,
                'precio_unitario' => (float) $detalle->precio_unitario,
                'movimientos' => $detalle->movimientos->map(function ($mov) {
                    return [
                        'stock_user_id' => (int) $mov->stock_user_id,
                        'deposito_id' => (int) $mov->deposito_id, // 🔥
                        'cantidad' => (float) $mov->cantidad,
                    ];
                })->values()->all(),
            ];
        })->values()->all();

        $vista = auth()->user()->role?->slug === 'vendedor' ? 'vendedor.ventas.edit' : 'admin.ventas.edit';
        return view($vista, compact('venta', 'stocks', 'admins', 'lineasExistentes'));
    }

    public function update(Request $request, Venta $venta)
    {
        if ($venta->estado !== 'pendiente') return redirect()->route($this->rutaVentas('index'))->with('error', 'Solo se editan ventas pendientes.');
        if ($venta->user_id !== auth()->id()) abort(403);

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) return redirect()->route($this->rutaVentas('edit'), $venta)->withErrors($validator)->withInput();

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

            $venta->detalles()->delete();
            $subtotal = 0;

            foreach ($request->items as $item) {
                $producto = Producto::with('tipoProducto')->findOrFail($item['producto_id']);
                $cantidad = (float) $item['cantidad'];
                $unidad = $item['unidad'];
                $tipo = mb_strtolower(trim((string) ($producto->tipoProducto?->nombre ?? '')), 'UTF-8');
                $esCeramica = in_array($tipo, ['cerámica', 'ceramica', 'porcelanato'], true);

                $conversion = $this->calcularCantidadStock($producto, $cantidad, $unidad);
                $cantidadStock = (float) $conversion['cantidad_stock'];

                if ($esCeramica && $unidad === 'm2') {
                    $m2PorCaja = (float) ($producto->m2_por_caja ?? 0);
                    $piezasPorCaja = (int) ($producto->piezas_por_caja ?? 0);
                    $m2PorPieza = $m2PorCaja / $piezasPorCaja;
                    $cantidadStock = (int) ceil(($cantidad / $m2PorPieza) - 0.000000001);
                }

                $movimientos = $item['movimientos'] ?? [];
                foreach ($movimientos as $mov) {
                    $stockUsuario = Stock::with('user')->where('user_id', $mov['stock_user_id'])
                        ->where('producto_id', $producto->id)
                        ->where('deposito_id', $mov['deposito_id']) // 🔥
                        ->first();
                    if (!$stockUsuario || (float) $stockUsuario->cantidad < (float) $mov['cantidad']) {
                        throw new \Exception('No hay suficiente stock en la tienda seleccionada.');
                    }
                }

                $totalMovimientos = collect($movimientos)->sum(fn($mov) => (float) ($mov['cantidad'] ?? 0));
                if (abs($totalMovimientos - $cantidadStock) > 0.01) throw new \Exception('El stock asignado no coincide.');

                $calculoPrecio = $this->calcularPrecioLinea($producto, $cantidad, $unidad);
                $subtotal += $calculoPrecio['subtotal'];

                $cantidadRegistrada = $cantidad;
                $unidadRegistrada = $unidad;
                if ($esCeramica && $unidad === 'm2') { $cantidadRegistrada = $cantidadStock; $unidadRegistrada = 'pieza'; }

                $detalle = VentaDetalle::create([
                    'venta_id'        => $venta->id,
                    'producto_id'     => $producto->id,
                    'cantidad'        => $cantidadRegistrada,
                    'unidad'          => $unidadRegistrada,
                    'cantidad_stock'  => $cantidadStock,
                    'precio_unitario' => $calculoPrecio['precio_unitario'],
                    'subtotal'        => $calculoPrecio['subtotal'],
                ]);

                foreach ($movimientos as $mov) {
                    $cantMov = (float) ($mov['cantidad'] ?? 0);
                    if ($cantMov <= 0) continue;
                    VentaMovimiento::create([
                        'venta_detalle_id' => $detalle->id,
                        'stock_user_id'    => $mov['stock_user_id'],
                        'deposito_id'      => $mov['deposito_id'], // 🔥
                        'cantidad'         => $cantMov,
                    ]);
                }
            }

            $descuento = (float) ($request->descuento ?? 0);
            $venta->update(['subtotal' => round($subtotal, 2), 'total' => round(max(0, $subtotal - $descuento), 2)]);

            DB::commit();
            return redirect()->route($this->rutaVentas('index'))->with('success', 'Venta actualizada.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route($this->rutaVentas('edit'), $venta)->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function confirmar(Venta $venta)
    {
        if ($venta->estado !== 'pendiente') return back()->with('error', 'Solo ventas pendientes.');
        if ($venta->user_id !== auth()->id()) abort(403);

        DB::beginTransaction();
        try {
            $venta->load('detalles.movimientos.stockUser', 'detalles.movimientos.deposito');

            // PRIMERA FASE: Validar
            foreach ($venta->detalles as $detalle) {
                foreach ($detalle->movimientos as $mov) {
                    $stock = Stock::where('user_id', $mov->stock_user_id)
                        ->where('producto_id', $detalle->producto_id)
                        ->where('deposito_id', $mov->deposito_id) // 🔥 Verificamos la tienda exacta
                        ->first();

                    $disponible = $stock ? (float) $stock->cantidad : 0;
                    $tienda = $mov->deposito->nombre ?? 'Desconocida';

                    if ($disponible < (float) $mov->cantidad) {
                        throw new \Exception("El admin " . ($mov->stockUser->name ?? '') . " no tiene suficiente stock de " . ($detalle->producto->nombre ?? '') . " en la tienda **{$tienda}**. Disponible: {$disponible}, requerido: {$mov->cantidad}");
                    }
                }
            }

            // SEGUNDA FASE: Descontar
            $notificados = [];
            foreach ($venta->detalles as $detalle) {
                foreach ($detalle->movimientos as $mov) {
                    $stock = Stock::where('user_id', $mov->stock_user_id)
                        ->where('producto_id', $detalle->producto_id)
                        ->where('deposito_id', $mov->deposito_id) // 🔥 Descontamos la tienda exacta
                        ->first();

                    if ($stock) $stock->decrement('cantidad', (float) $mov->cantidad);

                    $esVendedor = auth()->user()->role?->slug === 'vendedor';
                    if ($esVendedor || (int) $mov->stock_user_id !== (int) $venta->user_id) {
                        $key = $mov->stock_user_id . '-' . $detalle->producto_id . '-' . $mov->deposito_id;
                        if (!isset($notificados[$key])) {
                            Notificacion::create([
                                'user_id' => $mov->stock_user_id,
                                'from_user_id' => $venta->user_id,
                                'tipo' => 'stock_prestado',
                                'titulo' => 'Se tomó stock de tu inventario',
                                'mensaje' => auth()->user()->name . ' usó ' . $mov->cantidad . ' ' . ($detalle->producto->unidad_base ?? 'unidades') . ' de ' . $detalle->producto->nombre . ' en la tienda ' . ($mov->deposito->nombre ?? '') . ' para la venta ' . $venta->numero_venta . '.',
                                'venta_id' => $venta->id,
                            ]);
                            $notificados[$key] = true;
                        }
                    }
                }
            }

            $venta->update(['estado' => 'vendida']);
            DB::commit();
            return redirect()->route($this->rutaVentas('index'))->with('success', 'Venta confirmada.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancelar(Venta $venta)
    {
        if ($venta->estado === 'cancelada') return back()->with('error', 'Ya está cancelada.');
        if ($venta->user_id !== auth()->id()) abort(403);

        $estadoAnterior = $venta->estado;
        DB::beginTransaction();
        try {
            if ($estadoAnterior === 'vendida') {
                $venta->load('detalles.movimientos.stockUser');
                $notificados = [];
                foreach ($venta->detalles as $detalle) {
                    foreach ($detalle->movimientos as $mov) {
                        Stock::incrementar(
                            $mov->stock_user_id,
                            $detalle->producto_id,
                            (float) $mov->cantidad,
                            $mov->deposito_id // 🔥 Devolvemos a la tienda exacta
                        );

                        $esVendedor = auth()->user()->role?->slug === 'vendedor';
                        if ($esVendedor || (int) $mov->stock_user_id !== (int) $venta->user_id) {
                            $key = $mov->stock_user_id . '-' . $detalle->producto_id . '-' . $mov->deposito_id;
                            if (!isset($notificados[$key])) {
                                Notificacion::create([
                                    'user_id' => $mov->stock_user_id,
                                    'from_user_id' => $venta->user_id,
                                    'tipo' => 'stock_devuelto',
                                    'titulo' => 'Se devolvió stock a tu inventario',
                                    'mensaje' => 'La venta ' . $venta->numero_venta . ' fue cancelada. Se te devolvieron ' . $mov->cantidad . ' ' . ($detalle->producto->unidad_base ?? 'unidades') . ' de ' . $detalle->producto->nombre . '.',
                                    'venta_id' => $venta->id,
                                ]);
                                $notificados[$key] = true;
                            }
                        }
                    }
                }
            }
            $venta->update(['estado' => 'cancelada']);
            DB::commit();
            return redirect()->route($this->rutaVentas('index'))->with('success', 'Venta cancelada.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Venta $venta)
    {
        $rol = auth()->user()->role?->slug;
        if ($rol === 'vendedor' && (int) $venta->user_id !== (int) auth()->id()) abort(403);
        if ($rol === 'administrador') {
            $creador = User::with('role')->findOrFail($venta->user_id);
            if ($creador->role?->slug === 'administrador' && (int) $venta->user_id !== (int) auth()->id()) abort(403);
        }
        if ($venta->estado === 'vendida') return redirect()->route($this->rutaVentas('index'))->with('error', 'No se pueden eliminar ventas confirmadas.');

        $venta->delete();
        return redirect()->route($this->rutaVentas('index'))->with('success', 'Venta eliminada correctamente.');
    }

    private function calcularPrecioLinea(Producto $producto, float $cantidad, string $unidad): array
    {
        if ($cantidad <= 0) throw new \Exception('La cantidad debe ser mayor que cero.');
        $tipo = mb_strtolower(trim((string) ($producto->tipoProducto?->nombre ?? '')), 'UTF-8');
        $esCeramica = in_array($tipo, ['cerámica', 'ceramica', 'porcelanato'], true);

        if (!$esCeramica) {
            $precioUnitario = $this->obtenerPrecioVenta($producto, $unidad);
            return ['precio_unitario' => $precioUnitario, 'subtotal' => round($cantidad * $precioUnitario, 2)];
        }

        $precioCaja = (float) ($producto->precio_venta_caja ?? 0);
        if ($precioCaja <= 0) throw new \Exception('Falta precio por caja.');

        switch ($unidad) {
            case 'caja':
                if (floor($cantidad) != $cantidad) throw new \Exception('Cajas enteras.');
                return ['precio_unitario' => $precioCaja, 'subtotal' => round($cantidad * $precioCaja, 2)];
            case 'm2':
                $m2PorCaja = (float) ($producto->m2_por_caja ?? 0);
                $piezasPorCaja = (int) ($producto->piezas_por_caja ?? 0);
                if ($m2PorCaja <= 0 || $piezasPorCaja <= 0) throw new \Exception('Faltan m² o piezas por caja.');
                $m2PorPieza = $m2PorCaja / $piezasPorCaja;
                $piezasNecesarias = (int) ceil(($cantidad / $m2PorPieza) - 0.000000001);
                $precioUnitario = $precioCaja / $piezasPorCaja;
                return ['precio_unitario' => round($precioUnitario, 2), 'subtotal' => round($piezasNecesarias * $precioUnitario, 2)];
            case 'pieza':
                $piezasPorCaja = (int) ($producto->piezas_por_caja ?? 0);
                if ($piezasPorCaja <= 0) throw new \Exception('Faltan piezas por caja.');
                $precioUnitario = $precioCaja / $piezasPorCaja;
                return ['precio_unitario' => round($precioUnitario, 2), 'subtotal' => round($cantidad * $precioUnitario, 2)];
            default:
                throw new \Exception('Unidad inválida.');
        }
    }

    private function obtenerPrecioVenta(Producto $producto, string $unidad): float
    {
        $campoPrecio = match ($unidad) {
            'caja'  => 'precio_venta_caja',
            'm2'    => 'precio_venta_m2',
            'pieza' => 'precio_venta_pieza',
            'bolsa' => 'precio_venta_bolsa',
            default => null,
        };
        if ($campoPrecio === null) throw new \Exception('Unidad inválida.');
        $precio = $producto->{$campoPrecio};
        if ($precio === null || (float) $precio <= 0) throw new \Exception('No hay precio válido.');
        return round((float) $precio, 2);
    }

    private function unidadesVenta(Producto $producto): array
    {
        $tipo = mb_strtolower(trim((string) ($producto->tipoProducto?->nombre ?? '')), 'UTF-8');
        return match (true) {
            in_array($tipo, ['cerámica', 'ceramica', 'porcelanato'], true) => ['caja', 'm2', 'pieza'],
            in_array($tipo, ['cemento cola', 'pastinas'], true) => ['bolsa'],
            in_array($tipo, ['listelo', 'randa decorativa', 'esquinero de aluminio', 'esquinero de goma'], true) => ['pieza'],
            default => ['pieza'],
        };
    }

    private function calcularCantidadStock(Producto $producto, float $cantidad, string $unidad): array
    {
        $tipo = mb_strtolower(trim((string) ($producto->tipoProducto?->nombre ?? '')), 'UTF-8');
        $unidadesPermitidas = $this->unidadesVenta($producto);
        if (!in_array($unidad, $unidadesPermitidas, true)) throw new \Exception('Unidad inválida.');
        if ($cantidad <= 0) throw new \Exception('Cantidad mayor a cero.');

        if (in_array($tipo, ['cerámica', 'ceramica', 'porcelanato'], true)) {
            $piezasPorCaja = (int) ($producto->piezas_por_caja ?? 0);
            $m2PorCaja = (float) ($producto->m2_por_caja ?? 0);
            if ($piezasPorCaja <= 0 || $m2PorCaja <= 0) throw new \Exception('Faltan piezas o m².');

            if ($unidad === 'caja') {
                if (floor($cantidad) != $cantidad) throw new \Exception('Cajas enteras.');
                $cajas = (int) $cantidad;
                $piezas = $cajas * $piezasPorCaja;
                return ['cantidad_stock' => $piezas, 'cajas' => $cajas, 'piezas' => $piezas, 'm2_reales' => $cajas * $m2PorCaja];
            }

            if ($unidad === 'm2') {
                $m2PorPieza = $m2PorCaja / $piezasPorCaja;
                $piezas = (int) ceil(($cantidad / $m2PorPieza) - 0.000000001);
                $cajasCompletas = intdiv($piezas, $piezasPorCaja);
                $piezasSueltas = $piezas % $piezasPorCaja;
                return ['cantidad_stock' => $piezas, 'cajas' => $cajasCompletas, 'piezas' => $piezas, 'piezas_sueltas' => $piezasSueltas, 'm2_reales' => $piezas * $m2PorPieza];
            }

            if ($unidad === 'pieza') {
                if (floor($cantidad) != $cantidad) throw new \Exception('Piezas enteras.');
                $piezas = (int) $cantidad;
                return ['cantidad_stock' => $piezas, 'cajas' => intdiv($piezas, $piezasPorCaja), 'piezas' => $piezas, 'piezas_sueltas' => $piezas % $piezasPorCaja, 'm2_reales' => $piezas * ($m2PorCaja / $piezasPorCaja)];
            }
            throw new \Exception('Unidad no implementada.');
        }

        if (in_array($tipo, ['cemento cola', 'pastinas'], true)) {
            if ($unidad !== 'bolsa') throw new \Exception('Debe ser por bolsa.');
            if (floor($cantidad) != $cantidad) throw new \Exception('Bolsas enteras.');
            return ['cantidad_stock' => $cantidad, 'cajas' => 0, 'piezas' => 0, 'm2_reales' => 0];
        }

        if ($unidad !== 'pieza') throw new \Exception('Debe ser por pieza.');
        if (floor($cantidad) != $cantidad) throw new \Exception('Piezas enteras.');
        return ['cantidad_stock' => $cantidad, 'cajas' => 0, 'piezas' => $cantidad, 'm2_reales' => 0];
    }

    private function rules(): array
    {
        return [
            'cliente_nombre' => ['required', 'string', 'max:150'],
            'cliente_documento' => ['required', 'string', 'max:30'],
            'cliente_telefono' => ['nullable', 'string', 'max:30'],
            'fecha_venta' => ['required', 'date'],
            'descuento' => ['nullable', 'numeric', 'min:0'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'integer', 'exists:productos,id'],
            'items.*.cantidad' => ['required', 'numeric', 'min:0.01'],
            'items.*.unidad' => ['required', 'string', 'in:caja,m2,pieza,bolsa'],
            'items.*.movimientos' => ['required', 'array', 'min:1'],
            'items.*.movimientos.*.stock_user_id' => ['required', 'integer', 'exists:users,id'],
            'items.*.movimientos.*.deposito_id' => ['required', 'integer', 'exists:depositos,id'], // 🔥
            'items.*.movimientos.*.cantidad' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    private function messages(): array
    {
        return [
            'cliente_nombre.required' => 'El nombre del cliente es obligatorio.',
            'cliente_documento.required' => 'El CI/NIT del cliente es obligatorio.',
            'fecha_venta.required' => 'La fecha de venta es obligatoria.',
            'items.required' => 'Debes agregar al menos un producto a la venta.',
            'items.min' => 'Debes agregar al menos un producto a la venta.',
            'items.*.producto_id.required' => 'Selecciona un producto en cada línea.',
            'items.*.producto_id.exists' => 'El producto seleccionado no es válido.',
            'items.*.cantidad.required' => 'La cantidad es obligatoria en cada línea.',
            'items.*.cantidad.min' => 'La cantidad debe ser mayor a 0.',
            'items.*.unidad.required' => 'Selecciona la unidad de venta.',
            'items.*.unidad.in' => 'La unidad de venta seleccionada no es válida.',
            'items.*.movimientos.required' => 'Debes especificar de qué admin(es) se tomará el stock.',
            'items.*.movimientos.min' => 'Debes especificar al menos un origen de stock.',
        ];
    }

    private function rutaVentas(string $accion): string
    {
        $prefijo = auth()->user()->role?->slug === 'vendedor' ? 'vendedor.ventas.' : 'admin.ventas.';
        return $prefijo . $accion;
    }
}