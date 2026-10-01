@extends('layouts.plantilla_maestra')

@section('title', 'Nueva Venta')

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

@section('content')
<div class="container-fluid">

  <div class="compra-header">
    <span class="icon-wrap"><i class="mdi mdi-cart-plus"></i></span>
    <div>
      <h3>Nueva Venta</h3>
      <p>Registra una venta y selecciona de qué admin tomar el stock</p>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-danger mb-3">
      <i class="mdi mdi-alert-circle me-1"></i>
      Corrige los errores marcados abajo.
      <ul class="mb-0 mt-2">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.ventas.store') }}" method="POST" id="formVenta" novalidate>
    @csrf

    {{-- ============ DATOS DEL CLIENTE ============ --}}
    <div class="card-form mb-4">
      <div class="card-head">
        <i class="mdi mdi-account-box-outline"></i> Datos del cliente
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-5 mb-3">
            <label class="form-label">Nombre del cliente <span class="required">*</span></label>
            <input type="text" name="cliente_nombre" class="form-control @error('cliente_nombre') is-invalid @enderror"
                   value="{{ old('cliente_nombre') }}" required placeholder="Ej: Juan Pérez">
            @error('cliente_nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">CI / NIT <span class="required">*</span></label>
            <input type="text" name="cliente_documento" class="form-control @error('cliente_documento') is-invalid @enderror"
                   value="{{ old('cliente_documento') }}" required placeholder="Ej: 12345678">
            @error('cliente_documento') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3 mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="cliente_telefono" class="form-control"
                   value="{{ old('cliente_telefono') }}" placeholder="Opcional">
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Fecha de venta <span class="required">*</span></label>
            <input type="date" name="fecha_venta" class="form-control @error('fecha_venta') is-invalid @enderror"
                   value="{{ old('fecha_venta', now()->format('Y-m-d')) }}" required>
            @error('fecha_venta') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">N° Venta</label>
            <input type="text" class="form-control" value="V-{{ now()->year }}-####" readonly
                   style="font-family:var(--font-display);font-weight:600;color:var(--cyan);">
            <small class="text-muted">Se genera automáticamente.</small>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Descuento (Bs.)</label>
            <input type="number" name="descuento" min="0" step="0.01" id="inputDescuento"
                   class="form-control" value="{{ old('descuento', 0) }}">
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" rows="2" class="form-control"
                      placeholder="Notas sobre esta venta...">{{ old('observaciones') }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- ============ PRODUCTOS ============ --}}
    <div class="card-form mb-4">
      <div class="card-head">
        <i class="mdi mdi-format-list-bulleted"></i> Productos de la venta
      </div>
      <div class="card-body">

        @error('items') <div class="alert alert-danger mb-3">{{ $message }}</div> @enderror

        <div id="contenedorLineas"></div>

        <button type="button" class="btn-add-line" id="btnAgregarLinea">
          <i class="mdi mdi-plus"></i> Agregar producto
        </button>
      </div>
    </div>

    {{-- ============ TOTALES ============ --}}
    <div class="row">
      <div class="col-md-6 offset-md-6">
        <div class="totales-panel">
          <div class="total-row">
            <span>Subtotal</span>
            <span id="txtSubtotal">Bs. 0.00</span>
          </div>
          <div class="total-row">
            <span>Descuento</span>
            <span id="txtDescuento">Bs. 0.00</span>
          </div>
          <div class="total-row grand">
            <span>Total a pagar</span>
            <span id="txtTotal">Bs. 0.00</span>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
          <a href="{{ route('admin.ventas.index') }}" class="btn-secondary-app">
            <i class="mdi mdi-close"></i> Cancelar
          </a>
          <button type="submit" class="btn-primary-app" id="btnGuardar">
            <i class="mdi mdi-content-save"></i> Guardar como pendiente
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

{{-- Template de línea --}}
<template id="templateLinea">
  <div class="linea-venta" data-linea>
    <div class="linea-head">
      <div class="d-flex align-items-center gap-2">
        <span class="linea-num" data-num>1</span>
        <strong style="font-family:var(--font-display);color:var(--ink);font-size:0.9rem;">Producto</strong>
      </div>
      <button type="button" class="btn-remove-line" data-remove title="Quitar">
        <i class="mdi mdi-close"></i>
      </button>
    </div>

    <div class="row">
      <div class="col-md-5 mb-2">
        <label class="form-label">Producto <span class="required">*</span></label>
        <select class="form-select producto-select" required>
          <option value="">Seleccione un producto...</option>
          @foreach($stocks as $s)
            <option value="{{ $s->producto->id }}"
                    data-stock-propio="{{ (float) $s->cantidad }}"
                    data-unidad-base="{{ $s->producto->unidad_base }}">
              {{ $s->producto->nombre }}
              @if($s->producto->marca) · {{ $s->producto->marca }} @endif
              — <strong>{{ (float) $s->cantidad }}</strong> {{ $s->producto->unidad_base }} disponibles
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3 mb-2">
        <label class="form-label">Cantidad <span class="required">*</span></label>
        <input type="number" class="form-control cantidad-input" min="0.01" step="0.01" value="1" required>
      </div>

      <div class="col-md-4 mb-2">
        <label class="form-label">Precio unitario <span class="required">*</span></label>
        <input type="number" class="form-control precio-input" min="0.01" step="0.01" required placeholder="0.00">
      </div>
    </div>

    <div class="origenes-section" style="display:none;">
      <div class="origenes-title">
        <i class="mdi mdi-source-branch"></i>
        Selecciona de qué admin tomar el stock
      </div>
      <div class="origenes-lista"></div>
      <div class="alerta-stock"></div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-2">
      <small class="text-muted">Subtotal línea:</small>
      <span class="linea-subtotal" style="font-family:var(--font-display);font-weight:700;color:var(--cyan);font-size:1rem;">Bs. 0.00</span>
    </div>
  </div>
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const contenedor = document.getElementById('contenedorLineas');
  const template   = document.getElementById('templateLinea');
  const btnAgregar = document.getElementById('btnAgregarLinea');
  const inputDesc  = document.getElementById('inputDescuento');
  const txtSubtotal = document.getElementById('txtSubtotal');
  const txtDesc     = document.getElementById('txtDescuento');
  const txtTotal    = document.getElementById('txtTotal');

  let numLinea = 0;

  function fmt(n) {
    return 'Bs. ' + Number(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  /* ==================== CARGAR STOCK DE UN PRODUCTO ==================== */
  async function cargarStockProducto(linea, productoId) {
    const origenesSection = linea.querySelector('.origenes-section');
    const lista = linea.querySelector('.origenes-lista');
    const alerta = linea.querySelector('.alerta-stock');

    if (!productoId) {
      origenesSection.style.display = 'none';
      return;
    }

    lista.innerHTML = '<div style="padding:1rem;text-align:center;"><span class="spinner-inline"></span> Cargando stock...</div>';
    origenesSection.style.display = '';

    try {
      const res = await fetch(`{{ url('admin/ventas/stock-producto') }}/${productoId}`);
      const data = await res.json();

      lista.innerHTML = '';

      data.stocks.forEach(s => {
        const item = document.createElement('div');
        item.className = 'origen-item' + (s.es_propio ? ' es-propio' : '');
        item.dataset.userId = s.user_id;

        item.innerHTML = `
          <div class="info">
            <i class="mdi mdi-account-circle"></i>
            <span class="name">${s.user_name}</span>
            ${s.es_propio ? '<span class="label">Mi stock</span>' : ''}
          </div>
          <div class="disponible">
            <strong>${s.cantidad}</strong> ${data.producto.unidad_base}
          </div>
          <input type="number" class="cantidad-origen" min="0" step="0.01" value="0"
                 data-max="${s.cantidad}" data-user-id="${s.user_id}">
        `;

        lista.appendChild(item);
      });

      // Eventos en los inputs de cantidad
      lista.querySelectorAll('.cantidad-origen').forEach(input => {
        input.addEventListener('input', function () {
          const max = parseFloat(this.dataset.max) || 0;
          const val = parseFloat(this.value) || 0;

          if (val > max) {
            this.value = max;
          }

          if (val > 0) {
            this.classList.add('tiene-valor');
          } else {
            this.classList.remove('tiene-valor');
          }

          validarOrigenes(linea);
          recalcular();
        });
      });

      validarOrigenes(linea);
      recalcular();

    } catch (err) {
      lista.innerHTML = '<div style="padding:1rem;color:var(--error);">Error al cargar el stock.</div>';
    }
  }

  /* ==================== VALIDAR ORÍGENES ==================== */
  function validarOrigenes(linea) {
    const alerta = linea.querySelector('.alerta-stock');
    const cantidad = parseFloat(linea.querySelector('.cantidad-input').value) || 0;
    const inputs = linea.querySelectorAll('.cantidad-origen');

    let totalAsignado = 0;
    inputs.forEach(i => totalAsignado += parseFloat(i.value) || 0);

    if (cantidad <= 0) {
      alerta.className = 'alerta-stock';
      alerta.innerHTML = '';
      return;
    }

    const productoSelect = linea.querySelector('.producto-select');
    const opt = productoSelect.options[productoSelect.selectedIndex];
    const unidadBase = opt?.dataset.unidadBase || 'unidades';

    if (totalAsignado === cantidad) {
      alerta.className = 'alerta-stock ok';
      alerta.innerHTML = `<i class="mdi mdi-check-circle"></i> <span>Stock asignado correctamente: <strong>${totalAsignado} ${unidadBase}</strong></span>`;
    } else if (totalAsignado < cantidad) {
      const falta = cantidad - totalAsignado;
      alerta.className = 'alerta-stock warn';
      alerta.innerHTML = `<i class="mdi mdi-alert"></i> <span>Falta asignar <strong>${falta} ${unidadBase}</strong>. Total asignado: ${totalAsignado} de ${cantidad}.</span>`;
    } else {
      const exceso = totalAsignado - cantidad;
      alerta.className = 'alerta-stock danger';
      alerta.innerHTML = `<i class="mdi mdi-alert-circle"></i> <span>Te pasaste por <strong>${exceso} ${unidadBase}</strong>. Reduce alguna cantidad.</span>`;
    }
  }

  /* ==================== RECALCULAR TOTALES ==================== */
  function recalcular() {
    let subtotal = 0;

    contenedor.querySelectorAll('.linea-venta').forEach(linea => {
      const cant = parseFloat(linea.querySelector('.cantidad-input').value) || 0;
      const precio = parseFloat(linea.querySelector('.precio-input').value) || 0;
      const st = cant * precio;
      subtotal += st;
      linea.querySelector('.linea-subtotal').textContent = fmt(st);
    });

    const desc = parseFloat(inputDesc.value) || 0;
    const total = Math.max(0, subtotal - desc);

    txtSubtotal.textContent = fmt(subtotal);
    txtDesc.textContent = fmt(desc);
    txtTotal.textContent = fmt(total);
  }

  /* ==================== AGREGAR LÍNEA ==================== */
  function agregarLinea() {
    numLinea++;
    const html = template.innerHTML;
    const tmp = document.createElement('div');
    tmp.innerHTML = html.trim();
    const linea = tmp.firstElementChild;

    linea.querySelector('[data-num]').textContent = numLinea;

    // Evento producto
    linea.querySelector('.producto-select').addEventListener('change', function () {
      cargarStockProducto(linea, this.value);
    });

    // Eventos cantidad y precio
    linea.querySelector('.cantidad-input').addEventListener('input', function () {
      // Actualizar el máximo permitido del propio admin
      const totalEnOtros = Array.from(linea.querySelectorAll('.cantidad-origen'))
        .filter(i => i.dataset.userId !== '{{ auth()->id() }}')
        .reduce((sum, i) => sum + (parseFloat(i.value) || 0), 0);

      validarOrigenes(linea);
      recalcular();
    });

    linea.querySelector('.precio-input').addEventListener('input', recalcular);

    // Botón quitar
    linea.querySelector('[data-remove]').addEventListener('click', function () {
      linea.remove();
      recalcular();
      renumerar();
    });

    contenedor.appendChild(linea);
    recalcular();
  }

  function renumerar() {
    contenedor.querySelectorAll('.linea-venta').forEach((l, i) => {
      l.querySelector('[data-num]').textContent = i + 1;
    });
    numLinea = contenedor.querySelectorAll('.linea-venta').length;
  }

  btnAgregar.addEventListener('click', agregarLinea);
  inputDesc.addEventListener('input', recalcular);

  // Primera línea por defecto
  agregarLinea();

  /* ==================== GUARDAR — recolectar datos ==================== */
  document.getElementById('formVenta').addEventListener('submit', function (e) {
    if (this.dataset.confirmado === '1') return;
    e.preventDefault();

    // Validar que todas las líneas tengan sus orígenes completos
    let hayErrores = false;
    let mensajeError = '';

    contenedor.querySelectorAll('.linea-venta').forEach((linea, i) => {
      const cantidad = parseFloat(linea.querySelector('.cantidad-input').value) || 0;
      const inputs = linea.querySelectorAll('.cantidad-origen');
      let totalAsignado = 0;
      inputs.forEach(inp => totalAsignado += parseFloat(inp.value) || 0);

      if (Math.abs(totalAsignado - cantidad) > 0.01) {
        hayErrores = true;
        mensajeError = `Línea ${i + 1}: el total asignado (${totalAsignado}) no coincide con la cantidad (${cantidad}).`;
      }
    });

    if (hayErrores) {
      Swal.fire({
        title: 'Revisa los orígenes de stock',
        html: `<p style="color:var(--error);">${mensajeError}</p>`,
        icon: 'warning',
        confirmButtonText: 'Entendido',
        buttonsStyling: false,
        customClass: { confirmButton: 'swal2-confirm' },
      });
      return;
    }

    // Recolectar datos y construir inputs ocultos
    const form = this;
    form.querySelectorAll('input[name^="items"], select[name^="items"]').forEach(el => el.remove());

    let idx = 0;
    contenedor.querySelectorAll('.linea-venta').forEach(linea => {
      const productoId = linea.querySelector('.producto-select').value;
      const cantidad = linea.querySelector('.cantidad-input').value;
      const precio = linea.querySelector('.precio-input').value;

      const addHidden = (name, value) => {
        const h = document.createElement('input');
        h.type = 'hidden';
        h.name = name;
        h.value = value;
        form.appendChild(h);
      };

      addHidden(`items[${idx}][producto_id]`, productoId);
      addHidden(`items[${idx}][cantidad]`, cantidad);
      addHidden(`items[${idx}][precio_unitario]`, precio);

      let mIdx = 0;
      linea.querySelectorAll('.cantidad-origen').forEach(input => {
        const val = parseFloat(input.value) || 0;
        if (val > 0) {
          addHidden(`items[${idx}][movimientos][${mIdx}][stock_user_id]`, input.dataset.userId);
          addHidden(`items[${idx}][movimientos][${mIdx}][cantidad]`, val);
          mIdx++;
        }
      });

      idx++;
    });

    // Confirmación
    const total = txtTotal.textContent;
    const cantidadLineas = contenedor.querySelectorAll('.linea-venta').length;

    Swal.fire({
      title: '¿Registrar venta?',
      html: `
        <p style="margin:0 0 0.5rem 0;">Vas a registrar una venta con:</p>
        <p style="margin:0 0 0.5rem 0;">
          <strong style="color:var(--cyan);font-size:1.05rem;">${cantidadLineas}</strong>
          producto${cantidadLineas !== 1 ? 's' : ''}
        </p>
        <p style="margin:0;font-size:0.9rem;">Total: <strong style="color:var(--cyan);">${total}</strong></p>
        <p style="margin:0.75rem 0 0;font-size:0.82rem;color:var(--ink-muted);">
          Se guardará como <strong style="color:var(--warning);">pendiente</strong>.
          El stock se descontará al confirmarla.
        </p>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-check"></i> Sí, registrar',
      cancelButtonText: '<i class="mdi mdi-close"></i> Revisar',
      buttonsStyling: false,
      reverseButtons: true,
      focusCancel: true,
      customClass: { confirmButton: 'swal2-confirm', cancelButton: 'swal2-cancel' },
    }).then(r => {
      if (r.isConfirmed) {
        form.dataset.confirmado = '1';
        form.submit();
      }
    });
  });
});
</script>
@endpush