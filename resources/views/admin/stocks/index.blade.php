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
     SCROLL HORIZONTAL PREMIUM (para móviles y tablets)
     ===================================================== */
  .tabla-scroll-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* Scroll suave en iOS */
    padding-bottom: 0.5rem;
    /* Estilo de la barra de scroll */
    scrollbar-width: thin;
    scrollbar-color: var(--cyan) var(--bg-2);
  }
  .tabla-scroll-wrapper::-webkit-scrollbar {
    height: 8px;
  }
  .tabla-scroll-wrapper::-webkit-scrollbar-track {
    background: var(--bg-2);
    border-radius: 4px;
  }
  .tabla-scroll-wrapper::-webkit-scrollbar-thumb {
    background: var(--cyan);
    border-radius: 4px;
  }
  .tabla-scroll-wrapper::-webkit-scrollbar-thumb:hover {
    background: var(--cyan-2);
  }

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
    background: var(--bg-2); color: var(--ink);
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
    /* Evitamos que se aplasten las columnas */
    white-space: nowrap; 
  }
  
  /* Si es móvil, forzamos un ancho mínimo para que se pueda scrollear */
  @media (max-width: 991.98px) {
    .panel table.dataTable {
      min-width: 850px; 
    }
  }

  .panel table.dataTable thead th {
    background: var(--bg-2) !important;
    color: var(--ink-muted) !important;
    font-family: var(--font-display);
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 0.75rem 0.6rem !important;
    border-bottom: 1px solid var(--line) !important;
    border-top: none !important;
    white-space: nowrap;
  }
  .panel table.dataTable thead th:first-child { border-top-left-radius: var(--r-sm); }
  .panel table.dataTable thead th:last-child  { border-top-right-radius: var(--r-sm); }

  /* Fijar primera columna (Producto) al hacer scroll horizontal */
  .panel table.dataTable th:first-child,
  .panel table.dataTable td:first-child {
    position: sticky;
    left: 0;
    background: var(--bg-1) !important;
    z-index: 2;
    /* Sombra para denotar que está fija */
    box-shadow: 2px 0 5px -2px rgba(0,0,0,0.1);
  }
  .panel table.dataTable tr.odd td:first-child {
    background: color-mix(in srgb, var(--bg-2) 40%, var(--bg-1)) !important;
  }
  .panel table.dataTable tr.row-normal td:first-child { background: color-mix(in srgb, var(--success) 3%, var(--bg-1)) !important; }
  .panel table.dataTable tr.row-bajo td:first-child { background: color-mix(in srgb, var(--warning) 4%, var(--bg-1)) !important; }
  .panel table.dataTable tr.row-sinstock td:first-child { background: color-mix(in srgb, var(--error) 5%, var(--bg-1)) !important; }
  .panel table.dataTable tr.row-sobre td:first-child { background: color-mix(in srgb, #ff8c42 4%, var(--bg-1)) !important; }

  .panel table.dataTable tbody td {
    padding: 0.65rem 0.6rem !important;
    border-top: 1px solid var(--line) !important;
    color: var(--ink) !important;
    background: transparent !important;
    font-size: 0.82rem;
    vertical-align: middle;
  }
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

  /* =====================================================
     ACENTO LATERAL POR ESTADO
     ===================================================== */
  .panel table.dataTable tbody tr.row-estado > td:first-child {
    position: sticky; /* combinado con sticky left */
    left: 0;
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
  .panel table.dataTable tbody tr.row-normal:hover { background: color-mix(in srgb, var(--success) 6%, transparent) !important; }

  .panel table.dataTable tbody tr.row-bajo {
    --row-accent: var(--warning);
    --row-accent-glow: rgba(255, 181, 71, 0.4);
    background: color-mix(in srgb, var(--warning) 4%, transparent) !important;
  }
  .panel table.dataTable tbody tr.row-bajo:hover { background: color-mix(in srgb, var(--warning) 8%, transparent) !important; }

  .panel table.dataTable tbody tr.row-sinstock {
    --row-accent: var(--error);
    --row-accent-glow: rgba(255, 77, 109, 0.5);
    background: color-mix(in srgb, var(--error) 5%, transparent) !important;
  }
  .panel table.dataTable tbody tr.row-sinstock:hover { background: color-mix(in srgb, var(--error) 9%, transparent) !important; }

  .panel table.dataTable tbody tr.row-sobre {
    --row-accent: #ff8c42;
    --row-accent-glow: rgba(255, 140, 66, 0.5);
    background: color-mix(in srgb, #ff8c42 4%, transparent) !important;
  }
  .panel table.dataTable tbody tr.row-sobre:hover { background: color-mix(in srgb, #ff8c42 8%, transparent) !important; }

  .panel table.dataTable tbody tr.row-sinlimites {
    --row-accent: var(--ink-faint);
    --row-accent-glow: rgba(138, 152, 184, 0.3);
  }

  /* =====================================================
     CELDAS
     ===================================================== */
  .cell-product-name {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.85rem;
    display: block;
    max-width: 220px;
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

  .cell-price {
    font-size: 0.78rem;
    line-height: 1.45;
    color: var(--ink-muted);
    min-width: 120px;
    white-space: normal;
  }
  .cell-price strong { color: var(--ink); font-weight: 600; }
  .cell-price .sin-config { color: var(--ink-faint); font-style: italic; }

  .cell-stock {
    text-align: right;
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 0.82rem;
    color: var(--ink);
    min-width: 70px;
  }
  .cell-stock .unidad {
    display: block;
    font-family: var(--font-body);
    font-size: 0.65rem;
    color: var(--ink-faint);
    font-weight: 500;
    margin-top: 1px;
  }
  .cell-stock.zero { color: var(--ink-faint); font-weight: 500; }

  .cell-total {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--cyan);
    text-align: right;
  }
  .cell-total .unidad {
    display: block;
    font-family: var(--font-body);
    font-size: 0.65rem;
    color: var(--ink-faint);
    font-weight: 500;
    margin-top: 1px;
  }

  .cell-limit {
    text-align: right;
    font-size: 0.78rem;
    color: var(--ink-muted);
    font-family: var(--font-display);
    font-weight: 600;
    min-width: 90px;
    white-space: nowrap;
  }
  .cell-limit.sin-config {
    color: var(--ink-faint);
    font-style: italic;
    font-weight: 500;
  }
  .cell-limit .unidad {
    display: block;
    font-family: var(--font-body);
    font-size: 0.65rem;
    color: var(--ink-faint);
    font-weight: 500;
    margin-top: 1px;
  }

  /* =====================================================
     BADGE ESTADO
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
    width: 5px; height: 5px;
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
  .estado-badge.estado-sin-limites {
    color: var(--ink-faint);
    background: color-mix(in srgb, var(--ink-faint) 12%, transparent);
    border: 1px dashed color-mix(in srgb, var(--ink-faint) 40%, transparent);
  }

  /* =====================================================
     ACCIONES
     ===================================================== */
  .actions-cell {
    display: flex;
    gap: 0.3rem;
    justify-content: center;
    align-items: center;
  }
  .action-btn {
    width: 32px; height: 32px;
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
    opacity: 0.8;
    text-decoration: none;
  }
  .panel table.dataTable tbody tr:hover .action-btn { opacity: 1; }
  .action-btn:hover { transform: translateY(-2px); }
  .action-btn.view:hover,
  .action-btn.tune:hover,
  .action-btn.price:hover {
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
    width: 48px; height: 48px;
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
     OTROS ADMINS (modal ojo)
     ===================================================== */
  .otros-admin-card {
    border: 1px solid var(--line);
    border-radius: var(--r-md);
    background: var(--bg-2);
    padding: 0.85rem 1rem;
    margin-bottom: 0.75rem;
  }
  .otros-admin-card:last-child { margin-bottom: 0; }
  .otros-admin-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.6rem;
    flex-wrap: wrap;
  }
  .otros-admin-name {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-weight: 600;
    color: var(--ink);
    font-size: 0.88rem;
  }
  .otros-admin-name .mdi { color: var(--cyan); font-size: 1.05rem; }
  .otros-admin-total {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--cyan);
    font-size: 0.85rem;
  }
  .otros-admin-precio {
    font-size: 0.75rem;
    color: var(--ink-muted);
  }
  .otros-admin-depositos {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 0.45rem;
    margin-top: 0.4rem;
  }
  .otros-admin-dep {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    font-size: 0.78rem;
    padding: 0.35rem 0.55rem;
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-sm);
  }
  .otros-admin-dep .dep-nombre {
    color: var(--ink-muted);
    font-size: 0.72rem;
    font-weight: 600;
  }
  .otros-admin-dep .dep-cantidad {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--ink);
  }
  .otros-admin-dep.zero .dep-cantidad { color: var(--ink-faint); font-weight: 500; }

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
     RESPONSIVE GENERAL
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
    .stocks-header h3 { font-size: 1.15rem; }
    .otros-admin-depositos { grid-template-columns: 1fr 1fr; }
  }

  @media (max-width: 575.98px) {
    .stocks-header h3 .icon-wrap {
      width: 38px; height: 38px; font-size: 1.15rem;
    }
    .stocks-header h3 { font-size: 1.05rem; }
    .panel-body { padding: 0.9rem 1rem; }
    .otros-admin-depositos { grid-template-columns: 1fr; }
  }
</style>
@endpush


@section('content')
@php
  $cantidadFmt = function ($cantidad) {
      return rtrim(rtrim(number_format((float) $cantidad, 2, '.', ''), '0'), '.');
  };
@endphp

<div class="container-fluid">

  {{-- HEADER --}}
  <div class="stocks-header">
    <div>
      <h3>
        <span class="icon-wrap"><i class="mdi mdi-warehouse"></i></span>
        Mi inventario
      </h3>
      <p>Stock, precios y límites de tus productos por depósito</p>
    </div>
  </div>


  {{-- PANEL --}}
  <div class="panel">
    <div class="panel-body">
      
      {{-- WRAPPER DE SCROLL HORIZONTAL --}}
      <div class="tabla-scroll-wrapper">
        <table id="tablaStocks" class="table table-sm" style="width:100%">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Precios</th>
              @foreach($depositos as $dep)
                <th class="text-end">{{ $dep->nombre }}</th>
              @endforeach
              <th class="text-end">Total</th>
              <th class="text-end">Mín / Máx</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
          @forelse($inventario as $item)
            @php
              $producto  = $item['producto'];
              $config    = $item['config'];
              $totalPropio = (float) $item['stock_total_propio'];

              $tipoNombre = mb_strtolower(trim((string) ($producto->tipoProducto?->nombre ?? '')), 'UTF-8');
              $piezasPorCaja = (float) ($producto->piezas_por_caja ?? 0);
              $tieneCajas = $piezasPorCaja > 0 && in_array($tipoNombre, ['cerámica','ceramica','porcelanato']);

              $tipoPrecio = $tipoNombre;
              $esCeramicaPrecio = in_array($tipoPrecio, ['cerámica','ceramica','porcelanato']);
              $esBolsaPrecio = str_contains($tipoPrecio, 'cemento cola') || str_contains($tipoPrecio, 'pastina');
              $esPiezaPrecio = in_array($tipoPrecio, ['listelo','randa decorativa','esquinero de aluminio','esquinero de goma']);

              $min = $config ? (float) $config->stock_minimo : 0;
              $max = $config ? (float) $config->stock_maximo : 0;

              $unidad = $tieneCajas ? 'cajas' : ($esBolsaPrecio ? 'bolsas' : 'piezas');

              $pCaja  = $config?->precio_venta_caja;
              $pPieza = $config?->precio_venta_pieza;
              $pBolsa = $config?->precio_venta_bolsa;

              $detalleModal = [
                'producto' => [
                  'id'     => $producto->id,
                  'nombre' => $producto->nombre,
                  'marca'  => $producto->marca,
                  'medida' => $producto->medida,
                ],
                'depositos' => $depositos->map(fn($d) => [
                  'id' => $d->id,
                  'nombre' => $d->nombre,
                ])->values(),
                'otros_admins' => $item['otros_admins'],
              ];

              $estado      = $item['estado']       ?? 'Normal';
              $estadoClase = $item['estado_clase'] ?? 'normal';
              $filaClase   = $item['fila_clase']   ?? 'row-normal';
              $estadoIcono = $item['estado_icono'] ?? 'mdi-check-circle-outline';
            @endphp

            <tr class="row-estado {{ $filaClase }}">
              {{-- PRODUCTO --}}
              <td>
                <span class="cell-product-name" title="{{ $producto->nombre }}">
                  {{ $producto->nombre }}
                </span>
                @if($producto->marca || $producto->modelo || $producto->medida)
                  <span class="cell-product-meta">
                    @if($producto->marca)
                      <i class="mdi mdi-trademark"></i>{{ $producto->marca }}
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
              </td>

              {{-- PRECIOS --}}
              <td>
                <div class="cell-price">
                  @if($esCeramicaPrecio)
                    <div>Caja: <strong>{{ $pCaja !== null ? number_format((float)$pCaja, 2, ',', '.') . ' Bs' : '—' }}</strong></div>
                    <div>Pieza: <strong>{{ $pPieza !== null ? number_format((float)$pPieza, 2, ',', '.') . ' Bs' : '—' }}</strong></div>
                  @elseif($esBolsaPrecio)
                    <div>Bolsa: <strong>{{ $pBolsa !== null ? number_format((float)$pBolsa, 2, ',', '.') . ' Bs' : '—' }}</strong></div>
                  @elseif($esPiezaPrecio)
                    <div>Pieza: <strong>{{ $pPieza !== null ? number_format((float)$pPieza, 2, ',', '.') . ' Bs' : '—' }}</strong></div>
                  @else
                    <span class="sin-config">Revisar tipo</span>
                  @endif
                </div>
              </td>

              {{-- STOCK POR DEPÓSITO --}}
              @foreach($depositos as $dep)
                @php
                  $cantDep = (float) ($item['stock_por_deposito']->get($dep->id) ?? 0);
                @endphp
                <td>
                  <div class="cell-stock {{ $cantDep <= 0 ? 'zero' : '' }}">
                    {{ $cantidadFmt($cantDep) }}
                    <span class="unidad">{{ $unidad }}</span>
                  </div>
                </td>
              @endforeach

              {{-- TOTAL --}}
              <td>
                <div class="cell-total">
                  {{ $cantidadFmt($totalPropio) }}
                  <span class="unidad">{{ $unidad }}</span>
                </div>
              </td>

              {{-- MIN / MAX UNIFICADO --}}
              <td>
                <div class="cell-limit {{ ($min <= 0 && $max <= 0) ? 'sin-config' : '' }}">
                  @if($min > 0 || $max > 0)
                    {{ $min > 0 ? $cantidadFmt($min) : '—' }}
                    /
                    {{ $max > 0 ? $cantidadFmt($max) : '—' }}
                    <span class="unidad">{{ $unidad }}</span>
                  @else
                    Sin configurar
                  @endif
                </div>
              </td>

              {{-- ACCIONES --}}
              <td>
                <div class="actions-cell">
                  {{-- OJO → modal otros admins --}}
                  <button
                    type="button"
                    class="action-btn view"
                    title="Ver otros administradores"
                    data-bs-toggle="modal"
                    data-bs-target="#modalOtrosAdmins"
                    data-detalle='@json($detalleModal)'
                  >
                    <i class="mdi mdi-eye-outline"></i>
                  </button>

                  {{-- TUNE → límites --}}
                  <button
                    type="button"
                    class="action-btn tune"
                    title="Configurar límites"
                    data-bs-toggle="modal"
                    data-bs-target="#modalLimites"
                    data-id="{{ $producto->id }}"
                    data-nombre="{{ $producto->nombre }}"
                    data-minimo="{{ $min }}"
                    data-maximo="{{ $max }}"
                    data-unidad="{{ $unidad }}"
                  >
                    <i class="mdi mdi-tune-variant"></i>
                  </button>

                  {{-- PRICE → precios --}}
                  <button
                    type="button"
                    class="action-btn price"
                    title="Configurar precios de venta"
                    data-bs-toggle="modal"
                    data-bs-target="#modalPrecios"
                    data-id="{{ $producto->id }}"
                    data-nombre="{{ $producto->nombre }}"
                    data-tipo="{{ $tipoNombre }}"
                    data-caja="{{ $pCaja }}"
                    data-m2="{{ $config?->precio_venta_m2 }}"
                    data-pieza="{{ $pPieza }}"
                    data-bolsa="{{ $pBolsa }}"
                    data-compra="{{ $item['precio_compra'] ?? '' }}"
                    data-unidad-compra="{{ $item['unidad_precio_compra'] ?? '' }}"
                  >
                    <i class="mdi mdi-currency-usd"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ 4 + $depositos->count() + 1 }}" class="text-center py-5">
                <i class="mdi mdi-warehouse" style="font-size:44px;opacity:0.3;color:var(--ink-faint);"></i>
                <p class="mt-2 mb-0" style="color:var(--ink-muted);">Aún no tienes productos en tu inventario.</p>
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


{{-- MODALES (OTROS ADMINS, LÍMITES Y PRECIOS) SE MANTIENEN IGUAL QUE EN TU ÚLTIMA VERSIÓN --}}
{{-- ... Pega aquí los 3 modales tal como los tenías ... --}}
<div class="modal fade" id="modalOtrosAdmins" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header ameri-modal-header">
        <div class="d-flex align-items-center gap-3">
          <div class="ameri-modal-icon"><i class="mdi mdi-account-group-outline"></i></div>
          <div>
            <h5 class="modal-title">Otros administradores</h5>
            <p class="ameri-modal-sub" id="otrosAdminsProducto"></p>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div id="otrosAdminsLista"></div>
      </div>
      <div class="modal-footer ameri-modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalLimites" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formLimites" method="POST">
        @csrf @method('PATCH')
        <div class="modal-header ameri-modal-header">
          <div class="d-flex align-items-center gap-3">
            <div class="ameri-modal-icon"><i class="mdi mdi-tune-variant"></i></div>
            <div>
              <h5 class="modal-title">Configurar límites</h5>
              <p class="ameri-modal-sub">Define el stock mínimo y máximo para tu inventario</p>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p class="mb-4" style="color:var(--ink-muted);font-size:0.9rem;">
            Producto: <strong id="limitesNombreProducto" style="color:var(--ink);"></strong>
          </p>
          <div class="mb-3">
            <label class="form-label">Stock mínimo <span id="unidadMinimo" style="color:var(--cyan);font-weight:700;">piezas</span></label>
            <input type="number" name="stock_minimo" id="limitesMinimo" class="form-control" min="0" step="0.01" required>
          </div>
          <div class="mb-0">
            <label class="form-label">Stock máximo <span id="unidadMaximo" style="color:var(--cyan);font-weight:700;">piezas</span></label>
            <input type="number" name="stock_maximo" id="limitesMaximo" class="form-control" min="0" step="0.01" required>
          </div>
        </div>
        <div class="modal-footer ameri-modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save-outline me-1"></i> Guardar límites</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalPrecios" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formPrecios" method="POST">
        @csrf @method('PATCH')
        <div class="modal-header ameri-modal-header">
          <div class="d-flex align-items-center gap-3">
            <div class="ameri-modal-icon"><i class="mdi mdi-currency-usd"></i></div>
            <div>
              <h5 class="modal-title">Precios de venta</h5>
              <p class="ameri-modal-sub">Configura tus precios para este producto</p>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p class="mb-4" style="color:var(--ink-muted);font-size:0.9rem;">
            Producto: <strong id="preciosNombreProducto" style="color:var(--ink);"></strong>
          </p>
          <div class="mb-4 p-3" style="background:var(--bg-2);border:1px solid var(--line);border-radius:12px;">
            <div class="d-flex align-items-center gap-2 mb-2">
              <i class="mdi mdi-information-outline" style="font-size:1.2rem;color:var(--cyan);"></i>
              <strong style="color:var(--ink);font-size:0.88rem;">Precio de compra</strong>
            </div>
            <div id="precioCompraReferencia" style="font-size:1.15rem;font-weight:700;color:var(--ink);">Sin registro</div>
          </div>
          <div class="mb-3" id="campoPrecioCaja">
            <label class="form-label" for="precioVentaCaja">Precio por caja (Bs)</label>
            <input type="number" name="precio_venta_caja" id="precioVentaCaja" class="form-control" min="0.01" step="0.01" placeholder="Ej. 250.00">
          </div>
          <div class="mb-3" id="campoPrecioM2">
            <label class="form-label" for="precioVentaM2">Precio por m² (Bs)</label>
            <input type="number" name="precio_venta_m2" id="precioVentaM2" class="form-control" min="0.01" step="0.01" placeholder="Ej. 45.00">
          </div>
          <div class="mb-3" id="campoPrecioPieza">
            <label class="form-label" for="precioVentaPieza">Precio por pieza (Bs)</label>
            <input type="number" name="precio_venta_pieza" id="precioVentaPieza" class="form-control" min="0.01" step="0.01" placeholder="Ej. 12.00">
          </div>
          <div class="mb-0" id="campoPrecioBolsa">
            <label class="form-label" for="precioVentaBolsa">Precio por bolsa (Bs)</label>
            <input type="number" name="precio_venta_bolsa" id="precioVentaBolsa" class="form-control" min="0.01" step="0.01" placeholder="Ej. 35.00">
          </div>
        </div>
        <div class="modal-footer ameri-modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save-outline me-1"></i> Guardar precios</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // ... (Mismo script de DataTables y Modales que ya tienes) ...
  /* ==================== DATATABLES ==================== */
  const dtEspanol = {
    decimal: ',',
    emptyTable: 'No hay productos en tu inventario',
    info: 'Mostrando _START_ a _END_ de _TOTAL_ productos',
    infoEmpty: 'Mostrando 0 a 0 de 0 productos',
    infoFiltered: '(filtrado de _MAX_ productos totales)',
    lengthMenu: 'Mostrar _MENU_',
    loadingRecords: 'Cargando...', processing: 'Procesando...',
    search: 'Buscar:', zeroRecords: 'No se encontraron resultados',
    paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' },
  };

  const totalColumnas = $('#tablaStocks thead th').length;
  const colAcciones = totalColumnas - 1;

  $('#tablaStocks').DataTable({
    language: dtEspanol,
    pageLength: 7,
    lengthMenu: [[7, 10, 25, 50, -1], [7, 10, 25, 50, 'Todos']],
    order: [[0, 'asc']],
    autoWidth: false, // Importante dejarlo en false para que el CSS controle el ancho
    columnDefs: [
      { orderable: false, targets: [colAcciones] }
    ],
    dom: '<"dt-top"lf>t<"dt-bottom"ip>',
    // AQUÍ ESTÁ EL TRUCO: Quitamos el "responsive" que oculta columnas
    // y dejamos que el CSS del wrapper maneje el scroll horizontal.
  });

  // ... (Aquí siguen tus listeners de modales: limites, precios y otrosAdmins) ...
  // Los omito por brevedad pero asegúrate de mantenerlos.
  
  /* ==================== TOASTR ==================== */
  toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-right', timeOut: 4500 };
  @if(session('success')) toastr.success(@json(session('success')), '¡Listo!'); @endif
  @if(session('error')) toastr.error(@json(session('error')), 'Error'); @endif

  const modalLimites = document.getElementById('modalLimites');
  modalLimites.addEventListener('show.bs.modal', function (event) {
    const b = event.relatedTarget;
    document.getElementById('limitesNombreProducto').textContent = b.getAttribute('data-nombre');
    document.getElementById('limitesMinimo').value = b.getAttribute('data-minimo');
    document.getElementById('limitesMaximo').value = b.getAttribute('data-maximo');
    const u = b.getAttribute('data-unidad') || 'piezas';
    document.getElementById('unidadMinimo').textContent = u;
    document.getElementById('unidadMaximo').textContent = u;
    document.getElementById('formLimites').action = "{{ url('/admin/stocks/productos') }}/" + b.getAttribute('data-id') + "/limites";
  });

  const modalPrecios = document.getElementById('modalPrecios');
  modalPrecios.addEventListener('show.bs.modal', function (event) {
    const b = event.relatedTarget;
    const tipo = (b.getAttribute('data-tipo') || '').trim().toLocaleLowerCase('es');
    document.getElementById('preciosNombreProducto').textContent = b.getAttribute('data-nombre');
    
    const precioCompra = b.getAttribute('data-compra');
    const unidadCompra = b.getAttribute('data-unidad-compra');
    const ref = document.getElementById('precioCompraReferencia');
    if (precioCompra && !isNaN(Number(precioCompra))) {
      ref.textContent = Number(precioCompra).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Bs' + (unidadCompra ? ' por ' + unidadCompra : '');
    } else { ref.textContent = 'Sin registro'; }

    const campos = { caja: document.getElementById('campoPrecioCaja'), m2: document.getElementById('campoPrecioM2'), pieza: document.getElementById('campoPrecioPieza'), bolsa: document.getElementById('campoPrecioBolsa') };
    Object.values(campos).forEach(c => { c.style.display = 'none'; const i = c.querySelector('input'); if (i) i.disabled = true; });
    
    let unidades = [];
    if (['cerámica', 'ceramica', 'porcelanato'].includes(tipo)) unidades = ['caja', 'pieza'];
    else if (tipo.includes('cemento cola') || tipo.includes('pastina')) unidades = ['bolsa'];
    else if (['listelo','randa decorativa','esquinero de aluminio','esquinero de goma'].includes(tipo)) unidades = ['pieza'];
    
    unidades.forEach(u => { campos[u].style.display = 'block'; const i = campos[u].querySelector('input'); if (i) i.disabled = false; });
    
    document.getElementById('precioVentaCaja').value  = b.getAttribute('data-caja') || '';
    document.getElementById('precioVentaM2').value    = b.getAttribute('data-m2') || '';
    document.getElementById('precioVentaPieza').value = b.getAttribute('data-pieza') || '';
    document.getElementById('precioVentaBolsa').value = b.getAttribute('data-bolsa') || '';
    
    document.getElementById('formPrecios').action = "{{ url('/admin/stocks/productos') }}/" + b.getAttribute('data-id') + "/precios";
  });

  const modalOtros = document.getElementById('modalOtrosAdmins');
  modalOtros.addEventListener('show.bs.modal', function (event) {
    const b = event.relatedTarget;
    const raw = b.getAttribute('data-detalle');
    const contenedor = document.getElementById('otrosAdminsLista');
    const titulo = document.getElementById('otrosAdminsProducto');
    contenedor.innerHTML = '';
    if (!raw) return;
    let data;
    try { data = JSON.parse(raw); } catch (e) { return; }
    titulo.textContent = data.producto?.nombre || '';
    if (!data.otros_admins || data.otros_admins.length === 0) {
      contenedor.innerHTML = '<div style="color:var(--ink-muted);font-size:0.9rem;text-align:center;padding:1rem;">Ningún otro administrador tiene stock de este producto.</div>';
      return;
    }
    const fmt = function (n) { return Number(n).toLocaleString('es-BO', { minimumFractionDigits: 0, maximumFractionDigits: 2 }); };
    data.otros_admins.forEach(function (a) {
      const card = document.createElement('div');
      card.className = 'otros-admin-card';
      let preciosTxt = '';
      if (a.precio_caja !== null && a.precio_caja !== undefined) preciosTxt += 'Caja: ' + fmt(a.precio_caja) + ' Bs';
      if (a.precio_pieza !== null && a.precio_pieza !== undefined) { if (preciosTxt) preciosTxt += ' · '; preciosTxt += 'Pieza: ' + fmt(a.precio_pieza) + ' Bs'; }
      if (a.precio_bolsa !== null && a.precio_bolsa !== undefined) { if (preciosTxt) preciosTxt += ' · '; preciosTxt += 'Bolsa: ' + fmt(a.precio_bolsa) + ' Bs'; }
      
      let depositosHtml = '';
      a.por_deposito.forEach(function (d) {
        const c = Number(d.cantidad) || 0;
        depositosHtml += '<div class="otros-admin-dep ' + (c <= 0 ? 'zero' : '') + '"><span class="dep-nombre">' + d.nombre + '</span><span class="dep-cantidad">' + fmt(c) + '</span></div>';
      });
      
      card.innerHTML = '<div class="otros-admin-head"><div class="otros-admin-name"><i class="mdi mdi-account-circle-outline"></i>' + a.nombre + '<span class="otros-admin-precio">' + (preciosTxt || 'Sin precios configurados') + '</span></div><div class="otros-admin-total">Total: ' + fmt(a.stock_total) + '</div></div><div class="otros-admin-depositos">' + depositosHtml + '</div>';
      contenedor.appendChild(card);
    });
  });

});
</script>
@endpush