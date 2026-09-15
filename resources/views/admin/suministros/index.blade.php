@extends('layouts.plantilla_maestra')

@section('title', 'Suministros')

@push('styles')
<style>
  /* ==================== ENCABEZADO ==================== */
  .suministros-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.75rem;
  }
  .suministros-header h3 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin: 0;
  }

  .suministros-header h3 .icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: var(--r-md);
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    box-shadow: 0 6px 20px -6px var(--glow);
  }

  .suministros-header p {
    color: var(--ink-muted);
    margin: 0.25rem 0 0;
    font-size: 0.88rem;
  }

  .suministros-header .header-stats {
    display: flex;
    gap: 0.75rem;
  }

  .stat-chip {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.55rem 1rem;
    border-radius: var(--r-pill);
    border: 1px solid var(--line);
    background: var(--surface-alpha);
    font-size: 0.82rem;
    color: var(--ink-muted);
    font-family: var(--font-display);
    font-weight: 500;
  }
  .stat-chip strong { color: var(--cyan); font-weight: 700; }
  .stat-chip .mdi { font-size: 1.05rem; color: var(--cyan); }

  /* ==================== GRID 2 COLUMNAS ==================== */
  .suministros-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    align-items: start;
  }

  @media (max-width: 1199.98px) {
    .suministros-grid { grid-template-columns: 1fr; }
  }

  /* ==================== PANEL ==================== */
  .suministro-panel {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: 0 20px 40px -28px var(--shadow);
    transition: all 0.35s var(--ease);
  }

  .suministro-panel:hover {
    border-color: var(--line-strong);
    box-shadow: 0 28px 50px -28px var(--shadow), 0 0 0 1px var(--line);
  }

  .panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.15rem 1.35rem;
    border-bottom: 1px solid var(--line);
    background: linear-gradient(180deg,
      color-mix(in srgb, var(--cyan) 4%, transparent),
      transparent);
  }

  .panel-head-info {
    display: flex;
    align-items: center;
    gap: 0.85rem;
  }

  .panel-head-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--r-md);
    border: 1px solid var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 8%, transparent);
    color: var(--cyan);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
  }

  .panel-head-title {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--ink);
    font-size: 1.02rem;
    margin: 0;
    line-height: 1.2;
  }

  .panel-head-sub {
    font-size: 0.78rem;
    color: var(--ink-muted);
    margin: 0;
  }

  .btn-nuevo {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 1.05rem;
    border-radius: var(--r-pill);
    border: none;
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink);
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    box-shadow: 0 4px 14px -4px var(--glow);
    white-space: nowrap;
  }
  .btn-nuevo:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px -6px var(--glow), 0 0 0 1px var(--cyan);
  }
  .btn-nuevo .mdi { font-size: 1rem; }

  .panel-body { padding: 1.35rem; }

  /* ==================== DATATABLE — TOP BAR ALINEADO ==================== */
  .dataTables_wrapper {
    color: var(--ink);
    font-size: 0.85rem;
  }

  /* Barra superior: length + filter en una sola fila */
  .dataTables_wrapper .dt-top {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    width: 100%;
  }

  .dataTables_wrapper .dt-top > .dataTables_length,
  .dataTables_wrapper .dt-top > .dataTables_filter {
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
    width: auto !important;
    text-align: left !important;
    display: block !important;
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
    font-size: 0.82rem;
    color: var(--ink-muted);
    white-space: nowrap;
    font-weight: 500;
  }

  .dataTables_wrapper .dt-top .dataTables_length select {
    background: var(--bg-2) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-md) !important;
    padding: 0 1.75rem 0 0.65rem !important;
    font-size: 0.82rem;
    cursor: pointer;
    height: 36px;
    line-height: 1.2;
    margin: 0 !important;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238fa8ae' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.65rem center !important;
  }

  html[data-theme="light"] .dataTables_wrapper .dt-top .dataTables_length select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23476067' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") !important;
  }

  .dataTables_wrapper .dt-top .dataTables_filter input {
    background: var(--bg-2) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-md) !important;
    padding: 0.4rem 0.85rem !important;
    font-size: 0.85rem;
    margin: 0 0 0 0.5rem !important;
    height: 36px;
    min-width: 200px;
    transition: all 0.25s var(--ease);
  }

  .dataTables_wrapper .dt-top .dataTables_filter input:focus {
    border-color: var(--cyan) !important;
    box-shadow: 0 0 0 3px var(--glow-soft) !important;
    outline: none;
  }

  /* Barra inferior: info + pagination en una sola fila */
  .dataTables_wrapper .dt-bottom {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 1rem;
    margin-top: 1rem;
    flex-wrap: wrap;
    width: 100%;
  }

  .dataTables_wrapper .dt-bottom > .dataTables_info,
  .dataTables_wrapper .dt-bottom > .dataTables_paginate {
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
    width: auto !important;
    text-align: left !important;
  }

  .dataTables_wrapper .dt-bottom > .dataTables_paginate {
    margin-left: auto !important;
  }

  .dataTables_wrapper .dt-bottom .dataTables_info {
    padding-top: 0 !important;
    font-size: 0.78rem;
    color: var(--ink-faint);
    line-height: 36px;
  }

  .dataTables_wrapper .dt-bottom .dataTables_paginate .pagination {
    margin: 0 !important;
    justify-content: flex-end !important;
    display: flex;
    align-items: center;
    gap: 3px;
  }

  /* ==================== TABLA ==================== */
  .suministro-panel table.dataTable {
    width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0;
    margin: 0 !important;
  }

  .suministro-panel table.dataTable thead th {
    background: color-mix(in srgb, var(--bg-2) 60%, transparent) !important;
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
    position: relative;
  }

  .suministro-panel table.dataTable thead th::after,
  .suministro-panel table.dataTable thead th::before {
    opacity: 0.4;
  }
  .suministro-panel table.dataTable thead th.sorting_asc::after,
  .suministro-panel table.dataTable thead th.sorting_desc::after {
    color: var(--cyan) !important;
    opacity: 1;
  }

  .suministro-panel table.dataTable tbody td {
    padding: 0.75rem 0.75rem !important;
    border-top: 1px solid var(--line) !important;
    color: var(--ink);
    font-size: 0.85rem;
    vertical-align: middle;
  }

  .suministro-panel table.dataTable tbody tr {
    transition: background 0.2s ease;
  }
  .suministro-panel table.dataTable tbody tr:hover {
    background: var(--surface-alpha) !important;
  }
  .suministro-panel table.dataTable tbody tr.odd {
    background: color-mix(in srgb, var(--bg-2) 35%, transparent);
  }

  /* ==================== CELDA NOMBRE ==================== */
  .cell-name {
    display: block;
    max-width: 180px;
  }

  .cell-name-text {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    line-height: 1.3;
  }

  /* ==================== CELDA CONTACTO ==================== */
  .cell-contact {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
    line-height: 1.25;
  }

  .cell-contact .main-line {
    font-size: 0.85rem;
    color: var(--ink);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.35rem;
  }

  .cell-contact .sub-line {
    font-size: 0.72rem;
    color: var(--ink-faint);
    display: flex;
    align-items: center;
    gap: 0.35rem;
  }

  .cell-contact .main-line .mdi {
    font-size: 0.85rem;
    color: var(--cyan);
    opacity: 0.9;
  }

  .cell-contact .sub-line .mdi {
    font-size: 0.85rem;
    color: var(--ink-faint);
    opacity: 0.7;
  }

  /* ==================== CELDA ESPECIFICACIONES ==================== */
  .cell-specs {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
    line-height: 1.25;
  }

  .cell-specs .main-line {
    font-size: 0.85rem;
    color: var(--ink);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.35rem;
  }

  .cell-specs .sub-line {
    font-size: 0.72rem;
    color: var(--ink-faint);
    display: flex;
    align-items: center;
    gap: 0.35rem;
  }

  .cell-specs .main-line .mdi {
    font-size: 0.85rem;
    color: var(--cyan);
    opacity: 0.9;
  }

  .cell-specs .sub-line .mdi {
    font-size: 0.85rem;
    color: var(--ink-faint);
    opacity: 0.7;
  }
  /* =========================
   DETALLES DE PRODUCTOS
   ========================= */

  .product-details {
      display: flex;
      flex-direction: column;
      gap: 4px;
      min-width: 190px;
      line-height: 1.25;
  }

  .product-detail {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.76rem;
      color: var(--ink-muted);
  }

  .product-detail .mdi {
      font-size: 0.9rem;
      color: var(--cyan);
      flex-shrink: 0;
  }

  .product-detail strong {
      color: var(--ink);
      font-weight: 600;
  }

  .product-detail .detail-value {
      color: var(--ink-muted);
  }

  /* ==================== FILA INACTIVA ==================== */
  .suministro-panel table.dataTable tbody tr.row-inactive {
    background: color-mix(in srgb, var(--error) 6%, transparent) !important;
  }

  .suministro-panel table.dataTable tbody tr.row-inactive:hover {
    background: color-mix(in srgb, var(--error) 10%, transparent) !important;
  }

  .suministro-panel table.dataTable tbody tr.row-inactive > td:first-child {
    box-shadow: inset 3px 0 0 0 var(--error);
  }

  .suministro-panel table.dataTable tbody tr.row-inactive .cell-name-text {
    color: color-mix(in srgb, var(--ink) 75%, var(--error));
  }

  /* ==================== BADGES ==================== */
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
  .status-activo {
    background: color-mix(in srgb, var(--cyan) 14%, transparent);
    color: var(--cyan);
    border: 1px solid var(--line-strong);
  }
  .status-inactivo {
    background: color-mix(in srgb, var(--error) 12%, transparent);
    color: var(--error);
    border: 1px solid color-mix(in srgb, var(--error) 40%, transparent);
  }

  /* ==================== ACCIONES ==================== */
  .actions-cell {
    display: flex;
    gap: 0.35rem;
    justify-content: flex-end;
    align-items: center;
  }

  .action-btn {
    position: relative;
    overflow: hidden;
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
    transition: all 0.25s var(--ease);
    padding: 0;
    font-size: 1rem;
    opacity: 0.75;
  }

  .suministro-panel table.dataTable tbody tr:hover .action-btn { opacity: 1; }
  .suministro-panel table.dataTable tbody tr.row-inactive .action-btn { opacity: 1; }

  .action-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    border: 1px solid currentColor;
    opacity: 0;
    transform: scale(0.85);
    transition: opacity 0.25s var(--ease), transform 0.25s var(--ease);
    pointer-events: none;
  }

  .action-btn:hover::before { opacity: 1; transform: scale(1); }

  .action-btn::after {
    content: '';
    position: absolute;
    inset: 0;
    background: currentColor;
    opacity: 0;
    border-radius: inherit;
    transform: scale(0);
    transition: opacity 0.35s var(--ease), transform 0.35s var(--ease);
    pointer-events: none;
  }

  .action-btn:hover::after { opacity: 0.08; transform: scale(1.4); }

  .action-btn > * { position: relative; z-index: 1; }
  .action-btn:active { transform: scale(0.94); }
  .action-btn:hover { transform: translateY(-2px); }

  .action-btn.edit:hover {
    color: var(--cyan);
    border-color: var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    box-shadow: 0 0 14px -4px var(--glow);
  }
  .action-btn.toggle:hover {
    color: var(--warning);
    border-color: color-mix(in srgb, var(--warning) 50%, transparent);
    background: color-mix(in srgb, var(--warning) 10%, transparent);
    box-shadow: 0 0 14px -4px rgba(255, 181, 71, 0.4);
  }
  .action-btn.toggle.activate:hover {
    color: var(--cyan);
    border-color: var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
  }
  .action-btn.delete:hover {
    color: var(--error);
    border-color: color-mix(in srgb, var(--error) 50%, transparent);
    background: color-mix(in srgb, var(--error) 10%, transparent);
    box-shadow: 0 0 14px -4px rgba(255, 85, 112, 0.4);
  }

  /* ==================== PAGINACIÓN ==================== */
  .dataTables_wrapper .dataTables_paginate .page-item .page-link {
    background: var(--bg-1) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink-muted) !important;
    border-radius: var(--r-sm) !important;
    padding: 0.35rem 0.7rem;
    font-size: 0.82rem;
    transition: all 0.25s var(--ease);
    margin: 0;
  }
  .dataTables_wrapper .dataTables_paginate .page-item .page-link:hover {
    border-color: var(--line-strong) !important;
    color: var(--cyan) !important;
  }
  .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
    color: var(--cyan-ink) !important;
    border-color: transparent !important;
    box-shadow: 0 6px 16px -6px var(--glow);
    font-weight: 700;
  }
  .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
    opacity: 0.4;
    cursor: not-allowed;
  }

  table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before {
    background: var(--cyan) !important;
    border: 1px solid var(--cyan) !important;
    box-shadow: 0 0 8px var(--glow-soft);
  }

  /* ==================== TOASTR ==================== */
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
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2300fff0'%3E%3Cpath d='M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm-1.4 14.6l-4.2-4.2 1.4-1.4 2.8 2.8 6.2-6.2 1.4 1.4z'/%3E%3C/svg%3E");
    border: 1px solid var(--line-strong);
    border-left: 4px solid var(--cyan);
    color: var(--ink);
  }
  #toast-container > .toast-success .toast-message { color: var(--ink); }
  #toast-container > .toast-success .toast-title { color: var(--cyan); }

  #toast-container > .toast-error {
    background-color: var(--bg-1);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ff5570'%3E%3Cpath d='M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 15h-2v-2h2zm0-4h-2V7h2z'/%3E%3C/svg%3E");
    border: 1px solid rgba(255, 85, 112, 0.35);
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

  #toast-container > .toast-info {
    background-color: var(--bg-1);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2300fff0'%3E%3Cpath d='M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 15h-2v-6h2zm0-8h-2V7h2z'/%3E%3C/svg%3E");
    border: 1px solid var(--line-strong);
    border-left: 4px solid var(--cyan);
    color: var(--ink);
  }

  #toast-container > div:hover {
    box-shadow: 0 24px 50px -20px var(--shadow), 0 0 24px -6px var(--glow);
  }

  #toast-container .toast-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.88rem;
    letter-spacing: 0.01em;
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

  /* ==================== SWEETALERT2 ==================== */
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

  .swal2-html-container strong {
    color: var(--cyan) !important;
    font-weight: 600;
  }

  .swal2-icon { border-width: 3px !important; }
  .swal2-icon.swal2-warning { border-color: var(--warning) !important; color: var(--warning) !important; }
  .swal2-icon.swal2-success { border-color: var(--cyan) !important; color: var(--cyan) !important; }
  .swal2-icon.swal2-success .swal2-success-ring { border-color: var(--line-strong) !important; }
  .swal2-icon.swal2-success [class^='swal2-success-line'] { background-color: var(--cyan) !important; }
  .swal2-icon.swal2-error { border-color: var(--error) !important; color: var(--error) !important; }
  .swal2-icon.swal2-error [class^='swal2-x-mark-line'] { background-color: var(--error) !important; }
  .swal2-icon.swal2-question { border-color: var(--cyan) !important; color: var(--cyan) !important; }

  .swal2-actions { gap: 0.75rem !important; margin-top: 1.5rem !important; }

  .swal2-confirm,
  .swal2-cancel {
    font-family: var(--font-body) !important;
    font-weight: 700 !important;
    font-size: 0.88rem !important;
    padding: 0.7rem 1.5rem !important;
    border-radius: var(--r-pill) !important;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    border: none !important;
    outline: none !important;
    letter-spacing: 0.02em;
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
    background: linear-gradient(135deg, #ff5570, #e63e5a) !important;
    color: #fff !important;
    box-shadow: 0 6px 20px -6px rgba(255, 85, 112, 0.5) !important;
  }
  .swal2-confirm.btn-danger-swal:hover {
    box-shadow: 0 12px 28px -6px rgba(255, 85, 112, 0.6), 0 0 0 1px #ff5570 !important;
  }

  .swal2-confirm.btn-warning-swal {
    background: linear-gradient(135deg, #ffb547, #f39c12) !important;
    color: #1a1200 !important;
    box-shadow: 0 6px 20px -6px rgba(255, 181, 71, 0.5) !important;
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
    background: rgba(5, 8, 10, 0.65) !important;
    backdrop-filter: blur(4px);
  }
  html[data-theme="light"] .swal2-container.swal2-backdrop-show {
    background: rgba(10, 40, 45, 0.4) !important;
  }

  /* ==================== RESPONSIVE ==================== */
  @media (max-width: 767.98px) {
    .dataTables_wrapper .dt-top,
    .dataTables_wrapper .dt-bottom {
      flex-direction: column;
      align-items: stretch !important;
    }
    .dataTables_wrapper .dt-top > .dataTables_filter,
    .dataTables_wrapper .dt-bottom > .dataTables_paginate {
      margin-left: 0 !important;
      width: 100%;
    }
    .dataTables_wrapper .dt-top .dataTables_filter input {
      width: 100%;
      min-width: 0;
    }
    .dataTables_wrapper .dt-top .dataTables_length label,
    .dataTables_wrapper .dt-top .dataTables_filter label {
      width: 100%;
      justify-content: space-between;
    }
  }

  @media (max-width: 575.98px) {
    .suministros-header h3 { font-size: 1.1rem; }
    .panel-head { padding: 1rem; }
    .panel-body { padding: 1rem; }
    .cell-name { max-width: 120px; }
  }
</style>
@endpush

@section('content')
<div class="container-fluid">

  {{-- ==================== ENCABEZADO ==================== --}}
  <div class="suministros-header">
    <div>
      <h3>
        <span class="icon-wrap"><i class="mdi mdi-truck"></i></span>
        Suministros
      </h3>
      <p>Gestión completa de proveedores y productos</p>
    </div>

    <div class="header-stats">
      <div class="stat-chip">
        <i class="mdi mdi-truck"></i>
        Proveedores <strong>{{ $proveedores->count() }}</strong>
      </div>
      <div class="stat-chip">
        <i class="mdi mdi-cube"></i>
        Productos <strong>{{ $productos->count() }}</strong>
      </div>
    </div>
  </div>

  {{-- ==================== GRID 2 COLUMNAS ==================== --}}
  <div class="suministros-grid">

    {{-- ============ COLUMNA IZQUIERDA: PROVEEDORES ============ --}}
    <div class="suministro-panel">
      <div class="panel-head">
        <div class="panel-head-info">
          <span class="panel-head-icon"><i class="mdi mdi-truck"></i></span>
          <div>
            <h5 class="panel-head-title">Proveedores</h5>
            <p class="panel-head-sub">Empresas que te abastecen</p>
          </div>
        </div>

        <button type="button"
                class="btn-nuevo"
                data-bs-toggle="modal"
                data-bs-target="#modalCrearProveedor">
          <i class="mdi mdi-plus"></i> Nuevo
        </button>
      </div>

      <div class="panel-body">
        <table id="tablaProveedores" class="table dt-responsive nowrap" style="width:100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Proveedor</th>
              <th>NIT</th>
              <th>Contacto</th>
              <th>Estado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($proveedores as $proveedor)
              <tr class="{{ $proveedor->estado ? '' : 'row-inactive' }}">
                <td>{{ $proveedor->id }}</td>

                <td>
                  <span class="cell-name">
                    <span class="cell-name-text" title="{{ $proveedor->nombre }}">
                      {{ $proveedor->nombre }}
                    </span>
                  </span>
                </td>

                <td>{{ $proveedor->nit }}</td>

                <td>
                  <div class="cell-contact">
                    <span class="main-line">
                      <i class="mdi mdi-phone"></i>
                      {{ $proveedor->telefono }}
                    </span>
                    <span class="sub-line">
                      <i class="mdi mdi-map-marker"></i>
                      {{ $proveedor->ciudad }}
                    </span>
                  </div>
                </td>

                <td>
                  @if($proveedor->estado)
                    <span class="status-badge status-activo">Activo</span>
                  @else
                    <span class="status-badge status-inactivo">Inactivo</span>
                  @endif
                </td>

                <td>
                  <div class="actions-cell">
                    <button type="button"
                            class="action-btn edit"
                            title="Editar"
                            onclick='abrirModalEditarProveedor(@json($proveedor))'>
                      <i class="mdi mdi-pencil"></i>
                    </button>

                    <form id="form-estado-prov-{{ $proveedor->id }}"
                          action="{{ route('admin.suministros.proveedores.estado', $proveedor->id) }}"
                          method="POST" class="d-none">
                      @csrf @method('PATCH')
                    </form>

                    @if($proveedor->estado)
                      <button type="button"
                              class="action-btn toggle"
                              title="Inactivar"
                              onclick='confirmarEstado({
                                nombre: @json($proveedor->nombre),
                                url: "form-estado-prov-{{ $proveedor->id }}",
                                estadoActual: true
                              })'>
                        <i class="mdi mdi-account-off"></i>
                      </button>
                    @else
                      <button type="button"
                              class="action-btn toggle activate"
                              title="Activar"
                              onclick='confirmarEstado({
                                nombre: @json($proveedor->nombre),
                                url: "form-estado-prov-{{ $proveedor->id }}",
                                estadoActual: false
                              })'>
                        <i class="mdi mdi-account-check"></i>
                      </button>
                    @endif

                    <form id="form-delete-prov-{{ $proveedor->id }}"
                          action="{{ route('admin.suministros.proveedores.destroy', $proveedor->id) }}"
                          method="POST" class="d-none">
                      @csrf @method('DELETE')
                    </form>

                    <button type="button"
                            class="action-btn delete"
                            title="Eliminar"
                            onclick='confirmarEliminar({
                              nombre: @json($proveedor->nombre),
                              url: "form-delete-prov-{{ $proveedor->id }}",
                              tipo: "proveedor"
                            })'>
                      <i class="mdi mdi-delete"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- ============ COLUMNA DERECHA: PRODUCTOS ============ --}}
    <div class="suministro-panel">
      <div class="panel-head">
        <div class="panel-head-info">
          <span class="panel-head-icon"><i class="mdi mdi-cube"></i></span>
          <div>
            <h5 class="panel-head-title">Productos</h5>
            <p class="panel-head-sub">Catálogo de productos</p>
          </div>
        </div>

        <button type="button"
                class="btn-nuevo"
                data-bs-toggle="modal"
                data-bs-target="#modalCrearProducto">
          <i class="mdi mdi-plus"></i> Nuevo
        </button>
      </div>
      

      <div class="panel-body">

        <div class="row g-3 mb-3">

          {{-- Filtro por categoría --}}
          <div class="col-md-4">
              <label for="filtroCategoria" class="form-label fw-semibold">
                  <i class="mdi mdi-shape-outline me-1"></i>
                  Categoría
              </label>

              <select id="filtroCategoria" class="form-select">
                  <option value="">Todas</option>

                  @foreach($categoriasProductos as $categoria)
                      <option value="{{ $categoria->nombre }}">
                          {{ $categoria->nombre }}
                      </option>
                  @endforeach
              </select>
          </div>

          {{-- Filtro por tipo --}}
          <div class="col-md-4">
              <label for="filtroTipo" class="form-label fw-semibold">
                  <i class="mdi mdi-format-list-bulleted-type me-1"></i>
                  Tipo
              </label>

              <select id="filtroTipo" class="form-select">
                  <option value="">Todos</option>

                  @foreach($tiposProductos as $tipo)
                      <option
                          value="{{ $tipo->nombre }}"
                          data-categoria="{{ $tipo->categoria->nombre }}"
                      >
                          {{ $tipo->nombre }}
                      </option>
                  @endforeach
              </select>
        </div>

      </div>
        <table id="tablaProductos" class="table dt-responsive nowrap" style="width:100%">
          <thead>
              <tr>
                  <th>#</th>
                  <th>Producto</th>
                  <th>Categoría</th>
                  <th>Tipo</th>
                  <th>Detalles</th>
                  <th class="text-end">Acciones</th>
              </tr>
          </thead>

          <tbody>
              @foreach($productos as $producto)

                  @php
                      $tipo = $producto->tipoProducto?->nombre ?? '';
                  @endphp

                  <tr>
                      {{-- ID --}}
                      <td>
                          {{ $producto->id }}
                      </td>

                      {{-- PRODUCTO --}}
                      <td>
                          <div class="cell-product">
                              <span class="main-line">
                                  {{ $producto->nombre }}
                              </span>
                          </div>
                      </td>

                      {{-- CATEGORÍA --}}
                      <td>
                          {{ $producto->tipoProducto?->categoria?->nombre ?? '—' }}
                      </td>

                      {{-- TIPO --}}
                      <td>
                          {{ $tipo ?: '—' }}
                      </td>

                      {{-- DETALLES --}}
                      <td>
                          <div class="product-details">

                              {{-- CERÁMICA / PORCELANATO --}}
                              @if(in_array($tipo, ['Cerámica', 'Porcelanato']))

                                  @if($producto->marca)
                                      <div class="product-detail">
                                          <i class="mdi mdi-trademark"></i>
                                          <strong>Marca:</strong>
                                          <span class="detail-value">
                                              {{ $producto->marca }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->medida)
                                      <div class="product-detail">
                                          <i class="mdi mdi-ruler"></i>
                                          <strong>Medida:</strong>
                                          <span class="detail-value">
                                              {{ $producto->medida }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->piezas_por_caja)
                                      <div class="product-detail">
                                          <i class="mdi mdi-package-variant"></i>
                                          <span class="detail-value">
                                              {{ $producto->piezas_por_caja }} piezas/caja
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->m2_por_caja)
                                      <div class="product-detail">
                                          <i class="mdi mdi-ruler-square"></i>
                                          <span class="detail-value">
                                              {{ number_format((float) $producto->m2_por_caja, 2) }} m²/caja
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->color)
                                      <div class="product-detail">
                                          <i class="mdi mdi-palette"></i>
                                          <strong>Color:</strong>
                                          <span class="detail-value">
                                              {{ $producto->color }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->acabado)
                                      <div class="product-detail">
                                          <i class="mdi mdi-texture"></i>
                                          <strong>Acabado:</strong>
                                          <span class="detail-value">
                                              {{ $producto->acabado }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->presentacion)
                                      <div class="product-detail">
                                          <i class="mdi mdi-package-variant-closed"></i>
                                          <strong>Presentación:</strong>
                                          <span class="detail-value">
                                              {{ $producto->presentacion }}
                                          </span>
                                      </div>
                                  @endif


                              {{-- CEMENTO COLA --}}
                              @elseif($tipo === 'Cemento Cola')

                                  @if($producto->color)
                                      <div class="product-detail">
                                          <i class="mdi mdi-palette"></i>
                                          <strong>Color:</strong>
                                          <span class="detail-value">
                                              {{ $producto->color }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->peso)
                                      <div class="product-detail">
                                          <i class="mdi mdi-weight-kilogram"></i>
                                          <strong>Peso:</strong>
                                          <span class="detail-value">
                                              {{ number_format((float) $producto->peso, 2) }} kg
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->presentacion)
                                      <div class="product-detail">
                                          <i class="mdi mdi-package-variant-closed"></i>
                                          <strong>Presentación:</strong>
                                          <span class="detail-value">
                                              {{ $producto->presentacion }}
                                          </span>
                                      </div>
                                  @endif


                              {{-- LISTELO / RANDA / PASTINAS --}}
                              @elseif(in_array($tipo, ['Listelo', 'Randa decorativa', 'Pastinas']))

                                  @if($producto->modelo)
                                      <div class="product-detail">
                                          <i class="mdi mdi-shape-outline"></i>
                                          <strong>Modelo:</strong>
                                          <span class="detail-value">
                                              {{ $producto->modelo }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->medida)
                                      <div class="product-detail">
                                          <i class="mdi mdi-ruler"></i>
                                          <strong>Medida:</strong>
                                          <span class="detail-value">
                                              {{ $producto->medida }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->color)
                                      <div class="product-detail">
                                          <i class="mdi mdi-palette"></i>
                                          <strong>Color:</strong>
                                          <span class="detail-value">
                                              {{ $producto->color }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->piezas_por_caja)
                                      <div class="product-detail">
                                          <i class="mdi mdi-package-variant"></i>
                                          <span class="detail-value">
                                              {{ $producto->piezas_por_caja }} piezas/caja
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->presentacion)
                                      <div class="product-detail">
                                          <i class="mdi mdi-package-variant-closed"></i>
                                          <strong>Presentación:</strong>
                                          <span class="detail-value">
                                              {{ $producto->presentacion }}
                                          </span>
                                      </div>
                                  @endif


                              {{-- ESQUINERO DE ALUMINIO / GOMA --}}
                              @elseif(in_array($tipo, ['Esquinero de aluminio', 'Esquinero de goma']))

                                  @if($producto->color)
                                      <div class="product-detail">
                                          <i class="mdi mdi-palette"></i>
                                          <strong>Color:</strong>
                                          <span class="detail-value">
                                              {{ $producto->color }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->medida)
                                      <div class="product-detail">
                                          <i class="mdi mdi-ruler"></i>
                                          <strong>Medida:</strong>
                                          <span class="detail-value">
                                              {{ $producto->medida }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->piezas_por_caja)
                                      <div class="product-detail">
                                          <i class="mdi mdi-package-variant"></i>
                                          <span class="detail-value">
                                              {{ $producto->piezas_por_caja }} piezas/caja
                                          </span>
                                      </div>
                                  @endif


                              {{-- OTROS / SIN TIPO --}}
                              @else

                                  @if($producto->marca)
                                      <div class="product-detail">
                                          <i class="mdi mdi-trademark"></i>
                                          <strong>Marca:</strong>
                                          <span class="detail-value">
                                              {{ $producto->marca }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->modelo)
                                      <div class="product-detail">
                                          <i class="mdi mdi-shape-outline"></i>
                                          <strong>Modelo:</strong>
                                          <span class="detail-value">
                                              {{ $producto->modelo }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->medida)
                                      <div class="product-detail">
                                          <i class="mdi mdi-ruler"></i>
                                          <strong>Medida:</strong>
                                          <span class="detail-value">
                                              {{ $producto->medida }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->color)
                                      <div class="product-detail">
                                          <i class="mdi mdi-palette"></i>
                                          <strong>Color:</strong>
                                          <span class="detail-value">
                                              {{ $producto->color }}
                                          </span>
                                      </div>
                                  @endif

                                  @if($producto->presentacion)
                                      <div class="product-detail">
                                          <i class="mdi mdi-package-variant-closed"></i>
                                          <strong>Presentación:</strong>
                                          <span class="detail-value">
                                              {{ $producto->presentacion }}
                                          </span>
                                      </div>
                                  @endif

                              @endif

                              {{-- SI NO TIENE INFORMACIÓN --}}
                              @if(
                                  !$producto->marca &&
                                  !$producto->modelo &&
                                  !$producto->medida &&
                                  !$producto->piezas_por_caja &&
                                  !$producto->m2_por_caja &&
                                  !$producto->peso &&
                                  !$producto->color &&
                                  !$producto->acabado &&
                                  !$producto->presentacion
                              )
                                  <span class="detail-value">—</span>
                              @endif

                          </div>
                      </td>

                      {{-- ACCIONES --}}
                      <td class="text-end">
                          <div class="actions-cell">

                              <button
                                  type="button"
                                  class="action-btn edit"
                                  title="Editar"
                                  onclick='abrirModalEditarProducto(@json($producto))'
                              >
                                  <i class="mdi mdi-pencil"></i>
                              </button>

                              <form
                                  id="form-delete-prod-{{ $producto->id }}"
                                  action="{{ route('admin.suministros.productos.destroy', $producto) }}"
                                  method="POST"
                                  class="d-none"
                              >
                                  @csrf
                                  @method('DELETE')
                              </form>

                              <button
                                  type="button"
                                  class="action-btn delete"
                                  title="Eliminar"
                                  onclick='confirmarEliminar({
                                      nombre: @json($producto->nombre),
                                      url: "form-delete-prod-{{ $producto->id }}",
                                      tipo: "producto"
                                  })'
                              >
                                  <i class="mdi mdi-delete"></i>
                              </button>

                          </div>
                      </td>
                  </tr>

              @endforeach
          </tbody>
      </table>
      </div>
    </div>

  </div>
</div>

{{-- ==================== MODALES ==================== --}}
@include('admin.suministros.modals.proveedores.create')
@include('admin.suministros.modals.proveedores.edit')
@include('admin.suministros.modals.productos.create')
@include('admin.suministros.modals.productos.edit')

@endsection

@push('scripts')
<script>
  /* ==================== TOASTR ==================== */
  toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 4500,
    extendedTimeOut: 1500,
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut',
    newestOnTop: true,
    preventDuplicates: true,
  };

  /* ==================== SWEETALERT2 CONFIG BASE ==================== */
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
  function confirmarEliminar({ nombre, url, tipo = 'registro' }) {
    Swal.fire({
      ...swalConfig,
      title: '¿Eliminar ' + tipo + '?',
      html: `
        <p style="margin:0 0 0.5rem 0;">Estás a punto de eliminar:</p>
        <p style="font-family: 'Space Grotesk', sans-serif; font-weight:600; font-size:1rem; color: var(--ink); margin:0 0 0.75rem 0;">
          "${nombre}"
        </p>
        <p style="font-size:0.82rem; color: var(--error); margin:0;">
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
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById(url);
        if (form) form.submit();
      }
    });
  }

  /* ==================== CONFIRMAR CAMBIO DE ESTADO ==================== */
  function confirmarEstado({ nombre, url, estadoActual }) {
    const activar = !estadoActual;

    Swal.fire({
      ...swalConfig,
      title: activar ? '¿Activar proveedor?' : '¿Inactivar proveedor?',
      html: `
        <p style="margin:0 0 0.5rem 0;">${activar ? 'Vas a activar' : 'Vas a inactivar'}:</p>
        <p style="font-family: 'Space Grotesk', sans-serif; font-weight:600; font-size:1rem; color: var(--ink); margin:0 0 0.75rem 0;">
          "${nombre}"
        </p>
        <p style="font-size:0.82rem; color: var(--ink-muted); margin:0;">
          ${activar
            ? 'El proveedor volverá a estar disponible para asignaciones.'
            : 'El proveedor dejará de estar disponible pero no se eliminará.'}
        </p>
      `,
      icon: activar ? 'question' : 'warning',
      showCancelButton: true,
      confirmButtonText: activar
        ? '<i class="mdi mdi-account-check"></i> Sí, activar'
        : '<i class="mdi mdi-account-off"></i> Sí, inactivar',
      cancelButtonText: '<i class="mdi mdi-close"></i> Cancelar',
      customClass: {
        confirmButton: activar
          ? 'swal2-confirm'
          : 'swal2-confirm btn-warning-swal',
        cancelButton: 'swal2-cancel',
      },
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById(url);
        if (form) form.submit();
      }
    });
  }

  /* ==================== DATATABLES EN ESPAÑOL ==================== */
  const dtEspanol = {
    decimal:        ',',
    emptyTable:     'No hay datos disponibles',
    info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
    infoEmpty:      'Mostrando 0 a 0 de 0 registros',
    infoFiltered:   '(filtrado de _MAX_ registros totales)',
    lengthMenu:     'Mostrar _MENU_ registros',
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

  /* ==================== INICIALIZAR DATATABLES ==================== */
  $(document).ready(function () {

    $('#tablaProveedores').DataTable({
      language: dtEspanol,
      pageLength: 10,
      lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
      order: [[0, 'desc']],
      columnDefs: [
        { orderable: false, targets: [5] },
        { responsivePriority: 1, targets: [1, 5] },
        { responsivePriority: 2, targets: [3] },
        { responsivePriority: 3, targets: [4] },
      ],
      responsive: { details: { type: 'inline', target: 'tr' } },
      dom: '<"dt-top"lf>t<"dt-bottom"ip>',
    });

    const tablaProductos = $('#tablaProductos').DataTable({
        language: dtEspanol,
        pageLength: 10,

        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'Todos']
        ],

        order: [[0, 'desc']],

        columnDefs: [
            // Columna Acciones
            {
                orderable: false,
                targets: [5]
            },

            // Prioridades para responsive
            {
                responsivePriority: 1,
                targets: [1, 5]
            },
            {
                responsivePriority: 2,
                targets: [4]
            },
            {
                responsivePriority: 3,
                targets: [2, 3]
            }
        ],

        responsive: {
            details: {
                type: 'inline',
                target: 'tr'
            }
        },

        dom: '<"dt-top"lf>t<"dt-bottom"ip>',
    });

  const filtroCategoria = document.getElementById('filtroCategoria');
const filtroTipo = document.getElementById('filtroTipo');

if (filtroCategoria && filtroTipo) {

    const opcionesTipo = Array.from(
        filtroTipo.querySelectorAll('option')
    );

    filtroCategoria.addEventListener('change', function () {

        const categoriaSeleccionada = this.value;

        filtroTipo.innerHTML = '';

        const opcionTodos = document.createElement('option');
        opcionTodos.value = '';
        opcionTodos.textContent = 'Todos';

        filtroTipo.appendChild(opcionTodos);

        opcionesTipo.forEach(function (opcion) {

            if (!opcion.value) {
                return;
            }

            const categoria = opcion.dataset.categoria;

            if (
                !categoriaSeleccionada ||
                categoria === categoriaSeleccionada
            ) {
                const nuevaOpcion = opcion.cloneNode(true);
                filtroTipo.appendChild(nuevaOpcion);
            }
        });

        filtroTipo.value = '';

        aplicarFiltrosProductos();
    });

    filtroTipo.addEventListener('change', function () {
        aplicarFiltrosProductos();
    });

    function aplicarFiltrosProductos() {

        const categoria = filtroCategoria.value;
        const tipo = filtroTipo.value;

        tablaProductos
            .column(2)
            .search(categoria, false, false);

        tablaProductos
            .column(3)
            .search(tipo, false, false);

        tablaProductos.draw();
    }
}
  });

  /* ==================== NOTIFICACIONES FLASH ==================== */
  @if(session('success'))
    toastr.success('{{ session('success') }}', '¡Listo!');
  @endif

  @if(session('error'))
    toastr.error('{{ session('error') }}', 'Error');
  @endif

  @if($errors->any() && !session('open_modal'))
    toastr.error('Revisa los campos marcados en rojo.', 'Errores de validación');
  @endif

  /* ==================== REABRIR MODAL CON ERRORES ==================== */
  document.addEventListener('DOMContentLoaded', function () {
    const openModal = "{{ session('open_modal') }}";
    const editId    = "{{ session('edit_id') }}";

    if (!openModal) return;

    if (openModal === 'crear-proveedor') {
      new bootstrap.Modal(document.getElementById('modalCrearProveedor')).show();
    } else if (openModal === 'crear-producto') {
      new bootstrap.Modal(document.getElementById('modalCrearProducto')).show();
    } else if (openModal === 'editar-proveedor' && editId) {
      fetch(`/admin/suministros/proveedores/${editId}/data`)
        .then(r => r.json())
        .then(data => abrirModalEditarProveedor(data))
        .catch(() => {});
    } else if (openModal === 'editar-producto' && editId) {
      fetch(`/admin/suministros/productos/${editId}/data`)
        .then(r => r.json())
        .then(data => abrirModalEditarProducto(data))
        .catch(() => {});
    }
  });

  /* ==================== ABRIR MODAL EDITAR PROVEEDOR ==================== */
  function abrirModalEditarProveedor(proveedor) {
    const form = document.getElementById('formEditarProveedor');
    form.action = `/admin/suministros/proveedores/${proveedor.id}`;

    form.querySelector('[name="nombre"]').value    = proveedor.nombre || '';
    form.querySelector('[name="nit"]').value       = proveedor.nit || '';
    form.querySelector('[name="telefono"]').value  = proveedor.telefono || '';
    form.querySelector('[name="ciudad"]').value    = proveedor.ciudad || '';
    form.querySelector('[name="direccion"]').value = proveedor.direccion || '';

    new bootstrap.Modal(document.getElementById('modalEditarProveedor')).show();
  }

  /* ==================== ABRIR MODAL EDITAR PRODUCTO ==================== */
  function abrirModalEditarProducto(producto) {

    const form = document.getElementById('formEditarProducto');

    const categoriaSelect = document.getElementById(
        'editar_categoria_producto'
    );

    const tipoSelect = document.getElementById(
        'editar_tipo_producto'
    );

    /*
     * ==========================================
     * CONFIGURACIÓN INICIAL
     * ==========================================
     */

    form.action = `/admin/suministros/productos/${producto.id}`;


    /*
     * ==========================================
     * LIMPIAR TODOS LOS CAMPOS
     * ==========================================
     */

    form.querySelectorAll('input').forEach(function(input) {

        if (
            input.type !== 'hidden' &&
            input.name !== '_token'
        ) {
            input.value = '';
        }

    });


    /*
     * ==========================================
     * CARGAR NOMBRE
     * ==========================================
     */

    const nombre = form.querySelector('[name="nombre"]');

    if (nombre) {
        nombre.value = producto.nombre || '';
    }


    /*
     * ==========================================
     * CARGAR CATEGORÍA
     * ==========================================
     */

    if (categoriaSelect) {
        categoriaSelect.value =
            producto.tipo_producto?.categoria_id ||
            producto.tipoProducto?.categoria_id ||
            '';
    }


    /*
     * ==========================================
     * CARGAR TIPO
     * ==========================================
     */

    if (tipoSelect) {

        tipoSelect.value =
            producto.tipo_producto_id ||
            producto.tipoProducto?.id ||
            '';

        actualizarTiposEditar();

    }


    /*
     * ==========================================
     * CARGAR CAMPOS
     * ==========================================
     */

    form.querySelectorAll('[name]').forEach(function(input) {

        const nombreCampo = input.name;

        if (
            nombreCampo === 'nombre' ||
            nombreCampo === '_token' ||
            nombreCampo === '_method' ||
            nombreCampo === 'tipo_producto_id'
        ) {
            return;
        }

        if (
            producto[nombreCampo] !== undefined &&
            producto[nombreCampo] !== null
        ) {
            input.value = producto[nombreCampo];
        }

    });


    /*
     * ==========================================
     * MOSTRAR MODAL
     * ==========================================
     */

    const modalElement =
        document.getElementById('modalEditarProducto');

    const modal =
        new bootstrap.Modal(modalElement);

    modal.show();
}
function actualizarTiposEditar() {

    const categoriaSelect =
        document.getElementById('editar_categoria_producto');

    const tipoSelect =
        document.getElementById('editar_tipo_producto');

    if (!categoriaSelect || !tipoSelect) {
        return;
    }

    const categoriaSeleccionada =
        categoriaSelect.value;

    Array.from(tipoSelect.options).forEach(function(option) {

        if (!option.value) {
            option.hidden = false;
            return;
        }

        option.hidden =
            categoriaSeleccionada &&
            option.dataset.categoria !== categoriaSeleccionada;

    });

    if (
        tipoSelect.selectedOptions.length &&
        tipoSelect.selectedOptions[0].hidden
    ) {
        tipoSelect.value = '';
    }

    actualizarCamposEditar();
}
function actualizarCamposEditar() {
    const tipoSelect =
        document.getElementById('editar_tipo_producto');

    const colorDecorativo =
        document.getElementById('editar_color_decorativo');

    if (!tipoSelect) {
        return;
    }

    const tipo =
        tipoSelect.options[tipoSelect.selectedIndex]?.text || '';

    // Por defecto el color se muestra
    if (colorDecorativo) {
        colorDecorativo.style.display = '';
    }


    /*
     * ==========================================
     * OCULTAR TODOS LOS BLOQUES
     * ==========================================
     */

    document
        .querySelectorAll('.editar-campos-tipo')
        .forEach(function(bloque) {

            bloque.style.display = 'none';

            bloque
                .querySelectorAll('[data-required="true"]')
                .forEach(function(input) {

                    input.required = false;

                });

        });


    /*
     * ==========================================
     * CERÁMICA / PORCELANATO
     * ==========================================
     */

    if (
        tipo === 'Cerámica' ||
        tipo === 'Porcelanato'
    ) {

        mostrarCamposEditar(
            'editar_campos_ceramica'
        );

        return;
    }


    /*
     * ==========================================
     * CEMENTO COLA
     * ==========================================
     */

    if (tipo === 'Cemento Cola') {

        mostrarCamposEditar(
            'editar_campos_cemento_cola'
        );

        return;
    }


    /*
     * ==========================================
     * LISTELO / RANDA / PASTINAS
     * ==========================================
     */

    if (
        tipo === 'Listelo' ||
        tipo === 'Randa decorativa' ||
        tipo === 'Pastinas'
    ) {
        mostrarCamposEditar('editar_campos_decorativo');

        // Pastinas no utiliza color
        if (
            tipo === 'Pastinas' &&
            colorDecorativo
        ) {
            colorDecorativo.style.display = 'none';

            const colorInput =
                colorDecorativo.querySelector('[name="color"]');

            if (colorInput) {
                colorInput.value = '';
            }
        }

        return;
    }


    /*
     * ==========================================
     * ESQUINEROS
     * ==========================================
     */

    if (
        tipo === 'Esquinero de aluminio' ||
        tipo === 'Esquinero de goma'
    ) {

        mostrarCamposEditar(
            'editar_campos_esquinero'
        );

        return;
    }
}
function mostrarCamposEditar(id) {

    const bloque =
        document.getElementById(id);

    if (!bloque) {
        return;
    }

    bloque.style.display = 'block';

    bloque
        .querySelectorAll('[data-required="true"]')
        .forEach(function(input) {

            input.required = true;

        });
}
document.addEventListener('DOMContentLoaded', function() {

    const categoriaSelect =
        document.getElementById(
            'editar_categoria_producto'
        );

    const tipoSelect =
        document.getElementById(
            'editar_tipo_producto'
        );


    if (categoriaSelect) {

        categoriaSelect.addEventListener(
            'change',
            function() {

                actualizarTiposEditar();

            }
        );

    }


    if (tipoSelect) {

        tipoSelect.addEventListener(
            'change',
            function() {

                actualizarCamposEditar();

            }
        );

    }

});
</script>
@endpush