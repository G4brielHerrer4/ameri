<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        // ============================================================
        // 1. FECHAS POR DEFECTO
        // ============================================================
        $desde = $request->filled('desde')
            ? Carbon::parse($request->desde)->startOfDay()
            : now()->subDays(30)->startOfDay();

        $hasta = $request->filled('hasta')
            ? Carbon::parse($request->hasta)->endOfDay()
            : now()->endOfDay();

        // ============================================================
        // 2. FILTROS
        // ============================================================
        $estado     = $request->get('estado');
        $vendedorId = $request->get('vendedor_id');
        $productoId = $request->get('producto_id');

        // ============================================================
        // 3. QUERY BASE — todas las ventas del período
        // ============================================================
        $queryTodas = Venta::query()
            ->whereBetween('fecha_venta', [$desde, $hasta]);

        if ($vendedorId) {
            $queryTodas->where('user_id', $vendedorId);
        }

        if ($productoId) {
            $queryTodas->whereHas('detalles', function ($q) use ($productoId) {
                $q->where('producto_id', $productoId);
            });
        }

        // ============================================================
        // 4. QUERY DE VENTAS REALES
        // ============================================================
        $queryReales = (clone $queryTodas);

        if ($estado) {
            $queryReales->where('estado', $estado);
        } else {
            $queryReales->where('estado', 'vendida');
        }

        // ============================================================
        // 5. KPIs PRINCIPALES
        // ============================================================
        $totalVentas       = (clone $queryReales)->count();
        $montoTotal        = (clone $queryReales)->sum('total');
        $montoPromedio     = $totalVentas > 0 ? $montoTotal / $totalVentas : 0;

        $productosVendidos = VentaDetalle::query()
            ->whereIn('venta_id', (clone $queryReales)->pluck('id'))
            ->sum('cantidad');

        // ============================================================
        // 6. KPIs DE VENTAS PERDIDAS Y PENDIENTES
        // ============================================================
        $ventasPendientes       = (clone $queryTodas)->where('estado', 'pendiente');
        $totalPendientes        = (clone $ventasPendientes)->count();
        $montoPendientes        = (clone $ventasPendientes)->sum('total');

        $ventasCanceladas       = (clone $queryTodas)->where('estado', 'cancelada');
        $totalCanceladas        = (clone $ventasCanceladas)->count();
        $montoCanceladas        = (clone $ventasCanceladas)->sum('total');

        // ============================================================
        // 7. VENTAS POR DÍA
        // ============================================================
        $ventasPorDia = (clone $queryReales)
            ->selectRaw('DATE(fecha_venta) as fecha, COUNT(*) as cantidad, SUM(total) as monto')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // ============================================================
        // 8. TOP 5 VENDEDORES
        // ============================================================
        $ventasPorVendedor = (clone $queryReales)
            ->selectRaw('user_id, COUNT(*) as cantidad, SUM(total) as monto')
            ->groupBy('user_id')
            ->orderByDesc('monto')
            ->with('user:id,name')
            ->take(5)
            ->get();

        // ============================================================
        // 9. TOP 5 PRODUCTOS
        // ============================================================
        $topProductos = VentaDetalle::query()
            ->whereIn('venta_id', (clone $queryReales)->pluck('id'))
            ->selectRaw('
                producto_id,
                SUM(cantidad) as cantidad_total,
                SUM(subtotal) as monto_total
            ')
            ->groupBy('producto_id')
            ->orderByDesc('monto_total')
            ->with('producto:id,nombre,marca')
            ->take(5)
            ->get();

        // ============================================================
        // 10. VENTAS POR ESTADO
        // ============================================================
        $ventasPorEstado = (clone $queryTodas)
            ->selectRaw('estado, COUNT(*) as cantidad, SUM(total) as monto')
            ->groupBy('estado')
            ->get();

        // ============================================================
        // 11. LISTADOS PARA FILTROS
        // ============================================================
        $rolesVenta = Role::query()
            ->whereIn('slug', ['admin', 'vendedor'])
            ->pluck('id');

        $vendedores = User::query()
            ->whereIn('role_id', $rolesVenta)
            ->orderBy('name')
            ->get(['id', 'name']);

        $productos = Producto::query()
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'marca']);

        // ============================================================
        // 12. TABLA DETALLE
        // ============================================================
        $ventas = (clone $queryReales)
            ->with(['user:id,name', 'detalles.producto:id,nombre'])
            ->orderByDesc('fecha_venta')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reportes.index', compact(
            'desde',
            'hasta',
            'estado',
            'vendedorId',
            'productoId',
            'totalVentas',
            'montoTotal',
            'montoPromedio',
            'productosVendidos',
            'totalPendientes',
            'montoPendientes',
            'totalCanceladas',
            'montoCanceladas',
            'ventasPorDia',
            'ventasPorVendedor',
            'topProductos',
            'ventasPorEstado',
            'vendedores',
            'productos',
            'ventas'
        ));
    }


    /**
     * Exportar reporte de ventas a PDF con los mismos filtros.
     */
    public function exportarPdf(Request $request)
    {
        // ============================================================
        // 1. MISMOS FILTROS QUE EL INDEX
        // ============================================================
        $desde = $request->filled('desde')
            ? Carbon::parse($request->desde)->startOfDay()
            : now()->subDays(30)->startOfDay();

        $hasta = $request->filled('hasta')
            ? Carbon::parse($request->hasta)->endOfDay()
            : now()->endOfDay();

        $estado     = $request->get('estado');
        $vendedorId = $request->get('vendedor_id');
        $productoId = $request->get('producto_id');

        // ============================================================
        // 2. QUERY BASE
        // ============================================================
        $queryTodas = Venta::query()
            ->whereBetween('fecha_venta', [$desde, $hasta]);

        if ($vendedorId) {
            $queryTodas->where('user_id', $vendedorId);
        }

        if ($productoId) {
            $queryTodas->whereHas('detalles', function ($q) use ($productoId) {
                $q->where('producto_id', $productoId);
            });
        }

        $queryReales = (clone $queryTodas);

        if ($estado) {
            $queryReales->where('estado', $estado);
        } else {
            $queryReales->where('estado', 'vendida');
        }

        // ============================================================
        // 3. KPIs
        // ============================================================
        $totalVentas   = (clone $queryReales)->count();
        $montoTotal    = (clone $queryReales)->sum('total');
        $montoPromedio = $totalVentas > 0 ? $montoTotal / $totalVentas : 0;

        $productosVendidos = VentaDetalle::query()
            ->whereIn('venta_id', (clone $queryReales)->pluck('id'))
            ->sum('cantidad');

        // ============================================================
        // 4. PENDIENTES
        // ============================================================
        $ventasPendientes  = (clone $queryTodas)->where('estado', 'pendiente');
        $totalPendientes   = (clone $ventasPendientes)->count();
        $montoPendientes   = (clone $ventasPendientes)->sum('total');

        // ============================================================
        // 5. CANCELADAS — CON COLECCIÓN COMPLETA PARA EL PDF
        //    (con usuario y detalles de productos)
        // ============================================================
        $ventasCanceladas = (clone $queryTodas)
            ->where('estado', 'cancelada')
            ->with([
                'user:id,name',
                'detalles.producto:id,nombre,marca',
            ])
            ->orderByDesc('fecha_venta')
            ->orderByDesc('id')
            ->get();

        $totalCanceladas = $ventasCanceladas->count();
        $montoCanceladas = $ventasCanceladas->sum('total');

        // ============================================================
        // 6. TOP VENDEDORES Y PRODUCTOS
        // ============================================================
        $ventasPorVendedor = (clone $queryReales)
            ->selectRaw('user_id, COUNT(*) as cantidad, SUM(total) as monto')
            ->groupBy('user_id')
            ->orderByDesc('monto')
            ->with('user:id,name')
            ->take(5)
            ->get();

        $topProductos = VentaDetalle::query()
            ->whereIn('venta_id', (clone $queryReales)->pluck('id'))
            ->selectRaw('
                producto_id,
                SUM(cantidad) as cantidad_total,
                SUM(subtotal) as monto_total
            ')
            ->groupBy('producto_id')
            ->orderByDesc('monto_total')
            ->with('producto:id,nombre,marca')
            ->take(5)
            ->get();

        // ============================================================
        // 7. DETALLE DE VENTAS (todas, sin paginar para el PDF)
        // ============================================================
        $ventas = (clone $queryReales)
            ->with(['user:id,name', 'detalles.producto:id,nombre'])
            ->orderByDesc('fecha_venta')
            ->orderByDesc('id')
            ->take(200) // límite para no generar PDFs gigantes
            ->get();

        // ============================================================
        // 8. NOMBRES DE FILTROS PARA EL PDF
        // ============================================================
        $vendedorNombre = null;
        if ($vendedorId) {
            $vendedorNombre = User::find($vendedorId)?->name;
        }

        $productoNombre = null;
        if ($productoId) {
            $productoNombre = Producto::find($productoId)?->nombre;
        }

        // ============================================================
        // 9. GENERAR PDF
        // ============================================================
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.reportes.pdf',
            compact(
                'desde', 'hasta',
                'estado', 'vendedorId', 'productoId',
                'vendedorNombre', 'productoNombre',
                'totalVentas', 'montoTotal', 'montoPromedio',
                'productosVendidos',
                'totalPendientes', 'montoPendientes',
                'totalCanceladas', 'montoCanceladas',
                'ventasCanceladas',
                'ventasPorVendedor', 'topProductos',
                'ventas'
            )
        );

        $pdf->setPaper('A4', 'portrait');

        $nombreArchivo = 'reporte-ventas-' . now()->format('Y-m-d-His') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}