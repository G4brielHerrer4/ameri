@extends('layouts.plantilla_maestra')

@section('title', 'Stock por Administrador')

@push('styles')
<style>
  /* ==================== HEADER ==================== */
  .stocks-header {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; flex-wrap: wrap; margin-bottom: 1.75rem;
  }
  .stocks-header h3 {
    font-family: var(--font-display); font-weight: 600;
    color: var(--ink); margin: 0;
    display: flex; align-items: center; gap: 0.65rem;
  }
  .stocks-header h3 .icon-wrap {
    width: 44px; height: 44px; border-radius: var(--r-md);
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); display: inline-flex;
    align-items: center; justify-content: center; font-size: 1.35rem;
    box-shadow: 0 6px 20px -6px var(--glow);
  }
  .stocks-header p { color: var(--ink-muted); margin: 0.25rem 0 0; font-size: 0.88rem; }

  /* ==================== RESUMEN POR ADMIN ==================== */
  .resumen-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  .resumen-card {
    background: var(--bg-1); border: 1px solid var(--line);
    border-radius: var(--r-lg); padding: 1.25rem;
    box-shadow: 0 20px 40px -28px var(--shadow);
    transition: all 0.3s var(--ease);
    position: relative;
    overflow: hidden;
  }
  .resumen-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent);
    opacity: 0.5;
  }
  .resumen-card:hover {
    border-color: var(--line-strong);
    transform: translateY(-3px);
    box-shadow: 0 28px 50px -28px var(--shadow);
  }
  .resumen-card .rc-name {
    font-family: var(--font-display); font-weight: 600;
    color: var(--ink); font-size: 0.95rem; margin-bottom: 0.9rem;
    display: flex; align-items: center; gap: 0.5rem;
  }
  .resumen-card .rc-name .mdi { color: var(--cyan); font-size: 1.15rem; }
  .resumen-card .rc-stats {
    display: flex; gap: 1.5rem;
  }
  .resumen-card .rc-stat-label {
    font-size: 0.68rem; color: var(--ink-faint);
    text-transform: uppercase; letter-spacing: 0.06em;
    font-weight: 700; margin-bottom: 0.25rem;
  }
  .resumen-card .rc-stat-value {
    font-family: var(--font-display); font-weight: 700;
    font-size: 1.35rem; color: var(--cyan);
    text-shadow: 0 0 20px var(--glow-soft);
  }

  /* ==================== PANEL ==================== */
  .panel {
    background: var(--bg-1); border: 1px solid var(--line);
    border-radius: var(--r-lg); overflow: hidden;
    box-shadow: 0 20px 40px -28px var(--shadow);
  }
  .panel-body { padding: 1.35rem; }

  /* ==================== DATATABLE ==================== */
  .dataTables_wrapper { color: var(--ink); font-size: 0.85rem; }
  .dataTables_wrapper .dt-top {
    display: flex !important; align-items: center !important;
    justify-content: space-between !important; gap: 1rem;
    margin-bottom: 1rem; flex-wrap: wrap; width: 100%;
  }
  .dataTables_wrapper .dt-top > .dataTables_length,
  .dataTables_wrapper .dt-top > .dataTables_filter {
    float: none !important; margin: 0 !important; padding: 0 !important;
    width: auto !important; text-align: left !important; display: block !important;
  }
  .dataTables_wrapper .dt-top > .dataTables_filter { margin-left: auto !important; }
  .dataTables_wrapper .dt-top .dataTables_length label,
  .dataTables_wrapper .dt-top .dataTables_filter label {
    display: inline-flex !important; align-items: center !important;
    gap: 0.5rem !important; margin: 0 !important; padding: 0 !important;
    font-size: 0.82rem; color: var(--ink-muted); white-space: nowrap; font-weight: 500;
  }
  .dataTables_wrapper .dt-top .dataTables_length select {
    background: var(--bg-2) !important; border: 1px solid var(--line) !important;
    color: var(--ink) !important; border-radius: var(--r-md) !important;
    padding: 0 1.75rem 0 0.65rem !important; font-size: 0.82rem;
    cursor: pointer; height: 36px; margin: 0 !important;
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238fa8ae' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.65rem center !important;
  }
  .dataTables_wrapper .dt-top .dataTables_filter input {
    background: var(--bg-2) !important; border: 1px solid var(--line) !important;
    color: var(--ink) !important; border-radius: var(--r-md) !important;
    padding: 0.4rem 0.85rem !important; font-size: 0.85rem;
    margin: 0 0 0 0.5rem !important; height: 36px; min-width: 200px;
    transition: all 0.25s var(--ease);
  }
  .dataTables_wrapper .dt-top .dataTables_filter input:focus {
    border-color: var(--cyan) !important;
    box-shadow: 0 0 0 3px var(--glow-soft) !important; outline: none;
  }
  .dataTables_wrapper .dt-bottom {
    display: flex !important; align-items: center !important;
    justify-content: space-between !important; gap: 1rem;
    margin-top: 1rem; flex-wrap: wrap; width: 100%;
  }
  .dataTables_wrapper .dt-bottom > .dataTables_info,
  .dataTables_wrapper .dt-bottom > .dataTables_paginate {
    float: none !important; margin: 0 !important; padding: 0 !important;
    width: auto !important; text-align: left !important;
  }
  .dataTables_wrapper .dt-bottom > .dataTables_paginate { margin-left: auto !important; }
  .dataTables_wrapper .dt-bottom .dataTables_info {
    font-size: 0.78rem; color: var(--ink-faint); line-height: 36px;
    padding-top: 0 !important;
  }
  .dataTables_wrapper .dt-bottom .dataTables_paginate .pagination {
    margin: 0 !important; justify-content: flex-end !important;
    display: flex; align-items: center; gap: 3px;
  }
  .dataTables_wrapper .dataTables_paginate .page-item .page-link {
    background: var(--bg-1) !important; border: 1px solid var(--line) !important;
    color: var(--ink-muted) !important; border-radius: var(--r-sm) !important;
    padding: 0.35rem 0.7rem; font-size: 0.82rem;
    transition: all 0.25s var(--ease); margin: 0;
  }
  .dataTables_wrapper .dataTables_paginate .page-item .page-link:hover {
    border-color: var(--line-strong) !important; color: var(--cyan) !important;
  }
  .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
    color: var(--cyan-ink) !important; border-color: transparent !important;
    box-shadow: 0 6px 16px -6px var(--glow); font-weight: 700;
  }
  .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
    opacity: 0.4; cursor: not-allowed;
  }

  /* ==================== TABLA ==================== */
  .panel table.dataTable {
    width: 100% !important; border-collapse: separate !important;
    border-spacing: 0; margin: 0 !important;
  }
  .panel table.dataTable thead th {
    background: color-mix(in srgb, var(--bg-2) 60%, transparent) !important;
    color: var(--ink-muted) !important; font-family: var(--font-display);
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.06em;
    text-transform: uppercase; padding: 0.75rem 0.75rem !important;
    border-bottom: 1px solid var(--line) !important; border-top: none !important;
    white-space: nowrap;
  }
  .panel table.dataTable tbody td {
    padding: 0.75rem 0.75rem !important; border-top: 1px solid var(--line) !important;
    color: var(--ink); font-size: 0.85rem; vertical-align: middle;
  }
  .panel table.dataTable tbody tr:hover { background: var(--surface-alpha) !important; }
  .panel table.dataTable tbody tr.odd {
    background: color-mix(in srgb, var(--bg-2) 35%, transparent);
  }

  /* ==================== CELDAS ==================== */
  .cell-admin {
    display: flex; align-items: center; gap: 0.6rem;
  }
  .cell-admin-icon {
    width: 30px; height: 30px; border-radius: var(--r-sm);
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink);
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px -4px var(--glow);
    flex-shrink: 0;
  }
  .cell-admin-name {
    font-weight: 600; color: var(--ink); font-size: 0.85rem;
  }

  .cell-product-name {
    font-weight: 600; color: var(--ink); font-size: 0.85rem;
    display: block; max-width: 220px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .cell-product-meta {
    font-size: 0.72rem; color: var(--ink-faint);
    display: flex; gap: 0.35rem; align-items: center;
    margin-top: 0.15rem;
  }

  /* ==================== STOCK CANTIDAD ==================== */
  .stock-qty {
    display: inline-flex;
    align-items: baseline;
    gap: 0.3rem;
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.05rem;
    padding: 0.3rem 0.7rem;
    border-radius: var(--r-pill);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    border: 1px solid var(--line-strong);
    color: var(--cyan);
  }
  .stock-qty .unit {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--ink-muted);
    text-transform: lowercase;
  }

  .stock-qty.low {
    background: color-mix(in srgb, var(--warning) 12%, transparent);
    border-color: color-mix(in srgb, var(--warning) 40%, transparent);
    color: var(--warning);
  }
  .stock-qty.low .unit { color: color-mix(in srgb, var(--warning) 70%, var(--ink-muted)); }

  .stock-qty.zero {
    background: color-mix(in srgb, var(--error) 10%, transparent);
    border-color: color-mix(in srgb, var(--error) 40%, transparent);
    color: var(--error);
  }
  .stock-qty.zero .unit { color: color-mix(in srgb, var(--error) 70%, var(--ink-muted)); }

  /* ==================== RESPONSIVE ==================== */
  @media (max-width: 767.98px) {
    .dataTables_wrapper .dt-top,
    .dataTables_wrapper .dt-bottom {
      flex-direction: column; align-items: stretch !important;
    }
    .dataTables_wrapper .dt-top > .dataTables_filter,
    .dataTables_wrapper .dt-bottom > .dataTables_paginate {
      margin-left: 0 !important; width: 100%;
    }
    .dataTables_wrapper .dt-top .dataTables_filter input {
      width: 100%; min-width: 0;
    }
  }
</style>
@endpush

@section('content')
<div class="container-fluid">

  {{-- ==================== HEADER ==================== --}}
  <div class="stocks-header">
    <div>
      <h3>
        <span class="icon-wrap"><i class="mdi mdi-warehouse"></i></span>
        Stock por Administrador
      </h3>
      <p>Inventario actual acumulado a partir de compras recibidas</p>
    </div>
  </div>

  {{-- ==================== RESUMEN POR ADMIN ==================== --}}
  @if($resumenPorAdmin->count() > 0)
    <div class="resumen-grid">
      @foreach($resumenPorAdmin as $item)
        <div class="resumen-card">
          <div class="rc-name">
            <i class="mdi mdi-account-circle"></i>
            {{ $item['user']->name ?? 'Admin' }}
          </div>
          <div class="rc-stats">
            <div>
              <div class="rc-stat-label">Productos</div>
              <div class="rc-stat-value">{{ $item['productos'] }}</div>
            </div>
            <div>
              <div class="rc-stat-label">Unidades</div>
              <div class="rc-stat-value">
                {{ rtrim(rtrim(number_format($item['unidades'], 2, '.', ''), '0'), '.') }}
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif

  {{-- ==================== TABLA ==================== --}}
  <div class="panel">
    <div class="panel-body">
      <table id="tablaStocks" class="table dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>
            <th>#</th>
            <th>Administrador</th>
            <th>Producto</th>
            <th>Categoría / Tipo</th>
            <th class="text-end">Stock</th>
          </tr>
        </thead>
        <tbody>
          @forelse($stocks as $stock)
            <tr>
              <td>{{ $stock->id }}</td>

              {{-- Administrador --}}
              <td>
                <div class="cell-admin">
                  <span class="cell-admin-icon">
                    <i class="mdi mdi-account"></i>
                  </span>
                  <span class="cell-admin-name">
                    {{ $stock->user->name ?? '—' }}
                  </span>
                </div>
              </td>

              {{-- Producto --}}
              <td>
                <span class="cell-product-name" title="{{ $stock->producto->nombre ?? '' }}">
                  {{ $stock->producto->nombre ?? '—' }}
                </span>
                @if($stock->producto?->marca || $stock->producto?->medida)
                  <span class="cell-product-meta">
                    @if($stock->producto->marca)
                      <i class="mdi mdi-trademark"></i> {{ $stock->producto->marca }}
                    @endif
                    @if($stock->producto->medida)
                      · {{ $stock->producto->medida }}
                    @endif
                  </span>
                @endif
              </td>

              {{-- Categoría / Tipo --}}
              <td>
                <span style="font-size:0.82rem;color:var(--ink-muted);">
                  {{ $stock->producto?->tipoProducto?->categoria?->nombre ?? '—' }}
                </span>
                <br>
                <span style="font-size:0.72rem;color:var(--ink-faint);">
                  {{ $stock->producto?->tipoProducto?->nombre ?? '' }}
                </span>
              </td>

              {{-- Stock --}}
              <td class="text-end">
                @php
                  $cantidad = (float) $stock->cantidad;
                  $clase = $cantidad <= 0 ? 'zero' : ($cantidad < 5 ? 'low' : '');
                  $cantidadFmt = rtrim(rtrim(number_format($cantidad, 2, '.', ''), '0'), '.');
                @endphp
                <span class="stock-qty {{ $clase }}">
                  {{ $cantidadFmt }}
                  <span class="unit">{{ $stock->producto->unidad_base ?? 'unidades' }}</span>
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-5">
                <i class="mdi mdi-warehouse" style="font-size:44px;opacity:0.3;color:var(--ink-faint);"></i>
                <p class="mt-2 mb-0" style="color:var(--ink-muted);">
                  Aún no hay stock registrado.<br>
                  <small>Confirma compras como <strong>"recibidas"</strong> para generarlo.</small>
                </p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const dtEspanol = {
    decimal: ',', emptyTable: 'No hay datos disponibles',
    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
    infoEmpty: 'Mostrando 0 a 0 de 0 registros',
    infoFiltered: '(filtrado de _MAX_ registros totales)',
    lengthMenu: 'Mostrar _MENU_ registros',
    loadingRecords: 'Cargando...', processing: 'Procesando...', search: 'Buscar:',
    zeroRecords: 'No se encontraron resultados',
    paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' },
    aria: { sortAscending: ': ordenar ascendentemente', sortDescending: ': ordenar descendentemente' },
  };

  $(document).ready(function () {
    $('#tablaStocks').DataTable({
      language: dtEspanol,
      pageLength: 10,
      lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
      order: [[1, 'asc'], [2, 'asc']],
      columnDefs: [
        { responsivePriority: 1, targets: [1, 4] },
        { responsivePriority: 2, targets: [2] },
        { responsivePriority: 3, targets: [3] },
      ],
      responsive: { details: { type: 'inline', target: 'tr' } },
      dom: '<"dt-top"lf>t<"dt-bottom"ip>',
    });
  });
</script>
@endpush