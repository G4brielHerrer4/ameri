<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CamionChofer;
use App\Models\CamionViaje;
use App\Models\CamionGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CamionController extends Controller
{
    /**
     * =========================================
     * PANEL PRINCIPAL DEL CAMIÓN
     * =========================================
     */
    public function index(Request $request)
    {
        $hoy = now()->toDateString();

        $desde = $request->input('desde', $hoy);
        $hasta = $request->input('hasta', $hoy);

        $viajesQuery = CamionViaje::with('chofer');
        $gastosQuery = CamionGasto::query();

        if ($desde) {
            $viajesQuery->whereDate('fecha', '>=', $desde);
            $gastosQuery->whereDate('fecha', '>=', $desde);
        }

        if ($hasta) {
            $viajesQuery->whereDate('fecha', '<=', $hasta);
            $gastosQuery->whereDate('fecha', '<=', $hasta);
        }

        $viajes = (clone $viajesQuery)
            ->latest('fecha')
            ->latest('id')
            ->get();

        $gastos = (clone $gastosQuery)
            ->latest('fecha')
            ->latest('id')
            ->get();

        $ingresosCobrados = (clone $viajesQuery)
            ->where('estado_pago', 'pagado')
            ->sum('monto_cobrado');

        $ingresosPendientes = (clone $viajesQuery)
            ->where('estado_pago', 'pendiente')
            ->sum('monto_cobrado');

        $totalGastos = (clone $gastosQuery)
            ->sum('monto');

        $choferes = CamionChofer::orderBy('nombre')->get();

        return view('admin.camion.index', compact(
            'viajes',
            'gastos',
            'choferes',
            'desde',
            'hasta',
            'ingresosCobrados',
            'ingresosPendientes',
            'totalGastos'
        ));
    }

    /**
     * =========================================
     * FORMULARIO DE REGISTRO
     * =========================================
     */
    public function create(Request $request)
    {
        $tipo = $request->query('tipo', 'chofer');

        if (!in_array($tipo, ['chofer', 'viaje', 'gasto'], true)) {
            $tipo = 'chofer';
        }

        $choferes = CamionChofer::orderBy('nombre')->get();

        return view('admin.camion.create', compact(
            'tipo',
            'choferes'
        ));
    }

    /**
     * =========================================
     * FORMULARIOS DE EDICIÓN
     * =========================================
     */
    public function editarChofer(CamionChofer $chofer)
    {
        return view('admin.camion.edit', [
            'tipo' => 'chofer',
            'registro' => $chofer,
            'choferes' => collect(),
        ]);
    }

    public function editarViaje(CamionViaje $viaje)
    {
        return view('admin.camion.edit', [
            'tipo' => 'viaje',
            'registro' => $viaje,
            'choferes' => CamionChofer::orderBy('nombre')->get(),
        ]);
    }

    public function editarGasto(CamionGasto $gasto)
    {
        return view('admin.camion.edit', [
            'tipo' => 'gasto',
            'registro' => $gasto,
            'choferes' => collect(),
        ]);
    }

    /**
     * =========================================
     * REGISTRAR CHOFER
     * =========================================
     */
    public function guardarChofer(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
        ]);

        CamionChofer::create($datos);

        return redirect()
            ->route('admin.camion.index')
            ->with('success', 'Chofer registrado correctamente.');
    }

    /**
     * =========================================
     * ACTUALIZAR CHOFER
     * =========================================
     */
    public function actualizarChofer(Request $request, CamionChofer $chofer)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
        ]);

        $chofer->update($datos);

        return redirect()
            ->route('admin.camion.index')
            ->with('success', 'Chofer actualizado correctamente.');
    }

    /**
     * =========================================
     * ACTIVAR / INACTIVAR CHOFER
     * =========================================
     */
    public function cambiarEstadoChofer(CamionChofer $chofer)
    {
        $chofer->activo = !$chofer->activo;
        $chofer->save();

        $mensaje = $chofer->activo
            ? 'Chofer activado correctamente.'
            : 'Chofer inactivado correctamente.';

        return back()->with('success', $mensaje);
    }

    /**
     * =========================================
     * ELIMINAR CHOFER
     * Solo si no tiene viajes registrados.
     * =========================================
     */
    public function eliminarChofer(CamionChofer $chofer)
    {
        if ($chofer->viajes()->exists()) {
            return back()->with(
                'error',
                'No se puede eliminar este chofer porque tiene viajes registrados. Puedes inactivarlo.'
            );
        }

        $chofer->delete();

        return back()->with(
            'success',
            'Chofer eliminado correctamente.'
        );
    }

    /**
     * =========================================
     * REGISTRAR VIAJE
     * =========================================
     */
    public function guardarViaje(Request $request)
    {
        $datos = $request->validate([
            'chofer_id' => [
                'required',
                'integer',
                Rule::exists('camion_choferes', 'id')
                    ->where('activo', true),
            ],
            'fecha' => ['required', 'date'],
            'origen' => ['required', 'string', 'max:200'],
            'destino' => ['required', 'string', 'max:200'],
            'mercaderia' => ['nullable', 'string', 'max:5000'],
            'monto_cobrado' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
            'estado_pago' => [
                'required',
                Rule::in(['pagado', 'pendiente']),
            ],
            'observaciones' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        CamionViaje::create($datos);

        return redirect()
            ->route('admin.camion.index')
            ->with('success', 'Viaje registrado correctamente.');
    }

    /**
     * =========================================
     * ACTUALIZAR VIAJE
     * =========================================
     */
    public function actualizarViaje(Request $request, CamionViaje $viaje)
    {
        $datos = $request->validate([
            'chofer_id' => [
                'required',
                'integer',
                Rule::exists('camion_choferes', 'id')
                    ->where(function ($query) use ($viaje) {
                        $query->where('activo', true)
                            ->orWhere('id', $viaje->chofer_id);
                    }),
            ],
            'fecha' => ['required', 'date'],
            'origen' => ['required', 'string', 'max:200'],
            'destino' => ['required', 'string', 'max:200'],
            'mercaderia' => ['nullable', 'string', 'max:5000'],
            'monto_cobrado' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
            'estado_pago' => [
                'required',
                Rule::in(['pagado', 'pendiente']),
            ],
            'observaciones' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $viaje->update($datos);

        return redirect()
            ->route('admin.camion.index')
            ->with('success', 'Viaje actualizado correctamente.');
    }

    /**
     * =========================================
     * CAMBIAR ESTADO DE COBRO
     * =========================================
     */
    public function cambiarEstadoPago(CamionViaje $viaje)
    {
        $viaje->estado_pago = $viaje->estado_pago === 'pagado'
            ? 'pendiente'
            : 'pagado';

        $viaje->save();

        return back()->with(
            'success',
            'Estado del cobro actualizado a: '
                . ($viaje->estado_pago === 'pagado'
                    ? 'Pagado.'
                    : 'Pendiente.')
        );
    }

    /**
     * =========================================
     * ELIMINAR VIAJE
     * =========================================
     */
    public function eliminarViaje(CamionViaje $viaje)
    {
        $viaje->delete();

        return back()->with(
            'success',
            'Viaje eliminado correctamente.'
        );
    }

    /**
     * =========================================
     * REGISTRAR GASTO
     * =========================================
     */
    public function guardarGasto(Request $request)
    {
        $datos = $this->validarGasto($request);

        if (($datos['tipo_gasto'] ?? null) !== 'combustible') {
            $datos['tipo_combustible'] = null;
        }

        if ($request->hasFile('comprobante')) {
            $datos['comprobante'] = $request->file('comprobante')
                ->store('camion/comprobantes', 'local');
        }

        CamionGasto::create($datos);

        return redirect()
            ->route('admin.camion.index')
            ->with('success', 'Gasto registrado correctamente.');
    }

    /**
     * =========================================
     * ACTUALIZAR GASTO
     * Conserva el comprobante si no se reemplaza.
     * =========================================
     */
    public function actualizarGasto(Request $request, CamionGasto $gasto)
    {
        $datos = $this->validarGasto($request);

        if (($datos['tipo_gasto'] ?? null) !== 'combustible') {
            $datos['tipo_combustible'] = null;
        }

        $comprobanteAnterior = $gasto->comprobante;

        if ($request->hasFile('comprobante')) {
            $datos['comprobante'] = $request->file('comprobante')
                ->store('camion/comprobantes', 'local');
        } else {
            unset($datos['comprobante']);
        }

        $gasto->update($datos);

        if (
            $request->hasFile('comprobante')
            && $comprobanteAnterior
            && str_starts_with(
                $comprobanteAnterior,
                'camion/comprobantes/'
            )
        ) {
            Storage::disk('local')->delete($comprobanteAnterior);
        }

        return redirect()
            ->route('admin.camion.index')
            ->with('success', 'Gasto actualizado correctamente.');
    }

    /**
     * =========================================
     * VALIDACIONES DE GASTOS
     * =========================================
     */
    private function validarGasto(Request $request): array
    {
        return $request->validate([
            'fecha' => ['required', 'date'],

            'tipo_gasto' => [
                'required',
                Rule::in([
                    'combustible',
                    'aceite',
                    'mantenimiento',
                    'repuestos',
                    'otros',
                ]),
            ],

            'tipo_combustible' => [
                'nullable',
                Rule::in(['gasolina', 'gnv']),
                'required_if:tipo_gasto,combustible',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'monto' => [
                'required',
                'numeric',
                'gt:0',
                'max:9999999999.99',
            ],

            'kilometraje' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'comprobante' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);
    }

    /**
     * =========================================
     * ELIMINAR GASTO
     * También elimina su comprobante.
     * =========================================
     */
    public function eliminarGasto(CamionGasto $gasto)
    {
        $ruta = $gasto->comprobante;

        $gasto->delete();

        if (
            $ruta
            && str_starts_with($ruta, 'camion/comprobantes/')
        ) {
            Storage::disk('local')->delete($ruta);
        }

        return back()->with(
            'success',
            'Gasto eliminado correctamente.'
        );
    }

    /**
     * =========================================
     * MOSTRAR COMPROBANTE
     * Desde una ruta protegida.
     * =========================================
     */
    public function comprobante(CamionGasto $gasto)
    {
        abort_unless($gasto->comprobante, 404);

        $ruta = $gasto->comprobante;

        abort_unless(
            str_starts_with($ruta, 'camion/comprobantes/'),
            404
        );

        abort_unless(
            Storage::disk('local')->exists($ruta),
            404
        );

        return Storage::disk('local')->response($ruta);
    }
    /**
 * Exportar el historial del camión a PDF según el período filtrado.
 */
    public function exportarPdf(Request $request)
    {
        $datos = $request->validate([
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);

        $desde = $datos['desde'] ?? null;
        $hasta = $datos['hasta'] ?? null;

        $viajesQuery = CamionViaje::with('chofer');
        $gastosQuery = CamionGasto::query();

        if ($desde) {
            $viajesQuery->whereDate('fecha', '>=', $desde);
            $gastosQuery->whereDate('fecha', '>=', $desde);
        }

        if ($hasta) {
            $viajesQuery->whereDate('fecha', '<=', $hasta);
            $gastosQuery->whereDate('fecha', '<=', $hasta);
        }

        $viajes = (clone $viajesQuery)
            ->orderBy('fecha')
            ->orderBy('id')
            ->get();

        $gastos = (clone $gastosQuery)
            ->orderBy('fecha')
            ->orderBy('id')
            ->get();

        $ingresosCobrados = (clone $viajesQuery)
            ->where('estado_pago', 'pagado')
            ->sum('monto_cobrado');

        $ingresosPendientes = (clone $viajesQuery)
            ->where('estado_pago', 'pendiente')
            ->sum('monto_cobrado');

        $totalGastos = (clone $gastosQuery)->sum('monto');

        $balance = $ingresosCobrados - $totalGastos;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.camion.pdf',
            compact(
                'desde',
                'hasta',
                'viajes',
                'gastos',
                'ingresosCobrados',
                'ingresosPendientes',
                'totalGastos',
                'balance'
            )
        );

        $pdf->setPaper('A4', 'landscape');

        $nombreArchivo = 'reporte-camion-' . now()->format('Y-m-d-His') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}
