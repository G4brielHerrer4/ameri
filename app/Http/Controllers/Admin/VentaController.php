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
                'id'               => $producto->id,
                'nombre'           => $producto->nombre,
                'tipo'             => $producto->tipoProducto?->nombre,
                'unidad_base'      => $producto->unidad_base,
                'unidades_venta'   => $unidadesVenta,
                'piezas_por_caja'  => (int) ($producto->piezas_por_caja ?? 0),
                'm2_por_caja'      => (float) ($producto->m2_por_caja ?? 0),
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
                $unidad   = $item['unidad'];

                /*
                 * El servidor vuelve a calcular la cantidad física
                 * que realmente debe salir del inventario.
                 */
                $conversion = $this->calcularCantidadStock(
                    $producto,
                    $cantidad,
                    $unidad
                );

                $cantidadStock = $conversion['cantidad_stock'];

                /*
                 * Verificar que los movimientos enviados
                 * coincidan exactamente con el stock físico
                 * necesario.
                 */
                $totalMovimientos = collect(
                    $item['movimientos'] ?? []
                )->sum(function ($mov) {
                    return (float) $mov['cantidad'];
                });

                if (abs($totalMovimientos - $cantidadStock) > 0.01) {
                    throw new \Exception(
                        'El stock asignado para ' .
                        $producto->nombre .
                        ' no coincide con la cantidad física requerida.'
                    );
                }

                $precioUnit = (float) $item['precio_unitario'];

                /*
                 * El precio corresponde a la unidad que eligió
                 * el vendedor:
                 *
                 * caja  -> precio por caja
                 * m2    -> precio por m²
                 * pieza -> precio por pieza
                 * bolsa -> precio por bolsa
                 */
                $lineSubtotal = $cantidad * $precioUnit;

                $subtotal += $lineSubtotal;

                $detalle = VentaDetalle::create([
                    'venta_id'        => $venta->id,
                    'producto_id'     => $producto->id,
                    'cantidad'        => $cantidad,
                    'unidad'          => $unidad,
                    'cantidad_stock'  => $cantidadStock,
                    'precio_unitario' => $precioUnit,
                    'subtotal'        => $lineSubtotal,
                ]);

                foreach ($item['movimientos'] as $mov) {

                    $cantMov = (float) $mov['cantidad'];

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

            $venta->update([
                'subtotal' => $subtotal,
                'total'    => max(0, $subtotal - $descuento),
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

            $venta->detalles()->delete();

            $subtotal = 0;

            foreach ($request->items as $item) {

                $producto = Producto::with('tipoProducto')
                    ->findOrFail($item['producto_id']);

                $cantidad = (float) $item['cantidad'];
                $unidad   = $item['unidad'];

                $conversion = $this->calcularCantidadStock(
                    $producto,
                    $cantidad,
                    $unidad
                );

                $cantidadStock = $conversion['cantidad_stock'];

                $totalMovimientos = collect(
                    $item['movimientos'] ?? []
                )->sum(function ($mov) {
                    return (float) $mov['cantidad'];
                });

                if (abs($totalMovimientos - $cantidadStock) > 0.01) {
                    throw new \Exception(
                        'El stock asignado para ' .
                        $producto->nombre .
                        ' no coincide con la cantidad física requerida.'
                    );
                }

                $precioUnit = (float) $item['precio_unitario'];

                $lineSubtotal = $cantidad * $precioUnit;

                $subtotal += $lineSubtotal;

                $detalle = VentaDetalle::create([
                    'venta_id'        => $venta->id,
                    'producto_id'     => $producto->id,
                    'cantidad'        => $cantidad,
                    'unidad'          => $unidad,
                    'cantidad_stock'  => $cantidadStock,
                    'precio_unitario' => $precioUnit,
                    'subtotal'        => $lineSubtotal,
                ]);

                foreach ($item['movimientos'] as $mov) {

                    $cantMov = (float) $mov['cantidad'];

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

            $venta->update([
                'subtotal' => $subtotal,
                'total'    => max(0, $subtotal - $descuento),
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

    /*
    |--------------------------------------------------------------------------
    | UNIDADES DE VENTA
    |--------------------------------------------------------------------------
    */

    private function unidadesVenta(Producto $producto): array
    {
        $tipo = strtolower(
            trim($producto->tipoProducto?->nombre ?? '')
        );

        return match (true) {

            in_array(
                $tipo,
                ['cerámica', 'porcelanato']
            )
                => ['caja', 'm2', 'pieza'],

            $tipo === 'cemento cola'
                => ['bolsa'],

            $tipo === 'pastinas'
                => ['bolsa'],

            in_array(
                $tipo,
                [
                    'listelo',
                    'randa decorativa',
                    'esquinero de aluminio',
                    'esquinero de goma'
                ]
            )
                => ['pieza'],

            default
                => ['pieza'],
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

        $tipo = strtolower(
            trim($producto->tipoProducto?->nombre ?? '')
        );

        $unidadesPermitidas =
            $this->unidadesVenta($producto);

        if (!in_array($unidad, $unidadesPermitidas)) {

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
                ['cerámica', 'porcelanato']
            )
        ) {

            $piezasPorCaja =
                (int) ($producto->piezas_por_caja ?? 0);

            $m2PorCaja =
                (float) ($producto->m2_por_caja ?? 0);

            if (
                $piezasPorCaja <= 0 ||
                $m2PorCaja <= 0
            ) {

                throw new \Exception(
                    'El producto ' .
                    $producto->nombre .
                    ' no tiene configuradas correctamente las piezas o los m² por caja.'
                );
            }

            /*
             * VENTA POR CAJAS
             */
            if ($unidad === 'caja') {

                if (floor($cantidad) != $cantidad) {

                    throw new \Exception(
                        'La cantidad de cajas debe ser un número entero.'
                    );
                }

                $cajas = (int) $cantidad;

                return [
                    'cantidad_stock' =>
                        $cajas * $piezasPorCaja,

                    'cajas' =>
                        $cajas,

                    'm2_reales' =>
                        $cajas * $m2PorCaja,
                ];
            }

            /*
             * VENTA POR METROS CUADRADOS
             *
             * Se utilizan cajas completas.
             */
            if ($unidad === 'm2') {

                $cajas = (int) ceil(
                    $cantidad / $m2PorCaja
                );

                return [
                    'cantidad_stock' =>
                        $cajas * $piezasPorCaja,

                    'cajas' =>
                        $cajas,

                    'm2_reales' =>
                        $cajas * $m2PorCaja,
                ];
            }

            /*
             * VENTA POR PIEZAS
             *
             * Aquí no redondeamos a cajas.
             * Si se venden 30 piezas, se descuentan
             * exactamente 30 piezas.
             */
            if ($unidad === 'pieza') {

                return [
                    'cantidad_stock' =>
                        $cantidad,

                    'cajas' =>
                        $cantidad / $piezasPorCaja,

                    'm2_reales' =>
                        (
                            $cantidad /
                            $piezasPorCaja
                        ) * $m2PorCaja,
                ];
            }
        }

        /*
         * CEMENTO COLA / PASTINAS
         */
        if (
            $tipo === 'cemento cola' ||
            $tipo === 'pastinas'
        ) {

            return [
                'cantidad_stock' => $cantidad,
                'cajas'          => 0,
                'm2_reales'      => 0,
            ];
        }

        /*
         * LISTELO / RANDA / ESQUINERO
         */
        return [
            'cantidad_stock' => $cantidad,
            'cajas'          => 0,
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

            'items.*.precio_unitario' => [
                'required',
                'numeric',
                'min:0.01'
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

            'items.*.precio_unitario.required' =>
                'El precio unitario es obligatorio.',

            'items.*.precio_unitario.min' =>
                'El precio unitario debe ser mayor a 0.',

            'items.*.movimientos.required' =>
                'Debes especificar de qué admin(es) se tomará el stock.',

            'items.*.movimientos.min' =>
                'Debes especificar al menos un origen de stock.',
        ];
    }
}