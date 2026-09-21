<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function index()
    {
        $admins = User::whereHas('role', function ($q) {
            $q->where('slug', 'administrador');
        })
            ->orderBy('name')
            ->get();

        $productos = Producto::with([
            'tipoProducto.categoria',
            'stocks.user',
        ])
            ->orderBy('nombre')
            ->get();

        $inventario = $productos->map(function ($producto) {
            $stockTotal = $producto->stocks->sum('cantidad');

            $stockPorAdmin = $producto->stocks
                ->keyBy('user_id');

            return [
                'producto'       => $producto,
                'stock_total'    => (float) $stockTotal,
                'stock_por_admin'=> $stockPorAdmin,
                'stock_minimo'   => (float) $producto->stock_minimo,
                'stock_maximo'   => (float) $producto->stock_maximo,
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
}