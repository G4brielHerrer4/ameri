
@extends('layouts.plantilla_maestra')

@section('title', 'Gestión de Camión - AMERI')


@push('styles')
<style>
    /* =========================================
   NOTIFICACIONES TOASTR
   ADAPTADAS AL TEMA CLARO Y OSCURO
========================================= */
        #toast-container {
            z-index: 99999 !important;
        }

        #toast-container > .toast {
            background-color: var(--bg-1) !important;
            color: var(--ink) !important;

            border: 1px solid var(--line-strong) !important;
            border-radius: var(--r-md) !important;

            box-shadow: 0 12px 35px -12px var(--shadow) !important;

            opacity: 1 !important;
            background-image: none !important;

            padding: 16px 18px 16px 45px !important;
            min-width: 280px;
        }

        #toast-container > .toast-success {
            border-left: 4px solid var(--success) !important;
        }

        #toast-container > .toast-error {
            border-left: 4px solid var(--error) !important;
        }

        #toast-container > .toast-warning {
            border-left: 4px solid var(--warning) !important;
        }

        #toast-container > .toast-info {
            border-left: 4px solid var(--cyan) !important;
        }

        #toast-container .toast-title {
            color: var(--ink) !important;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: .9rem;
        }

        #toast-container .toast-message {
            color: var(--ink-muted) !important;
            font-size: .85rem;
            line-height: 1.5;
        }

        #toast-container .toast-close-button {
            color: var(--ink) !important;
            opacity: .8;
            text-shadow: none !important;
        }

        #toast-container .toast-progress {
            opacity: 1 !important;
        }

        #toast-container .toast-success .toast-progress {
            background-color: var(--success) !important;
        }

        #toast-container .toast-error .toast-progress {
            background-color: var(--error) !important;
        }

        #toast-container .toast-warning .toast-progress {
            background-color: var(--warning) !important;
        }

        #toast-container .toast-info .toast-progress {
            background-color: var(--cyan) !important;
        }
    /* =========================================
       ENCABEZADO
    ========================================= */
    .camion-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        border: 1px solid var(--line);
        border-radius: var(--r-lg);
        background: linear-gradient(
            135deg,
            color-mix(in srgb, var(--cyan) 12%, var(--bg-1)),
            var(--bg-1)
        );
    }

    .camion-header h1 {
        font-family: var(--font-display);
        font-size: 1.7rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 .4rem;
    }

    .camion-header h1 .mdi {
        color: var(--cyan);
    }

    .camion-header p {
        color: var(--ink-muted);
        margin: 0;
        font-size: .9rem;
    }

    /* =========================================
       BOTONES Y ACCIONES
    ========================================= */
    .camion-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        align-items: center;
    }

    .camion-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: .4rem;
        padding: .7rem 1rem;
        border: 0;
        border-radius: var(--r-pill);
        background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
        color: var(--cyan-ink);
        font-family: var(--font-display);
        font-size: .85rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: all .3s var(--ease);
    }

    .camion-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px -6px var(--glow);
        color: var(--cyan-ink);
    }

    .camion-btn.secondary {
        background: var(--bg-2);
        color: var(--ink);
        border: 1px solid var(--line);
    }

    .camion-btn.secondary:hover {
        border-color: var(--line-strong);
        color: var(--cyan);
    }

    .camion-btn.small {
        padding: .45rem .75rem;
        font-size: .76rem;
    }

    .camion-btn.danger {
        background: color-mix(in srgb, var(--error) 12%, var(--bg-1));
        color: var(--error);
        border: 1px solid color-mix(in srgb, var(--error) 35%, transparent);
    }

    .camion-btn.warning {
        background: color-mix(in srgb, var(--warning) 12%, var(--bg-1));
        color: var(--warning);
        border: 1px solid color-mix(in srgb, var(--warning) 35%, transparent);
    }

    .camion-btn.success {
        background: color-mix(in srgb, var(--success) 12%, var(--bg-1));
        color: var(--success);
        border: 1px solid color-mix(in srgb, var(--success) 35%, transparent);
    }

    .camion-btn.danger:hover,
    .camion-btn.warning:hover,
    .camion-btn.success:hover {
        box-shadow: none;
    }

    /* =========================================
       PANELES
    ========================================= */
    .camion-panel {
        background: var(--bg-1);
        border: 1px solid var(--line);
        border-radius: var(--r-lg);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        min-width: 0;
        box-shadow: 0 20px 40px -28px var(--shadow);
    }

    .camion-panel h2 {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-family: var(--font-display);
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 1rem;
    }

    .camion-panel h2 .mdi {
        color: var(--cyan);
    }

    /* =========================================
       FILTROS
    ========================================= */
    .camion-filter {
        display: flex;
        gap: .8rem;
        flex-wrap: wrap;
        align-items: end;
    }

    .camion-field {
        flex: 1;
        min-width: 150px;
    }

    .camion-field label {
        display: block;
        color: var(--ink-muted);
        font-family: var(--font-display);
        font-size: .76rem;
        font-weight: 600;
        margin-bottom: .45rem;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .camion-field input {
        display: block;
        width: 100%;
        max-width: 100%;
        padding: .7rem .85rem;
        border: 1px solid var(--line);
        border-radius: var(--r-md);
        background: var(--bg-2);
        color: var(--ink);
        box-sizing: border-box;
    }

    .camion-field input:focus {
        outline: none;
        border-color: var(--cyan);
        background: var(--bg-3);
        box-shadow: 0 0 0 3px var(--glow-soft);
    }

    /* =========================================
       TARJETAS FINANCIERAS
    ========================================= */
    .camion-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .camion-card {
        background: var(--bg-1);
        border: 1px solid var(--line);
        border-radius: var(--r-lg);
        padding: 1.25rem;
        min-width: 0;
        box-shadow: 0 12px 30px -24px var(--shadow);
    }

    .camion-card small {
        display: block;
        color: var(--ink-muted);
        font-family: var(--font-display);
        font-weight: 700;
        text-transform: uppercase;
        font-size: .72rem;
        letter-spacing: .04em;
        margin-bottom: .65rem;
    }

    .camion-card strong {
        display: block;
        font-family: var(--font-display);
        font-size: 1.65rem;
        font-weight: 700;
        color: var(--ink);
        overflow-wrap: anywhere;
    }

    .camion-card.ingreso {
        border-left: 4px solid var(--success);
    }

    .camion-card.pendiente {
        border-left: 4px solid var(--warning);
    }

    .camion-card.gasto {
        border-left: 4px solid var(--error);
    }

    .camion-card.balance {
        border-left: 4px solid var(--cyan);
    }

    /* =========================================
       TABLAS
    ========================================= */
    .camion-table-wrap {
        overflow-x: auto;
        width: 100%;
    }

    .camion-table {
        width: 100%;
        border-collapse: collapse;
        color: var(--ink);
        font-size: .84rem;
    }

    .camion-table th,
    .camion-table td {
        text-align: left;
        padding: .8rem;
        border-bottom: 1px solid var(--line);
        vertical-align: middle;
    }

    .camion-table th {
        background: color-mix(in srgb, var(--bg-2) 60%, transparent);
        color: var(--ink-muted);
        font-family: var(--font-display);
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }

    .camion-table tbody tr:last-child td {
        border-bottom: none;
    }

    .camion-table tbody tr:hover {
        background: color-mix(in srgb, var(--cyan) 3%, transparent);
    }

    /* =========================================
       ESTADOS
    ========================================= */
    .camion-badge,
    .camion-status {
        display: inline-block;
        padding: .3rem .65rem;
        border-radius: var(--r-pill);
        font-size: .74rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .camion-badge.pagado,
    .camion-status.activo {
        background: color-mix(in srgb, var(--success) 15%, transparent);
        color: var(--success);
    }

    .camion-badge.pendiente {
        background: color-mix(in srgb, var(--warning) 15%, transparent);
        color: var(--warning);
    }

    .camion-status.inactivo {
        background: color-mix(in srgb, var(--error) 12%, transparent);
        color: var(--error);
    }

    /* =========================================
       ALERTAS DE VALIDACIÓN
    ========================================= */
    .camion-alert {
        padding: .85rem 1rem;
        border: 1px solid var(--line);
        border-radius: var(--r-md);
        margin-bottom: 1rem;
        background: var(--bg-1);
        color: var(--ink);
        font-size: .88rem;
    }

    .camion-alert.error {
        border-color: var(--error);
        background: color-mix(in srgb, var(--error) 8%, var(--bg-1));
    }

    .camion-empty {
        text-align: center;
        padding: 1.5rem !important;
        color: var(--ink-muted);
    }

    /* =========================================
       RESPONSIVE
    ========================================= */
    @media (max-width: 767px) {
        .camion-header {
            padding: 1.15rem;
        }

        .camion-header h1 {
            font-size: 1.35rem;
        }

        .camion-card strong {
            font-size: 1.35rem;
        }

        .camion-panel {
            padding: 1rem;
        }

        .camion-actions {
            gap: .4rem;
        }
    }

    @media (max-width: 480px) {
        .camion-header .camion-actions {
            width: 100%;
        }

        .camion-header .camion-btn {
            flex: 1;
        }

        .camion-filter .camion-btn {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- =========================================
         ENCABEZADO
    ========================================= --}}
    <div class="camion-header">
        <div>
            <h1>
                <i class="mdi mdi-truck-outline"></i>
                Gestión del camión
            </h1>

            <p>Control de viajes, cobros, gastos y comprobantes.</p>
        </div>

        <div class="camion-actions">
            <a href="{{ route('admin.camion.create', ['tipo' => 'chofer']) }}"
               class="camion-btn">
                <i class="mdi mdi-account-plus-outline"></i>
                Registrar chofer
            </a>

            <a href="{{ route('admin.camion.create', ['tipo' => 'viaje']) }}"
               class="camion-btn secondary">
                <i class="mdi mdi-truck-delivery-outline"></i>
                Registrar viaje
            </a>

            <a href="{{ route('admin.camion.create', ['tipo' => 'gasto']) }}"
               class="camion-btn secondary">
                <i class="mdi mdi-cash-minus"></i>
                Registrar gasto
            </a>
             <a href="{{ route('admin.camion.exportar-pdf', request()->only(['desde', 'hasta'])) }}"
                class="camion-btn secondary"
                title="Exportar el historial del período seleccionado">
                    <i class="mdi mdi-file-pdf-box"></i>
                    Exportar PDF
            </a>
        </div>
    </div>

    {{-- =========================================
         ERRORES DE VALIDACIÓN
    ========================================= --}}
    @if($errors->any())
        <div class="camion-alert error">
            <strong>No se pudo completar la operación:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- =========================================
         FILTRO POR FECHAS
    ========================================= --}}
    <form method="GET"
          action="{{ route('admin.camion.index') }}"
          class="camion-panel">

        <h2>
            <i class="mdi mdi-calendar-range"></i>
            Filtrar período
        </h2>

        <div class="camion-filter">
            <div class="camion-field">
                <label for="desde">Desde</label>

                <input type="date"
                       id="desde"
                       name="desde"
                       value="{{ $desde }}">
            </div>

            <div class="camion-field">
                <label for="hasta">Hasta</label>

                <input type="date"
                       id="hasta"
                       name="hasta"
                       value="{{ $hasta }}">
            </div>

            <button type="submit" class="camion-btn">
                <i class="mdi mdi-filter-outline"></i>
                Filtrar
            </button>

            <a href="{{ route('admin.camion.index') }}"
               class="camion-btn secondary">
                <i class="mdi mdi-refresh"></i>
                Limpiar
            </a>
        </div>
    </form>

    {{-- =========================================
         RESUMEN FINANCIERO
    ========================================= --}}
    @php
        $balance = $ingresosCobrados - $totalGastos;
    @endphp

    <div class="camion-grid">
        <div class="camion-card ingreso">
            <small>
                <i class="mdi mdi-cash-check"></i>
                Ingresos cobrados
            </small>

            <strong>
                Bs {{ number_format($ingresosCobrados, 2, ',', '.') }}
            </strong>
        </div>

        <div class="camion-card pendiente">
            <small>
                <i class="mdi mdi-clock-outline"></i>
                Por cobrar
            </small>

            <strong>
                Bs {{ number_format($ingresosPendientes, 2, ',', '.') }}
            </strong>
        </div>

        <div class="camion-card gasto">
            <small>
                <i class="mdi mdi-cash-minus"></i>
                Total de gastos
            </small>

            <strong>
                Bs {{ number_format($totalGastos, 2, ',', '.') }}
            </strong>
        </div>

        <div class="camion-card balance">
            <small>
                <i class="mdi mdi-scale-balance"></i>
                Balance de caja
            </small>

            <strong>
                Bs {{ number_format($balance, 2, ',', '.') }}
            </strong>
        </div>
    </div>

    {{-- =========================================
         CHOFERES REGISTRADOS
    ========================================= --}}
    <div class="camion-panel" id="gestion-choferes">
        <h2>
            <i class="mdi mdi-account-group-outline"></i>
            Choferes registrados
        </h2>

        <div class="camion-table-wrap">
            <table class="camion-table">
                <thead>
                    <tr>
                        <th>Chofer</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($choferes as $chofer)
                        <tr>
                            <td>{{ $chofer->nombre }}</td>
                            <td>{{ $chofer->telefono ?: '—' }}</td>

                            <td>
                                <span class="camion-status {{ $chofer->activo ? 'activo' : 'inactivo' }}">
                                    {{ $chofer->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                <div class="camion-actions">

                                    {{-- EDITAR CHOFER --}}
                                    <a href="{{ route('admin.camion.choferes.edit', $chofer) }}"
                                       class="camion-btn small secondary">
                                        <i class="mdi mdi-pencil-outline"></i>
                                        Editar
                                    </a>

                                    {{-- ACTIVAR / INACTIVAR --}}
                                    <form method="POST"
                                          action="{{ route('admin.camion.choferes.estado', $chofer) }}"
                                          class="camion-confirmar"
                                          data-titulo="{{ $chofer->activo ? '¿Inactivar chofer?' : '¿Activar chofer?' }}"
                                          data-mensaje="{{ $chofer->activo ? 'El chofer dejará de estar disponible para nuevos viajes.' : 'El chofer estará disponible para nuevos viajes.' }}"
                                          data-icono="question"
                                          data-confirmar="{{ $chofer->activo ? 'Sí, inactivar' : 'Sí, activar' }}"
                                          data-color="{{ $chofer->activo ? '#f59e0b' : '#10b981' }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="camion-btn small {{ $chofer->activo ? 'warning' : 'success' }}">
                                            <i class="mdi {{ $chofer->activo ? 'mdi-account-off-outline' : 'mdi-account-check-outline' }}"></i>
                                            {{ $chofer->activo ? 'Inactivar' : 'Activar' }}
                                        </button>
                                    </form>

                                    {{-- ELIMINAR CHOFER --}}
                                    <form method="POST"
                                          action="{{ route('admin.camion.choferes.destroy', $chofer) }}"
                                          class="camion-confirmar"
                                          data-titulo="¿Eliminar chofer?"
                                          data-mensaje="Esta acción no se puede deshacer. No podrás eliminarlo si tiene viajes registrados."
                                          data-icono="warning"
                                          data-confirmar="Sí, eliminar"
                                          data-color="#ff4d6d">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="camion-btn small danger">
                                            <i class="mdi mdi-delete-outline"></i>
                                            Eliminar
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="camion-empty">
                                Todavía no hay choferes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- =========================================
         HISTORIAL DE VIAJES
    ========================================= --}}
    <div class="camion-panel">
        <h2>
            <i class="mdi mdi-map-marker-path"></i>
            Historial de viajes
        </h2>

        <div class="camion-table-wrap">
            <table class="camion-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Chofer</th>
                        <th>Ruta</th>
                        <th>Mercadería</th>
                        <th>Monto</th>
                        <th>Cobro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($viajes as $viaje)
                        <tr>
                            <td>{{ $viaje->fecha->format('d/m/Y') }}</td>

                            <td>{{ $viaje->chofer->nombre }}</td>

                            <td>
                                {{ $viaje->origen }}
                                <i class="mdi mdi-arrow-right"></i>
                                {{ $viaje->destino }}
                            </td>

                            <td>{{ $viaje->mercaderia ?: '—' }}</td>

                            <td>
                                Bs {{ number_format($viaje->monto_cobrado, 2, ',', '.') }}
                            </td>

                            <td>
                                <span class="camion-badge {{ $viaje->estado_pago }}">
                                    {{ $viaje->estado_pago === 'pagado' ? 'Pagado' : 'Pendiente' }}
                                </span>
                            </td>

                            <td>
                                <div class="camion-actions">

                                    {{-- EDITAR VIAJE --}}
                                    <a href="{{ route('admin.camion.viajes.edit', $viaje) }}"
                                       class="camion-btn small secondary">
                                        <i class="mdi mdi-pencil-outline"></i>
                                        Editar
                                    </a>

                                    {{-- CAMBIAR ESTADO DE COBRO --}}
                                    <form method="POST"
                                          action="{{ route('admin.camion.viajes.estado-pago', $viaje) }}"
                                          class="camion-confirmar"
                                          data-titulo="¿Cambiar estado de cobro?"
                                          data-mensaje="{{ $viaje->estado_pago === 'pagado' ? 'Este viaje volverá a estar pendiente de pago.' : 'Este viaje quedará registrado como pagado.' }}"
                                          data-icono="question"
                                          data-confirmar="{{ $viaje->estado_pago === 'pagado' ? 'Marcar pendiente' : 'Marcar pagado' }}"
                                          data-color="{{ $viaje->estado_pago === 'pagado' ? '#f59e0b' : '#10b981' }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="camion-btn small {{ $viaje->estado_pago === 'pagado' ? 'warning' : 'success' }}">
                                            <i class="mdi {{ $viaje->estado_pago === 'pagado' ? 'mdi-clock-outline' : 'mdi-cash-check' }}"></i>
                                            {{ $viaje->estado_pago === 'pagado' ? 'Marcar pendiente' : 'Marcar pagado' }}
                                        </button>
                                    </form>

                                    {{-- ELIMINAR VIAJE --}}
                                    <form method="POST"
                                          action="{{ route('admin.camion.viajes.destroy', $viaje) }}"
                                          class="camion-confirmar"
                                          data-titulo="¿Eliminar viaje?"
                                          data-mensaje="Se eliminará el viaje y dejará de contabilizarse en los ingresos. Esta acción no se puede deshacer."
                                          data-icono="warning"
                                          data-confirmar="Sí, eliminar"
                                          data-color="#ff4d6d">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="camion-btn small danger">
                                            <i class="mdi mdi-delete-outline"></i>
                                            Eliminar
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="camion-empty">
                                Todavía no hay viajes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- =========================================
         HISTORIAL DE GASTOS
    ========================================= --}}
    <div class="camion-panel">
        <h2>
            <i class="mdi mdi-receipt-text-outline"></i>
            Historial de gastos
        </h2>

        <div class="camion-table-wrap">
            <table class="camion-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Comprobante</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($gastos as $gasto)
                        <tr>
                            <td>{{ $gasto->fecha->format('d/m/Y') }}</td>

                            <td>
                                {{ ucfirst($gasto->tipo_gasto) }}

                                @if($gasto->tipo_combustible)
                                    ({{ strtoupper($gasto->tipo_combustible) }})
                                @endif
                            </td>

                            <td>{{ $gasto->descripcion ?: '—' }}</td>

                            <td>
                                Bs {{ number_format($gasto->monto, 2, ',', '.') }}
                            </td>

                            <td>
                                @if($gasto->comprobante)
                                    <a class="camion-btn secondary small"
                                       href="{{ route('admin.camion.gastos.comprobante', $gasto) }}"
                                       target="_blank"
                                       rel="noopener">
                                        <i class="mdi mdi-file-eye-outline"></i>
                                        Ver
                                    </a>
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                <div class="camion-actions">

                                    {{-- EDITAR GASTO --}}
                                    <a href="{{ route('admin.camion.gastos.edit', $gasto) }}"
                                       class="camion-btn small secondary">
                                        <i class="mdi mdi-pencil-outline"></i>
                                        Editar
                                    </a>

                                    {{-- ELIMINAR GASTO --}}
                                    <form method="POST"
                                          action="{{ route('admin.camion.gastos.destroy', $gasto) }}"
                                          class="camion-confirmar"
                                          data-titulo="¿Eliminar gasto?"
                                          data-mensaje="Se eliminará el gasto y su comprobante asociado. Esta acción no se puede deshacer."
                                          data-icono="warning"
                                          data-confirmar="Sí, eliminar"
                                          data-color="#ff4d6d">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="camion-btn small danger">
                                            <i class="mdi mdi-delete-outline"></i>
                                            Eliminar
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="camion-empty">
                                Todavía no hay gastos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

{{-- =========================================
     TOASTR Y SWEETALERT2
========================================= --}}
@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================================
    // NOTIFICACIONES TOASTR
    // =========================================
    if (typeof toastr !== 'undefined') {

        toastr.options = {
            closeButton: true,
            progressBar: true,
            newestOnTop: true,
            positionClass: 'toast-top-right',
            preventDuplicates: true,
            timeOut: 4000,
            extendedTimeOut: 1000,
            showDuration: 300,
            hideDuration: 300
        };

        @if(session('success'))
            toastr.success(@json(session('success')), '¡Éxito!');
        @endif

        @if(session('error'))
            toastr.error(@json(session('error')), '¡Error!');
        @endif

    }

    // =========================================
    // CONFIRMACIONES SWEETALERT2
    // =========================================
    document.querySelectorAll('.camion-confirmar').forEach(function(formulario) {

        formulario.addEventListener('submit', function(evento) {
            evento.preventDefault();

            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 no está disponible.');
                return;
            }

            const estilos = getComputedStyle(document.documentElement);

            const fondo = estilos.getPropertyValue('--bg-1').trim() || '#0a0f1c';
            const texto = estilos.getPropertyValue('--ink').trim() || '#e6ecff';

            Swal.fire({
                title: formulario.dataset.titulo,
                text: formulario.dataset.mensaje,
                icon: formulario.dataset.icono || 'warning',

                showCancelButton: true,
                confirmButtonText: formulario.dataset.confirmar || 'Confirmar',
                cancelButtonText: 'Cancelar',

                confirmButtonColor: formulario.dataset.color || '#ff4d6d',
                cancelButtonColor: '#55617d',

                reverseButtons: true,
                focusCancel: true,

                background: fondo,
                color: texto

            }).then(function(resultado) {

                if (!resultado.isConfirmed) {
                    return;
                }

                // Evita que se envíe dos veces.
                const boton = formulario.querySelector('button[type="submit"]');

                if (boton) {
                    boton.disabled = true;
                    boton.innerHTML =
                        '<i class="mdi mdi-loading mdi-spin"></i> Procesando...';
                }

                // Envía el formulario Laravel con su CSRF y PATCH/DELETE.
                HTMLFormElement.prototype.submit.call(formulario);

            });
        });

    });

});
</script>
@endsection
