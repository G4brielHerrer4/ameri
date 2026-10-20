<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\SolicitudStock;
use App\Models\SolicitudStockDetalle;
use App\Models\Stock;
use App\Models\Deposito;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudStockController extends Controller
{
    public function index()
    {
        $misSolicitudes = SolicitudStock::with(['proveedor', 'detalles.producto'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $solicitudesRecibidas = SolicitudStock::with(['solicitante', 'detalles.producto'])
            ->where('proveedor_user_id', auth()->id())
            ->orderBy('estado', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $depositos = Deposito::where('estado', true)->orderBy('nombre')->get();

        // Obtenemos el stock actual del admin logueado para mostrarlo en el select
        $misStocks = Stock::where('user_id', auth()->id())->get();

        return view('admin.solicitudes.index', compact(
            'misSolicitudes', 
            'solicitudesRecibidas', 
            'depositos',
            'misStocks'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|numeric|min:0.01',
            'nota' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $solicitud = SolicitudStock::create([
                'user_id' => auth()->id(),
                'proveedor_user_id' => $request->proveedor_user_id,
                'estado' => 'pendiente',
                'nota' => $request->nota,
            ]);

            foreach ($request->items as $item) {
                SolicitudStockDetalle::create([
                    'solicitud_id' => $solicitud->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                ]);
            }

            Notificacion::create([
                'user_id' => $request->proveedor_user_id,
                'from_user_id' => auth()->id(),
                'tipo' => 'info',
                'titulo' => 'Nueva solicitud de stock',
                'mensaje' => auth()->user()->name . ' te ha solicitado stock para una venta interna. Revisa la solicitud.',
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Solicitud enviada.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function aprobar(Request $request, SolicitudStock $solicitud)
    {
        if ($solicitud->proveedor_user_id !== auth()->id()) {
            abort(403);
        }

        // 🔥 Validación con mensajes claros
        $request->validate([
            'detalles' => 'required|array',
            'detalles.*.precio' => 'required|numeric|min:0.01',
            'detalles.*.deposito_origen_id' => 'required|exists:depositos,id'
        ], [
            'detalles.*.precio.required' => 'Debes ingresar el precio de venta.',
            'detalles.*.precio.min' => 'El precio debe ser mayor a 0.',
            'detalles.*.deposito_origen_id.required' => 'Debes seleccionar la tienda de origen.'
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->detalles as $detalleId => $data) {
                $detalle = SolicitudStockDetalle::findOrFail($detalleId);
                
                // Verificar stock en la tienda seleccionada
                $stock = Stock::where('user_id', auth()->id())
                    ->where('producto_id', $detalle->producto_id)
                    ->where('deposito_id', $data['deposito_origen_id'])
                    ->first();

                if (!$stock || $stock->cantidad < $detalle->cantidad) {
                    $tienda = Deposito::find($data['deposito_origen_id'])->nombre ?? 'esa tienda';
                    throw new \Exception("No tienes suficiente stock en {$tienda} para el producto " . $detalle->producto->nombre);
                }

                // Descontar stock
                $stock->decrement('cantidad', $detalle->cantidad);

                // Guardar precio y tienda origen
                $detalle->update([
                    'precio_acordado' => $data['precio'],
                    'deposito_origen_id' => $data['deposito_origen_id'],
                ]);
            }

            $solicitud->update(['estado' => 'aprobada']);

            Notificacion::create([
                'user_id' => $solicitud->user_id,
                'from_user_id' => auth()->id(),
                'tipo' => 'info',
                'titulo' => 'Solicitud Aprobada',
                'mensaje' => auth()->user()->name . ' aprobó tu solicitud y ya descontó el stock. Elige a qué tienda quieres que llegue.',
            ]);

            DB::commit();
            return redirect()->route('admin.solicitudes.index')->with('success', 'Solicitud aprobada y stock descontado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            // 🔥 Redirigimos con el error a la vista y mantenemos los datos
            return back()->with('error', 'Error al aprobar: ' . $e->getMessage())->withInput();
        }
    }

    public function recibir(Request $request, SolicitudStock $solicitud)
    {
        if ($solicitud->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'detalles' => 'required|array',
            'detalles.*.deposito_destino_id' => 'required|exists:depositos,id'
        ], [
            'detalles.*.deposito_destino_id.required' => 'Debes seleccionar tu tienda destino.'
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->detalles as $detalleId => $data) {
                $detalle = SolicitudStockDetalle::findOrFail($detalleId);
                
                Stock::incrementar(
                    auth()->id(),
                    $detalle->producto_id,
                    $detalle->cantidad,
                    $data['deposito_destino_id']
                );

                $detalle->update(['deposito_destino_id' => $data['deposito_destino_id']]);
            }

            $solicitud->update(['estado' => 'completada']);

            Notificacion::create([
                'user_id' => $solicitud->proveedor_user_id,
                'from_user_id' => auth()->id(),
                'tipo' => 'info',
                'titulo' => 'Recepción confirmada',
                'mensaje' => auth()->user()->name . ' confirmó la recepción del stock. La venta interna se completó con éxito.',
            ]);

            DB::commit();
            return redirect()->route('admin.solicitudes.index')->with('success', 'Stock ingresado a tus tiendas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al recibir: ' . $e->getMessage())->withInput();
        }
    }

    public function rechazar(SolicitudStock $solicitud)
    {
        if ($solicitud->proveedor_user_id !== auth()->id()) {
            abort(403);
        }
        
        $solicitud->update(['estado' => 'rechazada']);

        Notificacion::create([
            'user_id' => $solicitud->user_id,
            'from_user_id' => auth()->id(),
            'tipo' => 'info',
            'titulo' => 'Solicitud rechazada',
            'mensaje' => auth()->user()->name . ' rechazó tu solicitud de stock.',
        ]);

        return back()->with('success', 'Solicitud rechazada.');
    }
}