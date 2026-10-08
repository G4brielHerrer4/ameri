@extends('layouts.plantilla_maestra')

@section('title', 'Órdenes de Compra')

@push('styles')
<style>
  /* =====================================================
     HEADER
     ===================================================== */
  .compras-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
  }
  .compras-header h3 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin: 0;
    font-size: 1.35rem;
  }
  .compras-header h3 .icon-wrap {
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
  .compras-header p {
    color: var(--ink-muted);
    margin: 0.25rem 0 0;
    font-size: 0.88rem;
  }

  .btn-nuevo {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.65rem 1.25rem;
    border-radius: var(--r-pill);
    border: none;
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink);
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    box-shadow: 0 4px 14px -4px var(--glow);
    white-space: nowrap;
    text-decoration: none;
  }
  .btn-nuevo:hover {
    transform: translateY(-2px);
    color: var(--cyan-ink);
    box-shadow: 0 10px 24px -6px var(--glow), 0 0 0 1px var(--cyan);
  }
  .btn-nuevo .mdi { font-size: 1rem; }

  /* =====================================================
     PANEL
     ===================================================== */
  .panel {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: 0 20px 40px -28px var(--shadow);
    min-width: 0;
  }
  .panel-body { padding: 1.1rem 1.25rem; }

  /* =====================================================
     DATATABLES — top y bottom en una fila
     ===================================================== */
  .dataTables_wrapper {
    color: var(--ink) !important;
    font-size: 0.85rem;
    padding: 0;
    width: 100%;
  }
  .dataTables_wrapper * { box-sizing: border-box; }

  /* TOP BAR */
  .dataTables_wrapper .dt-top {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 0.75rem 1rem !important;
    margin-bottom: 1rem !important;
    flex-wrap: wrap !important;
    width: 100% !important;
  }
  .dataTables_wrapper .dt-top > .dataTables_length,
  .dataTables_wrapper .dt-top > .dataTables_filter {
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
    width: auto !important;
    display: flex !important;
    align-items: center !important;
    text-align: left !important;
    flex: 0 0 auto !important;
  }
  .dataTables_wrapper .dt-top > .dataTables_filter {
    margin-left: auto !important;
  }

  .dataTables_wrapper .dt-top .dataTables_length label,
  .dataTables_wrapper .dt-top .dataTables_filter label {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    margin: 0 !important;
    padding: 0 !important;
    font-size: 0.82rem !important;
    color: var(--ink-muted) !important;
    white-space: nowrap !important;
    font-weight: 500 !important;
    line-height: 1 !important;
  }

  /* Select "Mostrar X" */
  .dataTables_wrapper .dt-top .dataTables_length select {
    background-color: var(--bg-2) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-md) !important;
    padding: 0 1.75rem 0 0.65rem !important;
    font-size: 0.82rem !important;
    cursor: pointer;
    height: 36px !important;
    line-height: 1.2 !important;
    margin: 0 !important;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238a98b8' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.65rem center !important;
    transition: border-color .18s ease, box-shadow .18s ease;
  }
  .dataTables_wrapper .dt-top .dataTables_length select:focus {
    border-color: var(--cyan) !important;
    box-shadow: 0 0 0 3px var(--glow-soft) !important;
    outline: none;
  }
  .dataTables_wrapper .dt-top .dataTables_length select option {
    background: var(--bg-2);
    color: var(--ink);
  }
  html[data-theme="light"] .dataTables_wrapper .dt-top .dataTables_length select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%234a5875' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") !important;
  }

  /* Input "Buscar" */
  .dataTables_wrapper .dt-top .dataTables_filter input {
    background-color: var(--bg-2) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-md) !important;
    padding: 0.4rem 0.85rem !important;
    font-size: 0.85rem !important;
    margin: 0 0 0 0.5rem !important;
    height: 36px !important;
    min-width: 200px;
    transition: border-color .18s ease, box-shadow .18s ease;
  }
  .dataTables_wrapper .dt-top .dataTables_filter input:focus {
    border-color: var(--cyan) !important;
    box-shadow: 0 0 0 3px var(--glow-soft) !important;
    outline: none;
  }
  .dataTables_wrapper .dt-top .dataTables_filter input::placeholder {
    color: var(--ink-faint) !important;
  }

  /* BOTTOM BAR */
  .dataTables_wrapper .dt-bottom {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 0.75rem 1rem !important;
    margin-top: 1rem !important;
    flex-wrap: wrap !important;
    width: 100% !important;
  }
  .dataTables_wrapper .dt-bottom > .dataTables_info,
  .dataTables_wrapper .dt-bottom > .dataTables_paginate {
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
    width: auto !important;
    display: flex !important;
    align-items: center !important;
    text-align: left !important;
    flex: 0 0 auto !important;
  }
  .dataTables_wrapper .dt-bottom > .dataTables_paginate {
    margin-left: auto !important;
  }
  .dataTables_wrapper .dt-bottom .dataTables_info {
    font-size: 0.78rem !important;
    color: var(--ink-faint) !important;
    line-height: 36px !important;
  }
  .dataTables_wrapper .dt-bottom .dataTables_paginate ul.pagination {
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    gap: 3px;
    flex-wrap: wrap;
  }

  /* =====================================================
     TABLA
     ===================================================== */
  .panel table.dataTable {
    width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0;
    margin: 0 !important;
    background: transparent !important;
    color: var(--ink) !important;
  }
  .panel table.dataTable thead th {
    background: var(--bg-2) !important;
    color: var(--ink-muted) !important;
    font-family: var(--font-display);
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 0.75rem 0.75rem !important;
    border-bottom: 1px solid var(--line) !important;
    border-top: none !important;
    white-space: nowrap;
  }
  .panel table.dataTable thead th:first-child { border-top-left-radius: var(--r-sm); }
  .panel table.dataTable thead th:last-child  { border-top-right-radius: var(--r-sm); }

  .panel table.dataTable thead .sorting::before,
  .panel table.dataTable thead .sorting::after,
  .panel table.dataTable thead .sorting_asc::before,
  .panel table.dataTable thead .sorting_asc::after,
  .panel table.dataTable thead .sorting_desc::before,
  .panel table.dataTable thead .sorting_desc::after {
    color: var(--ink-faint) !important;
    opacity: 0.5;
  }
  .panel table.dataTable thead .sorting_asc::after,
  .panel table.dataTable thead .sorting_desc::after {
    color: var(--cyan) !important;
    opacity: 1;
  }

  .panel table.dataTable tbody td {
    padding: 0.75rem 0.75rem !important;
    border-top: 1px solid var(--line) !important;
    color: var(--ink) !important;
    background: transparent !important;
    font-size: 0.85rem;
    vertical-align: middle;
  }
  .panel table.dataTable tbody tr {
    background: transparent !important;
    transition: background 0.2s ease;
  }
  .panel table.dataTable tbody tr:hover {
    background: var(--surface-alpha) !important;
  }
  .panel table.dataTable tbody tr.odd {
    background: color-mix(in srgb, var(--bg-2) 40%, transparent) !important;
  }
  .panel table.dataTable tbody tr.odd:hover {
    background: var(--surface-alpha) !important;
  }

  /* =====================================================
     CELDAS CUSTOM
     ===================================================== */
  .cell-code {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--cyan);
    font-size: 0.82rem;
    letter-spacing: 0.02em;
  }
  .cell-muted { color: var(--ink-muted); font-size: 0.8rem; }
  .cell-strong { font-weight: 600; color: var(--ink); }
  .cell-total {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--ink);
    font-size: 0.9rem;
  }

  /* =====================================================
     BADGES ESTADO
     ===================================================== */
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.65rem;
    border-radius: var(--r-pill);
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    white-space: nowrap;
  }
  .status-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
  }
  .status-pendiente {
    background: color-mix(in srgb, var(--warning) 14%, transparent);
    color: var(--warning);
    border: 1px solid color-mix(in srgb, var(--warning) 40%, transparent);
  }
  .status-recibida {
    background: color-mix(in srgb, var(--cyan) 14%, transparent);
    color: var(--cyan);
    border: 1px solid var(--line-strong);
  }
  .status-cancelada {
    background: color-mix(in srgb, var(--error) 12%, transparent);
    color: var(--error);
    border: 1px solid color-mix(in srgb, var(--error) 40%, transparent);
  }

  /* =====================================================
     ACCIONES
     ===================================================== */
  .actions-cell {
    display: flex;
    gap: 0.35rem;
    justify-content: flex-end;
    align-items: center;
  }
  .action-btn {
    width: 32px;
    height: 32px;
    border-radius: var(--r-sm);
    border: 1px solid var(--line);
    background: var(--surface-alpha);
    color: var(--ink-muted);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.22s var(--ease);
    padding: 0;
    font-size: 1rem;
    opacity: 0.75;
    text-decoration: none;
  }
  .panel table.dataTable tbody tr:hover .action-btn { opacity: 1; }
  .action-btn:hover { transform: translateY(-2px); }
  .action-btn.view:hover,
  .action-btn.edit:hover,
  .action-btn.recibir:hover {
    color: var(--cyan);
    border-color: var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    box-shadow: 0 0 14px -4px var(--glow);
  }
  .action-btn.delete:hover {
    color: var(--error);
    border-color: color-mix(in srgb, var(--error) 50%, transparent);
    background: color-mix(in srgb, var(--error) 10%, transparent);
    box-shadow: 0 0 14px -4px rgba(255, 77, 109, 0.4);
  }

  /* Botón responsive "+" */
  table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before,
  table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control::before {
    background: var(--cyan) !important;
    border: 1px solid var(--cyan) !important;
    color: var(--cyan-ink) !important;
    box-shadow: 0 0 8px var(--glow-soft);
  }
  table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td.dtr-control::before,
  table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th.dtr-control::before {
    background: var(--error) !important;
    border-color: var(--error) !important;
  }

  /* Fila hija expandida */
  table.dataTable > tbody > tr.child {
    background: var(--bg-2) !important;
  }
  table.dataTable > tbody > tr.child ul.dtr-details {
    padding: 0.5rem 0.75rem;
    margin: 0;
  }
  table.dataTable > tbody > tr.child ul.dtr-details > li {
    border-bottom: 1px solid var(--line);
    padding: 0.55rem 0;
  }
  table.dataTable > tbody > tr.child ul.dtr-details > li:last-child {
    border-bottom: none;
  }
  table.dataTable > tbody > tr.child span.dtr-title {
    font-weight: 700;
    color: var(--ink-muted);
    min-width: 100px;
    display: inline-block;
    font-size: 0.76rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }
  table.dataTable > tbody > tr.child span.dtr-data {
    color: var(--ink);
  }

  /* =====================================================
     TOASTR
     ===================================================== */
  #toast-container > div {
    opacity: 1;
    border-radius: var(--r-md);
    box-shadow: 0 20px 40px -20px var(--shadow);
    background-position: 15px center;
    background-size: 22px;
    padding: 16px 18px 16px 55px;
    font-family: var(--font-body);
    font-size: 0.88rem;
    font-weight: 500;
  }
  #toast-container > .toast-success {
    background-color: var(--bg-1);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2300d4ff'%3E%3Cpath d='M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm-1.4 14.6l-4.2-4.2 1.4-1.4 2.8 2.8 6.2-6.2 1.4 1.4z'/%3E%3C/svg%3E");
    border: 1px solid var(--line-strong);
    border-left: 4px solid var(--cyan);
    color: var(--ink);
  }
  #toast-container > .toast-success .toast-title { color: var(--cyan); }
  #toast-container > .toast-error {
    background-color: var(--bg-1);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ff4d6d'%3E%3Cpath d='M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 15h-2v-2h2zm0-4h-2V7h2z'/%3E%3C/svg%3E");
    border: 1px solid rgba(255, 77, 109, 0.35);
    border-left: 4px solid var(--error);
    color: var(--ink);
  }
  #toast-container > .toast-error .toast-title { color: var(--error); }
  #toast-container > .toast-warning {
    background-color: var(--bg-1);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ffb547'%3E%3Cpath d='M1 21h22L12 2zm12-3h-2v-2h2zm0-4h-2v-4h2z'/%3E%3C/svg%3E");
    border: 1px solid rgba(255, 181, 71, 0.35);
    border-left: 4px solid var(--warning);
    color: var(--ink);
  }
  #toast-container > .toast-warning .toast-title { color: var(--warning); }
  #toast-container .toast-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.88rem;
    margin-bottom: 2px;
  }
  #toast-container .toast-message {
    font-size: 0.82rem;
    line-height: 1.4;
    color: var(--ink-muted);
  }
  #toast-container .toast-progress {
    background: linear-gradient(90deg, var(--cyan), var(--cyan-2));
    opacity: 0.8;
    height: 3px;
  }

  /* =====================================================
     SWEETALERT2
     ===================================================== */
  .swal2-popup {
    background: var(--bg-1) !important;
    color: var(--ink) !important;
    border: 1px solid var(--line-strong) !important;
    border-radius: var(--r-lg) !important;
    font-family: var(--font-body) !important;
    box-shadow: 0 40px 80px -40px var(--shadow), 0 0 0 1px var(--line) !important;
    padding: 1.75rem !important;
  }
  .swal2-title {
    font-family: var(--font-display) !important;
    color: var(--ink) !important;
    font-weight: 600 !important;
    font-size: 1.25rem !important;
  }
  .swal2-html-container {
    color: var(--ink-muted) !important;
    font-size: 0.9rem !important;
    line-height: 1.5 !important;
    margin-top: 0.75rem !important;
  }
  .swal2-html-container strong { color: var(--cyan) !important; font-weight: 600; }
  .swal2-icon { border-width: 3px !important; }
  .swal2-icon.swal2-question { border-color: var(--cyan) !important; color: var(--cyan) !important; }
  .swal2-icon.swal2-warning { border-color: var(--warning) !important; color: var(--warning) !important; }
  .swal2-icon.swal2-success { border-color: var(--cyan) !important; color: var(--cyan) !important; }
  .swal2-icon.swal2-success .swal2-success-ring { border-color: var(--line-strong) !important; }
  .swal2-icon.swal2-success [class^='swal2-success-line'] { background-color: var(--cyan) !important; }

  .swal2-actions { gap: 0.75rem !important; margin-top: 1.5rem !important; }
  .swal2-confirm, .swal2-cancel {
    font-family: var(--font-body) !important;
    font-weight: 700 !important;
    font-size: 0.88rem !important;
    padding: 0.7rem 1.5rem !important;
    border-radius: var(--r-pill) !important;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    border: none !important;
    outline: none !important;
    letter-spacing: 0.02em;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
  }
  .swal2-confirm {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
    color: var(--cyan-ink) !important;
    box-shadow: 0 6px 20px -6px var(--glow) !important;
  }
  .swal2-confirm:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 12px 28px -6px var(--glow), 0 0 0 1px var(--cyan) !important;
  }
  .swal2-confirm.btn-danger-swal {
    background: linear-gradient(135deg, #ff4d6d, #d6304d) !important;
    color: #fff !important;
    box-shadow: 0 6px 20px -6px rgba(255, 77, 109, 0.5) !important;
  }
  .swal2-confirm.btn-warning-swal {
    background: linear-gradient(135deg, #ffb547, #f39c12) !important;
    color: #1a1200 !important;
  }
  .swal2-cancel {
    background: var(--bg-2) !important;
    color: var(--ink) !important;
    border: 1px solid var(--line) !important;
  }
  .swal2-cancel:hover {
    border-color: var(--line-strong) !important;
    color: var(--cyan) !important;
    transform: translateY(-2px) !important;
  }
  .swal2-container.swal2-backdrop-show {
    background: rgba(3, 6, 12, 0.7) !important;
    backdrop-filter: blur(4px);
  }
  html[data-theme="light"] .swal2-container.swal2-backdrop-show {
    background: rgba(10, 30, 60, 0.4) !important;
  }

  /* =====================================================
     RESPONSIVE
     ===================================================== */
  @media (max-width: 767.98px) {
    .dataTables_wrapper .dt-top,
    .dataTables_wrapper .dt-bottom {
      flex-direction: column !important;
      align-items: stretch !important;
    }
    .dataTables_wrapper .dt-top > .dataTables_length,
    .dataTables_wrapper .dt-top > .dataTables_filter,
    .dataTables_wrapper .dt-bottom > .dataTables_info,
    .dataTables_wrapper .dt-bottom > .dataTables_paginate {
      width: 100% !important;
      margin-left: 0 !important;
    }
    .dataTables_wrapper .dt-top .dataTables_filter label,
    .dataTables_wrapper .dt-top .dataTables_length label {
      width: 100% !important;
      justify-content: space-between !important;
    }
    .dataTables_wrapper .dt-top .dataTables_filter input {
      width: 100% !important;
      min-width: 0 !important;
      margin: 0.35rem 0 0 0 !important;
    }
    .dataTables_wrapper .dt-bottom .dataTables_paginate {
      justify-content: center !important;
      padding-top: 0.5rem;
    }
    .dataTables_wrapper .dt-bottom .dataTables_info {
      text-align: center;
      line-height: 1.4 !important;
    }
    .compras-header h3 { font-size: 1.15rem; }
    .compras-header .btn-nuevo { width: 100%; justify-content: center; }
  }

  @media (max-width: 575.98px) {
    .compras-header h3 .icon-wrap {
      width: 38px;
      height: 38px;
      font-size: 1.15rem;
    }
    .compras-header h3 { font-size: 1.05rem; }
    .panel-body { padding: 0.9rem 1rem; }
    .dataTables_wrapper .dataTables_paginate .paginate_button,
    .dataTables_wrapper .dataTables_paginate .page-item .page-link {
      padding: 0.3rem 0.55rem !important;
      min-width: 30px;
      font-size: 0.78rem !important;
    }
  }
</style>
@endpush

@section('content')
<div class="container-fluid">

  {{-- ==================== HEADER ==================== --}}
  <div class="compras-header">
    <div>
      <h3>
        <span class="icon-wrap"><i class="mdi mdi-cart-plus"></i></span>
        Órdenes de Compra
      </h3>
      <p>Registro y seguimiento de compras a proveedores</p>
    </div>

    <a href="{{ route('admin.compras.create') }}" class="btn-nuevo">
      <i class="mdi mdi-plus"></i> Nueva compra
    </a>
  </div>

  {{-- ==================== TABLA ==================== --}}
  <div class="panel">
    <div class="panel-body">
      <table id="tablaCompras" class="table dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>
            <th>#</th>
            <th>N° Orden</th>
            <th>Fecha</th>
            <th>Proveedor</th>
            <th>Registrado por</th>
            <th>Total</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($compras as $compra)
            <tr>
              <td>{{ $compra->id }}</td>
              <td><span class="cell-code">{{ $compra->numero_orden }}</span></td>
              <td><span class="cell-muted">{{ $compra->fecha_compra->format('d/m/Y') }}</span></td>
              <td><span class="cell-strong">{{ $compra->proveedor->nombre ?? '—' }}</span></td>
              <td><span class="cell-muted">{{ $compra->user->name ?? '—' }}</span></td>
              <td><span class="cell-total">Bs. {{ number_format($compra->total, 2) }}</span></td>
              <td>
                @if($compra->estado === 'pendiente')
                  <span class="status-badge status-pendiente">Pendiente</span>
                @elseif($compra->estado === 'recibida')
                  <span class="status-badge status-recibida">Recibida</span>
                @else
                  <span class="status-badge status-cancelada">Cancelada</span>
                @endif
              </td>
              <td>
                <div class="actions-cell">

                  <a href="{{ route('admin.compras.show', $compra) }}"
                     class="action-btn view"
                     title="Ver detalle"
                     aria-label="Ver detalle">
                    <i class="mdi mdi-eye"></i>
                  </a>

                  @if($compra->estado !== 'recibida')
                    <a href="{{ route('admin.compras.edit', $compra) }}"
                       class="action-btn edit"
                       title="Editar"
                       aria-label="Editar">
                      <i class="mdi mdi-pencil"></i>
                    </a>
                  @endif

                  @if($compra->estado === 'pendiente')
                    <form id="form-recibir-{{ $compra->id }}"
                          action="{{ route('admin.compras.estado', $compra) }}"
                          method="POST" class="d-none">
                      @csrf @method('PATCH')
                      <input type="hidden" name="estado" value="recibida">
                    </form>

                    <button type="button"
                            class="action-btn recibir"
                            title="Marcar como recibida"
                            aria-label="Marcar como recibida"
                            onclick='confirmarRecibir({
                              numero: @json($compra->numero_orden),
                              proveedor: @json($compra->proveedor->nombre ?? ""),
                              total: @json(number_format($compra->total, 2)),
                              url: "form-recibir-{{ $compra->id }}"
                            })'>
                      <i class="mdi mdi-package-down"></i>
                    </button>
                  @endif

                  @if($compra->estado !== 'recibida')
                    <form id="form-delete-{{ $compra->id }}"
                          action="{{ route('admin.compras.destroy', $compra) }}"
                          method="POST" class="d-none">
                      @csrf @method('DELETE')
                    </form>

                    <button type="button"
                            class="action-btn delete"
                            title="Eliminar"
                            aria-label="Eliminar"
                            onclick='confirmarEliminar({
                              nombre: @json($compra->numero_orden),
                              url: "form-delete-{{ $compra->id }}"
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
document.addEventListener('DOMContentLoaded', function () {

  /* ==================== TOASTR ==================== */
  toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 4500,
    extendedTimeOut: 1500,
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut',
    newestOnTop: true,
    preventDuplicates: true,
  };

  @if(session('success'))
    toastr.success(@json(session('success')), '¡Listo!');
  @endif

  @if(session('error'))
    toastr.error(@json(session('error')), 'Error');
  @endif


  /* ==================== SWEETALERT CONFIG ==================== */
  const swalConfig = {
    buttonsStyling: false,
    reverseButtons: true,
    focusCancel: true,
    customClass: {
      confirmButton: 'swal2-confirm',
      cancelButton: 'swal2-cancel',
    },
  };


  /* ==================== CONFIRMAR ELIMINAR ==================== */
  window.confirmarEliminar = function ({ nombre, url }) {
    Swal.fire({
      ...swalConfig,
      title: '¿Eliminar orden de compra?',
      html: `
        <p style="margin:0 0 0.5rem 0;">Vas a eliminar la orden:</p>
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:1rem;color:var(--ink);margin:0 0 0.75rem 0;">
          "${nombre}"
        </p>
        <p style="font-size:0.82rem;color:var(--error);margin:0;">
          Esta acción no se puede deshacer.
        </p>
      `,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-delete"></i> Sí, eliminar',
      cancelButtonText: '<i class="mdi mdi-close"></i> Cancelar',
      customClass: {
        confirmButton: 'swal2-confirm btn-danger-swal',
        cancelButton: 'swal2-cancel',
      },
    }).then(function (r) {
      if (r.isConfirmed) {
        const form = document.getElementById(url);
        if (form) form.submit();
      }
    });
  };


  /* ==================== CONFIRMAR RECIBIR ==================== */
  window.confirmarRecibir = function ({ numero, proveedor, total, url }) {
    Swal.fire({
      ...swalConfig,
      title: '¿Marcar como recibida?',
      html: `
        <p style="margin:0 0 0.5rem 0;">Vas a confirmar la recepción de:</p>
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:1rem;color:var(--ink);margin:0 0 0.5rem 0;">
          "${numero}"
        </p>
        <p style="margin:0 0 0.5rem 0;font-size:0.85rem;">
          Proveedor: <strong style="color:var(--ink);">${proveedor}</strong><br>
          Total: <strong style="color:var(--cyan);">Bs. ${total}</strong>
        </p>
        <p style="font-size:0.82rem;color:var(--ink-muted);margin:0;">
          Al confirmar, se <strong style="color:var(--cyan);">sumará el stock</strong>
          al administrador registrado.
        </p>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-check-circle"></i> Sí, recibir',
      cancelButtonText: '<i class="mdi mdi-close"></i> Cancelar',
    }).then(function (r) {
      if (r.isConfirmed) {
        const form = document.getElementById(url);
        if (form) form.submit();
      }
    });
  };


  /* ==================== DATATABLES ==================== */
  const dtEspanol = {
    decimal:        ',',
    emptyTable:     'No hay órdenes de compra registradas',
    info:           'Mostrando _START_ a _END_ de _TOTAL_ órdenes',
    infoEmpty:      'Mostrando 0 a 0 de 0 órdenes',
    infoFiltered:   '(filtrado de _MAX_ órdenes totales)',
    lengthMenu:     'Mostrar _MENU_',
    loadingRecords: 'Cargando...',
    processing:     'Procesando...',
    search:         'Buscar:',
    zeroRecords:    'No se encontraron resultados',
    paginate: {
      first:    'Primero',
      last:     'Último',
      next:     'Siguiente',
      previous: 'Anterior',
    },
    aria: {
      sortAscending:  ': ordenar ascendentemente',
      sortDescending: ': ordenar descendentemente',
    },
  };

  $('#tablaCompras').DataTable({
    language: dtEspanol,
    pageLength: 5,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
    order: [[0, 'desc']],
    autoWidth: false,
    columnDefs: [
      { orderable: false, targets: [7] },
      { responsivePriority: 1, targets: [1, 7] },
      { responsivePriority: 2, targets: [3] },
      { responsivePriority: 3, targets: [5, 6] },
      { responsivePriority: 4, targets: [2, 4] },
    ],
    responsive: {
      details: {
        type: 'inline',
        target: 'tr',
      },
    },
    dom: '<"dt-top"lf>t<"dt-bottom"ip>',
  });

});
</script>
@endpush