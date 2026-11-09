<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VendedorController extends Controller
{

public function index(Request $request)
{
    $userId = auth()->id();

    // Fechas predeterminadas: último mes móvil hasta hoy.
    $fechaHastaDefault = now()->toDateString();
    $fechaDesdeDefault = now()->copy()->subMonthNoOverflow()->toDateString();

    // Obtener fechas del formulario o utilizar las predeterminadas.
    $request->validate([
        'desde' => ['nullable', 'date_format:Y-m-d'],
        'hasta' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:desde'],
    ], [
        'desde.date_format' => 'La fecha inicial no es válida.',
        'hasta.date_format' => 'La fecha final no es válida.',
        'hasta.after_or_equal' => 'La fecha Hasta debe ser igual o posterior a Desde.',
    ]);

    $desde = $request->filled('desde')
        ? \Carbon\Carbon::parse($request->desde)->startOfDay()
        : \Carbon\Carbon::parse($fechaDesdeDefault)->startOfDay();

    $hasta = $request->filled('hasta')
        ? \Carbon\Carbon::parse($request->hasta)->endOfDay()
        : \Carbon\Carbon::parse($fechaHastaDefault)->endOfDay();

    // Ventas del vendedor dentro del rango seleccionado.
    $consultaVentas = Venta::where('user_id', $userId)
        ->whereBetween('fecha_venta', [
            $desde->toDateString(),
            $hasta->toDateString()
        ]);

    $ventasPeriodo = (clone $consultaVentas)
        ->latest('fecha_venta')
        ->get();

    // Resumen de ventas.
    $totalVentas = $ventasPeriodo->count();

    $ventasPendientes = $ventasPeriodo
        ->where('estado', 'pendiente')
        ->count();

    $ventasVendidas = $ventasPeriodo
        ->where('estado', 'vendida')
        ->count();

    $ventasCanceladas = $ventasPeriodo
        ->where('estado', 'cancelada')
        ->count();

    // Ingresos únicamente de ventas confirmadas.
    $ingresosPeriodo = (clone $consultaVentas)
        ->where('estado', 'vendida')
        ->sum('total');

    // Datos del gráfico.
    $ventasGrafico = Venta::where('user_id', $userId)
        ->where('estado', 'vendida')
        ->whereBetween('fecha_venta', [
            $desde->toDateString(),
            $hasta->toDateString()
        ])
        ->selectRaw('DATE(fecha_venta) as dia, SUM(total) as total')
        ->groupBy(DB::raw('DATE(fecha_venta)'))
        ->orderBy('dia')
        ->get()
        ->keyBy('dia');

    $chartLabels = [];
    $chartData = [];

    $fechaRecorrido = $desde->copy()->startOfDay();
    $fechaFinal = $hasta->copy()->startOfDay();

    while ($fechaRecorrido->lte($fechaFinal)) {
        $dia = $fechaRecorrido->toDateString();

        $chartLabels[] = $fechaRecorrido->format('d/m');
        $chartData[] = (float) ($ventasGrafico[$dia]->total ?? 0);

        $fechaRecorrido->addDay();
    }

    $hayVentasPeriodo = $ventasGrafico->isNotEmpty();

    // Top 5 productos vendidos.
    $topProductos = VentaDetalle::query()
        ->join('ventas', 'ventas.id', '=', 'venta_detalles.venta_id')
        ->join('productos', 'productos.id', '=', 'venta_detalles.producto_id')
        ->where('ventas.user_id', $userId)
        ->where('ventas.estado', 'vendida')
        ->whereBetween('ventas.fecha_venta', [
            $desde->toDateString(),
            $hasta->toDateString()
        ])
        ->selectRaw('
            productos.id,
            productos.nombre,
            SUM(venta_detalles.cantidad_stock) as cantidad_total,
            SUM(venta_detalles.subtotal) as monto_total,
            COUNT(DISTINCT ventas.id) as num_ventas
        ')
        ->groupBy('productos.id', 'productos.nombre')
        ->orderByDesc('monto_total')
        ->take(5)
        ->get();

    return view('vendedor.dashboard', compact(
        'desde',
        'hasta',
        'ventasPeriodo',
        'totalVentas',
        'ventasPendientes',
        'ventasVendidas',
        'ventasCanceladas',
        'ingresosPeriodo',
        'chartLabels',
        'chartData',
        'hayVentasPeriodo',
        'topProductos'
    ));
}

}
