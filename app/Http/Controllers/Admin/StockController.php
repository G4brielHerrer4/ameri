<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompraDetalle;
use App\Models\Deposito;
use App\Models\PrecioProductoAdmin;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function index()
    {
        $adminActualId = Auth::id();

        $admins = User::whereHas('role', function ($q) {
            $q->where('slug', 'administrador');
        })->orderBy('name')->get();

        $depositos = Deposito::where('estado', true)->orderBy('nombre')->get();

        $productos = Producto::with([
            'tipoProducto.categoria',
            'stocks',
            'preciosAdmin',
        ])
            ->orderBy('nombre')
            ->get();

        $inventario = $productos->map(function ($producto) use ($adminActualId, $depositos, $admins) {

            // Stocks del admin logueado
            $stocksPropios = $producto->stocks
                ->where('user_id', $adminActualId);

            // Mapa por depósito para el admin logueado
            $stockPorDeposito = $depositos->mapWithKeys(function ($dep) use ($stocksPropios) {
                $s = $stocksPropios->firstWhere('deposito_id', $dep->id);
                return [$dep->id => $s ? (float) $s->cantidad : 0];
            });

            $stockTotalPropio = $stocksPropios->sum('cantidad');

            // Config del admin logueado (precios + límites)
            $config = $producto->preciosAdmin
                ->firstWhere('user_id', $adminActualId);

            // Stock por otros admins (para el modal ojo)
            $otrosAdmins = $producto->stocks
                ->where('user_id', '!=', $adminActualId)
                ->groupBy('user_id')
                ->map(function ($stocksDelOtro, $userId) use ($depositos, $producto, $admins) {
                    $admin = $admins->firstWhere('id', $userId);
                    $configOtro = $producto->preciosAdmin->firstWhere('user_id', $userId);

                    $porDeposito = $depositos->map(function ($dep) use ($stocksDelOtro) {
                        $s = $stocksDelOtro->firstWhere('deposito_id', $dep->id);
                        return [
                            'deposito_id' => $dep->id,
                            'nombre'      => $dep->nombre,
                            'cantidad'    => $s ? (float) $s->cantidad : 0,
                        ];
                    })->values();

                    return [
                        'user_id'      => $userId,
                        'nombre'       => $admin?->name ?? 'Admin #' . $userId,
                        'stock_total'  => (float) $stocksDelOtro->sum('cantidad'),
                        'por_deposito' => $porDeposito,
                        'precio_caja'  => $configOtro?->precio_venta_caja,
                        'precio_pieza' => $configOtro?->precio_venta_pieza,
                        'precio_bolsa' => $configOtro?->precio_venta_bolsa,
                    ];
                })->values();

            /* ============================================================
               ESTADO DE INVENTARIO
               Se calcula sobre la SUMA del stock del admin en todas sus tiendas
               ============================================================ */
            $tipoNombre     = mb_strtolower(trim((string) ($producto->tipoProducto?->nombre ?? '')), 'UTF-8');
            $piezasPorCaja  = (float) ($producto->piezas_por_caja ?? 0);
            $esCaja         = in_array($tipoNombre, ['cerámica', 'ceramica', 'porcelanato'], true) && $piezasPorCaja > 0;

            // Normalizamos el stock a "cajas" si aplica, para comparar con los límites
            $stockParaEstado = $esCaja ? ($stockTotalPropio / $piezasPorCaja) : $stockTotalPropio;

            $min = $config ? (float) $config->stock_minimo : 0;
            $max = $config ? (float) $config->stock_maximo : 0;

            $sinLimites = ($min <= 0 && $max <= 0);

            if ($sinLimites) {
                $estado      = 'Sin límites';
                $estadoClase = 'sin-limites';
                $filaClase   = 'row-sinlimites';
                $estadoIcono = 'mdi-tune-variant';
            } elseif ($stockTotalPropio <= 0) {
                $estado      = 'Sin stock';
                $estadoClase = 'sin-stock';
                $filaClase   = 'row-sinstock';
                $estadoIcono = 'mdi-close-circle-outline';
            } elseif ($min > 0 && $stockParaEstado < $min) {
                $estado      = 'Stock bajo';
                $estadoClase = 'bajo';
                $filaClase   = 'row-bajo';
                $estadoIcono = 'mdi-alert-outline';
            } elseif ($max > 0 && $stockParaEstado > $max) {
                $estado      = 'Sobreexistencia';
                $estadoClase = 'sobre';
                $filaClase   = 'row-sobre';
                $estadoIcono = 'mdi-alert-circle-outline';
            } else {
                $estado      = 'Normal';
                $estadoClase = 'normal';
                $filaClase   = 'row-normal';
                $estadoIcono = 'mdi-check-circle-outline';
            }

            // Último precio de compra recibida
            $ultimaCompra = CompraDetalle::where('producto_id', $producto->id)
                ->whereHas('compra', fn($q) => $q->where('estado', 'recibida'))
                ->latest('id')
                ->first();

            return [
                'producto'              => $producto,
                'stock_por_deposito'    => $stockPorDeposito,
                'stock_total_propio'    => (float) $stockTotalPropio,
                'config'                => $config,
                'otros_admins'          => $otrosAdmins,
                'precio_compra'         => $ultimaCompra?->precio_unitario,
                'unidad_precio_compra'  => $ultimaCompra?->unidad_compra,

                // Datos de estado para la vista
                'estado'                => $estado,
                'estado_clase'          => $estadoClase,
                'fila_clase'            => $filaClase,
                'estado_icono'          => $estadoIcono,
            ];
        })
        // Mostrar solo productos donde el admin tiene stock > 0 o tiene config guardada
        ->filter(function ($item) {
            return $item['stock_total_propio'] > 0 || $item['config'] !== null;
        })
        ->values();

        return view('admin.stocks.index', compact(
            'admins',
            'depositos',
            'inventario'
        ));
    }

    public function actualizarLimites(Request $request, Producto $producto)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'stock_minimo' => ['required', 'numeric', 'min:0'],
                'stock_maximo' => ['required', 'numeric', 'min:0'],
            ],
            [
                'stock_minimo.required' => 'El stock mínimo es obligatorio.',
                'stock_minimo.numeric'  => 'El stock mínimo debe ser numérico.',
                'stock_minimo.min'      => 'El stock mínimo no puede ser negativo.',

                'stock_maximo.required' => 'El stock máximo es obligatorio.',
                'stock_maximo.numeric'  => 'El stock máximo debe ser numérico.',
                'stock_maximo.min'      => 'El stock máximo no puede ser negativo.',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('error_limites_producto', $producto->id);
        }

        if ((float) $request->stock_maximo < (float) $request->stock_minimo) {
            return back()
                ->withErrors([
                    'stock_maximo' => 'El stock máximo no puede ser menor que el stock mínimo.',
                ])
                ->with('error_limites_producto', $producto->id);
        }

        PrecioProductoAdmin::updateOrCreate(
            [
                'user_id'     => Auth::id(),
                'producto_id' => $producto->id,
            ],
            [
                'stock_minimo' => $request->stock_minimo,
                'stock_maximo' => $request->stock_maximo,
            ]
        );

        return redirect()
            ->route('admin.stocks.index')
            ->with('success', 'Límites de inventario actualizados correctamente.');
    }

    public function actualizarPrecios(Request $request, Producto $producto)
    {
        $tipo = mb_strtolower(
            trim((string) ($producto->tipoProducto?->nombre ?? '')),
            'UTF-8'
        );

        $esCeramica = in_array($tipo, ['cerámica', 'ceramica', 'porcelanato'], true);
        $esBolsa    = in_array($tipo, ['cemento cola', 'pastinas'], true);
        $esPieza    = in_array($tipo, [
            'listelo', 'randa decorativa',
            'esquinero de aluminio', 'esquinero de goma',
        ], true);

        if ($esCeramica) {
            $campos = [
                'precio_venta_caja'  => ['required', 'numeric', 'min:0.01'],
                'precio_venta_pieza' => ['required', 'numeric', 'min:0.01'],
            ];
        } elseif ($esBolsa) {
            $campos = ['precio_venta_bolsa' => ['required', 'numeric', 'min:0.01']];
        } elseif ($esPieza) {
            $campos = ['precio_venta_pieza' => ['required', 'numeric', 'min:0.01']];
        } else {
            $campos = [
                'precio_venta_caja'  => ['nullable', 'numeric', 'min:0.01'],
                'precio_venta_m2'    => ['nullable', 'numeric', 'min:0.01'],
                'precio_venta_pieza' => ['nullable', 'numeric', 'min:0.01'],
                'precio_venta_bolsa' => ['nullable', 'numeric', 'min:0.01'],
            ];
        }

        $validator = Validator::make($request->all(), $campos, [
            'precio_venta_caja.required'  => 'Debes ingresar el precio de venta por caja.',
            'precio_venta_pieza.required' => 'Debes ingresar el precio de venta por pieza.',
            'precio_venta_bolsa.required' => 'Debes ingresar el precio de venta por bolsa.',
            'precio_venta_caja.numeric'   => 'El precio por caja debe ser numérico.',
            'precio_venta_pieza.numeric'  => 'El precio por pieza debe ser numérico.',
            'precio_venta_bolsa.numeric'  => 'El precio por bolsa debe ser numérico.',
            'precio_venta_caja.min'       => 'El precio por caja debe ser mayor que cero.',
            'precio_venta_pieza.min'      => 'El precio por pieza debe ser mayor que cero.',
            'precio_venta_bolsa.min'      => 'El precio por bolsa debe ser mayor que cero.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('error_precios_producto', $producto->id)
                ->withInput();
        }

        $valores = [
            'precio_venta_caja'  => null,
            'precio_venta_m2'    => null,
            'precio_venta_pieza' => null,
            'precio_venta_bolsa' => null,
        ];

        if ($esCeramica) {
            $valores['precio_venta_caja']  = $request->input('precio_venta_caja');
            $valores['precio_venta_pieza'] = $request->input('precio_venta_pieza');
        } elseif ($esBolsa) {
            $valores['precio_venta_bolsa'] = $request->input('precio_venta_bolsa');
        } elseif ($esPieza) {
            $valores['precio_venta_pieza'] = $request->input('precio_venta_pieza');
        } else {
            foreach (array_keys($valores) as $campo) {
                if ($request->exists($campo)) {
                    $v = $request->input($campo);
                    $valores[$campo] = ($v === '' || $v === null) ? null : $v;
                }
            }
        }

        PrecioProductoAdmin::updateOrCreate(
            [
                'user_id'     => Auth::id(),
                'producto_id' => $producto->id,
            ],
            $valores
        );

        return redirect()
            ->route('admin.stocks.index')
            ->with('success', 'Precios de venta actualizados correctamente.');
    }
}