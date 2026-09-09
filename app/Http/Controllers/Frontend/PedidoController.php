<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PedidoController extends Controller
{
    public function __construct()
    {
       
    }

    public function checkout()
    {
        $carrito = session()->get('carrito', []);
        if (empty($carrito)) {
            return redirect()->route('frontend.carrito')->with('error', 'Tu carrito está vacío');
        }

        $departamentos = [
            'La Paz', 'Cochabamba', 'Santa Cruz', 'Oruro', 'Potosí',
            'Tarija', 'Chuquisaca', 'Beni', 'Pando'
        ];

        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['total'];
        }

        return view('frontend.checkout', compact('carrito', 'total', 'departamentos'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'telefono' => 'required|string|max:20',
            'departamento' => 'required|string|max:50',
            'direccion' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $carrito = session()->get('carrito', []);
        if (empty($carrito)) {
            return response()->json(['message' => 'Carrito vacío'], 400);
        }

        $subtotal = 0;
        foreach ($carrito as $item) {
            $subtotal += $item['total'];
        }

        try {
            DB::beginTransaction();

            $pedido = Pedido::create([
                'user_id' => auth()->id(),
                'codigo' => Pedido::generarCodigo(),
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'estado' => 'pendiente',
                'nombre_completo' => $request->nombre_completo,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'departamento' => $request->departamento,
                'direccion' => $request->direccion,
                'observaciones' => $request->observaciones,
            ]);

            foreach ($carrito as $item) {
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['id'],
                    'color_id' => $item['color']['id'] ?? null,
                    'producto_nombre' => $item['nombre'],
                    'color_nombre' => $item['color']['nombre'] ?? null,
                    'medida' => $item['medida'] ?? null,
                    'material' => $item['material'] ?? null,
                    'precio_unitario' => $item['precio_base'] + ($item['color']['precio'] ?? 0),
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $item['total'],
                ]);
            }

            // Limpiar carrito
            session()->forget('carrito');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido realizado exitosamente',
                'pedido_id' => $pedido->id,
                'codigo' => $pedido->codigo
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al procesar el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function confirmacion($id)
    {
        $pedido = Pedido::with('detalles')->where('user_id', auth()->id())->findOrFail($id);
        return view('cliente.dashboard', compact('pedido'));
    }

    public function misPedidos()
    {
        $pedidos = Pedido::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('frontend.mis_pedidos', compact('pedidos'));
    }

    public function show($id)
    {
        $pedido = Pedido::with('detalles')->where('user_id', auth()->id())->findOrFail($id);
        return view('frontend.pedido_detalle', compact('pedido'));
    }
}