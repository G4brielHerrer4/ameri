<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Producto;
use App\Models\SolicitudStock;
use App\Models\SolicitudStockDetalle;
use App\Models\Stock;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\VentaMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VentaVendedorController extends Controller
{
    /* =========================================================
       INDEX — ventas del vendedor logueado
       ========================================================= */
    public function index()
    {
        $ventas = Venta::with(['detalles'])
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();

        return view('vendedor.ventas.index', compact('ventas'));
    }

    /* =========================================================
       CREATE — solo admins con stock
       ========================================================= */
    public function create()
    {
        $admins = User::whereHas('role', fn($q) => $q->where('slug', 'administrador'))
            ->whereHas('stocks', fn($q) => $q->where('cantidad', '>', 0))
            ->orderBy('name')
            ->get();

        return view('vendedor.ventas.create', compact('admins'));
    }

    /* =========================================================
       AJAX: productos con stock del admin elegido
       GET /vendedor/ventas/productos-admin/{admin}
       ========================================================= */
    public function productosAdmin(User $admin)
    {
        abort_unless($admin->role?->slug === 'administrador', 403);

        $productos = Stock::with(['producto.tipoProducto.categoria'])
            ->where('user_id', $admin->id)
            ->where('cantidad', '>', 0)
            ->get()
            ->filter(fn($s) => $s->producto !== null)
            ->groupBy('producto_id')
            ->map(function ($grupo) {
                $primero = $grupo->first();
                return [
                    'id'             => $primero->producto_id,
                    'nombre'         => $primero->producto->nombre,
                    'tipo'           => $primero->producto->tipoProducto?->nombre,
                    'unidad_base'    => $primero->producto->unidad_base,
                    'cantidad_total' => (float) $grupo->sum('cantidad'),
                ];
            })
            ->values();

        return response()->json(['productos' => $productos]);
    }

    /* =========================================================
       AJAX: stock de un producto (del admin elegido)
       GET /vendedor/ventas/stock-producto/{producto}?admin_id=X
       ========================================================= */
    public function stockProducto(Producto $producto, Request $request)
    {
        $request->validate(['admin_id' => 'required|exists:users,id']);

        $adminId = (int) $request->query('admin_id');

        $admin = User::with('role')->find($adminId);
        abort_unless($admin && $admin->role?->slug === 'administrador', 422, 'Administrador inválido.');

        $producto->load('tipoProducto');
        $unidadesVenta = $this->unidadesVenta($producto);

        $stocksDelAdmin = Stock::with(['user', 'deposito'])
            ->where('producto_id', $producto->id)
            ->where('user_id', $adminId)
            ->where('cantidad', '>', 0)
            ->get();

        $stockTotalAdmin = (float) $stocksDelAdmin->sum('cantidad');

        $stocksMapeados = $stocksDelAdmin->map(fn($s) => [
            'user_id'         => $s->user_id,
            'user_name'       => $s->user->name ?? 'Administrador',
            'deposito_id'     => $s->deposito_id,
            'deposito_nombre' => $s->deposito->nombre ?? 'Tienda',
            'cantidad'        => (float) $s->cantidad,
        ])->values();

        $otrosStocks = Stock::with(['user', 'deposito'])
            ->where('producto_id', $producto->id)
            ->where('user_id', '!=', $adminId)
            ->where('cantidad', '>', 0)
            ->whereHas('user.role', fn($q) => $q->where('slug', 'administrador'))
            ->get()
            ->groupBy('user_id')
            ->map(function ($grupo) {
                $primero = $grupo->first();
                return [
                    'user_id'         => $primero->user_id,
                    'user_name'       => $primero->user->name ?? 'Administrador',
                    'total_cantidad'  => (float) $grupo->sum('cantidad'),
                    'detalle_tiendas' => $grupo->map(fn($s) => [
                        'deposito_id'     => $s->deposito_id,
                        'deposito_nombre' => $s->deposito->nombre ?? 'Tienda',
                        'cantidad'        => (float) $s->cantidad,
                    ])->values(),
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
                'precios_venta'   => [
                    'caja'  => $producto->precio_venta_caja  !== null ? (float) $producto->precio_venta_caja  : null,
                    'm2'    => $producto->precio_venta_m2    !== null ? (float) $producto->precio_venta_m2    : null,
                    'pieza' => $producto->precio_venta_pieza !== null ? (float) $producto->precio_venta_pieza : null,
                    'bolsa' => $producto->precio_venta_bolsa !== null ? (float) $producto->precio_venta_bolsa : null,
                ],
            ],
            'admin' => [
                'id'   => $admin->id,
                'name' => $admin->name,
            ],
            'stocks'                 => $stocksMapeados,
            'stock_total_admin'      => $stockTotalAdmin,   // 👈 NUEVO
            'otros_stocks'           => $otrosStocks,
            'requiere_venta_interna' => $stockTotalAdmin <= 0 && $otrosStocks->isNotEmpty(),
        ]);
    }

    /* =========================================================
       STORE
       ========================================================= */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return redirect()->route('vendedor.ventas.create')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $adminId = (int) $request->admin_id;
            $admin   = User::with('role')->find($adminId);
            if (!$admin || $admin->role?->slug !== 'administrador') {
                throw new \Exception('Administrador inválido.');
            }

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

            $subtotal = $this->procesarLineas($venta, $request->items, $adminId);

            $descuento = (float) ($request->descuento ?? 0);
            if ($descuento < 0 || $descuento > $subtotal) {
                throw new \Exception('El descuento no puede superar el subtotal.');
            }

            $venta->update([
                'subtotal' => round($subtotal, 2),
                'total'    => round(max(0, $subtotal - $descuento), 2),
            ]);

            DB::commit();
            return redirect()->route('vendedor.ventas.index')
                ->with('success', 'Venta registrada como pendiente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('vendedor.ventas.create')
                ->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    /**
     * POST /vendedor/ventas/notificar-faltante
     * Notifica al administrador que falta stock de un producto para una venta.
     * Solo crea la notificación (no crea SolicitudStock — eso lo hacen los admins).
     */
    public function notificarFaltante(Request $request)
    {
        $request->validate([
            'admin_id'          => 'required|exists:users,id',
            'producto_id'       => 'required|exists:productos,id',
            'cantidad_faltante' => 'required|numeric|min:0.01',
            'cantidad_total'    => 'required|numeric|min:0.01',
            'stock_actual'      => 'required|numeric|min:0',
        ]);

        try {
            $admin    = User::with('role')->findOrFail($request->admin_id);
            $producto = Producto::findOrFail($request->producto_id);
            $vendedor = auth()->user();

            if ($admin->role?->slug !== 'administrador') {
                throw new \Exception('El destinatario no es administrador.');
            }

            $faltante    = (float) $request->cantidad_faltante;
            $totalVenta  = (float) $request->cantidad_total;
            $stockActual = (float) $request->stock_actual;
            $unidad      = $producto->unidad_base ?? 'unidades';

            Notificacion::create([
                'user_id'      => $admin->id,
                'from_user_id' => $vendedor->id,
                'tipo'         => 'stock_faltante',
                'titulo'       => 'Falta stock: ' . $producto->nombre,
                'mensaje'      => sprintf(
                    '%s necesita %s %s de "%s" para completar una venta de %s %s. ' .
                    'Actualmente tienes %s %s en tu inventario, así que faltan %s %s. ' .
                    'Por favor, gestiona una compra o coordina una venta interna con otro administrador.',
                    $vendedor->name,
                    $faltante, $unidad,
                    $producto->nombre,
                    $totalVenta, $unidad,
                    $stockActual, $unidad,
                    $faltante, $unidad
                ),
                'leida'        => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notificación enviada al administrador. Se le informó que faltan ' .
                            $faltante . ' ' . $unidad . ' de ' . $producto->nombre . '.',
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* =========================================================
       SHOW
       ========================================================= */
    public function show(Venta $venta)
    {
        if ((int) $venta->user_id !== (int) auth()->id()) abort(403);

        $venta->load([
            'detalles.producto.tipoProducto',
            'detalles.movimientos.stockUser',
            'detalles.movimientos.deposito',
        ]);

        return view('vendedor.ventas.show', compact('venta'));
    }

    /* =========================================================
       EDIT / UPDATE
       ========================================================= */
    public function edit(Venta $venta)
    {
        if ((int) $venta->user_id !== (int) auth()->id()) abort(403);
        if ($venta->estado !== 'pendiente') {
            return redirect()->route('vendedor.ventas.index')->with('error', 'Solo ventas pendientes.');
        }

        $venta->load('detalles.movimientos');

        $admins = User::whereHas('role', fn($q) => $q->where('slug', 'administrador'))
            ->whereHas('stocks', fn($q) => $q->where('cantidad', '>', 0))
            ->orderBy('name')->get();

        $lineasExistentes = $venta->detalles->map(fn($d) => [
            'producto_id'     => (int) $d->producto_id,
            'cantidad'        => (float) $d->cantidad,
            'unidad'          => $d->unidad,
            'precio_unitario' => (float) $d->precio_unitario,
            'movimientos'     => $d->movimientos->map(fn($m) => [
                'stock_user_id' => (int) $m->stock_user_id,
                'deposito_id'   => (int) $m->deposito_id,
                'cantidad'      => (float) $m->cantidad,
            ])->values()->all(),
        ])->values()->all();

        return view('vendedor.ventas.edit', compact('venta', 'admins', 'lineasExistentes'));
    }

    public function update(Request $request, Venta $venta)
    {
        if ((int) $venta->user_id !== (int) auth()->id()) abort(403);
        if ($venta->estado !== 'pendiente') {
            return redirect()->route('vendedor.ventas.index')->with('error', 'Solo ventas pendientes.');
        }

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return redirect()->route('vendedor.ventas.edit', $venta)->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $adminId = (int) $request->admin_id;

            $venta->update([
                'cliente_nombre'    => $request->cliente_nombre,
                'cliente_documento' => $request->cliente_documento,
                'cliente_telefono'  => $request->cliente_telefono,
                'fecha_venta'       => $request->fecha_venta,
                'descuento'         => $request->descuento ?? 0,
                'observaciones'     => $request->observaciones,
            ]);

            $venta->detalles()->delete();

            $subtotal  = $this->procesarLineas($venta, $request->items, $adminId);
            $descuento = (float) ($request->descuento ?? 0);

            $venta->update([
                'subtotal' => round($subtotal, 2),
                'total'    => round(max(0, $subtotal - $descuento), 2),
            ]);

            DB::commit();
            return redirect()->route('vendedor.ventas.index')->with('success', 'Venta actualizada.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('vendedor.ventas.edit', $venta)->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /* =========================================================
       CONFIRMAR
       ========================================================= */
    public function confirmar(Venta $venta)
    {
        if ((int) $venta->user_id !== (int) auth()->id()) abort(403);
        if ($venta->estado !== 'pendiente') return back()->with('error', 'Solo ventas pendientes.');

        DB::beginTransaction();
        try {
            $venta->load('detalles.movimientos.stockUser', 'detalles.movimientos.deposito');

            // 1) Validar
            foreach ($venta->detalles as $detalle) {
                foreach ($detalle->movimientos as $mov) {
                    $stock = Stock::where('user_id', $mov->stock_user_id)
                        ->where('producto_id', $detalle->producto_id)
                        ->where('deposito_id', $mov->deposito_id)
                        ->first();

                    $disponible = $stock ? (float) $stock->cantidad : 0;
                    $tienda     = $mov->deposito->nombre ?? 'Desconocida';

                    if ($disponible < (float) $mov->cantidad) {
                        throw new \Exception(
                            'El admin ' . ($mov->stockUser->name ?? '') .
                            ' no tiene suficiente stock de ' . ($detalle->producto->nombre ?? '') .
                            " en la tienda **{$tienda}**. Disponible: {$disponible}, requerido: {$mov->cantidad}"
                        );
                    }
                }
            }

            // 2) Descontar + notificar
            $notificados = [];
            foreach ($venta->detalles as $detalle) {
                foreach ($detalle->movimientos as $mov) {
                    Stock::where('user_id', $mov->stock_user_id)
                        ->where('producto_id', $detalle->producto_id)
                        ->where('deposito_id', $mov->deposito_id)
                        ->decrement('cantidad', (float) $mov->cantidad);

                    $key = $mov->stock_user_id . '-' . $detalle->producto_id . '-' . $mov->deposito_id;
                    if (!isset($notificados[$key])) {
                        Notificacion::create([
                            'user_id'      => $mov->stock_user_id,
                            'from_user_id' => $venta->user_id,
                            'tipo'         => 'stock_prestado',
                            'titulo'       => 'Se tomó stock de tu inventario',
                            'mensaje'      => auth()->user()->name . ' usó ' . $mov->cantidad . ' ' .
                                              ($detalle->producto->unidad_base ?? 'unidades') . ' de ' .
                                              $detalle->producto->nombre . ' en ' .
                                              ($mov->deposito->nombre ?? 'tu tienda') .
                                              ' para la venta ' . $venta->numero_venta . '.',
                            'venta_id'     => $venta->id,
                        ]);
                        $notificados[$key] = true;
                    }
                }
            }

            $venta->update(['estado' => 'vendida']);
            DB::commit();
            return redirect()->route('vendedor.ventas.index')->with('success', 'Venta confirmada.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* =========================================================
       CANCELAR
       ========================================================= */
    public function cancelar(Venta $venta)
    {
        if ((int) $venta->user_id !== (int) auth()->id()) abort(403);
        if ($venta->estado === 'cancelada') return back()->with('error', 'Ya está cancelada.');

        DB::beginTransaction();
        try {
            if ($venta->estado === 'vendida') {
                $venta->load('detalles.movimientos.stockUser');

                $notificados = [];
                foreach ($venta->detalles as $detalle) {
                    foreach ($detalle->movimientos as $mov) {
                        Stock::incrementar(
                            $mov->stock_user_id,
                            $detalle->producto_id,
                            (float) $mov->cantidad,
                            $mov->deposito_id
                        );

                        $key = $mov->stock_user_id . '-' . $detalle->producto_id . '-' . $mov->deposito_id;
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
            $venta->update(['estado' => 'cancelada']);
            DB::commit();
            return redirect()->route('vendedor.ventas.index')->with('success', 'Venta cancelada.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /* =========================================================
       DESTROY
       ========================================================= */
    public function destroy(Venta $venta)
    {
        if ((int) $venta->user_id !== (int) auth()->id()) abort(403);
        if ($venta->estado === 'vendida') {
            return redirect()->route('vendedor.ventas.index')->with('error', 'No se pueden eliminar ventas confirmadas.');
        }

        $venta->delete();
        return redirect()->route('vendedor.ventas.index')->with('success', 'Venta eliminada correctamente.');
    }

    /* =========================================================
       SOLICITAR VENTA INTERNA
       POST /vendedor/ventas/solicitar-venta-interna
       ========================================================= */
    public function solicitarVentaInterna(Request $request)
    {
        $request->validate([
            'proveedor_user_id' => 'required|exists:users,id',
            'producto_id'       => 'required|exists:productos,id',
            'cantidad'          => 'required|numeric|min:0.01',
            'nota'              => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $producto = Producto::findOrFail($request->producto_id);

            $solicitud = SolicitudStock::create([
                'user_id'           => auth()->id(),
                'proveedor_user_id' => $request->proveedor_user_id,
                'estado'            => 'pendiente',
                'nota'              => $request->nota
                    ?? ('Solicitud generada desde venta por ' . auth()->user()->name . ' para ' . $producto->nombre),
            ]);

            SolicitudStockDetalle::create([
                'solicitud_id' => $solicitud->id,
                'producto_id'  => $producto->id,
                'cantidad'     => $request->cantidad,
            ]);

            Notificacion::create([
                'user_id'      => $request->proveedor_user_id,
                'from_user_id' => auth()->id(),
                'tipo'         => 'info',
                'titulo'       => 'Solicitud de stock (venta interna)',
                'mensaje'      => auth()->user()->name . ' necesita ' . $request->cantidad . ' ' .
                                  ($producto->unidad_base ?? 'unidades') . ' de ' . $producto->nombre .
                                  ' para una venta. Revisa la solicitud y coordina la venta interna.',
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de venta interna enviada al administrador.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* =========================================================
       PROCESAR LÍNEAS (compartido store / update)
       ========================================================= */
    private function procesarLineas(Venta $venta, array $items, int $adminId): float
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $producto = Producto::with('tipoProducto')->findOrFail($item['producto_id']);
            $cantidad = (float) $item['cantidad'];
            $unidad   = $item['unidad'];

            $tipo       = mb_strtolower(trim((string) ($producto->tipoProducto?->nombre ?? '')), 'UTF-8');
            $esCeramica = in_array($tipo, ['cerámica', 'ceramica', 'porcelanato'], true);

            $conversion    = $this->calcularCantidadStock($producto, $cantidad, $unidad);
            $cantidadStock = (float) $conversion['cantidad_stock'];

            if ($esCeramica && $unidad === 'm2') {
                $m2PorCaja     = (float) ($producto->m2_por_caja ?? 0);
                $piezasPorCaja = (int) ($producto->piezas_por_caja ?? 0);
                if ($m2PorCaja <= 0 || $piezasPorCaja <= 0) {
                    throw new \Exception('Faltan m² o piezas por caja.');
                }
                $m2PorPieza    = $m2PorCaja / $piezasPorCaja;
                $cantidadStock = (int) ceil(($cantidad / $m2PorPieza) - 0.000000001);
            }

            $movimientos = $item['movimientos'] ?? [];

            foreach ($movimientos as $mov) {
                // 🔥 SOLO el admin elegido
                if ((int) $mov['stock_user_id'] !== $adminId) {
                    throw new \Exception('Como vendedor solo puedes tomar stock del administrador seleccionado.');
                }

                $stock = Stock::where('user_id', $adminId)
                    ->where('producto_id', $producto->id)
                    ->where('deposito_id', $mov['deposito_id'])
                    ->first();

                if (!$stock || (float) $stock->cantidad < (float) $mov['cantidad']) {
                    throw new \Exception(
                        'Stock insuficiente del administrador seleccionado para ' . $producto->nombre .
                        '. Se requiere venta interna.'
                    );
                }
            }

            $totalMovimientos = collect($movimientos)->sum(fn($m) => (float) ($m['cantidad'] ?? 0));
            if (abs($totalMovimientos - $cantidadStock) > 0.01) {
                throw new \Exception('El stock asignado no coincide con la cantidad física requerida.');
            }

            $calculoPrecio = $this->calcularPrecioLinea($producto, $cantidad, $unidad);
            $subtotal     += $calculoPrecio['subtotal'];

            $cantidadRegistrada = $cantidad;
            $unidadRegistrada   = $unidad;
            if ($esCeramica && $unidad === 'm2') {
                $cantidadRegistrada = $cantidadStock;
                $unidadRegistrada   = 'pieza';
            }

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
                    'deposito_id'      => $mov['deposito_id'],
                    'cantidad'         => $cantMov,
                ]);
            }
        }

        return $subtotal;
    }

    /* =========================================================
       HELPERS
       ========================================================= */
    private function calcularPrecioLinea(Producto $producto, float $cantidad, string $unidad): array
    {
        if ($cantidad <= 0) throw new \Exception('La cantidad debe ser mayor que cero.');

        $tipo       = mb_strtolower(trim((string) ($producto->tipoProducto?->nombre ?? '')), 'UTF-8');
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
                $m2PorCaja     = (float) ($producto->m2_por_caja ?? 0);
                $piezasPorCaja = (int) ($producto->piezas_por_caja ?? 0);
                if ($m2PorCaja <= 0 || $piezasPorCaja <= 0) throw new \Exception('Faltan m² o piezas por caja.');
                $m2PorPieza       = $m2PorCaja / $piezasPorCaja;
                $piezasNecesarias = (int) ceil(($cantidad / $m2PorPieza) - 0.000000001);
                $precioUnitario   = $precioCaja / $piezasPorCaja;
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
            $m2PorCaja     = (float) ($producto->m2_por_caja ?? 0);
            if ($piezasPorCaja <= 0 || $m2PorCaja <= 0) throw new \Exception('Faltan piezas o m².');

            if ($unidad === 'caja') {
                if (floor($cantidad) != $cantidad) throw new \Exception('Cajas enteras.');
                $cajas  = (int) $cantidad;
                $piezas = $cajas * $piezasPorCaja;
                return ['cantidad_stock' => $piezas, 'cajas' => $cajas, 'piezas' => $piezas, 'm2_reales' => $cajas * $m2PorCaja];
            }

            if ($unidad === 'm2') {
                $m2PorPieza     = $m2PorCaja / $piezasPorCaja;
                $piezas         = (int) ceil(($cantidad / $m2PorPieza) - 0.000000001);
                $cajasCompletas = intdiv($piezas, $piezasPorCaja);
                $piezasSueltas  = $piezas % $piezasPorCaja;
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
            'admin_id'          => ['required', 'integer', 'exists:users,id'],
            'cliente_nombre'    => ['required', 'string', 'max:150'],
            'cliente_documento' => ['required', 'string', 'max:30'],
            'cliente_telefono'  => ['nullable', 'string', 'max:30'],
            'fecha_venta'       => ['required', 'date'],
            'descuento'         => ['nullable', 'numeric', 'min:0'],
            'observaciones'     => ['nullable', 'string', 'max:1000'],
            'items'             => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'integer', 'exists:productos,id'],
            'items.*.cantidad'    => ['required', 'numeric', 'min:0.01'],
            'items.*.unidad'      => ['required', 'string', 'in:caja,m2,pieza,bolsa'],
            'items.*.movimientos' => ['required', 'array', 'min:1'],
            'items.*.movimientos.*.stock_user_id' => ['required', 'integer', 'exists:users,id'],
            'items.*.movimientos.*.deposito_id'   => ['required', 'integer', 'exists:depositos,id'],
            'items.*.movimientos.*.cantidad'      => ['required', 'numeric', 'min:0.01'],
        ];
    }

    private function messages(): array
    {
        return [
            'admin_id.required'          => 'Debes seleccionar el administrador para esta venta.',
            'cliente_nombre.required'    => 'El nombre del cliente es obligatorio.',
            'cliente_documento.required' => 'El CI/NIT del cliente es obligatorio.',
            'fecha_venta.required'       => 'La fecha de venta es obligatoria.',
            'items.required'             => 'Debes agregar al menos un producto a la venta.',
            'items.min'                  => 'Debes agregar al menos un producto a la venta.',
            'items.*.producto_id.required' => 'Selecciona un producto en cada línea.',
            'items.*.cantidad.required'    => 'La cantidad es obligatoria en cada línea.',
            'items.*.unidad.required'      => 'Selecciona la unidad de venta.',
            'items.*.movimientos.required' => 'Debes especificar de qué admin(es) se tomará el stock.',
        ];
    }
}