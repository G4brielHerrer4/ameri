@extends('layouts.plantilla_maestra')

@section('title', 'Reportes de Ventas')

@push('styles')
<style>
  /* =====================================================
     1. CONTENEDOR PRINCIPAL
     (se adapta al sidebar contraído o expandido)
     ===================================================== */
  .reporte-wrapper {
    width: 100%;
    min-width: 0;
    max-width: 100%;
  }

  /* =====================================================
     2. HEADER
     ===================================================== */
  .reporte-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
  }
  .reporte-header-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0;
    flex: 1 1 260px;
  }
  .reporte-header h3 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin: 0;
    font-size: 1.35rem;
    line-height: 1.2;
  }
  .reporte-header h3 .icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: var(--r-md);
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
    box-shadow: 0 6px 20px -6px var(--glow);
  }
  .reporte-header-info p {
    color: var(--ink-muted);
    margin: 0.15rem 0 0;
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .reporte-header-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    flex: 0 0 auto;
  }

  /* =====================================================
     3. FILTROS
     ===================================================== */
  .filtros-card {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    padding: 1.15rem 1.35rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 20px 40px -28px var(--shadow);
    width: 100%;
  }
  .filtros-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 0.85rem;
    align-items: end;
  }
  .filtros-grid .form-label {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--ink-muted);
    margin-bottom: 0.4rem;
    font-family: var(--font-display);
    letter-spacing: 0.05em;
    text-transform: uppercase;
    white-space: nowrap;
  }
  .filtros-grid .form-label .mdi { color: var(--cyan); font-size: 0.9rem; }
  .filtros-grid .form-control,
  .filtros-grid .form-select {
    width: 100%;
    min-width: 0;
  }

  .filtros-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
  }

  .btn-filtro {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.65rem 1.25rem;
    border-radius: var(--r-md);
    border: none;
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink);
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    box-shadow: 0 6px 16px -8px var(--glow);
    white-space: nowrap;
    text-decoration: none;
  }
  .btn-filtro:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px -8px var(--glow);
    color: var(--cyan-ink);
  }
  .btn-filtro-limpiar {
    background: var(--bg-2);
    color: var(--ink-muted);
    border: 1px solid var(--line);
    box-shadow: none;
  }
  .btn-filtro-limpiar:hover {
    color: var(--cyan);
    border-color: var(--line-strong);
    box-shadow: none;
  }

  /* =====================================================
     4. KPI CARDS
     ===================================================== */
  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
  }
  .kpi-grid-secondary {
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    margin-bottom: 1.5rem;
  }
  .kpi-card {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    padding: 1.15rem 1.25rem;
    position: relative;
    overflow: hidden;
    transition: transform .25s var(--ease), border-color .25s var(--ease);
    min-width: 0;
  }
  .kpi-card:hover {
    transform: translateY(-3px);
    border-color: color-mix(in srgb, var(--kpi-color) 50%, var(--line));
  }
  .kpi-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--kpi-color);
    box-shadow: 0 0 20px -4px var(--kpi-color);
  }
  .kpi-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
  }
  .kpi-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--ink-muted);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 0;
  }
  .kpi-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--r-md);
    background: color-mix(in srgb, var(--kpi-color) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--kpi-color) 30%, transparent);
    color: var(--kpi-color);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
  }
  .kpi-value {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.75rem;
    line-height: 1;
    color: var(--ink);
    margin: 0 0 0.35rem;
    word-break: break-word;
  }
  .kpi-sub {
    font-size: 0.75rem;
    color: var(--ink-muted);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .kpi-cyan    { --kpi-color: var(--cyan); }
  .kpi-green   { --kpi-color: #22c55e; }
  .kpi-orange  { --kpi-color: #f97316; }
  .kpi-warning { --kpi-color: #f5b04c; }
  .kpi-danger  { --kpi-color: #f2667b; }

  .kpi-grid-secondary .kpi-value { font-size: 1.5rem; }
  .kpi-grid-secondary .kpi-card  { padding: 1rem 1.15rem; }

  /* =====================================================
     5. CHARTS
     ===================================================== */
  .charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .chart-card {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    padding: 1.25rem;
    box-shadow: 0 20px 40px -28px var(--shadow);
    min-width: 0;
    overflow: hidden;
  }
  .chart-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    gap: 0.75rem;
    flex-wrap: wrap;
  }
  .chart-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--ink);
    margin: 0;
  }
  .chart-title .mdi { color: var(--cyan); font-size: 1.15rem; }
  .chart-sub {
    font-size: 0.72rem;
    color: var(--ink-muted);
    margin: 0;
  }
  .chart-body {
    position: relative;
    width: 100%;
    height: 260px;
    min-width: 0;
  }

  /* =====================================================
     6. TABLA
     ===================================================== */
  .tabla-card {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: 0 20px 40px -28px var(--shadow);
    width: 100%;
  }
  .tabla-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.25rem;
    border-bottom: 1px solid var(--line);
    background: linear-gradient(180deg,
      color-mix(in srgb, var(--cyan) 4%, transparent),
      transparent);
    gap: 0.75rem;
    flex-wrap: wrap;
  }
  .tabla-card-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 0.98rem;
    color: var(--ink);
    margin: 0;
  }
  .tabla-card-title .mdi { color: var(--cyan); }
  .tabla-card-body {
    padding: 0;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  .tabla-ventas {
    width: 100%;
    min-width: 720px; /* fuerza scroll horizontal en móvil */
    border-collapse: separate;
    border-spacing: 0;
  }
  .tabla-ventas thead th {
    background: var(--bg-2);
    color: var(--ink-muted);
    font-family: var(--font-display);
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 0.85rem 1rem;
    border-bottom: 1px solid var(--line);
    text-align: left;
    white-space: nowrap;
  }
  .tabla-ventas tbody td {
    padding: 0.85rem 1rem;
    border-top: 1px solid var(--line);
    color: var(--ink);
    font-size: 0.86rem;
    vertical-align: middle;
    white-space: nowrap;
  }
  .tabla-ventas tbody tr:hover { background: var(--surface-alpha); }

  .cell-code {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--cyan);
    font-size: 0.82rem;
  }
  .cell-strong { font-weight: 600; color: var(--ink); }
  .cell-muted  { font-size: 0.78rem; color: var(--ink-faint); }
  .cell-total  { font-family: var(--font-display); font-weight: 700; color: var(--ink); }

  .badge-estado {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.3rem 0.7rem;
    border-radius: var(--r-pill);
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }
  .badge-estado::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
  }
  .badge-pendiente {
    color: var(--warning);
    background: color-mix(in srgb, var(--warning) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--warning) 40%, transparent);
  }
  .badge-vendida {
    color: var(--cyan);
    background: color-mix(in srgb, var(--cyan) 12%, transparent);
    border: 1px solid var(--line-strong);
  }
  .badge-cancelada {
    color: var(--error);
    background: color-mix(in srgb, var(--error) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--error) 40%, transparent);
  }

  /* Paginación */
  .pagination-wrap {
    padding: 1rem 1.25rem;
    border-top: 1px solid var(--line);
    display: flex;
    justify-content: flex-end;
    overflow-x: auto;
  }
  .pagination-wrap nav { margin: 0; }

  /* Empty state */
  .tabla-empty {
    padding: 3rem 1rem;
    text-align: center;
    color: var(--ink-muted);
  }
  .tabla-empty .mdi {
    font-size: 3rem;
    color: var(--ink-faint);
    opacity: 0.4;
    display: block;
    margin-bottom: 0.5rem;
  }

  /* =====================================================
     7. RESPONSIVE
     ===================================================== */

  /* Tablet grande (992–1199): 1 gráfico por fila */
  @media (max-width: 1199.98px) {
    .charts-grid {
      grid-template-columns: 1fr;
    }
  }

  /* Tablet (768–991): KPIs en 2 columnas */
  @media (max-width: 991.98px) {
    .kpi-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    .kpi-grid-secondary {
      grid-template-columns: repeat(2, 1fr);
    }
    .chart-body { height: 240px; }
  }

  /* Móvil grande (576–767): KPIs en 2 col, header apilado */
  @media (max-width: 767.98px) {
    .reporte-header {
      align-items: flex-start;
    }
    .reporte-header-info {
      flex: 1 1 100%;
    }
    .reporte-header h3 { font-size: 1.15rem; }
    .reporte-header-actions {
      width: 100%;
    }
    .reporte-header-actions .btn-filtro {
      flex: 1;
      justify-content: center;
    }
    .filtros-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 0.75rem;
    }
    .filtros-actions {
      grid-column: 1 / -1;
      width: 100%;
    }
    .filtros-actions .btn-filtro {
      flex: 1;
    }
    .kpi-value { font-size: 1.35rem; }
    .kpi-grid-secondary .kpi-value { font-size: 1.25rem; }
    .chart-body { height: 220px; }
    .chart-card { padding: 1rem; }
  }

  /* Móvil pequeño (<576): todo en 1 columna */
  @media (max-width: 575.98px) {
    .reporte-header h3 { font-size: 1.05rem; }
    .reporte-header h3 .icon-wrap {
      width: 38px;
      height: 38px;
      font-size: 1.15rem;
    }
    .filtros-grid {
      grid-template-columns: 1fr;
    }
    .kpi-grid,
    .kpi-grid-secondary {
      grid-template-columns: 1fr;
    }
    .kpi-value { font-size: 1.5rem; }
    .kpi-grid-secondary .kpi-value { font-size: 1.4rem; }
    .filtros-card { padding: 1rem; }
    .chart-body { height: 200px; }
    .tabla-card-head { padding: 1rem; }
  }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="reporte-wrapper">

    {{-- ==================== HEADER ==================== --}}
    <div class="reporte-header">

      <div class="reporte-header-info">
        <h3>
          <span class="icon-wrap"><i class="mdi mdi-chart-box-outline"></i></span>
          <span>Reportes de Ventas</span>
        </h3>
        <p>Análisis por período, vendedor y producto</p>
      </div>

      <div class="reporte-header-actions">
        {{-- Exportar PDF --}}
        <a href="{{ route('admin.reportes.exportar-pdf', request()->query()) }}"
           class="btn-filtro"
           target="_blank"
           rel="noopener">
          <i class="mdi mdi-file-pdf-box"></i>
          <span>Exportar PDF</span>
        </a>

        {{-- Actualizar --}}
        <a href="{{ route('admin.reportes.index') }}" class="btn-filtro btn-filtro-limpiar">
          <i class="mdi mdi-refresh"></i>
          <span>Actualizar</span>
        </a>
      </div>

    </div>

    {{-- ==================== FILTROS ==================== --}}
    <form method="GET" action="{{ route('admin.reportes.index') }}" class="filtros-card">
      <div class="filtros-grid">

        <div>
          <label class="form-label">
            <i class="mdi mdi-calendar-start"></i> Desde
          </label>
          <input type="date" name="desde" class="form-control"
                 value="{{ $desde->format('Y-m-d') }}">
        </div>

        <div>
          <label class="form-label">
            <i class="mdi mdi-calendar-end"></i> Hasta
          </label>
          <input type="date" name="hasta" class="form-control"
                 value="{{ $hasta->format('Y-m-d') }}">
        </div>

        <div>
          <label class="form-label">
            <i class="mdi mdi-account-tie"></i> Vendedor
          </label>
          <select name="vendedor_id" class="form-select">
            <option value="">Todos</option>
            @foreach($vendedores as $v)
              <option value="{{ $v->id }}"
                {{ (string) $vendedorId === (string) $v->id ? 'selected' : '' }}>
                {{ $v->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label">
            <i class="mdi mdi-package-variant"></i> Producto
          </label>
          <select name="producto_id" class="form-select">
            <option value="">Todos</option>
            @foreach($productos as $p)
              <option value="{{ $p->id }}"
                {{ (string) $productoId === (string) $p->id ? 'selected' : '' }}>
                {{ $p->nombre }}{{ $p->marca ? ' · ' . $p->marca : '' }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label">
            <i class="mdi mdi-tag-outline"></i> Estado
          </label>
          <select name="estado" class="form-select">
            <option value="">Vendidas (por defecto)</option>
            <option value="vendida"   {{ $estado === 'vendida' ? 'selected' : '' }}>Vendidas</option>
            <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendientes</option>
            <option value="cancelada" {{ $estado === 'cancelada' ? 'selected' : '' }}>Canceladas</option>
          </select>
        </div>

        <div class="filtros-actions">
          <button type="submit" class="btn-filtro">
            <i class="mdi mdi-filter"></i> Filtrar
          </button>
          <a href="{{ route('admin.reportes.index') }}" class="btn-filtro btn-filtro-limpiar">
            <i class="mdi mdi-close"></i> Limpiar
          </a>
        </div>

      </div>
    </form>

    {{-- ==================== KPIs PRINCIPALES ==================== --}}
    <div class="kpi-grid">

      <div class="kpi-card kpi-cyan">
        <div class="kpi-head">
          <p class="kpi-label">
            @if($estado === 'cancelada')
              Ventas canceladas
            @elseif($estado === 'pendiente')
              Ventas pendientes
            @else
              Ventas realizadas
            @endif
          </p>
          <span class="kpi-icon"><i class="mdi mdi-cart-check"></i></span>
        </div>
        <p class="kpi-value">{{ number_format($totalVentas) }}</p>
        <p class="kpi-sub">en el período</p>
      </div>

      <div class="kpi-card kpi-green">
        <div class="kpi-head">
          <p class="kpi-label">Monto</p>
          <span class="kpi-icon"><i class="mdi mdi-cash-multiple"></i></span>
        </div>
        <p class="kpi-value">Bs. {{ number_format($montoTotal, 2) }}</p>
        <p class="kpi-sub">ingresos del período</p>
      </div>

      <div class="kpi-card kpi-orange">
        <div class="kpi-head">
          <p class="kpi-label">Productos vendidos</p>
          <span class="kpi-icon"><i class="mdi mdi-package-variant-closed"></i></span>
        </div>
        <p class="kpi-value">{{ number_format($productosVendidos, 0) }}</p>
        <p class="kpi-sub">unidades registradas</p>
      </div>

    </div>

    {{-- ==================== KPIs SECUNDARIOS ==================== --}}
    <div class="kpi-grid kpi-grid-secondary">

      <div class="kpi-card kpi-warning">
        <div class="kpi-head">
          <p class="kpi-label">Pendientes</p>
          <span class="kpi-icon"><i class="mdi mdi-clock-outline"></i></span>
        </div>
        <p class="kpi-value">{{ number_format($totalPendientes) }}</p>
        <p class="kpi-sub">Bs. {{ number_format($montoPendientes, 2) }} en camino</p>
      </div>

      <div class="kpi-card kpi-danger">
        <div class="kpi-head">
          <p class="kpi-label">Ventas perdidas</p>
          <span class="kpi-icon"><i class="mdi mdi-close-circle-outline"></i></span>
        </div>
        <p class="kpi-value">{{ number_format($totalCanceladas) }}</p>
        <p class="kpi-sub">Bs. {{ number_format($montoCanceladas, 2) }} cancelado</p>
      </div>

    </div>

    {{-- ==================== CHARTS ==================== --}}
    <div class="charts-grid">

      {{-- Línea: ventas por día --}}
      <div class="chart-card">
        <div class="chart-head">
          <div>
            <h6 class="chart-title">
              <i class="mdi mdi-chart-areaspline"></i>
              Evolución de ventas
            </h6>
            <p class="chart-sub">Monto vendido por día</p>
          </div>
        </div>
        <div class="chart-body">
          <canvas id="chartVentasPorDia"></canvas>
        </div>
      </div>

      {{-- Dona: ventas por estado --}}
      <div class="chart-card">
        <div class="chart-head">
          <div>
            <h6 class="chart-title">
              <i class="mdi mdi-chart-donut"></i>
              Por estado
            </h6>
            <p class="chart-sub">Distribución del período</p>
          </div>
        </div>
        <div class="chart-body">
          <canvas id="chartVentasPorEstado"></canvas>
        </div>
      </div>

    </div>

    <div class="charts-grid">

      {{-- Barras: top vendedores --}}
      <div class="chart-card">
        <div class="chart-head">
          <div>
            <h6 class="chart-title">
              <i class="mdi mdi-account-star-outline"></i>
              Top vendedores
            </h6>
            <p class="chart-sub">Ranking por monto vendido</p>
          </div>
        </div>
        <div class="chart-body">
          <canvas id="chartVentasPorVendedor"></canvas>
        </div>
      </div>

      {{-- Barras horizontales: top productos --}}
      <div class="chart-card">
        <div class="chart-head">
          <div>
            <h6 class="chart-title">
              <i class="mdi mdi-cube-outline"></i>
              Top productos
            </h6>
            <p class="chart-sub">Más vendidos en el período</p>
          </div>
        </div>
        <div class="chart-body">
          <canvas id="chartTopProductos"></canvas>
        </div>
      </div>

    </div>

    {{-- ==================== TABLA ==================== --}}
    <div class="tabla-card">
      <div class="tabla-card-head">
        <h6 class="tabla-card-title">
          <i class="mdi mdi-format-list-bulleted"></i>
          Detalle de ventas
        </h6>
        <span class="cell-muted">
          {{ $ventas->total() }} resultado{{ $ventas->total() !== 1 ? 's' : '' }}
        </span>
      </div>

      <div class="tabla-card-body">
        @if($ventas->count() > 0)
          <table class="tabla-ventas">
            <thead>
              <tr>
                <th>N° Venta</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Productos</th>
                <th class="text-end">Total</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              @foreach($ventas as $v)
                <tr>
                  <td><span class="cell-code">{{ $v->numero_venta }}</span></td>
                  <td>
                    <span class="cell-strong">{{ $v->fecha_venta->format('d/m/Y') }}</span>
                  </td>
                  <td>
                    <div class="cell-strong">{{ $v->cliente_nombre }}</div>
                    <div class="cell-muted">{{ $v->cliente_documento }}</div>
                  </td>
                  <td>
                    <span class="cell-strong">{{ $v->user->name ?? '—' }}</span>
                  </td>
                  <td>
                    <span class="cell-muted">{{ $v->detalles->count() }} ítem(s)</span>
                  </td>
                  <td class="text-end">
                    <span class="cell-total">Bs. {{ number_format($v->total, 2) }}</span>
                  </td>
                  <td>
                    @php
                      $clase = match($v->estado) {
                        'pendiente' => 'badge-pendiente',
                        'vendida'   => 'badge-vendida',
                        'cancelada' => 'badge-cancelada',
                        default     => 'badge-pendiente',
                      };
                    @endphp
                    <span class="badge-estado {{ $clase }}">
                      {{ ucfirst($v->estado) }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="tabla-empty">
            <i class="mdi mdi-chart-box-outline"></i>
            <p>No hay ventas en el período seleccionado.</p>
          </div>
        @endif
      </div>

      @if($ventas->hasPages())
        <div class="pagination-wrap">
          {{ $ventas->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

  /* ============================================================
     CONFIGURACIÓN GLOBAL DE CHART.JS
     ============================================================ */
  if (typeof Chart === 'undefined') {
    console.warn('Chart.js no está cargado');
    return;
  }

  const style = getComputedStyle(document.documentElement);
  const cyan  = style.getPropertyValue('--cyan').trim()       || '#00fff0';
  const ink   = style.getPropertyValue('--ink').trim()        || '#e6eff1';
  const muted = style.getPropertyValue('--ink-muted').trim()  || '#92a7ad';
  const line  = style.getPropertyValue('--line').trim()       || 'rgba(255,255,255,0.07)';

  Chart.defaults.color = muted;
  Chart.defaults.font.family = "'Instrument Sans', system-ui, sans-serif";
  Chart.defaults.font.size = 11;
  Chart.defaults.borderColor = line;

  function gradiente(ctx, color) {
    const g = ctx.createLinearGradient(0, 0, 0, 260);
    g.addColorStop(0, `color-mix(in srgb, ${color} 35%, transparent)`);
    g.addColorStop(1, `color-mix(in srgb, ${color} 0%, transparent)`);
    return g;
  }

  const tooltipBase = {
    backgroundColor: 'rgba(15, 23, 27, 0.95)',
    titleColor: ink,
    bodyColor: muted,
    borderColor: cyan,
    borderWidth: 1,
    padding: 10,
    cornerRadius: 8,
  };

  function vaciar(canvas, texto) {
    canvas.parentElement.innerHTML =
      '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--ink-muted);font-size:0.85rem;">' +
      texto + '</div>';
  }

  /* ============================================================
     GRÁFICO 1: Ventas por día
     ============================================================ */
  const dataDia = @json($ventasPorDia);
  const ctxDia  = document.getElementById('chartVentasPorDia');

  if (ctxDia && dataDia.length > 0) {
    const labels = dataDia.map(d => {
      const f = new Date(d.fecha + 'T00:00:00');
      return f.toLocaleDateString('es-BO', { day: '2-digit', month: 'short' });
    });
    const montos = dataDia.map(d => Number(d.monto));

    new Chart(ctxDia, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Monto (Bs.)',
          data: montos,
          borderColor: cyan,
          backgroundColor: gradiente(ctxDia.getContext('2d'), cyan),
          borderWidth: 2,
          tension: 0.4,
          fill: true,
          pointBackgroundColor: cyan,
          pointBorderColor: 'transparent',
          pointRadius: 3,
          pointHoverRadius: 6,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
          legend: { display: false },
          tooltip: {
            ...tooltipBase,
            callbacks: {
              label: ctx => 'Bs. ' + Number(ctx.parsed.y).toLocaleString('es-BO', { minimumFractionDigits: 2 })
            }
          }
        },
        scales: {
          x: { grid: { display: false } },
          y: {
            beginAtZero: true,
            ticks: { callback: v => 'Bs. ' + Number(v).toLocaleString('es-BO') }
          }
        }
      }
    });
  } else if (ctxDia) {
    vaciar(ctxDia, 'Sin datos en el período');
  }

  /* ============================================================
     GRÁFICO 2: Estado (dona)
     ============================================================ */
  const dataEstado = @json($ventasPorEstado);
  const ctxEstado  = document.getElementById('chartVentasPorEstado');

  if (ctxEstado && dataEstado.length > 0) {
    const labels  = dataEstado.map(d => d.estado.charAt(0).toUpperCase() + d.estado.slice(1));
    const valores = dataEstado.map(d => Number(d.cantidad));
    const colores = dataEstado.map(d => {
      if (d.estado === 'vendida')   return cyan;
      if (d.estado === 'pendiente') return '#f5b04c';
      return '#f2667b';
    });

    new Chart(ctxEstado, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data: valores,
          backgroundColor: colores,
          borderColor: 'transparent',
          borderWidth: 0,
          hoverOffset: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 12,
              usePointStyle: true,
              pointStyle: 'circle',
              color: muted,
              font: { size: 11 }
            }
          },
          tooltip: {
            ...tooltipBase,
            callbacks: {
              label: ctx => {
                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                return `${ctx.label}: ${ctx.parsed} (${pct}%)`;
              }
            }
          }
        }
      }
    });
  } else if (ctxEstado) {
    vaciar(ctxEstado, 'Sin datos en el período');
  }

  /* ============================================================
     GRÁFICO 3: Top vendedores (barras)
     ============================================================ */
  const dataVend = @json($ventasPorVendedor);
  const ctxVend  = document.getElementById('chartVentasPorVendedor');

  if (ctxVend && dataVend.length > 0) {
    const labels = dataVend.map(d => d.user?.name || 'Sin nombre');
    const montos = dataVend.map(d => Number(d.monto));

    new Chart(ctxVend, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Monto (Bs.)',
          data: montos,
          backgroundColor: cyan,
          borderRadius: 8,
          borderSkipped: false,
          maxBarThickness: 42
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            ...tooltipBase,
            callbacks: {
              label: ctx => 'Bs. ' + Number(ctx.parsed.y).toLocaleString('es-BO', { minimumFractionDigits: 2 })
            }
          }
        },
        scales: {
          x: { grid: { display: false } },
          y: {
            beginAtZero: true,
            ticks: { callback: v => 'Bs. ' + Number(v).toLocaleString('es-BO') }
          }
        }
      }
    });
  } else if (ctxVend) {
    vaciar(ctxVend, 'Sin datos en el período');
  }

  /* ============================================================
     GRÁFICO 4: Top productos (barras horizontales)
     ============================================================ */
  const dataTopProd = @json($topProductos);
  const ctxTopProd  = document.getElementById('chartTopProductos');

  if (ctxTopProd && dataTopProd.length > 0) {
    const labels = dataTopProd.map(d => {
      const n = d.producto?.nombre || 'Producto';
      return n.length > 22 ? n.substring(0, 22) + '…' : n;
    });
    const montos = dataTopProd.map(d => Number(d.monto_total));

    new Chart(ctxTopProd, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Monto (Bs.)',
          data: montos,
          backgroundColor: '#a855f7',
          borderRadius: 8,
          borderSkipped: false,
          maxBarThickness: 28
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            ...tooltipBase,
            borderColor: '#a855f7',
            callbacks: {
              label: ctx => 'Bs. ' + Number(ctx.parsed.x).toLocaleString('es-BO', { minimumFractionDigits: 2 })
            }
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            ticks: { callback: v => 'Bs. ' + Number(v).toLocaleString('es-BO') }
          },
          y: { grid: { display: false } }
        }
      }
    });
  } else if (ctxTopProd) {
    vaciar(ctxTopProd, 'Sin datos en el período');
  }

  /* ============================================================
     REDIMENSIONAR GRÁFICOS AL COLAPSAR/EXPANDIR SIDEBAR
     (esto es lo que faltaba para que se adapten)
     ============================================================ */
  const sidebarToggle = document.getElementById('sidebarToggle');

  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function () {
      // Esperamos a que termine la transición CSS del grid (180ms)
      setTimeout(function () {
        Chart.instances && Object.values(Chart.instances).forEach(function (chart) {
          try { chart.resize(); } catch (e) {}
        });
      }, 260);
    });
  }

  // También al redimensionar la ventana
  let resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
      Chart.instances && Object.values(Chart.instances).forEach(function (chart) {
        try { chart.resize(); } catch (e) {}
      });
    }, 150);
  });

});
</script>
@endpush