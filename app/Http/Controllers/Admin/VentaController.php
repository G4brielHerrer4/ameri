<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\VentaMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['user', 'detalles'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.ventas.index', compact('ventas'));
    }

    public function create()
    {
        $stocks = Stock::with(['producto.tipoProducto.categoria'])
            ->where('cantidad', '>', 0)
            ->get()
            ->filter(fn($s) => $s->producto !== null)
            ->unique('producto_id')
            ->values();

        return view('admin.ventas.create', compact('stocks'));
    }

    /**
     * Devuelve el stock disponible de un producto
     * y toda la información necesaria para realizar
     * las conversiones de venta.
     */
    public function stockProducto(Producto $producto)
    {
        $producto->load('tipoProducto');

        $unidadesVenta = $this->unidadesVenta($producto);

        $stocks = Stock::with('user')
            ->where('producto_id', $producto->id)
            ->where('cantidad', '>', 0)
            ->whereHas('user.role', fn($q) => $q->where('slug', 'administrador'))
            ->orderBy('cantidad', 'desc')
            ->get()
            ->map(function ($s) use ($producto, $unidadesVenta) {

                $cantidadFisica = (float) $s->cantidad;

                $cajasDisponibles = null;
                $m2Disponibles = null;

                $tipo = strtolower(
                    trim($producto->tipoProducto?->nombre ?? '')
                );

                if (
                    in_array($tipo, ['cerámica', 'porcelanato']) &&
                    (int) $producto->piezas_por_caja > 0
                ) {
                    $piezasPorCaja = (int) $producto->piezas_por_caja;

                    $cajasDisponibles = floor(
                        $cantidadFisica / $piezasPorCaja
                    );

                    if ((float) $producto->m2_por_caja > 0) {
                        $m2Disponibles =
                            ($cantidadFisica / $piezasPorCaja)
                            * (float) $producto->m2_por_caja;
                    }
                }

                return [
                    'user_id'          => $s->user_id,
                    'user_name'        => $s->user->name ?? 'Admin',
                    'cantidad'         => $cantidadFisica,
                    'unidad_stock'     => $producto->unidad_base,
                    'cajas_disponibles'=> $cajasDisponibles,
                    'm2_disponibles'   => $m2Disponibles,
                    'es_propio'        => $s->user_id === auth()->id(),
                ];
            });

        return response()->json([
            
            'producto' => [
                'id'              => $producto->id,
                'nombre'          => $producto->nombre,
                'tipo'            => $producto->tipoProducto?->nombre,
                'unidad_base'     => $producto->unidad_base,
                'unidades_venta'  => $unidadesVenta,
                'piezas_por_caja' => (int) ($producto->piezas_por_caja ?? 0),
                'm2_por_caja'     => (float) ($producto->m2_por_caja ?? 0),

                // Precios configurados en el inventario.
                'precios_venta' => [
                    'caja'  => $producto->precio_venta_caja !== null
                        ? (float) $producto->precio_venta_caja
                        : null,

                    'm2'    => $producto->precio_venta_m2 !== null
                        ? (float) $producto->precio_venta_m2
                        : null,

                    'pieza' => $producto->precio_venta_pieza !== null
                        ? (float) $producto->precio_venta_pieza
                        : null,

                    'bolsa' => $producto->precio_venta_bolsa !== null
                        ? (float) $producto->precio_venta_bolsa
                        : null,
                ],
            ],
            'stocks' => $stocks,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            $this->messages()
        );

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
                $producto = Producto::with('tipoProducto')
                    ->findOrFail($item['producto_id']);

                $cantidad = (float) $item['cantidad'];
                $unidad = $item['unidad'];

                $tipo = mb_strtolower(
                    trim((string) ($producto->tipoProducto?->nombre ?? '')),
                    'UTF-8'
                );

                $esCeramica = in_array(
                    $tipo,
                    ['cerámica', 'ceramica', 'porcelanato'],
                    true
                );

                /*
                * Calcular la cantidad física necesaria.
                */
                $conversion = $this->calcularCantidadStock(
                    $producto,
                    $cantidad,
                    $unidad
                );

                $cantidadStock = (float) $conversion['cantidad_stock'];

                /*
                * Para cerámica vendida por m², calcular las piezas
                * enteras que realmente se entregarán.
                */
                if ($esCeramica && $unidad === 'm2') {
                    $m2PorCaja = (float) ($producto->m2_por_caja ?? 0);
                    $piezasPorCaja = (int) ($producto->piezas_por_caja ?? 0);

                    if ($m2PorCaja <= 0 || $piezasPorCaja <= 0) {
                        throw new \Exception(
                            'El producto "' . $producto->nombre .
                            '" debe tener configurados los m² y las piezas por caja.'
                        );
                    }

                    $m2PorPieza = $m2PorCaja / $piezasPorCaja;

                    $cantidadStock = (int) ceil(
                        ($cantidad / $m2PorPieza) - 0.000000001
                    );
                }

                /*
                * Los movimientos deben sumar exactamente el stock físico
                * requerido. Para m² de cerámica, se asignan piezas.
                */
                $movimientos = $item['movimientos'] ?? [];

                $totalMovimientos = collect($movimientos)->sum(
                    function ($mov) {
                        return (float) ($mov['cantidad'] ?? 0);
                    }
                );

                if (abs($totalMovimientos - $cantidadStock) > 0.01) {
                    throw new \Exception(
                        'El stock asignado para ' . $producto->nombre .
                        ' no coincide con la cantidad física requerida. ' .
                        'Se necesitan ' . $cantidadStock .
                        ' unidades físicas.'
                    );
                }

                /*
                * Calcular precio y subtotal en el servidor.
                */
                $calculoPrecio = $this->calcularPrecioLinea(
                    $producto,
                    $cantidad,
                    $unidad
                );

                $precioUnit = $calculoPrecio['precio_unitario'];
                $lineSubtotal = $calculoPrecio['subtotal'];

                /*
                * Conservar la unidad de venta original, excepto cuando
                * la cerámica se vende por m²: se registra la cantidad
                * física de piezas entregadas.
                */
                $cantidadRegistrada = $cantidad;
                $unidadRegistrada = $unidad;

                if ($esCeramica && $unidad === 'm2') {
                    $cantidadRegistrada = $cantidadStock;
                    $unidadRegistrada = 'pieza';
                }

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

                /*
                * Guardar la distribución física entre los dueños.
                * No se descuenta el inventario en este método.
                */
                foreach ($movimientos as $mov) {
                    $cantMov = (float) ($mov['cantidad'] ?? 0);

                    if ($cantMov <= 0) {
                        continue;
                    }

                    VentaMovimiento::create([
                        'venta_detalle_id' => $detalle->id,
                        'stock_user_id'    => $mov['stock_user_id'],
                        'cantidad'         => $cantMov,
                    ]);
                }
            }

            $descuento = (float) ($request->descuento ?? 0);

            if ($descuento < 0 || $descuento > $subtotal) {
                throw new \Exception(
                    'El descuento no puede ser negativo ni superar el subtotal.'
                );
            }

            $venta->update([
                'subtotal' => round($subtotal, 2),
                'total'    => round(max(0, $subtotal - $descuento), 2),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.ventas.index')
                ->with(
                    'success',
                    'Venta registrada como pendiente. Revísala y confírmala para descontar stock.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.ventas.create')
                ->withInput()
                ->with(
                    'error',
                    'Error al registrar la venta: ' . $e->getMessage()
                );
        }
    }



    public function show(Venta $venta)
    {
        $venta->load([
            'user',
            'detalles.producto.tipoProducto.categoria',
            'detalles.movimientos.stockUser',
        ]);

        return view('admin.ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        if ($venta->estado !== 'pendiente') {
            return redirect()
                ->route('admin.ventas.index')
                ->with(
                    'error',
                    'Solo se pueden editar ventas en estado pendiente.'
                );
        }

        if ($venta->user_id !== auth()->id()) {
            abort(
                403,
                'No puedes editar ventas de otros administradores.'
            );
        }

        $venta->load('detalles.movimientos');

        $stocks = Stock::with(['producto.tipoProducto.categoria'])
            ->where('cantidad', '>', 0)
            ->get()
            ->filter(fn($s) => $s->producto !== null)
            ->unique('producto_id')
            ->values();

        return view(
            'admin.ventas.edit',
            compact('venta', 'stocks')
        );
    }

    public function update(Request $request, Venta $venta)
    {
        if ($venta->estado !== 'pendiente') {
            return redirect()
                ->route('admin.ventas.index')
                ->with(
                    'error',
                    'Solo se pueden editar ventas en estado pendiente.'
                );
        }

        if ($venta->user_id !== auth()->id()) {
            abort(403);
        }

        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            $this->messages()
        );

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

            /*
            * Eliminar los detalles anteriores de la venta pendiente.
            */
            $venta->detalles()->delete();

            $subtotal = 0;

            foreach ($request->items as $item) {
                $producto = Producto::with('tipoProducto')
                    ->findOrFail($item['producto_id']);

                $cantidad = (float) $item['cantidad'];
                $unidad = $item['unidad'];

                $tipo = mb_strtolower(
                    trim((string) ($producto->tipoProducto?->nombre ?? '')),
                    'UTF-8'
                );

                $esCeramica = in_array(
                    $tipo,
                    ['cerámica', 'ceramica', 'porcelanato'],
                    true
                );

                /*
                * Calcular la cantidad física necesaria.
                */
                $conversion = $this->calcularCantidadStock(
                    $producto,
                    $cantidad,
                    $unidad
                );

                $cantidadStock = (float) $conversion['cantidad_stock'];

                /*
                * Para cerámica vendida por m², calcular las piezas
                * enteras que realmente se entregarán.
                */
                if ($esCeramica && $unidad === 'm2') {
                    $m2PorCaja = (float) ($producto->m2_por_caja ?? 0);
                    $piezasPorCaja = (int) ($producto->piezas_por_caja ?? 0);

                    if ($m2PorCaja <= 0 || $piezasPorCaja <= 0) {
                        throw new \Exception(
                            'El producto "' . $producto->nombre .
                            '" debe tener configurados los m² y las piezas por caja.'
                        );
                    }

                    $m2PorPieza = $m2PorCaja / $piezasPorCaja;

                    $cantidadStock = (int) ceil(
                        ($cantidad / $m2PorPieza) - 0.000000001
                    );
                }

                /*
                * Verificar que las asignaciones entre los dueños
                * coincidan con las unidades físicas necesarias.
                */
                $movimientos = $item['movimientos'] ?? [];

                $totalMovimientos = collect($movimientos)->sum(
                    function ($mov) {
                        return (float) ($mov['cantidad'] ?? 0);
                    }
                );

                if (abs($totalMovimientos - $cantidadStock) > 0.01) {
                    throw new \Exception(
                        'El stock asignado para ' . $producto->nombre .
                        ' no coincide con la cantidad física requerida. ' .
                        'Se necesitan ' . $cantidadStock .
                        ' unidades físicas.'
                    );
                }

                /*
                * Recalcular el precio desde el servidor.
                */
                $calculoPrecio = $this->calcularPrecioLinea(
                    $producto,
                    $cantidad,
                    $unidad
                );

                $precioUnit = $calculoPrecio['precio_unitario'];
                $lineSubtotal = $calculoPrecio['subtotal'];

                /*
                * Guardar piezas físicas cuando la cerámica se vende
                * por m²; para las demás unidades, conservar la cantidad
                * y unidad de venta originales.
                */
                $cantidadRegistrada = $cantidad;
                $unidadRegistrada = $unidad;

                if ($esCeramica && $unidad === 'm2') {
                    $cantidadRegistrada = $cantidadStock;
                    $unidadRegistrada = 'pieza';
                }

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

                /*
                * Reconstruir los movimientos de stock del detalle.
                */
                foreach ($movimientos as $mov) {
                    $cantMov = (float) ($mov['cantidad'] ?? 0);

                    if ($cantMov <= 0) {
                        continue;
                    }

                    VentaMovimiento::create([
                        'venta_detalle_id' => $detalle->id,
                        'stock_user_id'    => $mov['stock_user_id'],
                        'cantidad'         => $cantMov,
                    ]);
                }
            }

            $descuento = (float) ($request->descuento ?? 0);

            if ($descuento < 0 || $descuento > $subtotal) {
                throw new \Exception(
                    'El descuento no puede ser negativo ni superar el subtotal.'
                );
            }

            $venta->update([
                'subtotal' => round($subtotal, 2),
                'total'    => round(max(0, $subtotal - $descuento), 2),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.ventas.index')
                ->with(
                    'success',
                    'Venta actualizada correctamente.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.ventas.edit', $venta)
                ->withInput()
                ->with(
                    'error',
                    'Error al actualizar la venta: ' . $e->getMessage()
                );
        }
    }



    public function confirmar(Venta $venta)
    {
        if ($venta->estado !== 'pendiente') {
            return back()
                ->with(
                    'error',
                    'Solo se pueden confirmar ventas pendientes.'
                );
        }

        if ($venta->user_id !== auth()->id()) {
            abort(403);
        }

        DB::beginTransaction();

        try {

            $venta->load('detalles.movimientos.stockUser');

            /*
             * PRIMERA FASE:
             * Verificar que todos los stocks sigan disponibles.
             */
            foreach ($venta->detalles as $detalle) {

                foreach ($detalle->movimientos as $mov) {

                    $stock = Stock::where('user_id', $mov->stock_user_id)
                        ->where('producto_id', $detalle->producto_id)
                        ->first();

                    $disponible = $stock
                        ? (float) $stock->cantidad
                        : 0;

                    if ($disponible < (float) $mov->cantidad) {

                        throw new \Exception(
                            'El admin ' .
                            ($mov->stockUser->name ?? '') .
                            ' ya no tiene suficiente stock de ' .
                            ($detalle->producto->nombre ?? '') .
                            '. Disponible: ' .
                            $disponible .
                            ', requerido: ' .
                            $mov->cantidad
                        );
                    }
                }
            }

            /*
             * SEGUNDA FASE:
             * Descontar físicamente el stock.
             */
            $notificados = [];

            foreach ($venta->detalles as $detalle) {

                foreach ($detalle->movimientos as $mov) {

                    $stock = Stock::where(
                        'user_id',
                        $mov->stock_user_id
                    )
                        ->where(
                            'producto_id',
                            $detalle->producto_id
                        )
                        ->first();

                    if ($stock) {
                        $stock->decrement(
                            'cantidad',
                            (float) $mov->cantidad
                        );
                    }

                    /*
                     * Notificar si se utilizó stock de otro admin.
                     */
                    if (
                        $mov->stock_user_id !==
                        $venta->user_id
                    ) {

                        $key =
                            $mov->stock_user_id .
                            '-' .
                            $detalle->producto_id;

                        if (!isset($notificados[$key])) {

                            Notificacion::create([
                                'user_id' =>
                                    $mov->stock_user_id,

                                'from_user_id' =>
                                    $venta->user_id,

                                'tipo' =>
                                    'stock_prestado',

                                'titulo' =>
                                    'Se tomó stock de tu inventario',

                                'mensaje' =>
                                    auth()->user()->name .
                                    ' usó ' .
                                    $mov->cantidad .
                                    ' ' .
                                    (
                                        $detalle->producto
                                            ->unidad_base
                                        ?? 'unidades'
                                    ) .
                                    ' de ' .
                                    $detalle->producto->nombre .
                                    ' para la venta ' .
                                    $venta->numero_venta .
                                    '.',

                                'venta_id' =>
                                    $venta->id,
                            ]);

                            $notificados[$key] = true;
                        }
                    }
                }
            }

            $venta->update([
                'estado' => 'vendida'
            ]);

            DB::commit();

            return redirect()
                ->route('admin.ventas.index')
                ->with(
                    'success',
                    'Venta confirmada. El stock fue descontado correctamente.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->with('error', $e->getMessage());
        }
    }

    public function cancelar(Venta $venta)
    {
        if ($venta->estado === 'cancelada') {
            return back()
                ->with(
                    'error',
                    'Esta venta ya está cancelada.'
                );
        }

        if ($venta->user_id !== auth()->id()) {
            abort(403);
        }

        /*
         * Guardamos el estado anterior porque después
         * de actualizarlo ya no podremos saber si estaba vendida.
         */
        $estadoAnterior = $venta->estado;

        DB::beginTransaction();

        try {

            if ($estadoAnterior === 'vendida') {

                $venta->load(
                    'detalles.movimientos.stockUser'
                );

                $notificados = [];

                foreach ($venta->detalles as $detalle) {

                    foreach ($detalle->movimientos as $mov) {

                        Stock::incrementar(
                            $mov->stock_user_id,
                            $detalle->producto_id,
                            (float) $mov->cantidad
                        );

                        if (
                            $mov->stock_user_id !==
                            $venta->user_id
                        ) {

                            $key =
                                $mov->stock_user_id .
                                '-' .
                                $detalle->producto_id;

                            if (!isset($notificados[$key])) {

                                Notificacion::create([
                                    'user_id' =>
                                        $mov->stock_user_id,

                                    'from_user_id' =>
                                        $venta->user_id,

                                    'tipo' =>
                                        'stock_devuelto',

                                    'titulo' =>
                                        'Se devolvió stock a tu inventario',

                                    'mensaje' =>
                                        'La venta ' .
                                        $venta->numero_venta .
                                        ' fue cancelada. Se te devolvieron ' .
                                        $mov->cantidad .
                                        ' ' .
                                        (
                                            $detalle->producto
                                                ->unidad_base
                                            ?? 'unidades'
                                        ) .
                                        ' de ' .
                                        $detalle->producto->nombre .
                                        '.',

                                    'venta_id' =>
                                        $venta->id,
                                ]);

                                $notificados[$key] = true;
                            }
                        }
                    }
                }
            }

            $venta->update([
                'estado' => 'cancelada'
            ]);

            DB::commit();

            $mensaje = $estadoAnterior === 'vendida'
                ? 'Venta cancelada. El stock fue devuelto a los administradores.'
                : 'Venta cancelada.';

            return redirect()
                ->route('admin.ventas.index')
                ->with('success', $mensaje);

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->with(
                    'error',
                    'Error al cancelar la venta: ' .
                    $e->getMessage()
                );
        }
    }

    public function destroy(Venta $venta)
    {
        if ($venta->estado === 'vendida') {
            return redirect()
                ->route('admin.ventas.index')
                ->with(
                    'error',
                    'No se puede eliminar una venta ya confirmada. Cancélala primero.'
                );
        }

        if ($venta->user_id !== auth()->id()) {
            abort(403);
        }

        $venta->delete();

        return redirect()
            ->route('admin.ventas.index')
            ->with(
                'success',
                'Venta eliminada correctamente.'
            );
    }


        
/**
 * Calcula el precio de la línea de venta.
 *
 * Cerámicas y porcelanatos:
 * el único precio de referencia es el precio por caja.
 *
 * Los demás productos mantienen el precio configurado
 * para su unidad de venta.
 */
    private function calcularPrecioLinea(
        Producto $producto,
        float $cantidad,
        string $unidad
    ): array {
        if ($cantidad <= 0) {
            throw new \Exception(
                'La cantidad de venta debe ser mayor que cero.'
            );
        }

        $tipo = mb_strtolower(
            trim((string) ($producto->tipoProducto?->nombre ?? '')),
            'UTF-8'
        );

        $esCeramica = in_array(
            $tipo,
            ['cerámica', 'ceramica', 'porcelanato'],
            true
        );

        // Los demás productos mantienen su cálculo actual.
        if (!$esCeramica) {
            $precioUnitario = $this->obtenerPrecioVenta(
                $producto,
                $unidad
            );

            return [
                'precio_unitario' => $precioUnitario,
                'subtotal' => round(
                    $cantidad * $precioUnitario,
                    2
                ),
            ];
        }

        // Para cerámicas y porcelanatos se utiliza el precio por caja.
        $precioCaja = (float) (
            $producto->precio_venta_caja ?? 0
        );

        if ($precioCaja <= 0) {
            throw new \Exception(
                'El producto "' . $producto->nombre .
                '" no tiene un precio de venta por caja válido. ' .
                'Configúralo en el inventario.'
            );
        }

        switch ($unidad) {
            case 'caja':
                if (floor($cantidad) != $cantidad) {
                    throw new \Exception(
                        'La cantidad de cajas debe ser un número entero.'
                    );
                }

                $precioUnitario = $precioCaja;
                $subtotalLinea = $cantidad * $precioCaja;
                break;

            case 'm2':
                $m2PorCaja = (float) (
                    $producto->m2_por_caja ?? 0
                );

                $piezasPorCaja = (int) (
                    $producto->piezas_por_caja ?? 0
                );

                if ($m2PorCaja <= 0) {
                    throw new \Exception(
                        'El producto "' . $producto->nombre .
                        '" no tiene configurados los m² por caja.'
                    );
                }

                if ($piezasPorCaja <= 0) {
                    throw new \Exception(
                        'El producto "' . $producto->nombre .
                        '" no tiene configuradas las piezas por caja.'
                    );
                }

                // Calcular los m² que cubre una pieza.
                $m2PorPieza = $m2PorCaja / $piezasPorCaja;

                // Redondear hacia arriba para entregar piezas enteras.
                $piezasNecesarias = (int) ceil(
                    ($cantidad / $m2PorPieza) - 0.000000001
                );

                // Precio proporcional por pieza.
                $precioUnitario = $precioCaja / $piezasPorCaja;

                // Cobrar únicamente las piezas necesarias.
                $subtotalLinea =
                    $piezasNecesarias * $precioUnitario;
                break;

            case 'pieza':
                $piezasPorCaja = (int) (
                    $producto->piezas_por_caja ?? 0
                );

                if ($piezasPorCaja <= 0) {
                    throw new \Exception(
                        'El producto "' . $producto->nombre .
                        '" no tiene configuradas las piezas por caja.'
                    );
                }

                $precioUnitario = $precioCaja / $piezasPorCaja;

                $subtotalLinea =
                    $cantidad * $precioUnitario;
                break;

            default:
                throw new \Exception(
                    'La unidad seleccionada no es válida para el producto "' .
                    $producto->nombre . '".'
                );
        }

        return [
            'precio_unitario' => round($precioUnitario, 2),
            'subtotal' => round($subtotalLinea, 2),
        ];
    }

    /**
     * Obtiene el precio configurado para la unidad seleccionada.
     * El precio se consulta directamente desde la base de datos.
     */
    private function obtenerPrecioVenta(
        Producto $producto,
        string $unidad
    ): float {
        $campoPrecio = match ($unidad) {
            'caja'  => 'precio_venta_caja',
            'm2'    => 'precio_venta_m2',
            'pieza' => 'precio_venta_pieza',
            'bolsa' => 'precio_venta_bolsa',
            default => null,
        };

        if ($campoPrecio === null) {
            throw new \Exception(
                'La unidad de venta seleccionada no es válida.'
            );
        }

        $precio = $producto->{$campoPrecio};

        if ($precio === null || (float) $precio <= 0) {
            throw new \Exception(
                'El producto "' . $producto->nombre .
                '" no tiene un precio de venta válido para ' .
                $unidad . '. Configura su precio en el inventario.'
            );
        }

        return round((float) $precio, 2);
    }


    /*
    |--------------------------------------------------------------------------
    | UNIDADES DE VENTA
    |--------------------------------------------------------------------------
    */

    private function unidadesVenta(Producto $producto): array
    {
        $tipo = mb_strtolower(
            trim((string) ($producto->tipoProducto?->nombre ?? '')),
            'UTF-8'
        );

        return match (true) {

            in_array(
                $tipo,
                ['cerámica', 'ceramica', 'porcelanato'],
                true
            ) => ['caja', 'm2', 'pieza'],

            in_array(
                $tipo,
                ['cemento cola', 'pastinas'],
                true
            ) => ['bolsa'],

            in_array(
                $tipo,
                [
                    'listelo',
                    'randa decorativa',
                    'esquinero de aluminio',
                    'esquinero de goma',
                ],
                true
            ) => ['pieza'],

            default => ['pieza'],
        };
    }


    /*
    |--------------------------------------------------------------------------
    | CONVERSIÓN A STOCK FÍSICO
    |--------------------------------------------------------------------------
    |
    | Esta función es la autoridad del servidor.
    |
    | Cerámica / porcelanato:
    |
    |   4 cajas
    |   4 × piezas_por_caja
    |
    |   25 m²
    |   ceil(25 / m²_por_caja) × piezas_por_caja
    |
    |   30 piezas
    |   30 piezas
    |
    | El stock siempre termina expresado en piezas.
    |
    */

    private function calcularCantidadStock(
        Producto $producto,
        float $cantidad,
        string $unidad
    ): array {

        $tipo = mb_strtolower(
            trim((string) ($producto->tipoProducto?->nombre ?? '')),
            'UTF-8'
        );

        $unidadesPermitidas = $this->unidadesVenta($producto);

        if (!in_array($unidad, $unidadesPermitidas, true)) {
            throw new \Exception(
                'La unidad seleccionada no es válida para el producto ' .
                $producto->nombre . '.'
            );
        }

        if ($cantidad <= 0) {
            throw new \Exception(
                'La cantidad debe ser mayor a cero.'
            );
        }

        /*
        * CERÁMICA / PORCELANATO
        */
        if (
            in_array(
                $tipo,
                ['cerámica', 'ceramica', 'porcelanato'],
                true
            )
        ) {

            $piezasPorCaja = (int) (
                $producto->piezas_por_caja ?? 0
            );

            $m2PorCaja = (float) (
                $producto->m2_por_caja ?? 0
            );

            if ($piezasPorCaja <= 0 || $m2PorCaja <= 0) {
                throw new \Exception(
                    'El producto ' . $producto->nombre .
                    ' no tiene configuradas correctamente las piezas o los m² por caja.'
                );
            }

            /*
            * VENTA POR CAJAS
            * El inventario se controla en piezas.
            */
            if ($unidad === 'caja') {

                if (floor($cantidad) != $cantidad) {
                    throw new \Exception(
                        'La cantidad de cajas debe ser un número entero.'
                    );
                }

                $cajas = (int) $cantidad;
                $piezas = $cajas * $piezasPorCaja;

                return [
                    'cantidad_stock' => $piezas,
                    'cajas'          => $cajas,
                    'piezas'         => $piezas,
                    'm2_reales'      => $cajas * $m2PorCaja,
                ];
            }

            /*
            * VENTA POR METROS CUADRADOS
            * Se calculan las piezas enteras necesarias.
            * No se redondea a cajas completas.
            */
            if ($unidad === 'm2') {

                $m2PorPieza = $m2PorCaja / $piezasPorCaja;

                $piezas = (int) ceil(
                    ($cantidad / $m2PorPieza) - 0.000000001
                );

                $cajasCompletas = intdiv(
                    $piezas,
                    $piezasPorCaja
                );

                $piezasSueltas = $piezas % $piezasPorCaja;

                return [
                    'cantidad_stock' => $piezas,
                    'cajas'          => $cajasCompletas,
                    'piezas'         => $piezas,
                    'piezas_sueltas' => $piezasSueltas,
                    'm2_reales'      => $piezas * $m2PorPieza,
                ];
            }

            /*
            * VENTA POR PIEZAS
            * Se descuentan exactamente las piezas vendidas.
            */
            if ($unidad === 'pieza') {

                if (floor($cantidad) != $cantidad) {
                    throw new \Exception(
                        'La cantidad de piezas debe ser un número entero.'
                    );
                }

                $piezas = (int) $cantidad;

                return [
                    'cantidad_stock' => $piezas,
                    'cajas'          => intdiv(
                        $piezas,
                        $piezasPorCaja
                    ),
                    'piezas'         => $piezas,
                    'piezas_sueltas' => $piezas % $piezasPorCaja,
                    'm2_reales'      => $piezas *
                        ($m2PorCaja / $piezasPorCaja),
                ];
            }

            throw new \Exception(
                'La unidad de venta no está implementada para cerámica o porcelanato.'
            );
        }

        /*
        * CEMENTO COLA / PASTINAS
        * Se conserva la cantidad en bolsas.
        */
        if (
            in_array(
                $tipo,
                ['cemento cola', 'pastinas'],
                true
            )
        ) {
            if ($unidad !== 'bolsa') {
                throw new \Exception(
                    'Este producto debe venderse por bolsas.'
                );
            }

            if (floor($cantidad) != $cantidad) {
                throw new \Exception(
                    'La cantidad de bolsas debe ser un número entero.'
                );
            }

            return [
                'cantidad_stock' => $cantidad,
                'cajas'          => 0,
                'piezas'         => 0,
                'm2_reales'      => 0,
            ];
        }

        /*
        * LISTELO / RANDA / ESQUINERO
        * Se conserva la venta por piezas.
        */
        if ($unidad !== 'pieza') {
            throw new \Exception(
                'Este producto debe venderse por piezas.'
            );
        }

        if (floor($cantidad) != $cantidad) {
            throw new \Exception(
                'La cantidad de piezas debe ser un número entero.'
            );
        }

        return [
            'cantidad_stock' => $cantidad,
            'cajas'          => 0,
            'piezas'         => $cantidad,
            'm2_reales'      => 0,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDACIÓN
    |--------------------------------------------------------------------------
    */

    private function rules(): array
    {
        return [

            'cliente_nombre' => [
                'required',
                'string',
                'max:150'
            ],

            'cliente_documento' => [
                'required',
                'string',
                'max:30'
            ],

            'cliente_telefono' => [
                'nullable',
                'string',
                'max:30'
            ],

            'fecha_venta' => [
                'required',
                'date'
            ],

            'descuento' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'items' => [
                'required',
                'array',
                'min:1'
            ],

            'items.*.producto_id' => [
                'required',
                'integer',
                'exists:productos,id'
            ],

            'items.*.cantidad' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'items.*.unidad' => [
                'required',
                'string',
                'in:caja,m2,pieza,bolsa'
            ],


            'items.*.movimientos' => [
                'required',
                'array',
                'min:1'
            ],

            'items.*.movimientos.*.stock_user_id' => [
                'required',
                'integer',
                'exists:users,id'
            ],

            'items.*.movimientos.*.cantidad' => [
                'required',
                'numeric',
                'min:0.01'
            ],
        ];
    }

    private function messages(): array
    {
        return [

            'cliente_nombre.required' =>
                'El nombre del cliente es obligatorio.',

            'cliente_documento.required' =>
                'El CI/NIT del cliente es obligatorio.',

            'fecha_venta.required' =>
                'La fecha de venta es obligatoria.',

            'items.required' =>
                'Debes agregar al menos un producto a la venta.',

            'items.min' =>
                'Debes agregar al menos un producto a la venta.',

            'items.*.producto_id.required' =>
                'Selecciona un producto en cada línea.',

            'items.*.producto_id.exists' =>
                'El producto seleccionado no es válido.',

            'items.*.cantidad.required' =>
                'La cantidad es obligatoria en cada línea.',

            'items.*.cantidad.min' =>
                'La cantidad debe ser mayor a 0.',

            'items.*.unidad.required' =>
                'Selecciona la unidad de venta.',

            'items.*.unidad.in' =>
                'La unidad de venta seleccionada no es válida.',

            'items.*.movimientos.required' =>
                'Debes especificar de qué admin(es) se tomará el stock.',

            'items.*.movimientos.min' =>
                'Debes especificar al menos un origen de stock.',
        ];
    }
}