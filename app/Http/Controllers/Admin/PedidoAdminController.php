<?php
// app/Http/Controllers/Admin/PedidoController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\ProductoColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoAdminController extends Controller
{
    public function __construct()
    {
    
    }

    // Lista de todos los pedidos
    public function index()
    {
        $pedidos = Pedido::with('user')->orderBy('created_at', 'desc')->get();
        $estados = ['pendiente', 'listo', 'entregado'];
        return view('admin.ordenes.pedidos.index', compact('pedidos', 'estados'));
    }

    // Ver detalle de un pedido específico
    public function show($id)
    {
        $pedido = Pedido::with(['user', 'detalles'])->findOrFail($id);
        return view('admin.ordenes.pedidos.show', compact('pedido'));
    }

    // Actualizar estado del pedido
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,listo,entregado'
        ]);

        $pedido = Pedido::findOrFail($id);
        $estadoAnterior = $pedido->estado;
        $nuevoEstado = $request->estado;

        // Si el nuevo estado es "entregado" y el anterior no era "entregado", descontar stock
        if ($nuevoEstado === 'entregado' && $estadoAnterior !== 'entregado') {
            try {
                DB::beginTransaction();
                
                foreach ($pedido->detalles as $detalle) {
                    // Descontar stock del producto principal
                    $producto = Producto::find($detalle->producto_id);
                    if ($producto) {
                        $producto->stock -= $detalle->cantidad;
                        $producto->save();
                    }
                    
                    // Descontar stock del color específico si existe
                    if ($detalle->color_id) {
                        $color = ProductoColor::find($detalle->color_id);
                        if ($color) {
                            $color->stock -= $detalle->cantidad;
                            $color->save();
                        }
                    }
                }
                
                $pedido->estado = $nuevoEstado;
                $pedido->save();
                
                DB::commit();
                
                $message = 'Estado actualizado correctamente y stock descontado';
                
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error al descontar el stock: ' . $e->getMessage()
                ], 500);
            }
        } else {
            // Solo actualizar estado sin descontar stock
            $pedido->estado = $nuevoEstado;
            $pedido->save();
            $message = 'Estado actualizado correctamente';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'estado' => $pedido->estado
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    // Eliminar pedido
    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pedido eliminado correctamente'
        ]);
    }
}