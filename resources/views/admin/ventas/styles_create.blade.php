
@push('styles')
<style>
  .compra-header {
    display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.75rem;
  }
  .compra-header .icon-wrap {
    width: 44px; height: 44px; border-radius: var(--r-md);
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); display: inline-flex; align-items: center;
    justify-content: center; font-size: 1.35rem;
    box-shadow: 0 6px 20px -6px var(--glow);
  }
  .compra-header h3 {
    font-family: var(--font-display); font-weight: 600;
    color: var(--ink); margin: 0;
  }
  .compra-header p { color: var(--ink-muted); margin: 0.15rem 0 0; font-size: 0.88rem; }

  .card-form {
    background: var(--bg-1); border: 1px solid var(--line);
    border-radius: var(--r-lg); overflow: hidden;
    box-shadow: 0 20px 40px -28px var(--shadow);
  }
  .card-form .card-head {
    padding: 1.1rem 1.35rem; border-bottom: 1px solid var(--line);
    background: linear-gradient(180deg, color-mix(in srgb, var(--cyan) 4%, transparent), transparent);
    font-family: var(--font-display); font-weight: 600;
    color: var(--ink); font-size: 0.98rem;
    display: flex; align-items: center; gap: 0.6rem;
  }
  .card-form .card-head .mdi { color: var(--cyan); font-size: 1.15rem; }
  .card-form .card-body { padding: 1.35rem; }

  .form-label {
    display: flex; align-items: center; gap: 0.35rem;
    font-size: 0.76rem; font-weight: 600; color: var(--ink-muted);
    margin-bottom: 0.45rem; font-family: var(--font-display);
    letter-spacing: 0.04em; text-transform: uppercase;
  }
  .form-label .required { color: var(--error); }
  .form-control, .form-select {
    width: 100%; padding: 0.7rem 0.9rem;
    border: 1px solid var(--line); border-radius: var(--r-md);
    background: var(--bg-2); color: var(--ink);
    font-family: inherit; font-size: 0.9rem;
    transition: all 0.25s var(--ease);
  }
  .form-control:focus, .form-select:focus {
    outline: none; border-color: var(--cyan);
    background: var(--bg-3);
    box-shadow: 0 0 0 3px var(--glow-soft), 0 0 18px -8px var(--glow);
  }
  .form-control.is-invalid, .form-select.is-invalid {
    border-color: var(--error) !important;
    box-shadow: 0 0 0 3px rgba(255, 85, 112, 0.15) !important;
  }
  .invalid-feedback { color: var(--error); font-size: 0.78rem; margin-top: 0.35rem; display: block; }

  /* ==================== LÍNEAS DE VENTA ==================== */
  .linea-venta {
    background: color-mix(in srgb, var(--bg-2) 40%, transparent);
    border: 1px solid var(--line);
    border-radius: var(--r-md);
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.3s var(--ease);
  }
  .linea-venta:hover { border-color: var(--line-strong); }

  .linea-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 0.85rem; gap: 0.75rem;
  }
  .linea-num {
    width: 28px; height: 28px; border-radius: var(--r-sm);
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); font-family: var(--font-display);
    font-weight: 700; font-size: 0.82rem;
    display: inline-flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .stock-info {
    display: flex; align-items: center; gap: 0.35rem;
    font-size: 0.72rem; color: var(--ink-muted);
    padding: 0.3rem 0.65rem; border-radius: var(--r-pill);
    background: var(--surface-alpha); border: 1px solid var(--line);
  }
  .stock-info .mdi { color: var(--cyan); font-size: 0.9rem; }
  .stock-info strong { color: var(--ink); font-family: var(--font-display); }

  /* Orígenes de stock */
  .origenes-section {
    border-top: 1px dashed var(--line);
    margin-top: 0.85rem; padding-top: 0.85rem;
  }
  .origenes-title {
    font-size: 0.72rem; font-weight: 700; color: var(--ink-muted);
    text-transform: uppercase; letter-spacing: 0.06em;
    font-family: var(--font-display); margin-bottom: 0.6rem;
    display: flex; align-items: center; gap: 0.4rem;
  }
  .origenes-title .mdi { color: var(--cyan); }

  .origen-item {
    display: grid; grid-template-columns: 1fr 140px 100px;
    align-items: center; gap: 0.75rem;
    padding: 0.55rem 0.75rem;
    border-radius: var(--r-sm);
    background: var(--bg-1);
    border: 1px solid var(--line);
    margin-bottom: 0.5rem;
  }
  .origen-item.es-propio {
    background: color-mix(in srgb, var(--cyan) 6%, transparent);
    border-color: var(--line-strong);
  }
  .origen-item .info {
    display: flex; align-items: center; gap: 0.55rem;
    min-width: 0;
  }
  .origen-item .info .mdi {
    color: var(--cyan); font-size: 1.05rem; flex-shrink: 0;
  }
  .origen-item .info .name {
    font-weight: 600; color: var(--ink); font-size: 0.85rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .origen-item .info .label {
    font-size: 0.68rem; color: var(--cyan);
    background: var(--glow-soft);
    padding: 0.1rem 0.45rem;
    border-radius: var(--r-pill);
    font-weight: 700; text-transform: uppercase;
  }
  .origen-item .disponible {
    font-size: 0.8rem; color: var(--ink-muted);
    text-align: right;
  }
  .origen-item .disponible strong {
    color: var(--ink); font-family: var(--font-display);
  }
  .origen-item input.cantidad-origen {
    background: var(--bg-2); border: 1px solid var(--line);
    color: var(--ink); border-radius: var(--r-sm);
    padding: 0.4rem 0.6rem; font-size: 0.85rem;
    width: 100%; text-align: right;
    transition: all 0.25s var(--ease);
    font-family: var(--font-display); font-weight: 600;
  }
  .origen-item input.cantidad-origen:focus {
    outline: none; border-color: var(--cyan);
    box-shadow: 0 0 0 2px var(--glow-soft);
  }
  .origen-item input.cantidad-origen.tiene-valor {
    border-color: var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 8%, transparent);
  }

  .alerta-stock {
    display: flex; align-items: flex-start; gap: 0.5rem;
    padding: 0.65rem 0.85rem; border-radius: var(--r-sm);
    font-size: 0.78rem; margin-top: 0.6rem;
  }
  .alerta-stock.ok {
    background: color-mix(in srgb, var(--cyan) 8%, transparent);
    color: var(--cyan);
    border: 1px solid var(--line-strong);
  }
  .alerta-stock.warn {
    background: color-mix(in srgb, var(--warning) 10%, transparent);
    color: var(--warning);
    border: 1px solid color-mix(in srgb, var(--warning) 40%, transparent);
  }
  .alerta-stock.danger {
    background: color-mix(in srgb, var(--error) 10%, transparent);
    color: var(--error);
    border: 1px solid color-mix(in srgb, var(--error) 40%, transparent);
  }
  .alerta-stock .mdi { font-size: 1.05rem; flex-shrink: 0; margin-top: 0.1rem; }

  .btn-remove-line {
    width: 30px; height: 30px; border-radius: var(--r-sm);
    border: 1px solid var(--line); background: var(--surface-alpha);
    color: var(--ink-muted); cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    transition: all 0.25s var(--ease);
  }
  .btn-remove-line:hover {
    color: var(--error);
    border-color: color-mix(in srgb, var(--error) 50%, transparent);
    background: color-mix(in srgb, var(--error) 10%, transparent);
  }

  .btn-add-line {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.55rem 1.05rem; border-radius: var(--r-pill);
    border: 1px dashed var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 5%, transparent);
    color: var(--cyan); font-weight: 700; font-size: 0.82rem;
    cursor: pointer; transition: all 0.3s var(--ease);
  }
  .btn-add-line:hover {
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    border-style: solid; transform: translateY(-1px);
    box-shadow: 0 6px 16px -6px var(--glow);
  }

  /* Totales */
  .totales-panel {
    background: var(--bg-2); border-radius: var(--r-md);
    padding: 1.15rem 1.35rem; border: 1px solid var(--line);
  }
  .total-row {
    display: flex; justify-content: space-between;
    align-items: center; padding: 0.45rem 0;
    font-size: 0.88rem; color: var(--ink-muted);
  }
  .total-row span:last-child {
    font-family: var(--font-display); font-weight: 600;
    color: var(--ink);
  }
  .total-row.grand {
    padding-top: 0.85rem; margin-top: 0.5rem;
    border-top: 1px solid var(--line);
    font-size: 1rem; color: var(--ink);
  }
  .total-row.grand span:last-child {
    font-size: 1.35rem; color: var(--cyan);
    text-shadow: 0 0 20px var(--glow-soft);
  }

  .btn-secondary-app {
    padding: 0.7rem 1.5rem; border-radius: var(--r-pill);
    border: 1px solid var(--line); background: var(--bg-2);
    color: var(--ink); font-weight: 700; font-size: 0.88rem;
    cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 0.4rem;
    transition: all 0.3s var(--ease);
  }
  .btn-secondary-app:hover {
    border-color: var(--line-strong); color: var(--cyan);
    transform: translateY(-2px);
  }
  .btn-primary-app {
    padding: 0.7rem 1.75rem; border-radius: var(--r-pill);
    border: none;
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); font-weight: 700; font-size: 0.88rem;
    cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;
    transition: all 0.3s var(--ease);
    box-shadow: 0 6px 20px -6px var(--glow);
  }
  .btn-primary-app:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px -6px var(--glow), 0 0 0 1px var(--cyan);
  }

  .spinner-inline {
    display: inline-block; width: 14px; height: 14px;
    border: 2px solid var(--line-strong);
    border-top-color: var(--cyan);
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

    .conversion-info {
    margin-top: 0.5rem;
    padding: 0.55rem 0.75rem;
    border-radius: var(--r-sm);
    background: color-mix(in srgb, var(--cyan) 6%, transparent);
    border: 1px solid var(--line);
    color: var(--ink-muted);
    font-size: 0.76rem;
    display: none;
  }

  .conversion-info strong {
    color: var(--cyan);
    font-family: var(--font-display);
  }

  .unidad-select {
  display: block;
}

.conversion-info {
  margin-top: 0.65rem;
  padding: 0.8rem 1rem;
  border-radius: var(--r-sm);
  background: color-mix(in srgb, var(--cyan) 6%, transparent);
  border: 1px solid var(--line);
  color: var(--ink-muted);
  font-size: 0.78rem;
  line-height: 1.65;
}

.conversion-info .conversion-titulo {
  display: flex;
  align-items: center;
  gap: 0.35rem;
  color: var(--cyan);
  font-family: var(--font-display);
  font-weight: 700;
  margin-bottom: 0.2rem;
}

.conversion-info .conversion-linea {
  display: flex;
  flex-wrap: wrap;
  gap: 0.35rem;
}

.conversion-info strong {
  color: var(--cyan);
  font-family: var(--font-display);
}

.conversion-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.2rem 0.5rem;
  border-radius: var(--r-pill);
  background: var(--bg-2);
  border: 1px solid var(--line);
  color: var(--ink);
  font-weight: 600;
}

.stock-conversion {
  display: flex;
  flex-direction: column;
  gap: 0.15rem;
  text-align: right;
}

.stock-conversion .stock-principal {
  color: var(--ink);
  font-family: var(--font-display);
  font-weight: 700;
}

.stock-conversion .stock-equivalencia {
  color: var(--ink-muted);
  font-size: 0.68rem;
}

.origen-unidad-label {
  display: block;
  margin-top: 0.1rem;
  color: var(--cyan);
  font-size: 0.68rem;
  font-weight: 700;
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
</style>
@endpush