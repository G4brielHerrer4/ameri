<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\CompraDetalle;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
   
public function index()
{
    // Obtener todos los administradores.
    $admins = User::whereHas('role', function ($q) {
        $q->where('slug', 'administrador');
    })
        ->orderBy('name')
        ->get();

    // Obtener productos junto con sus stocks y administradores.
    $productos = Producto::with([
        'tipoProducto.categoria',
        'stocks.user',
    ])
        ->orderBy('nombre')
        ->get();

    $inventario = $productos->map(function ($producto) use ($admins) {
        // Sumar el stock real de todos los administradores.
        $stockTotal = $producto->stocks->sum('cantidad');

        // Indexar los stocks por ID de administrador.
        $stocksExistentes = $producto->stocks->keyBy('user_id');

        // Preparar una entrada para cada administrador.
        $stockPorAdmin = $admins->mapWithKeys(function ($admin) use ($stocksExistentes) {
            return [
                $admin->id => $stocksExistentes->get($admin->id),
            ];
        });

        // Obtener la última compra recibida del producto.
        $ultimaCompra = CompraDetalle::where('producto_id', $producto->id)
            ->whereHas('compra', function ($query) {
                $query->where('estado', 'recibida');
            })
            ->latest('id')
            ->first();

        return [
            'producto'             => $producto,
            'stock_total'          => (float) $stockTotal,
            'stock_por_admin'      => $stockPorAdmin,
            'stock_minimo'         => (float) $producto->stock_minimo,
            'stock_maximo'         => (float) $producto->stock_maximo,
            'precio_compra'        => $ultimaCompra?->precio_unitario,
            'unidad_precio_compra' => $ultimaCompra?->unidad_compra,
        ];
    });

    return view('admin.stocks.index', compact(
        'admins',
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

        $producto->update([
            'stock_minimo' => $request->stock_minimo,
            'stock_maximo' => $request->stock_maximo,
        ]);

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

    $esCeramica = in_array(
        $tipo,
        ['cerámica', 'ceramica', 'porcelanato'],
        true
    );

    $esBolsa = in_array(
        $tipo,
        ['cemento cola', 'pastinas'],
        true
    );

    $esPieza = in_array(
        $tipo,
        [
            'listelo',
            'randa decorativa',
            'esquinero de aluminio',
            'esquinero de goma',
        ],
        true
    );

    // Validar los precios según el tipo de producto.
    if ($esCeramica) {
        $campos = [
            'precio_venta_caja' => ['required', 'numeric', 'min:0.01'],
            'precio_venta_pieza' => ['required', 'numeric', 'min:0.01'],
        ];
    } elseif ($esBolsa) {
        $campos = [
            'precio_venta_bolsa' => ['required', 'numeric', 'min:0.01'],
        ];
    } elseif ($esPieza) {
        $campos = [
            'precio_venta_pieza' => ['required', 'numeric', 'min:0.01'],
        ];
    } else {
        $campos = [
            'precio_venta_caja'  => ['nullable', 'numeric', 'min:0.01'],
            'precio_venta_m2'    => ['nullable', 'numeric', 'min:0.01'],
            'precio_venta_pieza' => ['nullable', 'numeric', 'min:0.01'],
            'precio_venta_bolsa' => ['nullable', 'numeric', 'min:0.01'],
        ];
    }

    $validator = Validator::make(
        $request->all(),
        $campos,
        [
            'precio_venta_caja.required' =>
                'Debes ingresar el precio de venta por caja.',
            'precio_venta_pieza.required' =>
                'Debes ingresar el precio de venta por pieza.',
            'precio_venta_bolsa.required' =>
                'Debes ingresar el precio de venta por bolsa.',
            'precio_venta_caja.numeric' =>
                'El precio por caja debe ser numérico.',
            'precio_venta_pieza.numeric' =>
                'El precio por pieza debe ser numérico.',
            'precio_venta_bolsa.numeric' =>
                'El precio por bolsa debe ser numérico.',
            'precio_venta_caja.min' =>
                'El precio por caja debe ser mayor que cero.',
            'precio_venta_pieza.min' =>
                'El precio por pieza debe ser mayor que cero.',
            'precio_venta_bolsa.min' =>
                'El precio por bolsa debe ser mayor que cero.',
        ]
    );

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->with('error_precios_producto', $producto->id)
            ->withInput();
    }

    // Cerámicas y porcelanatos: guardar ambos precios independientes.
    if ($esCeramica) {
        $producto->update([
            'precio_venta_caja' => $request->input('precio_venta_caja'),
            'precio_venta_pieza' => $request->input('precio_venta_pieza'),
        ]);
    } elseif ($esBolsa) {
        $producto->update([
            'precio_venta_bolsa' => $request->input('precio_venta_bolsa'),
        ]);
    } elseif ($esPieza) {
        $producto->update([
            'precio_venta_pieza' => $request->input('precio_venta_pieza'),
        ]);
    } else {
        // En otros tipos, actualizar únicamente los campos enviados.
        $precios = [];

        foreach ([
            'precio_venta_caja',
            'precio_venta_m2',
            'precio_venta_pieza',
            'precio_venta_bolsa',
        ] as $campo) {
            if ($request->exists($campo)) {
                $valor = $request->input($campo);

                $precios[$campo] = $valor === '' || $valor === null
                    ? null
                    : $valor;
            }
        }

        if (!empty($precios)) {
            $producto->update($precios);
        }
    }

    return redirect()
        ->route('admin.stocks.index')
        ->with('success', 'Precios de venta actualizados correctamente.');
}

}