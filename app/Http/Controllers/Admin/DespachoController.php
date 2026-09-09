<?php
// app/Http/Controllers/Admin/DespachoController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\ProductoColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DespachoController extends Controller
{
    public function __construct()
    {

    }

    // Lista de pedidos listos para despachar
    public function index()
    {
        $pedidos = Pedido::with('user')
            ->whereIn('estado', ['listo', 'entregado'])
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('admin.ordenes.despachos.index', compact('pedidos'));
    }

    // Marcar pedido como entregado (con descuento de stock)
    public function marcarEntregado($id)
    {
        try {
            DB::beginTransaction();
            
            $pedido = Pedido::findOrFail($id);
            
            // Verificar que no esté ya entregado
            if ($pedido->estado === 'entregado') {
                return response()->json([
                    'success' => false,
                    'message' => 'El pedido ya está marcado como entregado'
                ], 400);
            }
            
            // Descontar stock por cada producto del pedido
            foreach ($pedido->detalles as $detalle) {
                // Descontar stock del producto principal
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->stock -= $detalle->cantidad;
                    $producto->save();
                }
                
                // Descontar stock del color específico
                if ($detalle->color_id) {
                    $color = ProductoColor::find($detalle->color_id);
                    if ($color) {
                        $color->stock -= $detalle->cantidad;
                        $color->save();
                    }
                }
            }
            
            $pedido->estado = 'entregado';
            $pedido->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pedido marcado como entregado y stock actualizado'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar: ' . $e->getMessage()
            ], 500);
        }
    }
}