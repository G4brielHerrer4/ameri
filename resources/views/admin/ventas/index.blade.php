@extends('layouts.plantilla_maestra')

@section('title', 'Historial de Ventas')

@push('styles')
<style>
  /* =====================================================
     HEADER
     ===================================================== */
  .ventas-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
  }
  .ventas-header h3 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin: 0;
    font-size: 1.35rem;
  }
  .ventas-header h3 .icon-wrap {
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
  .ventas-header p {
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

  /* =====================================================
     FILTROS CHIP
     ===================================================== */
  .filter-bar {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1.25rem;
  }
  .filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    border-radius: var(--r-pill);
    border: 1px solid var(--line);
    background: var(--surface-alpha);
    color: var(--ink-muted);
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.22s var(--ease);
    text-decoration: none;
    white-space: nowrap;
  }
  .filter-chip:hover {
    border-color: var(--line-strong);
    color: var(--cyan);
  }
  .filter-chip.active {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink);
    border-color: transparent;
    box-shadow: 0 4px 14px -4px var(--glow);
  }
  .filter-chip .mdi { font-size: 1rem; }
  .filter-chip .count {
    background: rgba(0, 0, 0, 0.15);
    padding: 0.1rem 0.45rem;
    border-radius: var(--r-pill);
    font-size: 0.7rem;
    font-weight: 700;
    min-width: 22px;
    text-align: center;
  }
  .filter-chip.active .count { background: rgba(0, 0, 0, 0.22); }

  /* =====================================================
     PANEL + TABLA
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
     DATATABLES
     ===================================================== */
  .dataTables_wrapper {
    color: var(--ink) !important;
    font-size: 0.85rem;
    padding: 0;
    width: 100%;
  }
  .dataTables_wrapper * { box-sizing: border-box; }

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
    padding: 0.8rem 0.75rem !important;
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
     BADGES DE ESTADO
     ===================================================== */
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.7rem;
    border-radius: var(--r-pill);
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    white-space: nowrap;
  }
  .status-badge::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
  }
  .status-pendiente {
    background: color-mix(in srgb, var(--warning) 14%, transparent);
    color: var(--warning);
    border: 1px solid color-mix(in srgb, var(--warning) 40%, transparent);
  }
  .status-vendida {
    background: color-mix(in srgb, var(--cyan) 14%, transparent);
    color: var(--cyan);
    border: 1px solid var(--line-strong);
  }
  .status-cancelada {
    background: color-mix(in srgb, var(--error) 12%, transparent);
    color: var(--error);
    border: 1px solid color-mix(in srgb, var(--error) 40%, transparent);
  }

  .badge-tu {
    display: inline-flex;
    align-items: center;
    background: var(--glow-soft);
    color: var(--cyan);
    font-size: 0.62rem;
    font-weight: 700;
    padding: 0.15rem 0.45rem;
    border-radius: var(--r-pill);
    margin-left: 0.35rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
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
  .action-btn.confirm:hover {
    color: var(--cyan);
    border-color: var(--line-strong);
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
     SWEETALERT2 — igual que en stocks
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
  .swal2-html-container p {
    color: var(--ink-muted) !important;
    margin: 0 0 0.5rem 0;
  }
  .swal2-html-container strong {
    color: var(--cyan) !important;
    font-weight: 600;
  }
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
    background: rgba(3, 6, 12, 0.7) !important;
    backdrop-filter: blur(4px);
  }
  html[data-theme="light"] .swal2-container.swal2-backdrop-show {
    background: rgba(10, 30, 60, 0.4) !important;
  }

  /* =====================================================
     RESPONSIVE
     ===================================================== */
  @media (max-width: 991.98px) {
    .panel-body { padding: 1rem; }
  }

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
    .ventas-header h3 { font-size: 1.15rem; }
    .ventas-header .btn-nuevo { width: 100%; justify-content: center; }
    .filter-bar { gap: 0.35rem; }
    .filter-chip { font-size: 0.78rem; padding: 0.45rem 0.85rem; }
  }

  @media (max-width: 575.98px) {
    .ventas-header h3 .icon-wrap {
      width: 38px;
      height: 38px;
      font-size: 1.15rem;
    }
    .ventas-header h3 { font-size: 1.05rem; }
    .panel-body { padding: 0.9rem 1rem; }
    .dataTables_wrapper .dataTables_paginate .page-item .page-link {
      padding: 0.3rem 0.55rem;
      min-width: 30px;
      font-size: 0.78rem;
    }
  }
</style>
@endpush

@section('content')
<div class="container-fluid">

  {{-- ==================== HEADER ==================== --}}
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
    $countTodas      = $ventas->count();
    $countPendientes = $ventas->where('estado', 'pendiente')->count();
    $countVendidas   = $ventas->where('estado', 'vendida')->count();
    $countCanceladas = $ventas->where('estado', 'cancelada')->count();
    $countMias       = $ventas->where('user_id', auth()->id())->count();
  @endphp

  {{-- ==================== FILTROS CHIP ==================== --}}
  <div class="filter-bar">
    <button type="button" class="filter-chip active" data-filter="all">
      <i class="mdi mdi-view-list"></i>
      Todas
      <span class="count">{{ $countTodas }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="mias">
      <i class="mdi mdi-account"></i>
      Mis ventas
      <span class="count">{{ $countMias }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="pendiente">
      <i class="mdi mdi-clock-outline"></i>
      Pendientes
      <span class="count">{{ $countPendientes }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="vendida">
      <i class="mdi mdi-check-circle-outline"></i>
      Vendidas
      <span class="count">{{ $countVendidas }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="cancelada">
      <i class="mdi mdi-close-circle-outline"></i>
      Canceladas
      <span class="count">{{ $countCanceladas }}</span>
    </button>
  </div>

  {{-- ==================== TABLA ==================== --}}
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
                  <span class="badge-tu">Tú</span>
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

                  {{-- VER --}}
                  <a href="{{ route('admin.ventas.show', $venta) }}"
                     class="action-btn view"
                     title="Ver detalle"
                     aria-label="Ver detalle">
                    <i class="mdi mdi-eye"></i>
                  </a>

                  {{-- EDITAR --}}
                  @if($venta->estado === 'pendiente' && $venta->user_id === auth()->id())
                    <a href="{{ route('admin.ventas.edit', $venta) }}"
                       class="action-btn edit"
                       title="Editar"
                       aria-label="Editar">
                      <i class="mdi mdi-pencil"></i>
                    </a>
                  @endif

                  {{-- CONFIRMAR --}}
                  @if($venta->estado === 'pendiente' && $venta->user_id === auth()->id())
                    <form id="form-confirmar-{{ $venta->id }}"
                          action="{{ route('admin.ventas.confirmar', $venta) }}"
                          method="POST"
                          class="d-none">
                      @csrf @method('PATCH')
                    </form>
                    <button type="button"
                            class="action-btn confirm"
                            title="Confirmar venta"
                            aria-label="Confirmar venta"
                            onclick='confirmarVenta({
                              numero: @json($venta->numero_venta),
                              cliente: @json($venta->cliente_nombre),
                              total: @json(number_format($venta->total, 2)),
                              url: "form-confirmar-{{ $venta->id }}"
                            })'>
                      <i class="mdi mdi-check-circle"></i>
                    </button>
                  @endif

                  {{-- CANCELAR --}}
                  @if($venta->estado !== 'cancelada' && $venta->user_id === auth()->id())
                    <form id="form-cancelar-{{ $venta->id }}"
                          action="{{ route('admin.ventas.cancelar', $venta) }}"
                          method="POST"
                          class="d-none">
                      @csrf @method('PATCH')
                    </form>
                    <button type="button"
                            class="action-btn cancel"
                            title="Cancelar venta"
                            aria-label="Cancelar venta"
                            onclick='cancelarVenta({
                              numero: @json($venta->numero_venta),
                              estado: @json($venta->estado),
                              url: "form-cancelar-{{ $venta->id }}"
                            })'>
                      <i class="mdi mdi-close-circle"></i>
                    </button>
                  @endif

                  {{-- ELIMINAR --}}
                  @if(
                      $venta->estado !== 'vendida' &&
                      (
                          (int) $venta->user_id === (int) auth()->id() ||
                          $venta->user?->role?->slug === 'vendedor'
                      )
                  )
                    <form id="form-delete-{{ $venta->id }}"
                          action="{{ route('admin.ventas.destroy', $venta) }}"
                          method="POST"
                          class="d-none">
                      @csrf @method('DELETE')
                    </form>
                    <button type="button"
                            class="action-btn delete"
                            title="Eliminar"
                            aria-label="Eliminar"
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
document.addEventListener('DOMContentLoaded', function () {

  /* ==================== TOASTR ==================== */
  toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 4500,
    extendedTimeOut: 1500,
    newestOnTop: true,
    preventDuplicates: true,
  };

  @if(session('success'))
    toastr.success(@json(session('success')), '¡Listo!');
  @endif
  @if(session('error'))
    toastr.error(@json(session('error')), 'Error');
  @endif


  /* ==================== SWEETALERT CONFIG BASE ==================== */
  const swalConfig = {
    buttonsStyling: false,
    reverseButtons: true,
    focusCancel: true,
    customClass: {
      confirmButton: 'swal2-confirm',
      cancelButton: 'swal2-cancel',
    },
  };


  /* ==================== CONFIRMAR VENTA ==================== */
  window.confirmarVenta = function ({ numero, cliente, total, url }) {
    Swal.fire({
      ...swalConfig,
      title: '¿Confirmar venta?',
      html: `
        <p>Vas a confirmar la venta:</p>
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:1rem;color:var(--ink);margin:0 0 0.5rem 0;">
          "${numero}"
        </p>
        <p style="font-size:0.85rem;">
          Cliente: <strong>${cliente}</strong><br>
          Total: <strong>Bs. ${total}</strong>
        </p>
        <p style="font-size:0.82rem;">
          Se <strong>descontará el stock</strong> de los administradores y se notificará a quienes prestaron.
        </p>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-check-circle"></i> Sí, confirmar',
      cancelButtonText: '<i class="mdi mdi-close"></i> Cancelar',
    }).then(function (r) {
      if (r.isConfirmed) {
        const form = document.getElementById(url);
        if (form) form.submit();
      }
    });
  };


  /* ==================== CANCELAR VENTA ==================== */
  window.cancelarVenta = function ({ numero, estado, url }) {
    const esVendida = estado === 'vendida';

    Swal.fire({
      ...swalConfig,
      title: esVendida ? '¿Cancelar venta vendida?' : '¿Cancelar venta?',
      html: `
        <p>Vas a cancelar:</p>
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:1rem;color:var(--ink);margin:0 0 0.5rem 0;">
          "${numero}"
        </p>
        ${esVendida
          ? '<p style="font-size:0.85rem;font-weight:600;">Se devolverá el stock a cada administrador de origen.</p>'
          : '<p style="font-size:0.85rem;">La venta se marcará como cancelada.</p>'}
      `,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-close-circle"></i> Sí, cancelar',
      cancelButtonText: '<i class="mdi mdi-arrow-left"></i> Volver',
      customClass: {
        confirmButton: 'swal2-confirm btn-warning-swal',
        cancelButton: 'swal2-cancel',
      },
    }).then(function (r) {
      if (r.isConfirmed) {
        const form = document.getElementById(url);
        if (form) form.submit();
      }
    });
  };


  /* ==================== ELIMINAR VENTA ==================== */
  window.eliminarVenta = function ({ numero, url }) {
    Swal.fire({
      ...swalConfig,
      title: '¿Eliminar venta?',
      html: `
        <p>Vas a eliminar:</p>
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:1rem;color:var(--ink);margin:0 0 0.75rem 0;">
          "${numero}"
        </p>
        <p style="font-size:0.82rem;color:var(--error);">Esta acción no se puede deshacer.</p>
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


  /* ==================== DATATABLES ==================== */
  const dtEspanol = {
    decimal: ',',
    emptyTable: 'No hay ventas registradas',
    info: 'Mostrando _START_ a _END_ de _TOTAL_ ventas',
    infoEmpty: 'Mostrando 0 a 0 de 0 ventas',
    infoFiltered: '(filtrado de _MAX_ ventas totales)',
    lengthMenu: 'Mostrar _MENU_',
    loadingRecords: 'Cargando...',
    processing: 'Procesando...',
    search: 'Buscar:',
    zeroRecords: 'No se encontraron ventas',
    paginate: {
      first: 'Primero',
      last: 'Último',
      next: 'Siguiente',
      previous: 'Anterior',
    },
    aria: {
      sortAscending: ': ordenar ascendentemente',
      sortDescending: ': ordenar descendentemente',
    },
  };

  const tabla = $('#tablaVentas').DataTable({
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
      details: { type: 'inline', target: 'tr' },
    },
    dom: '<"dt-top"lf>t<"dt-bottom"ip>',
  });


  /* ==================== FILTROS CHIP ==================== */
  document.querySelectorAll('.filter-chip').forEach(function (chip) {
    chip.addEventListener('click', function () {
      document.querySelectorAll('.filter-chip').forEach(function (c) {
        c.classList.remove('active');
      });
      this.classList.add('active');

      const filtro = this.dataset.filter;

      // Limpiar filtros previos
      $.fn.dataTable.ext.search.pop();

      if (filtro === 'mias') {
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
          const row = tabla.row(dataIndex).node();
          return row && row.dataset.esMia === '1';
        });
      } else if (filtro !== 'all') {
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
          const row = tabla.row(dataIndex).node();
          return row && row.dataset.estado === filtro;
        });
      }

      tabla.draw();
    });
  });

});
</script>
@endpush