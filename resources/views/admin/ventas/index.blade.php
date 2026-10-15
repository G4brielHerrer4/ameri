@extends('layouts.plantilla_maestra')

@section('title', 'Historial de Ventas')

@push('styles')
<style>
  .ventas-header {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; flex-wrap: wrap; margin-bottom: 1.75rem;
  }
  .ventas-header h3 {
    font-family: var(--font-display); font-weight: 600; color: var(--ink);
    display: flex; align-items: center; gap: 0.65rem; margin: 0;
  }
  .ventas-header h3 .icon-wrap {
    width: 44px; height: 44px; border-radius: var(--r-md);
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); display: inline-flex; align-items: center;
    justify-content: center; font-size: 1.35rem;
    box-shadow: 0 6px 20px -6px var(--glow);
  }
  .ventas-header p { color: var(--ink-muted); margin: 0.25rem 0 0; font-size: 0.88rem; }

  .btn-nuevo {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.65rem 1.25rem; border-radius: var(--r-pill); border: none;
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); font-size: 0.85rem; font-weight: 700;
    cursor: pointer; transition: all 0.3s var(--ease);
    box-shadow: 0 4px 14px -4px var(--glow); white-space: nowrap;
    text-decoration: none;
  }
  .btn-nuevo:hover {
    transform: translateY(-2px); color: var(--cyan-ink);
    box-shadow: 0 10px 24px -6px var(--glow), 0 0 0 1px var(--cyan);
  }

  /* Filtros rápidos */
  .filter-bar {
    display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;
  }
  .filter-chip {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.45rem 1rem; border-radius: var(--r-pill);
    border: 1px solid var(--line); background: var(--surface-alpha);
    color: var(--ink-muted); font-size: 0.82rem; font-weight: 600;
    cursor: pointer; transition: all 0.25s var(--ease);
    text-decoration: none;
  }
  .filter-chip:hover {
    border-color: var(--line-strong); color: var(--cyan);
  }
  .filter-chip.active {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); border-color: transparent;
    box-shadow: 0 4px 14px -4px var(--glow);
  }
  .filter-chip .mdi { font-size: 1rem; }
  .filter-chip .count {
    background: rgba(0,0,0,0.15);
    padding: 0.1rem 0.4rem;
    border-radius: var(--r-pill);
    font-size: 0.7rem;
    font-weight: 700;
  }
  .filter-chip.active .count { background: rgba(0,0,0,0.2); }

  /* Panel */
  .panel {
    background: var(--bg-1); border: 1px solid var(--line);
    border-radius: var(--r-lg); overflow: hidden;
    box-shadow: 0 20px 40px -28px var(--shadow);
  }
  .panel-body { padding: 1.35rem; }

  /* DataTable */
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
    padding-top: 0 !important; font-size: 0.78rem;
    color: var(--ink-faint); line-height: 36px;
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

  /* Tabla */
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

  .cell-code {
    font-family: var(--font-display); font-weight: 700;
    color: var(--cyan); font-size: 0.82rem; letter-spacing: 0.02em;
  }
  .cell-muted { color: var(--ink-muted); font-size: 0.8rem; }
  .cell-strong { font-weight: 600; color: var(--ink); }
  .cell-total {
    font-family: var(--font-display); font-weight: 700;
    color: var(--ink); font-size: 0.9rem;
  }

  /* Badges estado */
  .status-badge {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.3rem 0.65rem; border-radius: var(--r-pill);
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.03em;
    text-transform: uppercase; white-space: nowrap;
  }
  .status-badge::before {
    content: ''; width: 6px; height: 6px;
    border-radius: 50%; background: currentColor;
  }
  .status-pendiente {
    background: color-mix(in srgb, var(--warning) 14%, transparent);
    color: var(--warning);
    border: 1px solid color-mix(in srgb, var(--warning) 40%, transparent);
  }
  .status-vendida {
    background: color-mix(in srgb, var(--cyan) 14%, transparent);
    color: var(--cyan); border: 1px solid var(--line-strong);
  }
  .status-cancelada {
    background: color-mix(in srgb, var(--error) 12%, transparent);
    color: var(--error);
    border: 1px solid color-mix(in srgb, var(--error) 40%, transparent);
  }

  /* Acciones */
  .actions-cell {
    display: flex; gap: 0.35rem;
    justify-content: flex-end; align-items: center;
  }
  .action-btn {
    position: relative; overflow: hidden;
    width: 32px; height: 32px; border-radius: var(--r-sm);
    border: 1px solid var(--line); background: var(--surface-alpha);
    color: var(--ink-muted); cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    transition: all 0.25s var(--ease); padding: 0; font-size: 1rem;
    opacity: 0.75; text-decoration: none;
  }
  .panel table.dataTable tbody tr:hover .action-btn { opacity: 1; }
  .action-btn:hover { transform: translateY(-2px); }
  .action-btn.view:hover, .action-btn.edit:hover {
    color: var(--cyan); border-color: var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    box-shadow: 0 0 14px -4px var(--glow);
  }
  .action-btn.confirm:hover {
    color: var(--cyan); border-color: var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    box-shadow: 0 0 14px -4px var(--glow);
  }
  .action-btn.cancel:hover {
    color: var(--warning);
    border-color: color-mix(in srgb, var(--warning) 50%, transparent);
    background: color-mix(in srgb, var(--warning) 10%, transparent);
  }
  .action-btn.delete:hover {
    color: var(--error);
    border-color: color-mix(in srgb, var(--error) 50%, transparent);
    background: color-mix(in srgb, var(--error) 10%, transparent);
    box-shadow: 0 0 14px -4px rgba(255, 85, 112, 0.4);
  }

  /* SweetAlert2 */
  .swal2-popup {
    background: var(--bg-1) !important; color: var(--ink) !important;
    border: 1px solid var(--line-strong) !important;
    border-radius: var(--r-lg) !important;
    font-family: var(--font-body) !important;
  }
  .swal2-title {
    font-family: var(--font-display) !important;
    color: var(--ink) !important; font-weight: 600 !important;
  }
  .swal2-html-container { color: var(--ink-muted) !important; }
  .swal2-confirm, .swal2-cancel {
    font-weight: 700 !important;
    padding: 0.7rem 1.5rem !important;
    border-radius: var(--r-pill) !important;
    border: none !important;
  }
  .swal2-confirm {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
    color: var(--cyan-ink) !important;
  }
  .swal2-cancel {
    background: var(--bg-2) !important; color: var(--ink) !important;
    border: 1px solid var(--line) !important;
  }
  .swal2-confirm.btn-danger-swal {
    background: linear-gradient(135deg, #ff5570, #e63e5a) !important;
    color: #fff !important;
  }
  .swal2-confirm.btn-warning-swal {
    background: linear-gradient(135deg, #ffb547, #f39c12) !important;
    color: #1a1200 !important;
  }
  .swal2-container.swal2-backdrop-show {
    background: rgba(5, 8, 10, 0.65) !important;
    backdrop-filter: blur(4px);
  }

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

  <div class="ventas-header">
    <div>
      <h3>
        <span class="icon-wrap"><i class="mdi mdi-cart-outline"></i></span>
        Historial de Ventas
      </h3>
      <p>Registro y seguimiento de todas las ventas</p>
    </div>

    <a href="{{ route('admin.ventas.create') }}" class="btn-nuevo">
      <i class="mdi mdi-plus"></i> Hacer venta
    </a>
  </div>

  @php
    $countTodas       = $ventas->count();
    $countPendientes  = $ventas->where('estado', 'pendiente')->count();
    $countVendidas    = $ventas->where('estado', 'vendida')->count();
    $countCanceladas  = $ventas->where('estado', 'cancelada')->count();
    $countMias        = $ventas->where('user_id', auth()->id())->count();
  @endphp

  {{-- Filtros rápidos --}}
  <div class="filter-bar">
    <button type="button" class="filter-chip active" data-filter="all">
      <i class="mdi mdi-view-list"></i> Todas
      <span class="count">{{ $countTodas }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="mias">
      <i class="mdi mdi-account"></i> Mis ventas
      <span class="count">{{ $countMias }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="pendiente">
      <i class="mdi mdi-clock-outline"></i> Pendientes
      <span class="count">{{ $countPendientes }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="vendida">
      <i class="mdi mdi-check-circle-outline"></i> Vendidas
      <span class="count">{{ $countVendidas }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="cancelada">
      <i class="mdi mdi-close-circle-outline"></i> Canceladas
      <span class="count">{{ $countCanceladas }}</span>
    </button>
  </div>

  <div class="panel">
    <div class="panel-body">
      <table id="tablaVentas" class="table dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>
            <th>#</th>
            <th>N° Venta</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Vendedor</th>
            <th>Total</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($ventas as $venta)
            <tr data-estado="{{ $venta->estado }}"
                data-es-mia="{{ $venta->user_id === auth()->id() ? '1' : '0' }}">
              <td>{{ $venta->id }}</td>
              <td><span class="cell-code">{{ $venta->numero_venta }}</span></td>
              <td>
                <span class="cell-muted">
                  <i class="mdi mdi-calendar" style="color:var(--cyan);"></i>
                  {{ $venta->fecha_venta->format('d/m/Y') }}
                </span>
                <br>
                <small class="cell-muted">
                  <i class="mdi mdi-clock-outline" style="color:var(--cyan);"></i>
                  {{ $venta->created_at ? $venta->created_at->format('H:i') : '—' }}
                </small>
              </td>
              <td>
                <span class="cell-strong">{{ $venta->cliente_nombre }}</span>
                <br>
                <small class="cell-muted">{{ $venta->cliente_documento }}</small>
              </td>
              <td>
                <span class="cell-muted">{{ $venta->user->name ?? '—' }}</span>
                
                @if($venta->user_id === auth()->id())
                  <span class="badge" style="background:var(--glow-soft);color:var(--cyan);font-size:0.6rem;padding:0.15rem 0.4rem;border-radius:var(--r-pill);margin-left:0.25rem;">Tú</span>
                @endif
              </td>
              <td><span class="cell-total">Bs. {{ number_format($venta->total, 2) }}</span></td>
              <td>
                @if($venta->estado === 'pendiente')
                  <span class="status-badge status-pendiente">Pendiente</span>
                @elseif($venta->estado === 'vendida')
                  <span class="status-badge status-vendida">Vendida</span>
                @else
                  <span class="status-badge status-cancelada">Cancelada</span>
                @endif
              </td>
              <td>
                <div class="actions-cell">
                  <a href="{{ route('admin.ventas.show', $venta) }}" class="action-btn view" title="Ver">
                    <i class="mdi mdi-eye"></i>
                  </a>

                  @if($venta->estado === 'pendiente' && $venta->user_id === auth()->id())
                    <a href="{{ route('admin.ventas.edit', $venta) }}" class="action-btn edit" title="Editar">
                      <i class="mdi mdi-pencil"></i>
                    </a>

                    <form id="form-confirmar-{{ $venta->id }}"
                          action="{{ route('admin.ventas.confirmar', $venta) }}"
                          method="POST" class="d-none">
                      @csrf @method('PATCH')
                    </form>
                    <button type="button" class="action-btn confirm" title="Confirmar venta"
                            onclick='confirmarVenta({
                              numero: @json($venta->numero_venta),
                              cliente: @json($venta->cliente_nombre),
                              total: @json(number_format($venta->total, 2)),
                              url: "form-confirmar-{{ $venta->id }}"
                            })'>
                      <i class="mdi mdi-check-circle"></i>
                    </button>
                  @endif

                  @if($venta->estado !== 'cancelada' && $venta->user_id === auth()->id())
                    <form id="form-cancelar-{{ $venta->id }}"
                          action="{{ route('admin.ventas.cancelar', $venta) }}"
                          method="POST" class="d-none">
                      @csrf @method('PATCH')
                    </form>
                    <button type="button" class="action-btn cancel" title="Cancelar venta"
                            onclick='cancelarVenta({
                              numero: @json($venta->numero_venta),
                              estado: @json($venta->estado),
                              url: "form-cancelar-{{ $venta->id }}"
                            })'>
                      <i class="mdi mdi-close-circle"></i>
                    </button>
                  @endif

                  @if(
                      $venta->estado !== 'vendida' &&
                      (
                          (int) $venta->user_id === (int) auth()->id() ||
                          $venta->user?->role?->slug === 'vendedor'
                      )
                  )
                      <form id="form-delete-{{ $venta->id }}"
                            action="{{ route('admin.ventas.destroy', $venta) }}"
                            method="POST" class="d-none">
                          @csrf
                          @method('DELETE')
                      </form>

                      <button type="button" class="action-btn delete" title="Eliminar"
                              onclick='eliminarVenta({
                                  numero: @json($venta->numero_venta),
                                  url: "form-delete-{{ $venta->id }}"
                              })'>
                          <i class="mdi mdi-delete"></i>
                      </button>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const swalConfig = {
    buttonsStyling: false, reverseButtons: true, focusCancel: true,
    customClass: { confirmButton: 'swal2-confirm', cancelButton: 'swal2-cancel' },
  };

  function confirmarVenta({ numero, cliente, total, url }) {
    Swal.fire({
      ...swalConfig,
      title: '¿Confirmar venta?',
      html: `
        <p style="margin:0 0 0.5rem 0;">Vas a confirmar la venta:</p>
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:1rem;color:var(--ink);margin:0 0 0.5rem 0;">
          "${numero}"
        </p>
        <p style="margin:0 0 0.5rem 0;font-size:0.85rem;">
          Cliente: <strong style="color:var(--ink);">${cliente}</strong><br>
          Total: <strong style="color:var(--cyan);">Bs. ${total}</strong>
        </p>
        <p style="font-size:0.82rem;color:var(--ink-muted);margin:0;">
          Se <strong style="color:var(--cyan);">descontará el stock</strong> de los administradores y se notificará a quienes prestaron.
        </p>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-check-circle"></i> Sí, confirmar',
      cancelButtonText: '<i class="mdi mdi-close"></i> Cancelar',
    }).then(r => {
      if (r.isConfirmed) document.getElementById(url)?.submit();
    });
  }

  function cancelarVenta({ numero, estado, url }) {
    const esVendida = estado === 'vendida';
    Swal.fire({
      ...swalConfig,
      title: esVendida ? '¿Cancelar venta vendida?' : '¿Cancelar venta?',
      html: `
        <p style="margin:0 0 0.5rem 0;">Vas a cancelar:</p>
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:1rem;color:var(--ink);margin:0 0 0.5rem 0;">
          "${numero}"
        </p>
        ${esVendida
          ? '<p style="font-size:0.85rem;color:var(--cyan);margin:0;font-weight:600;">Se devolverá el stock a cada administrador de origen.</p>'
          : '<p style="font-size:0.85rem;color:var(--ink-muted);margin:0;">La venta se marcará como cancelada.</p>'}
      `,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-close-circle"></i> Sí, cancelar',
      cancelButtonText: '<i class="mdi mdi-arrow-left"></i> Volver',
      customClass: { confirmButton: 'swal2-confirm btn-warning-swal', cancelButton: 'swal2-cancel' },
    }).then(r => {
      if (r.isConfirmed) document.getElementById(url)?.submit();
    });
  }

  function eliminarVenta({ numero, url }) {
    Swal.fire({
      ...swalConfig,
      title: '¿Eliminar venta?',
      html: `
        <p style="margin:0 0 0.5rem 0;">Vas a eliminar:</p>
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:1rem;color:var(--ink);margin:0 0 0.75rem 0;">
          "${numero}"
        </p>
        <p style="font-size:0.82rem;color:var(--error);margin:0;">Esta acción no se puede deshacer.</p>
      `,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-delete"></i> Sí, eliminar',
      cancelButtonText: '<i class="mdi mdi-close"></i> Cancelar',
      customClass: { confirmButton: 'swal2-confirm btn-danger-swal', cancelButton: 'swal2-cancel' },
    }).then(r => {
      if (r.isConfirmed) document.getElementById(url)?.submit();
    });
  }

  toastr.options = {
    closeButton: true, progressBar: true, positionClass: 'toast-top-right',
    timeOut: 4500, extendedTimeOut: 1500, newestOnTop: true, preventDuplicates: true,
  };

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
    const tabla = $('#tablaVentas').DataTable({
      language: dtEspanol,
      pageLength: 10,
      lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
      order: [[0, 'desc']],
      columnDefs: [
        { orderable: false, targets: [7] },
        { responsivePriority: 1, targets: [1, 7] },
        { responsivePriority: 2, targets: [3] },
        { responsivePriority: 3, targets: [5, 6] },
      ],
      responsive: { details: { type: 'inline', target: 'tr' } },
      dom: '<"dt-top"lf>t<"dt-bottom"ip>',
    });

    // Filtros rápidos
    document.querySelectorAll('.filter-chip').forEach(chip => {
      chip.addEventListener('click', function () {
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');

        const filtro = this.dataset.filter;

        // Limpiar filtros previos
        $.fn.dataTable.ext.search.pop();

        if (filtro === 'mias') {
          $.fn.dataTable.ext.search.push((settings, data, dataIndex) => {
            const row = tabla.row(dataIndex).node();
            return row.dataset.esMia === '1';
          });
        } else if (filtro !== 'all') {
          $.fn.dataTable.ext.search.push((settings, data, dataIndex) => {
            const row = tabla.row(dataIndex).node();
            return row.dataset.estado === filtro;
          });
        }

        tabla.draw();
      });
    });

    @if(session('success')) toastr.success('{{ session('success') }}', '¡Listo!'); @endif
    @if(session('error'))   toastr.error('{{ session('error') }}', 'Error'); @endif
  });
</script>
@endpush