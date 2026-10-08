@extends('layouts.plantilla_maestra')

@section('title', 'Inventario')

@push('styles')
<style>
  /* =====================================================
     HEADER
     ===================================================== */
  .stocks-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
  }
  .stocks-header h3 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--ink);
    margin: 0;
    font-size: 1.35rem;
    display: flex;
    align-items: center;
    gap: 0.65rem;
  }
  .stocks-header h3 .icon-wrap {
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
  .stocks-header p {
    color: var(--ink-muted);
    margin: 0.25rem 0 0;
    font-size: 0.88rem;
  }

  /* =====================================================
     PANEL DE ALERTAS (resumen arriba del CRUD)
     ===================================================== */
  .alert-panel {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.85rem;
    margin-bottom: 1.5rem;
  }
  .alert-card {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 1rem 1.1rem;
    border-radius: var(--r-md);
    background: var(--bg-1);
    border: 1px solid var(--line);
    position: relative;
    overflow: hidden;
    transition: transform .2s ease, border-color .2s ease;
  }
  .alert-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--alert-color, var(--cyan));
  }
  .alert-card:hover {
    transform: translateY(-2px);
    border-color: color-mix(in srgb, var(--alert-color) 50%, var(--line));
  }
  .alert-card-icon {
    width: 42px;
    height: 42px;
    border-radius: var(--r-sm);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
    background: color-mix(in srgb, var(--alert-color) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--alert-color) 35%, transparent);
    color: var(--alert-color);
  }
  .alert-card-info { min-width: 0; }
  .alert-card-label {
    font-size: 0.72rem;
    color: var(--ink-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 700;
    display: block;
    margin-bottom: 0.15rem;
  }
  .alert-card-value {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.5rem;
    line-height: 1;
    color: var(--ink);
  }
  .alert-card-sub {
    font-size: 0.72rem;
    color: var(--ink-faint);
    display: block;
    margin-top: 0.2rem;
  }

  .alert-normal { --alert-color: var(--success); }
  .alert-bajo { --alert-color: var(--warning); }
  .alert-sinstock { --alert-color: var(--error); }
  .alert-sobre { --alert-color: #ff8c42; }
  .alert-sinlimites { --alert-color: var(--ink-faint); }

  /* =====================================================
     PANEL CRUD
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
  .dataTables_wrapper .dt-top > .dataTables_filter { margin-left: auto !important; }
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
    min-width: 220px;
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
  .dataTables_wrapper .dt-bottom > .dataTables_paginate { margin-left: auto !important; }
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

  /* =====================================================
     FILA CON ESTADO (acento de color dinámico)
     ===================================================== */
  .panel table.dataTable tbody tr {
    background: transparent !important;
    transition: background 0.2s ease;
    position: relative;
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

  /* Acento lateral en la primera celda según estado */
  .panel table.dataTable tbody tr.row-estado > td:first-child {
    position: relative;
    padding-left: 1.1rem !important;
  }
  .panel table.dataTable tbody tr.row-estado > td:first-child::before {
    content: '';
    position: absolute;
    left: 0;
    top: 12%;
    bottom: 12%;
    width: 3px;
    border-radius: 0 3px 3px 0;
    background: var(--row-accent);
    box-shadow: 0 0 10px -2px var(--row-accent-glow, var(--row-accent));
  }

  .panel table.dataTable tbody tr.row-normal {
    --row-accent: var(--success);
    --row-accent-glow: rgba(0, 230, 168, 0.4);
    background: color-mix(in srgb, var(--success) 3%, transparent) !important;
  }
  .panel table.dataTable tbody tr.row-normal:hover {
    background: color-mix(in srgb, var(--success) 6%, transparent) !important;
  }

  .panel table.dataTable tbody tr.row-bajo {
    --row-accent: var(--warning);
    --row-accent-glow: rgba(255, 181, 71, 0.4);
    background: color-mix(in srgb, var(--warning) 4%, transparent) !important;
  }
  .panel table.dataTable tbody tr.row-bajo:hover {
    background: color-mix(in srgb, var(--warning) 8%, transparent) !important;
  }

  .panel table.dataTable tbody tr.row-sinstock {
    --row-accent: var(--error);
    --row-accent-glow: rgba(255, 77, 109, 0.5);
    background: color-mix(in srgb, var(--error) 5%, transparent) !important;
  }
  .panel table.dataTable tbody tr.row-sinstock:hover {
    background: color-mix(in srgb, var(--error) 9%, transparent) !important;
  }

  .panel table.dataTable tbody tr.row-sobre {
    --row-accent: #ff8c42;
    --row-accent-glow: rgba(255, 140, 66, 0.5);
    background: color-mix(in srgb, #ff8c42 4%, transparent) !important;
  }
  .panel table.dataTable tbody tr.row-sobre:hover {
    background: color-mix(in srgb, #ff8c42 8%, transparent) !important;
  }

  .panel table.dataTable tbody tr.row-sinlimites {
    --row-accent: var(--ink-faint);
    --row-accent-glow: rgba(138, 152, 184, 0.3);
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
     PRODUCTO
     ===================================================== */
  .cell-product-name {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.85rem;
    display: block;
    max-width: 230px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .cell-product-meta {
    font-size: 0.72rem;
    color: var(--ink-faint);
    display: flex;
    gap: 0.35rem;
    align-items: center;
    margin-top: 0.15rem;
    flex-wrap: wrap;
  }
  .cell-product-meta .mdi { font-size: 0.75rem; }

  /* =====================================================
     EXISTENCIA
     ===================================================== */
  .existencia {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.18rem;
    min-width: 125px;
  }
  .existencia-linea {
    display: flex;
    align-items: baseline;
    justify-content: flex-end;
    gap: 0.3rem;
    white-space: nowrap;
  }
  .existencia-cantidad {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.94rem;
    color: var(--cyan);
  }
  .existencia-unidad {
    font-size: 0.68rem;
    font-weight: 600;
    color: var(--ink-muted);
  }
  .existencia-secundaria .existencia-cantidad {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--ink);
  }
  .existencia-secundaria .existencia-unidad {
    font-size: 0.64rem;
    color: var(--ink-faint);
  }

  /* =====================================================
     INDICADOR DE SIN LÍMITES
     ===================================================== */
  .sin-limites-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--ink-faint);
    background: color-mix(in srgb, var(--ink-faint) 12%, transparent);
    border: 1px dashed color-mix(in srgb, var(--ink-faint) 40%, transparent);
    padding: 0.15rem 0.45rem;
    border-radius: var(--r-pill);
    margin-left: 0.35rem;
  }
  .sin-limites-badge .mdi { font-size: 0.72rem; }

  /* =====================================================
     BADGE DE ESTADO (dentro de la celda del producto)
     ===================================================== */
  .estado-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    border-radius: var(--r-pill);
    padding: 0.2rem 0.55rem;
    font-size: 0.66rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    white-space: nowrap;
    margin-top: 0.35rem;
  }
  .estado-badge::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
  }
  .estado-badge.estado-normal {
    color: var(--success);
    background: color-mix(in srgb, var(--success) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--success) 35%, transparent);
  }
  .estado-badge.estado-bajo {
    color: var(--warning);
    background: color-mix(in srgb, var(--warning) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--warning) 40%, transparent);
  }
  .estado-badge.estado-sin-stock {
    color: var(--error);
    background: color-mix(in srgb, var(--error) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--error) 40%, transparent);
  }
  .estado-badge.estado-sobre {
    color: #ff8c42;
    background: color-mix(in srgb, #ff8c42 12%, transparent);
    border: 1px solid color-mix(in srgb, #ff8c42 40%, transparent);
  }

  /* =====================================================
     BOTONES DE ACCIÓN
     ===================================================== */
  .actions-cell {
    display: flex;
    gap: 0.35rem;
    justify-content: center;
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
  }
  .panel table.dataTable tbody tr:hover .action-btn { opacity: 1; }
  .action-btn:hover { transform: translateY(-2px); }
  .action-btn.view:hover,
  .action-btn.tune:hover {
    color: var(--cyan);
    border-color: var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    box-shadow: 0 0 14px -4px var(--glow);
  }

  /* =====================================================
     MODALES
     ===================================================== */
  .modal-content {
    background: var(--bg-1) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-lg) !important;
    overflow: hidden;
    box-shadow: 0 40px 80px -40px var(--shadow), 0 0 0 1px var(--line) !important;
  }
  .ameri-modal-header {
    background: linear-gradient(135deg,
      color-mix(in srgb, var(--cyan) 18%, var(--bg-1)) 0%,
      var(--bg-2) 100%) !important;
    border-bottom: 1px solid var(--line) !important;
    padding: 1.25rem 1.5rem !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
  }
  .ameri-modal-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--r-md);
    background: color-mix(in srgb, var(--cyan) 15%, transparent);
    border: 1px solid var(--line-strong);
    color: var(--cyan);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 0 16px -4px var(--glow);
  }
  .ameri-modal-header .modal-title {
    color: var(--ink) !important;
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
    line-height: 1.2;
  }
  .ameri-modal-sub {
    color: var(--ink-muted) !important;
    font-size: 0.8rem;
    margin: 0.2rem 0 0;
    line-height: 1.2;
  }
  .ameri-modal-header .btn-close {
    opacity: 0.7;
    transition: opacity .2s ease, transform .2s ease;
  }
  .ameri-modal-header .btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
  }
  .modal-body {
    padding: 1.5rem !important;
    background: var(--bg-1) !important;
    color: var(--ink) !important;
  }
  .ameri-modal-footer {
    background: var(--bg-2) !important;
    border-top: 1px solid var(--line) !important;
    padding: 1rem 1.5rem !important;
    display: flex;
    justify-content: flex-end;
    gap: 0.65rem;
  }

  .modal-body .form-label {
    color: var(--ink-muted);
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 0.4rem;
    display: block;
  }
  .modal-body .form-control {
    background: var(--bg-2);
    border: 1px solid var(--line);
    color: var(--ink);
    border-radius: var(--r-md);
    padding: 0.6rem 0.85rem;
  }
  .modal-body .form-control:focus {
    background: var(--bg-2);
    color: var(--ink);
    border-color: var(--cyan);
    box-shadow: 0 0 0 3px var(--glow-soft);
    outline: none;
  }

  /* =====================================================
     DETALLE PRODUCTO
     ===================================================== */
  .detalle-seccion {
    border: 1px solid var(--line);
    border-radius: var(--r-md);
    padding: 1rem 1.15rem;
    background: var(--bg-2);
    margin-bottom: 1rem;
  }
  .detalle-seccion:last-child { margin-bottom: 0; }
  .detalle-seccion-titulo {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    color: var(--cyan);
    font-family: var(--font-display);
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 0.85rem;
  }
  .detalle-seccion-titulo .mdi { font-size: 1rem; }

  .detalle-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem 1.25rem;
  }
  .detalle-item { min-width: 0; }
  .detalle-label {
    display: block;
    color: var(--ink-faint);
    font-size: 0.7rem;
    margin-bottom: 0.15rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }
  .detalle-valor {
    display: block;
    color: var(--ink);
    font-size: 0.84rem;
    font-weight: 600;
    word-break: break-word;
  }

  .detalle-stock-principal {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 0.75rem;
  }
  .detalle-stock-card {
    border: 1px solid var(--line);
    border-radius: var(--r-md);
    padding: 0.85rem;
    text-align: center;
    background: var(--bg-1);
  }
  .detalle-stock-card .numero {
    display: block;
    color: var(--cyan);
    font-family: var(--font-display);
    font-size: 1.15rem;
    font-weight: 700;
  }
  .detalle-stock-card .unidad {
    display: block;
    color: var(--ink-muted);
    font-size: 0.7rem;
    margin-top: 0.15rem;
  }

  .detalle-admin {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.7rem 0;
    border-bottom: 1px solid var(--line);
  }
  .detalle-admin:last-child { border-bottom: none; padding-bottom: 0; }
  .detalle-admin:first-child { padding-top: 0; }
  .detalle-admin-nombre {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    color: var(--ink);
    font-size: 0.82rem;
    font-weight: 600;
  }
  .detalle-admin-nombre i { color: var(--cyan); font-size: 1rem; }
  .detalle-admin-cantidad {
    color: var(--cyan);
    font-family: var(--font-display);
    font-size: 0.82rem;
    font-weight: 700;
    text-align: right;
  }
  .detalle-admin-cantidad small {
    color: var(--ink-muted);
    font-family: inherit;
    font-size: 0.68rem;
    font-weight: 500;
  }

  .detalle-estado {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    border-radius: var(--r-pill);
    padding: 0.35rem 0.7rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }
  .detalle-estado .mdi { font-size: 0.9rem; }

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
     ALERTAS GLOBALES
     ===================================================== */
  .alert {
    border-radius: var(--r-md);
    border: 1px solid var(--line);
    background: var(--bg-1);
    color: var(--ink);
    display: flex;
    align-items: center;
    padding: 0.85rem 1.1rem;
    font-size: 0.88rem;
  }
  .alert-success {
    background: color-mix(in srgb, var(--cyan) 9%, var(--bg-1)) !important;
    border-color: var(--line-strong) !important;
    color: var(--cyan) !important;
  }
  .alert-success .mdi { color: var(--cyan); }
  .alert-danger {
    background: color-mix(in srgb, var(--error) 9%, var(--bg-1)) !important;
    border-color: color-mix(in srgb, var(--error) 40%, transparent) !important;
    color: var(--error) !important;
  }
  .alert-danger .mdi { color: var(--error); }
  .alert .btn-close { filter: invert(1); opacity: 0.5; }
  html[data-theme="light"] .alert .btn-close { filter: none; }

  /* =====================================================
     RESPONSIVE
     ===================================================== */
  @media (max-width: 991.98px) {
    .panel-body { padding: 1rem; }
    .alert-panel { grid-template-columns: repeat(2, 1fr); }
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

    .existencia { align-items: flex-start; }
    .existencia-linea { justify-content: flex-start; }

    .detalle-grid { grid-template-columns: 1fr; }
    .detalle-stock-principal { grid-template-columns: 1fr; }

    .stocks-header h3 { font-size: 1.15rem; }
    .alert-panel { grid-template-columns: 1fr; }
  }

  @media (max-width: 575.98px) {
    .stocks-header h3 .icon-wrap {
      width: 38px;
      height: 38px;
      font-size: 1.15rem;
    }
    .stocks-header h3 { font-size: 1.05rem; }
    .panel-body { padding: 0.9rem 1rem; }
    .dataTables_wrapper .dataTables_paginate .page-item .page-link {
      padding: 0.3rem 0.55rem;
      min-width: 30px;
      font-size: 0.78rem;
    }
    .alert-card { padding: 0.85rem 0.95rem; }
    .alert-card-value { font-size: 1.25rem; }
  }
</style>
@endpush


@section('content')
@php
  /* ====================================================
     CALCULAR RESUMEN PARA EL PANEL DE ALERTAS
     ==================================================== */
  $contNormal = 0;
  $contBajo = 0;
  $contSinStock = 0;
  $contSobre = 0;
  $contSinLimites = 0;
  $totalProductos = 0;

  foreach ($inventario as $item) {
      $totalProductos++;
      $p = $item['producto'];
      $stockTotal = (float) $item['stock_total'];
      $min = (float) $item['stock_minimo'];
      $max = (float) $item['stock_maximo'];

      $tipoNombre = mb_strtolower(trim((string) ($p->tipoProducto?->nombre ?? '')), 'UTF-8');
      $piezasPorCaja = (float) ($p->piezas_por_caja ?? 0);
      $esCaja = in_array($tipoNombre, ['cerámica','ceramica','porcelanato']) && $piezasPorCaja > 0;
      $stockParaEstado = $esCaja ? ($stockTotal / $piezasPorCaja) : $stockTotal;

      $sinLimites = ($min <= 0 && $max <= 0);

      if ($sinLimites) {
          $contSinLimites++;
          continue;
      }

      if ($stockTotal <= 0) {
          $contSinStock++;
      } elseif ($min > 0 && $stockParaEstado < $min) {
          $contBajo++;
      } elseif ($max > 0 && $stockParaEstado > $max) {
          $contSobre++;
      } else {
          $contNormal++;
      }
  }
@endphp

<div class="container-fluid">

  {{-- ==================== HEADER ==================== --}}
  <div class="stocks-header">
    <div>
      <h3>
        <span class="icon-wrap"><i class="mdi mdi-warehouse"></i></span>
        Inventario
      </h3>
      <p>Existencias actuales de todos los productos y distribución por administrador</p>
    </div>
  </div>

  {{-- ==================== ALERTAS DE SESIÓN ==================== --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
      <i class="mdi mdi-check-circle-outline me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
      <i class="mdi mdi-alert-circle-outline me-2"></i>
      {{ session('error') }}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  {{-- ==================== PANEL DE RESUMEN ==================== --}}
  <div class="alert-panel">

    <div class="alert-card alert-normal">
      <div class="alert-card-icon"><i class="mdi mdi-check-circle-outline"></i></div>
      <div class="alert-card-info">
        <span class="alert-card-label">Normal</span>
        <span class="alert-card-value">{{ $contNormal }}</span>
        <span class="alert-card-sub">productos en rango</span>
      </div>
    </div>

    <div class="alert-card alert-bajo">
      <div class="alert-card-icon"><i class="mdi mdi-alert-outline"></i></div>
      <div class="alert-card-info">
        <span class="alert-card-label">Stock bajo</span>
        <span class="alert-card-value">{{ $contBajo }}</span>
        <span class="alert-card-sub">bajo el mínimo</span>
      </div>
    </div>

    <div class="alert-card alert-sinstock">
      <div class="alert-card-icon"><i class="mdi mdi-close-circle-outline"></i></div>
      <div class="alert-card-info">
        <span class="alert-card-label">Sin stock</span>
        <span class="alert-card-value">{{ $contSinStock }}</span>
        <span class="alert-card-sub">agotados</span>
      </div>
    </div>

    <div class="alert-card alert-sobre">
      <div class="alert-card-icon"><i class="mdi mdi-alert-circle-outline"></i></div>
      <div class="alert-card-info">
        <span class="alert-card-label">Sobreexistencia</span>
        <span class="alert-card-value">{{ $contSobre }}</span>
        <span class="alert-card-sub">sobre el máximo</span>
      </div>
    </div>

    @if($contSinLimites > 0)
      <div class="alert-card alert-sinlimites">
        <div class="alert-card-icon"><i class="mdi mdi-tune-variant"></i></div>
        <div class="alert-card-info">
          <span class="alert-card-label">Sin límites</span>
          <span class="alert-card-value">{{ $contSinLimites }}</span>
          <span class="alert-card-sub">configura mín/máx</span>
        </div>
      </div>
    @endif

  </div>

  {{-- ==================== TABLA ==================== --}}
  <div class="panel">
    <div class="panel-body">
      <table id="tablaStocks" class="table dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Categoría / Tipo</th>
            <th class="text-end">Existencia total</th>
            @foreach($admins as $admin)
              <th class="text-end">{{ $admin->name }}</th>
            @endforeach
            <th class="text-end">Mínimo</th>
            <th class="text-end">Máximo</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
        @forelse($inventario as $item)
          @php
            $producto = $item['producto'];
            $stockTotal = (float) $item['stock_total'];
            $stockMinimo = (float) $item['stock_minimo'];
            $stockMaximo = (float) $item['stock_maximo'];

            $tipoNombre = mb_strtolower(trim((string) ($producto->tipoProducto?->nombre ?? '')), 'UTF-8');

            if (in_array($tipoNombre, ['cerámica','ceramica','porcelanato'])) {
                $unidadLimite = 'cajas';

            

            } elseif (
                str_contains($tipoNombre, 'cemento cola')
                || str_contains($tipoNombre, 'pastina')
            ) {


                $unidadLimite = 'bolsas';
            } else {
                $unidadLimite = 'piezas';
            }

            $piezasPorCaja = (float) ($producto->piezas_por_caja ?? 0);
            $m2PorCaja = (float) ($producto->m2_por_caja ?? 0);
            $pesoProducto = (float) ($producto->peso ?? 0);
            $tieneCajas = $piezasPorCaja > 0;

            $cajasTotal = 0;
            $piezasTotal = 0;
            $m2Total = 0;

            if ($tieneCajas) {
                $cajasTotal = floor($stockTotal / $piezasPorCaja);
                $piezasTotal = $stockTotal;
                if ($m2PorCaja > 0) {
                    $m2Total = ($stockTotal / $piezasPorCaja) * $m2PorCaja;
                }
            }

            if ($unidadLimite === 'cajas' && $piezasPorCaja > 0) {
                $stockParaEstado = $stockTotal / $piezasPorCaja;
            } else {
                $stockParaEstado = $stockTotal;
            }

            $sinLimites = ($stockMinimo <= 0 && $stockMaximo <= 0);

            /* --- DETERMINAR ESTADO Y CLASE DE FILA --- */
            if ($sinLimites) {
                $estado = 'Sin límites';
                $estadoClase = 'sin-limites';
                $filaClase = 'row-sinlimites';
                $estadoIcono = 'mdi-tune-variant';
            } elseif ($stockTotal <= 0) {
                $estado = 'Sin stock';
                $estadoClase = 'sin-stock';
                $filaClase = 'row-sinstock';
                $estadoIcono = 'mdi-close-circle-outline';
            } elseif ($stockMinimo > 0 && $stockParaEstado < $stockMinimo) {
                $estado = 'Stock bajo';
                $estadoClase = 'bajo';
                $filaClase = 'row-bajo';
                $estadoIcono = 'mdi-alert-outline';
            } elseif ($stockMaximo > 0 && $stockParaEstado > $stockMaximo) {
                $estado = 'Sobreexistencia';
                $estadoClase = 'sobre';
                $filaClase = 'row-sobre';
                $estadoIcono = 'mdi-alert-circle-outline';
            } else {
                $estado = 'Normal';
                $estadoClase = 'normal';
                $filaClase = 'row-normal';
                $estadoIcono = 'mdi-check-circle-outline';
            }

            $cantidadFormateada = function ($cantidad) {
                return rtrim(rtrim(number_format((float) $cantidad, 2, '.', ''), '0'), '.');
            };

            $detalleAdmins = [];
            foreach ($admins as $admin) {
                $stockAdmin = $item['stock_por_admin']->get($admin->id);
                $cantidadAdmin = $stockAdmin ? (float) $stockAdmin->cantidad : 0;
                $cajasAdmin = 0;
                $piezasAdmin = 0;
                $m2Admin = 0;
                if ($tieneCajas) {
                    $cajasAdmin = floor($cantidadAdmin / $piezasPorCaja);
                    $piezasAdmin = $cantidadAdmin;
                    if ($m2PorCaja > 0) {
                        $m2Admin = ($cantidadAdmin / $piezasPorCaja) * $m2PorCaja;
                    }
                }
                $detalleAdmins[] = [
                    'id' => $admin->id,
                    'nombre' => $admin->name,
                    'cantidad' => $cantidadAdmin,
                    'cajas' => $cajasAdmin,
                    'piezas' => $piezasAdmin,
                    'm2' => $m2Admin,
                ];
            }

            $detalleProducto = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'marca' => $producto->marca,
                'modelo' => $producto->modelo,
                'medida' => $producto->medida,
                'color' => $producto->color,
                'acabado' => $producto->acabado,
                'presentacion' => $producto->presentacion,
                'categoria' => $producto->tipoProducto?->categoria?->nombre,
                'tipo' => $producto->tipoProducto?->nombre,
                'piezas_caja' => $piezasPorCaja,
                'm2_caja' => $m2PorCaja,
                'peso' => $pesoProducto,
                'tiene_cajas' => $tieneCajas,
                'stock_total' => $stockTotal,
                'stock_minimo' => $stockMinimo,
                'stock_maximo' => $stockMaximo,
                'unidad' => $unidadLimite,
                'cajas_total' => $cajasTotal,
                'piezas_total' => $piezasTotal,
                'm2_total' => $m2Total,
                'estado' => $estado,
                'estado_clase' => $estadoClase,
                'admins' => $detalleAdmins,
            ];
          @endphp

          <tr class="row-estado {{ $filaClase }}">
            <td>
              <span class="cell-product-name" title="{{ $producto->nombre }}">
                {{ $producto->nombre }}
              </span>
              @if($producto->marca || $producto->modelo || $producto->medida)
                <span class="cell-product-meta">
                  @if($producto->marca)
                    <i class="mdi mdi-trademark"></i>
                    {{ $producto->marca }}
                  @endif
                  @if($producto->modelo) · {{ $producto->modelo }} @endif
                  @if($producto->medida) · {{ $producto->medida }} @endif
                </span>
              @endif

              {{-- Badge de estado --}}
              <span class="estado-badge estado-{{ $estadoClase }}">
                <i class="mdi {{ $estadoIcono }}"></i>
                {{ $estado }}
              </span>

              @if($sinLimites)
                <span class="sin-limites-badge">
                  <i class="mdi mdi-tune-variant"></i> Configurar
                </span>
              @endif
            </td>

            <td>
              <span style="font-size:0.82rem;color:var(--ink-muted);">
                {{ $producto->tipoProducto?->categoria?->nombre ?? '—' }}
              </span>
              <br>
              <span style="font-size:0.72rem;color:var(--ink-faint);">
                {{ $producto->tipoProducto?->nombre ?? '' }}
              </span>
            </td>

            <td class="text-end">
              <div class="existencia">
                @if($tieneCajas)
                  <div class="existencia-linea">
                    <span class="existencia-cantidad">{{ $cantidadFormateada($cajasTotal) }}</span>
                    <span class="existencia-unidad">{{ $cajasTotal == 1 ? 'caja' : 'cajas' }}</span>
                  </div>
                  <div class="existencia-linea existencia-secundaria">
                    <span class="existencia-cantidad">{{ $cantidadFormateada($piezasTotal) }}</span>
                    <span class="existencia-unidad">{{ $piezasTotal == 1 ? 'pieza' : 'piezas' }}</span>
                  </div>
                  @if($m2PorCaja > 0)
                    <div class="existencia-linea existencia-secundaria">
                      <span class="existencia-cantidad">{{ $cantidadFormateada($m2Total) }}</span>
                      <span class="existencia-unidad">m²</span>
                    </div>
                  @endif
                @else
                  <div class="existencia-linea">
                    <span class="existencia-cantidad">{{ $cantidadFormateada($stockTotal) }}</span>
                    <span class="existencia-unidad">{{ $unidadLimite }}</span>
                  </div>
                @endif
              </div>
            </td>

            @foreach($admins as $admin)
              @php
                $stockAdmin = $item['stock_por_admin']->get($admin->id);
                $cantidadAdmin = $stockAdmin ? (float) $stockAdmin->cantidad : 0;
                $cajasAdmin = 0;
                $piezasAdmin = 0;
                $m2Admin = 0;
                if ($tieneCajas) {
                    $cajasAdmin = floor($cantidadAdmin / $piezasPorCaja);
                    $piezasAdmin = $cantidadAdmin;
                    if ($m2PorCaja > 0) {
                        $m2Admin = ($cantidadAdmin / $piezasPorCaja) * $m2PorCaja;
                    }
                }
              @endphp

              <td class="text-end">
                <div class="existencia">
                  @if($tieneCajas)
                    <div class="existencia-linea">
                      <span class="existencia-cantidad">{{ $cantidadFormateada($cajasAdmin) }}</span>
                      <span class="existencia-unidad">{{ $cajasAdmin == 1 ? 'caja' : 'cajas' }}</span>
                    </div>
                    <div class="existencia-linea existencia-secundaria">
                      <span class="existencia-cantidad">{{ $cantidadFormateada($piezasAdmin) }}</span>
                      <span class="existencia-unidad">{{ $piezasAdmin == 1 ? 'pieza' : 'piezas' }}</span>
                    </div>
                    @if($m2PorCaja > 0)
                      <div class="existencia-linea existencia-secundaria">
                        <span class="existencia-cantidad">{{ $cantidadFormateada($m2Admin) }}</span>
                        <span class="existencia-unidad">m²</span>
                      </div>
                    @endif
                  @else
                    <div class="existencia-linea">
                      <span class="existencia-cantidad">{{ $cantidadFormateada($cantidadAdmin) }}</span>
                      <span class="existencia-unidad">{{ $unidadLimite }}</span>
                    </div>
                  @endif
                </div>
              </td>
            @endforeach

            <td class="text-end">
              <div class="existencia">
                <div class="existencia-linea">
                  <span class="existencia-cantidad" style="color:var(--ink-muted);font-size:0.85rem;">
                    @if($stockMinimo > 0)
                      {{ $cantidadFormateada($stockMinimo) }}
                    @else
                      —
                    @endif
                  </span>
                  @if($stockMinimo > 0)
                    <span class="existencia-unidad">{{ $unidadLimite }}</span>
                  @endif
                </div>
              </div>
            </td>

            <td class="text-end">
              <div class="existencia">
                <div class="existencia-linea">
                  <span class="existencia-cantidad" style="color:var(--ink-muted);font-size:0.85rem;">
                    @if($stockMaximo > 0)
                      {{ $cantidadFormateada($stockMaximo) }}
                    @else
                      —
                    @endif
                  </span>
                  @if($stockMaximo > 0)
                    <span class="existencia-unidad">{{ $unidadLimite }}</span>
                  @endif
                </div>
              </div>
            </td>

            <td class="text-center">
              <div class="actions-cell">
                <button
                  type="button"
                  class="action-btn view"
                  title="Ver detalle"
                  data-bs-toggle="modal"
                  data-bs-target="#modalDetalleProducto"
                  data-detalle='@json($detalleProducto)'
                >
                  <i class="mdi mdi-eye-outline"></i>
                </button>

                <button
                  type="button"
                  class="action-btn tune"
                  title="Configurar límites"
                  data-bs-toggle="modal"
                  data-bs-target="#modalLimites"
                  data-id="{{ $producto->id }}"
                  data-nombre="{{ $producto->nombre }}"
                  data-minimo="{{ $stockMinimo }}"
                  data-maximo="{{ $stockMaximo }}"
                  data-unidad="{{ $unidadLimite }}"
                >
                  <i class="mdi mdi-tune-variant"></i>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="{{ 6 + $admins->count() }}" class="text-center py-5">
              <i class="mdi mdi-warehouse" style="font-size:44px;opacity:0.3;color:var(--ink-faint);"></i>
              <p class="mt-2 mb-0" style="color:var(--ink-muted);">No hay productos registrados.</p>
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>


{{-- ================================================================ --}}
{{-- MODAL LÍMITES                                                    --}}
{{-- ================================================================ --}}
<div class="modal fade" id="modalLimites" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formLimites" method="POST">
        @csrf
        @method('PATCH')

        <div class="modal-header ameri-modal-header">
          <div class="d-flex align-items-center gap-3">
            <div class="ameri-modal-icon"><i class="mdi mdi-tune-variant"></i></div>
            <div>
              <h5 class="modal-title">Configurar límites</h5>
              <p class="ameri-modal-sub">Define el stock mínimo y máximo</p>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <p class="mb-4" style="color:var(--ink-muted);font-size:0.9rem;">
            Producto:
            <strong id="limitesNombreProducto" style="color:var(--ink);"></strong>
          </p>

          <div class="mb-3">
            <label class="form-label">
              Stock mínimo
              <span id="unidadMinimo" style="color:var(--cyan);font-weight:700;">piezas</span>
            </label>
            <input type="number" name="stock_minimo" id="limitesMinimo"
                   class="form-control" min="0" step="0.01" required>
            <small style="color:var(--ink-faint);font-size:0.78rem;display:block;margin-top:0.35rem;">
              Si el stock total queda por debajo, aparecerá como <strong style="color:var(--warning);">stock bajo</strong>.
            </small>
          </div>

          <div class="mb-0">
            <label class="form-label">
              Stock máximo
              <span id="unidadMaximo" style="color:var(--cyan);font-weight:700;">piezas</span>
            </label>
            <input type="number" name="stock_maximo" id="limitesMaximo"
                   class="form-control" min="0" step="0.01" required>
            <small style="color:var(--ink-faint);font-size:0.78rem;display:block;margin-top:0.35rem;">
              Si el stock total supera, aparecerá como <strong style="color:#ff8c42;">sobreexistencia</strong>.
            </small>
          </div>
        </div>

        <div class="modal-footer ameri-modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-content-save-outline me-1"></i>
            Guardar límites
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


{{-- ================================================================ --}}
{{-- MODAL DETALLE DEL PRODUCTO                                      --}}
{{-- ================================================================ --}}
<div class="modal fade" id="modalDetalleProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header ameri-modal-header">
        <div class="d-flex align-items-center gap-3">
          <div class="ameri-modal-icon"><i class="mdi mdi-package-variant-closed"></i></div>
          <div>
            <h5 class="modal-title">Detalle del producto</h5>
            <p class="ameri-modal-sub" id="detalleIdProducto"></p>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="detalle-seccion">
          <div class="detalle-seccion-titulo">
            <i class="mdi mdi-information-outline"></i>
            Información del producto
          </div>
          <div class="detalle-grid">
            <div class="detalle-item"><span class="detalle-label">Nombre</span><span class="detalle-valor" id="detalleNombre"></span></div>
            <div class="detalle-item"><span class="detalle-label">Categoría</span><span class="detalle-valor" id="detalleCategoria"></span></div>
            <div class="detalle-item"><span class="detalle-label">Tipo</span><span class="detalle-valor" id="detalleTipo"></span></div>
            <div class="detalle-item"><span class="detalle-label">Marca</span><span class="detalle-valor" id="detalleMarca"></span></div>
            <div class="detalle-item"><span class="detalle-label">Modelo</span><span class="detalle-valor" id="detalleModelo"></span></div>
            <div class="detalle-item"><span class="detalle-label">Medida</span><span class="detalle-valor" id="detalleMedida"></span></div>
            <div class="detalle-item"><span class="detalle-label">Color</span><span class="detalle-valor" id="detalleColor"></span></div>
            <div class="detalle-item"><span class="detalle-label">Acabado</span><span class="detalle-valor" id="detalleAcabado"></span></div>
            <div class="detalle-item"><span class="detalle-label">Presentación</span><span class="detalle-valor" id="detallePresentacion"></span></div>
            <div class="detalle-item" id="detallePesoContainer"><span class="detalle-label">Peso</span><span class="detalle-valor" id="detallePeso"></span></div>
          </div>
        </div>

        <div class="detalle-seccion" id="detalleCaracteristicas">
          <div class="detalle-seccion-titulo">
            <i class="mdi mdi-package-variant"></i>
            Características de presentación
          </div>
          <div class="detalle-grid">
            <div class="detalle-item" id="detallePiezasCajaContainer">
              <span class="detalle-label">Piezas por caja</span>
              <span class="detalle-valor" id="detallePiezasCaja"></span>
            </div>
            <div class="detalle-item" id="detalleM2CajaContainer">
              <span class="detalle-label">m² por caja</span>
              <span class="detalle-valor" id="detalleM2Caja"></span>
            </div>
          </div>
        </div>

        <div class="detalle-seccion">
          <div class="detalle-seccion-titulo">
            <i class="mdi mdi-warehouse"></i>
            Existencia actual
          </div>
          <div class="detalle-stock-principal" id="detalleStockCajas">
            <div class="detalle-stock-card">
              <span class="numero" id="detalleCajasTotal">0</span>
              <span class="unidad">cajas</span>
            </div>
            <div class="detalle-stock-card">
              <span class="numero" id="detallePiezasTotal">0</span>
              <span class="unidad">piezas</span>
            </div>
            <div class="detalle-stock-card">
              <span class="numero" id="detalleM2Total">0</span>
              <span class="unidad">m²</span>
            </div>
          </div>
          <div id="detalleStockSimple" class="detalle-stock-card" style="display:none;">
            <span class="numero" id="detalleStockSimpleCantidad">0</span>
            <span class="unidad" id="detalleStockSimpleUnidad">piezas</span>
          </div>
        </div>

        <div class="detalle-seccion">
          <div class="detalle-seccion-titulo">
            <i class="mdi mdi-chart-line"></i>
            Control de inventario
          </div>
          <div class="detalle-grid">
            <div class="detalle-item"><span class="detalle-label">Stock mínimo</span><span class="detalle-valor" id="detalleMinimo"></span></div>
            <div class="detalle-item"><span class="detalle-label">Stock máximo</span><span class="detalle-valor" id="detalleMaximo"></span></div>
            <div class="detalle-item"><span class="detalle-label">Unidad de control</span><span class="detalle-valor" id="detalleUnidad"></span></div>
            <div class="detalle-item"><span class="detalle-label">Estado</span><span id="detalleEstado" class="detalle-estado"></span></div>
          </div>
        </div>

        <div class="detalle-seccion">
          <div class="detalle-seccion-titulo">
            <i class="mdi mdi-account-group-outline"></i>
            Stock por dueño
          </div>
          <div id="detalleStockAdmins"></div>
        </div>
      </div>

      <div class="modal-footer ameri-modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
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

  /* ==================== DATATABLES ==================== */
  const dtEspanol = {
    decimal: ',',
    emptyTable: 'No hay datos disponibles',
    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
    infoEmpty: 'Mostrando 0 a 0 de 0 registros',
    infoFiltered: '(filtrado de _MAX_ registros totales)',
    lengthMenu: 'Mostrar _MENU_',
    loadingRecords: 'Cargando...',
    processing: 'Procesando...',
    search: 'Buscar:',
    zeroRecords: 'No se encontraron resultados',
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

  const totalColumnas = $('#tablaStocks thead th').length;
  const colAcciones = totalColumnas - 1;

  $('#tablaStocks').DataTable({
    language: dtEspanol,
    pageLength: 7,
    lengthMenu: [[7, 10, 25, 50, -1], [7, 10, 25, 50, 'Todos']],
    order: [[0, 'asc']],
    autoWidth: false,
    columnDefs: [
      { orderable: false, targets: [colAcciones] },
      { responsivePriority: 1, targets: [0, colAcciones] },
      { responsivePriority: 2, targets: [2, totalColumnas - 3] },
    ],
    responsive: {
      details: { type: 'inline', target: 'tr' },
    },
    dom: '<"dt-top"lf>t<"dt-bottom"ip>',
  });


  /* ==================== MODAL LÍMITES ==================== */
  const modalLimites = document.getElementById('modalLimites');

  modalLimites.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    const id      = button.getAttribute('data-id');
    const nombre  = button.getAttribute('data-nombre');
    const minimo  = button.getAttribute('data-minimo');
    const maximo  = button.getAttribute('data-maximo');
    const unidad  = button.getAttribute('data-unidad') || 'piezas';

    document.getElementById('limitesNombreProducto').textContent = nombre;
    document.getElementById('limitesMinimo').value = minimo;
    document.getElementById('limitesMaximo').value = maximo;
    document.getElementById('unidadMinimo').textContent = unidad;
    document.getElementById('unidadMaximo').textContent = unidad;

    document.getElementById('formLimites').action =
      "{{ url('/admin/stocks/productos') }}/" + id + "/limites";
  });


  /* ==================== MODAL DETALLE ==================== */
  const modalDetalleProducto = document.getElementById('modalDetalleProducto');

  modalDetalleProducto.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const detalleJson = button.getAttribute('data-detalle');

    if (!detalleJson) return;

    let producto;
    try {
      producto = JSON.parse(detalleJson);
    } catch (error) {
      console.error('No se pudo cargar el detalle del producto:', error);
      return;
    }

    const formatear = function (valor) {
      const numero = Number(valor || 0);
      return numero.toLocaleString('es-BO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
      });
    };

    const texto = function (valor) {
      if (valor === null || valor === undefined || valor === '') return '—';
      return valor;
    };

    /* INFO GENERAL */
    document.getElementById('detalleIdProducto').textContent = 'Código de producto #' + producto.id;
    document.getElementById('detalleNombre').textContent = texto(producto.nombre);
    document.getElementById('detalleCategoria').textContent = texto(producto.categoria);
    document.getElementById('detalleTipo').textContent = texto(producto.tipo);
    document.getElementById('detalleMarca').textContent = texto(producto.marca);
    document.getElementById('detalleModelo').textContent = texto(producto.modelo);
    document.getElementById('detalleMedida').textContent = texto(producto.medida);
    document.getElementById('detalleColor').textContent = texto(producto.color);
    document.getElementById('detalleAcabado').textContent = texto(producto.acabado);
    document.getElementById('detallePresentacion').textContent = texto(producto.presentacion);

    /* PESO */
    const pesoContainer = document.getElementById('detallePesoContainer');
    if (Number(producto.peso || 0) > 0) {
      pesoContainer.style.display = 'block';
      document.getElementById('detallePeso').textContent = formatear(producto.peso) + ' kg';
    } else {
      pesoContainer.style.display = 'none';
    }

    /* CARACTERÍSTICAS */
    const piezasCajaContainer = document.getElementById('detallePiezasCajaContainer');
    const m2CajaContainer = document.getElementById('detalleM2CajaContainer');
    const caracteristicas = document.getElementById('detalleCaracteristicas');

    let tieneCaracteristicas = false;

    if (Number(producto.piezas_caja || 0) > 0) {
      piezasCajaContainer.style.display = 'block';
      document.getElementById('detallePiezasCaja').textContent = formatear(producto.piezas_caja) + ' piezas';
      tieneCaracteristicas = true;
    } else {
      piezasCajaContainer.style.display = 'none';
    }

    if (Number(producto.m2_caja || 0) > 0) {
      m2CajaContainer.style.display = 'block';
      document.getElementById('detalleM2Caja').textContent = formatear(producto.m2_caja) + ' m²';
      tieneCaracteristicas = true;
    } else {
      m2CajaContainer.style.display = 'none';
    }

    caracteristicas.style.display = tieneCaracteristicas ? 'block' : 'none';

    /* EXISTENCIA */
    const stockCajas = document.getElementById('detalleStockCajas');
    const stockSimple = document.getElementById('detalleStockSimple');

    if (producto.tiene_cajas) {
      stockCajas.style.display = 'grid';
      stockSimple.style.display = 'none';
      document.getElementById('detalleCajasTotal').textContent = formatear(producto.cajas_total);
      document.getElementById('detallePiezasTotal').textContent = formatear(producto.piezas_total);
      document.getElementById('detalleM2Total').textContent = formatear(producto.m2_total);
    } else {
      stockCajas.style.display = 'none';
      stockSimple.style.display = 'block';
      document.getElementById('detalleStockSimpleCantidad').textContent = formatear(producto.stock_total);
      document.getElementById('detalleStockSimpleUnidad').textContent = producto.unidad || 'piezas';
    }

    /* CONTROL */
    document.getElementById('detalleMinimo').textContent =
      Number(producto.stock_minimo) > 0
        ? formatear(producto.stock_minimo) + ' ' + (producto.unidad || 'piezas')
        : 'Sin configurar';
    document.getElementById('detalleMaximo').textContent =
      Number(producto.stock_maximo) > 0
        ? formatear(producto.stock_maximo) + ' ' + (producto.unidad || 'piezas')
        : 'Sin configurar';
    document.getElementById('detalleUnidad').textContent = producto.unidad || 'piezas';

    /* ESTADO */
    const estadoElemento = document.getElementById('detalleEstado');
    estadoElemento.textContent = '';
    const iconoEstado = document.createElement('i');

    if (producto.estado === 'Normal') {
      estadoElemento.className = 'detalle-estado estado-normal';
      iconoEstado.className = 'mdi mdi-check-circle-outline';
    } else if (producto.estado === 'Stock bajo') {
      estadoElemento.className = 'detalle-estado estado-bajo';
      iconoEstado.className = 'mdi mdi-alert-outline';
    } else if (producto.estado === 'Sin stock') {
      estadoElemento.className = 'detalle-estado estado-sin-stock';
      iconoEstado.className = 'mdi mdi-close-circle-outline';
    } else if (producto.estado === 'Sobreexistencia') {
      estadoElemento.className = 'detalle-estado estado-sobre';
      iconoEstado.className = 'mdi mdi-alert-circle-outline';
    } else {
      estadoElemento.className = 'detalle-estado estado-sin-limites';
      iconoEstado.className = 'mdi mdi-tune-variant';
    }

    estadoElemento.appendChild(iconoEstado);
    estadoElemento.appendChild(document.createTextNode(' ' + producto.estado));

    /* STOCK POR DUEÑO */
    const contenedorAdmins = document.getElementById('detalleStockAdmins');
    contenedorAdmins.innerHTML = '';

    if (producto.admins && producto.admins.length > 0) {
      producto.admins.forEach(function (admin) {
        const fila = document.createElement('div');
        fila.className = 'detalle-admin';

        const nombre = document.createElement('div');
        nombre.className = 'detalle-admin-nombre';
        nombre.innerHTML = '<i class="mdi mdi-account-circle-outline"></i> ' + admin.nombre;

        const cantidad = document.createElement('div');
        cantidad.className = 'detalle-admin-cantidad';

        if (producto.tiene_cajas) {
          let textoCantidad =
            formatear(admin.cajas) + (Number(admin.cajas) === 1 ? ' caja' : ' cajas');
          textoCantidad +=
            '<br><small>' + formatear(admin.piezas) +
            (Number(admin.piezas) === 1 ? ' pieza' : ' piezas');
          if (Number(producto.m2_caja || 0) > 0) {
            textoCantidad += ' · ' + formatear(admin.m2) + ' m²';
          }
          textoCantidad += '</small>';
          cantidad.innerHTML = textoCantidad;
        } else {
          cantidad.textContent = formatear(admin.cantidad) + ' ' + (producto.unidad || 'piezas');
        }

        fila.appendChild(nombre);
        fila.appendChild(cantidad);
        contenedorAdmins.appendChild(fila);
      });
    } else {
      contenedorAdmins.innerHTML =
        '<div style="color:var(--ink-faint);font-size:0.82rem;">' +
        'No hay información de stock por dueño.' +
        '</div>';
    }
  });

});
</script>
@endpush