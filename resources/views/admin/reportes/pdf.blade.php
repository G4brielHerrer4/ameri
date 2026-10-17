<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Reporte de Ventas - AMERI</title>

  <style>
    /* ============================================================
       CONFIGURACIÓN GENERAL
       ============================================================ */
    @page {
      margin: 0;
      padding: 0;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'DejaVu Sans', sans-serif;
      font-size: 9px;
      color: #1a1a1a;
      line-height: 1.45;
      background: #ffffff;
      padding: 28px 34px 34px 34px;
    }

    /* ============================================================
       HEADER
       ============================================================ */
    .pdf-header {
      display: table;
      width: 100%;
      padding-bottom: 14px;
      border-bottom: 3px solid #00b8b0;
      margin-bottom: 18px;
    }
    .pdf-header-left {
      display: table-cell;
      vertical-align: middle;
      width: 60%;
    }
    .pdf-header-left h1 {
      font-size: 22px;
      color: #0a1013;
      margin-bottom: 3px;
      letter-spacing: -0.3px;
      font-weight: bold;
    }
    .pdf-header-left .brand {
      font-size: 10px;
      color: #00b8b0;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1.2px;
    }
    .pdf-header-left p {
      font-size: 9px;
      color: #666;
      margin-top: 2px;
    }
    .pdf-header-right {
      display: table-cell;
      vertical-align: middle;
      text-align: right;
      width: 40%;
      font-size: 8.5px;
      color: #555;
      line-height: 1.5;
    }
    .pdf-header-right .badge-generado {
      display: inline-block;
      background: #0a1013;
      color: #00fff0;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 8px;
      font-weight: bold;
      letter-spacing: 0.5px;
      margin-bottom: 5px;
    }
    .pdf-header-right p {
      margin: 0;
    }
    .pdf-header-right strong {
      color: #0a1013;
    }

    /* ============================================================
       FILTROS APLICADOS
       ============================================================ */
    .filtros-aplicados {
      background: #f0f9f8;
      border-left: 4px solid #00b8b0;
      padding: 10px 14px;
      margin-bottom: 16px;
      border-radius: 5px;
    }
    .filtros-aplicados .titulo {
      font-size: 8px;
      font-weight: bold;
      color: #0a1013;
      text-transform: uppercase;
      letter-spacing: 0.7px;
      margin-bottom: 4px;
    }
    .filtros-aplicados .filtro-item {
      display: inline-block;
      margin-right: 16px;
      font-size: 8.5px;
      color: #555;
      padding: 2px 0;
    }
    .filtros-aplicados .filtro-item strong {
      color: #00b8b0;
      font-weight: bold;
    }

    /* ============================================================
       KPI CARDS (3 por fila)
       ============================================================ */
    .kpi-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 8px 0;
      margin-bottom: 14px;
    }
    .kpi-table td {
      width: 33.333%;
      vertical-align: top;
      padding: 0;
    }

    .kpi-card {
      background: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 6px;
      padding: 11px 13px;
      position: relative;
      overflow: hidden;
      min-height: 70px;
    }
    .kpi-card::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 4px;
      background: #00b8b0;
    }
    .kpi-card.verde::before    { background: #22c55e; }
    .kpi-card.naranja::before  { background: #f97316; }
    .kpi-card.amarillo::before { background: #f5b04c; }
    .kpi-card.rojo::before     { background: #f2667b; }

    .kpi-card .label {
      font-size: 7.5px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: #888;
      margin-bottom: 5px;
    }
    .kpi-card .value {
      font-size: 17px;
      font-weight: bold;
      color: #0a1013;
      margin-bottom: 3px;
      line-height: 1.1;
    }
    .kpi-card .sub {
      font-size: 7.5px;
      color: #999;
    }

    /* ============================================================
       SECCIÓN GENÉRICA
       ============================================================ */
    .seccion {
      margin-bottom: 16px;
    }
    .seccion-title {
      font-size: 11px;
      font-weight: bold;
      color: #0a1013;
      padding-bottom: 6px;
      border-bottom: 2px solid #00b8b0;
      margin-bottom: 10px;
      text-transform: uppercase;
      letter-spacing: 0.4px;
    }
    .seccion-title .badge-count {
      display: inline-block;
      background: #00b8b0;
      color: #ffffff;
      font-size: 8px;
      padding: 2px 7px;
      border-radius: 9px;
      margin-left: 6px;
      vertical-align: middle;
    }

    /* ============================================================
       SECCIÓN VERDE — cards sueltas, fluyen una debajo de otra
       ============================================================ */
    .ventas-section {
      margin-bottom: 16px;
    }

    .ventas-head {
      display: table;
      width: 100%;
      padding: 10px 14px;
      margin-bottom: 10px;
      background: #f0fdf4;
      border: 1px solid #86efac;
      border-left: 4px solid #22c55e;
      border-radius: 6px;
    }
    .ventas-head-title {
      display: table-cell;
      vertical-align: middle;
      font-size: 11px;
      font-weight: bold;
      color: #166534;
    }
    .ventas-head-title .mdi-icon {
      display: inline-block;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: #22c55e;
      color: #ffffff;
      text-align: center;
      line-height: 18px;
      font-size: 11px;
      font-weight: bold;
      margin-right: 6px;
      vertical-align: middle;
    }
    .ventas-head-total {
      display: table-cell;
      vertical-align: middle;
      text-align: right;
      font-size: 10px;
      color: #166534;
      font-weight: bold;
    }
    .ventas-head-total .monto {
      background: #22c55e;
      color: #ffffff;
      padding: 3px 9px;
      border-radius: 5px;
      font-size: 10px;
      margin-left: 5px;
    }

    .venta-card {
      background: #ffffff;
      border: 1px solid #bbf7d0;
      border-left: 3px solid #22c55e;
      border-radius: 6px;
      padding: 10px 12px;
      margin-bottom: 8px;
      page-break-inside: avoid;
    }

    .venta-header {
      display: table;
      width: 100%;
      margin-bottom: 6px;
    }
    .venta-numero {
      display: table-cell;
      font-size: 9.5px;
      font-weight: bold;
      color: #166534;
      vertical-align: middle;
    }
    .venta-fecha {
      display: table-cell;
      font-size: 7.5px;
      color: #999;
      text-align: right;
      vertical-align: middle;
    }

    .venta-cliente {
      font-size: 9.5px;
      font-weight: bold;
      color: #0a1013;
      margin-bottom: 1px;
    }
    .venta-doc {
      font-size: 7.5px;
      color: #999;
      margin-bottom: 6px;
    }

    .venta-vendedor {
      background: #f5f7f9;
      border-radius: 4px;
      padding: 5px 8px;
      margin-bottom: 6px;
      display: table;
      width: 100%;
    }
    .venta-vendedor .label {
      display: table-cell;
      font-size: 7px;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: #888;
      font-weight: bold;
      vertical-align: middle;
      width: 40%;
    }
    .venta-vendedor .nombre {
      display: table-cell;
      font-size: 8.5px;
      color: #0a1013;
      font-weight: bold;
      text-align: right;
      vertical-align: middle;
    }

    .venta-productos {
      background: #f0fdf4;
      border-radius: 4px;
      padding: 5px 8px;
      margin-bottom: 6px;
    }
    .venta-productos .label {
      font-size: 7px;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: #166534;
      font-weight: bold;
      display: block;
      margin-bottom: 3px;
    }
    .venta-producto-item {
      font-size: 8px;
      color: #555;
      padding: 2px 0;
      border-bottom: 1px dotted #bbf7d0;
    }
    .venta-producto-item:last-child {
      border-bottom: none;
    }
    .venta-producto-item .nombre {
      color: #0a1013;
      font-weight: bold;
    }
    .venta-producto-item .cantidad {
      color: #166534;
      font-weight: bold;
      float: right;
    }

    .venta-footer {
      display: table;
      width: 100%;
      padding-top: 6px;
      border-top: 1px dashed #bbf7d0;
    }
    .venta-label {
      display: table-cell;
      font-size: 7px;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: #888;
      font-weight: bold;
      vertical-align: middle;
    }
    .venta-monto {
      display: table-cell;
      text-align: right;
      font-size: 11px;
      font-weight: bold;
      color: #16a34a;
      vertical-align: middle;
    }

    /* Mensaje si no hay ventas */
    .ventas-empty {
      padding: 20px 16px;
      text-align: center;
      background: #f0fdf4;
      border: 1px dashed #86efac;
      border-radius: 7px;
      color: #166534;
      font-size: 9px;
    }
    .ventas-empty .icon {
      display: block;
      font-size: 18px;
      color: #22c55e;
      margin-bottom: 4px;
    }

    /* ============================================================
       SECCIÓN ROJA — mismo patrón
       ============================================================ */
    .canceladas-section {
      margin-bottom: 16px;
    }

    .canceladas-head {
      display: table;
      width: 100%;
      padding: 10px 14px;
      margin-bottom: 10px;
      background: #fff5f7;
      border: 1px solid #fdd4db;
      border-left: 4px solid #f2667b;
      border-radius: 6px;
    }
    .canceladas-head-title {
      display: table-cell;
      vertical-align: middle;
      font-size: 11px;
      font-weight: bold;
      color: #b91c3c;
    }
    .canceladas-head-title .mdi-icon {
      display: inline-block;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: #f2667b;
      color: #ffffff;
      text-align: center;
      line-height: 18px;
      font-size: 11px;
      font-weight: bold;
      margin-right: 6px;
      vertical-align: middle;
    }
    .canceladas-head-total {
      display: table-cell;
      vertical-align: middle;
      text-align: right;
      font-size: 10px;
      color: #b91c3c;
      font-weight: bold;
    }
    .canceladas-head-total .monto {
      background: #f2667b;
      color: #ffffff;
      padding: 3px 9px;
      border-radius: 5px;
      font-size: 10px;
      margin-left: 5px;
    }

    .cancelada-card {
      background: #ffffff;
      border: 1px solid #fdd4db;
      border-left: 3px solid #f2667b;
      border-radius: 6px;
      padding: 10px 12px;
      margin-bottom: 8px;
      page-break-inside: avoid;
    }

    /* ============================================================
       RESUMEN FINANCIERO
       ============================================================ */
    .resumen-financiero {
      background: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 16px;
    }
    .resumen-financiero .resumen-head {
      background: #0a1013;
      color: #ffffff;
      padding: 8px 14px;
      font-size: 10px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.7px;
    }
    .resumen-financiero table {
      width: 100%;
      border-collapse: collapse;
    }
    .resumen-financiero tbody td {
      padding: 8px 14px;
      font-size: 9.5px;
      border-bottom: 1px solid #f0f0f0;
      color: #333;
    }
    .resumen-financiero tbody td:last-child {
      text-align: right;
      font-weight: bold;
    }
    .resumen-financiero tbody tr:nth-child(1) td:last-child { color: #0d9488; }
    .resumen-financiero tbody tr:nth-child(2) td:last-child { color: #d97706; }
    .resumen-financiero tbody tr:nth-child(3) td:last-child { color: #dc2626; }
    .resumen-financiero tbody tr:last-child td {
      border-bottom: none;
      background: #00b8b0;
      color: #ffffff;
      font-size: 10.5px;
      font-weight: bold;
      padding: 10px 14px;
    }

    /* ============================================================
       TABLAS
       ============================================================ */
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 8.5px;
    }
    table.data-table thead th {
      background: #0a1013;
      color: #ffffff;
      padding: 7px 9px;
      text-align: left;
      font-weight: bold;
      font-size: 7.5px;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      border: 1px solid #0a1013;
    }
    table.data-table tbody td {
      padding: 6px 9px;
      border-bottom: 1px solid #e5e5e5;
      color: #333;
    }
    table.data-table tbody tr:nth-child(even) td {
      background: #f9fafb;
    }
    table.data-table .text-right  { text-align: right; }
    table.data-table .text-center { text-align: center; }
    table.data-table .text-bold   { font-weight: bold; color: #0a1013; }
    table.data-table .text-cyan   { color: #00b8b0; font-weight: bold; }
    table.data-table .text-muted  { color: #999; font-size: 7.5px; }

    /* Número de ranking plano (sin círculo) */
    .rank-num-plain {
      font-size: 10px;
      font-weight: bold;
      color: #00b8b0;
    }

    /* ============================================================
       BADGES ESTADO
       ============================================================ */
    .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 9px;
      font-size: 6.5px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .badge-pendiente { background: #fef3c7; color: #92400e; }
    .badge-vendida   { background: #d1fae5; color: #065f46; }
    .badge-cancelada { background: #fee2e2; color: #991b1b; }

    /* ============================================================
       FOOTER
       ============================================================ */
    .pdf-footer {
      margin-top: 24px;
      padding-top: 12px;
      border-top: 2px solid #00b8b0;
      text-align: center;
      font-size: 7.5px;
      color: #888;
      line-height: 1.5;
    }
    .pdf-footer strong {
      color: #0a1013;
    }

    /* ============================================================
       PAGE BREAK
       ============================================================ */
    .page-break {
      page-break-before: always;
    }
  </style>
</head>
<body>

{{-- ====================================================
     HEADER
     ==================================================== --}}
<div class="pdf-header">
  <div class="pdf-header-left">
    <h1>Reporte de Ventas</h1>
    <div class="brand">AMERI · Cerámicos y Cemento Cola</div>
    <p>Análisis del período seleccionado</p>
  </div>
  <div class="pdf-header-right">
    <div class="badge-generado">REPORTE OFICIAL</div>
    <p><strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    <p><strong>Por:</strong> {{ auth()->user()->name ?? 'Usuario' }}</p>
    <p><strong>Período:</strong> {{ $desde->format('d/m/Y') }} → {{ $hasta->format('d/m/Y') }}</p>
  </div>
</div>

{{-- ====================================================
     FILTROS APLICADOS
     ==================================================== --}}
<div class="filtros-aplicados">
  <div class="titulo">Filtros aplicados</div>

  <span class="filtro-item">
    Período: <strong>{{ $desde->format('d/m/Y') }} → {{ $hasta->format('d/m/Y') }}</strong>
  </span>

  <span class="filtro-item">
    Estado: <strong>{{ $estado ? ucfirst($estado) : 'Vendidas' }}</strong>
  </span>

  @if($vendedorNombre)
    <span class="filtro-item">
      Vendedor: <strong>{{ $vendedorNombre }}</strong>
    </span>
  @endif

  @if($productoNombre)
    <span class="filtro-item">
      Producto: <strong>{{ $productoNombre }}</strong>
    </span>
  @endif
</div>

{{-- ====================================================
     KPIs PRINCIPALES
     ==================================================== --}}
<table class="kpi-table">
  <tr>
    <td>
      <div class="kpi-card">
        <div class="label">Ventas realizadas</div>
        <div class="value">{{ number_format($totalVentas) }}</div>
        <div class="sub">en el período</div>
      </div>
    </td>
    <td>
      <div class="kpi-card verde">
        <div class="label">Monto total</div>
        <div class="value">Bs. {{ number_format($montoTotal, 2) }}</div>
        <div class="sub">ingresos confirmados</div>
      </div>
    </td>
    <td>
      <div class="kpi-card naranja">
        <div class="label">Productos vendidos</div>
        <div class="value">{{ number_format($productosVendidos, 0) }}</div>
        <div class="sub">unidades registradas</div>
      </div>
    </td>
  </tr>
</table>

{{-- ====================================================
     KPIs SECUNDARIOS
     ==================================================== --}}
<table class="kpi-table">
  <tr>
    <td>
      <div class="kpi-card amarillo">
        <div class="label">Pendientes</div>
        <div class="value">{{ number_format($totalPendientes) }}</div>
        <div class="sub">Bs. {{ number_format($montoPendientes, 2) }} en camino</div>
      </div>
    </td>
    <td>
      <div class="kpi-card rojo">
        <div class="label">Ventas canceladas</div>
        <div class="value">{{ number_format($totalCanceladas) }}</div>
        <div class="sub">Bs. {{ number_format($montoCanceladas, 2) }} perdido</div>
      </div>
    </td>
    <td></td>
  </tr>
</table>

{{-- ====================================================
     SECCIÓN VERDE: VENTAS DEL PERÍODO FILTRADO
     ==================================================== --}}
@if($ventas->count() > 0)

  <div class="ventas-section">

    <div class="ventas-head">
      <div class="ventas-head-title">
        <span class="mdi-icon">✓</span>
        Ventas del período
        @if($estado)
          · {{ ucfirst($estado) }}
        @else
          · Confirmadas
        @endif
      </div>
      <div class="ventas-head-total">
        Total:
        <span class="monto">Bs. {{ number_format($montoTotal, 2) }}</span>
      </div>
    </div>

    @foreach($ventas as $venta)
      <div class="venta-card">

        <div class="venta-header">
          <div class="venta-numero">{{ $venta->numero_venta }}</div>
          <div class="venta-fecha">{{ $venta->fecha_venta->format('d/m/Y') }}</div>
        </div>

        <div class="venta-cliente">{{ $venta->cliente_nombre }}</div>
        <div class="venta-doc">CI/NIT: {{ $venta->cliente_documento }}</div>

        <div class="venta-vendedor">
          <span class="label">Vendedor</span>
          <span class="nombre">{{ $venta->user->name ?? 'Sin asignar' }}</span>
        </div>

        @if($venta->detalles->count() > 0)
          <div class="venta-productos">
            <span class="label">Productos ({{ $venta->detalles->count() }})</span>

            @foreach($venta->detalles as $detalle)
              <div class="venta-producto-item">
                <span class="nombre">
                  {{ $detalle->producto->nombre ?? 'Producto eliminado' }}
                  @if($detalle->producto && $detalle->producto->marca)
                    · {{ $detalle->producto->marca }}
                  @endif
                </span>
                <span class="cantidad">
                  × {{ number_format($detalle->cantidad, 0) }}
                </span>
              </div>
            @endforeach
          </div>
        @endif

        <div class="venta-footer">
          <span class="venta-label">Total</span>
          <span class="venta-monto">Bs. {{ number_format($venta->total, 2) }}</span>
        </div>

      </div>
    @endforeach

  </div>

@else

  <div class="ventas-empty">
    <span class="icon">○</span>
    <strong>Sin ventas en el período</strong><br>
    No se registraron ventas con los filtros seleccionados.
  </div>

@endif

{{-- ====================================================
     SECCIÓN ROJA: VENTAS CANCELADAS
     ==================================================== --}}
@if($ventasCanceladas->count() > 0)

  <div class="canceladas-section">

    <div class="canceladas-head">
      <div class="canceladas-head-title">
        <span class="mdi-icon">×</span>
        Ventas canceladas en el período
      </div>
      <div class="canceladas-head-total">
        Total perdido:
        <span class="monto">Bs. {{ number_format($montoCanceladas, 2) }}</span>
      </div>
    </div>

    @foreach($ventasCanceladas as $venta)
      <div class="cancelada-card">

        <div class="venta-header">
          <div class="venta-numero" style="color:#b91c3c;">{{ $venta->numero_venta }}</div>
          <div class="venta-fecha">{{ $venta->fecha_venta->format('d/m/Y') }}</div>
        </div>

        <div class="venta-cliente">{{ $venta->cliente_nombre }}</div>
        <div class="venta-doc">CI/NIT: {{ $venta->cliente_documento }}</div>

        <div class="venta-vendedor">
          <span class="label">Vendedor</span>
          <span class="nombre">{{ $venta->user->name ?? 'Sin asignar' }}</span>
        </div>

        @if($venta->detalles->count() > 0)
          <div class="venta-productos" style="background:#fff5f7;">
            <span class="label" style="color:#b91c3c;">Productos ({{ $venta->detalles->count() }})</span>

            @foreach($venta->detalles as $detalle)
              <div class="venta-producto-item" style="border-color:#fdd4db;">
                <span class="nombre">
                  {{ $detalle->producto->nombre ?? 'Producto eliminado' }}
                  @if($detalle->producto && $detalle->producto->marca)
                    · {{ $detalle->producto->marca }}
                  @endif
                </span>
                <span class="cantidad" style="color:#b91c3c;">
                  × {{ number_format($detalle->cantidad, 0) }}
                </span>
              </div>
            @endforeach
          </div>
        @endif

        <div class="venta-footer" style="border-color:#fdd4db;">
          <span class="venta-label">Monto perdido</span>
          <span class="venta-monto" style="color:#f2667b;">
            Bs. {{ number_format($venta->total, 2) }}
          </span>
        </div>

      </div>
    @endforeach

  </div>

@endif

{{-- ====================================================
     RESUMEN FINANCIERO
     ==================================================== --}}
<div class="resumen-financiero">
  <div class="resumen-head">Resumen financiero del período</div>
  <table>
    <tbody>
      <tr>
        <td>Ingresos confirmados (ventas vendidas)</td>
        <td>Bs. {{ number_format($montoTotal, 2) }}</td>
      </tr>
      <tr>
        <td>Monto en ventas pendientes de cobro</td>
        <td>Bs. {{ number_format($montoPendientes, 2) }}</td>
      </tr>
      <tr>
        <td>Monto perdido (ventas canceladas)</td>
        <td>Bs. {{ number_format($montoCanceladas, 2) }}</td>
      </tr>
      <tr>
        <td>INGRESOS TOTALES REALES</td>
        <td>Bs. {{ number_format($montoTotal, 2) }}</td>
      </tr>
    </tbody>
  </table>
</div>

{{-- ====================================================
     TOP VENDEDORES
     ==================================================== --}}
@if($ventasPorVendedor->count() > 0)
  <div class="seccion page-break">
    <div class="seccion-title">Top Vendedores</div>

    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 8%;">#</th>
          <th style="width: 50%;">Vendedor</th>
          <th style="width: 15%;" class="text-center">Ventas</th>
          <th style="width: 27%;" class="text-right">Monto</th>
        </tr>
      </thead>
      <tbody>
        @foreach($ventasPorVendedor as $i => $v)
          <tr>
            <td class="text-center">
              <span class="rank-num-plain">{{ $i + 1 }}</span>
            </td>
            <td class="text-bold">{{ $v->user->name ?? 'Sin nombre' }}</td>
            <td class="text-center">{{ $v->cantidad }}</td>
            <td class="text-right text-cyan">Bs. {{ number_format($v->monto, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif

{{-- ====================================================
     TOP PRODUCTOS
     ==================================================== --}}
@if($topProductos->count() > 0)
  <div class="seccion">
    <div class="seccion-title">Top Productos más Vendidos</div>

    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 8%;">#</th>
          <th style="width: 52%;">Producto</th>
          <th style="width: 15%;" class="text-center">Cantidad</th>
          <th style="width: 25%;" class="text-right">Monto</th>
        </tr>
      </thead>
      <tbody>
        @foreach($topProductos as $i => $p)
          <tr>
            <td class="text-center">
              <span class="rank-num-plain">{{ $i + 1 }}</span>
            </td>
            <td>
              <span class="text-bold">{{ $p->producto->nombre ?? 'Producto' }}</span>
              @if($p->producto && $p->producto->marca)
                <br><span class="text-muted">{{ $p->producto->marca }}</span>
              @endif
            </td>
            <td class="text-center">{{ number_format($p->cantidad_total, 2) }}</td>
            <td class="text-right text-cyan">Bs. {{ number_format($p->monto_total, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif

{{-- ====================================================
     DETALLE DE VENTAS (tabla resumen)
     ==================================================== --}}
<div class="seccion page-break">
  <div class="seccion-title">
    Detalle Resumido
    <span class="badge-count">{{ $ventas->count() }}</span>
  </div>

  @if($ventas->count() > 0)
    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 13%;">N° Venta</th>
          <th style="width: 11%;">Fecha</th>
          <th style="width: 25%;">Cliente</th>
          <th style="width: 15%;">Vendedor</th>
          <th style="width: 8%;" class="text-center">Ítems</th>
          <th style="width: 15%;" class="text-right">Total</th>
          <th style="width: 13%;" class="text-center">Estado</th>
        </tr>
      </thead>
      <tbody>
        @foreach($ventas as $v)
          <tr>
            <td class="text-cyan">{{ $v->numero_venta }}</td>
            <td>{{ $v->fecha_venta->format('d/m/Y') }}</td>
            <td>
              <span class="text-bold">{{ $v->cliente_nombre }}</span>
              <br><span class="text-muted">{{ $v->cliente_documento }}</span>
            </td>
            <td>{{ $v->user->name ?? '—' }}</td>
            <td class="text-center">{{ $v->detalles->count() }}</td>
            <td class="text-right text-bold">Bs. {{ number_format($v->total, 2) }}</td>
            <td class="text-center">
              <span class="badge badge-{{ $v->estado }}">
                {{ ucfirst($v->estado) }}
              </span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align:center;padding:24px;color:#888;">
      No hay ventas en el período seleccionado.
    </p>
  @endif
</div>

{{-- ====================================================
     FOOTER
     ==================================================== --}}
<div class="pdf-footer">
  <strong>AMERI</strong> · Cerámicos y Cemento Cola
  · Reporte generado automáticamente el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
  · Documento oficial
</div>

</body>
</html>