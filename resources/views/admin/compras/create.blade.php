@extends('layouts.plantilla_maestra')

@section('title', 'Nueva Compra')

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

  /* ==================== LÍNEAS ==================== */
  .lineas-tabla { width: 100%; border-collapse: separate; border-spacing: 0; }
  .lineas-tabla thead th {
    background: color-mix(in srgb, var(--bg-2) 60%, transparent);
    color: var(--ink-muted); font-family: var(--font-display);
    font-size: 0.68rem; font-weight: 700; letter-spacing: 0.05em;
    text-transform: uppercase; padding: 0.6rem 0.5rem;
    border-bottom: 1px solid var(--line); text-align: left;
    white-space: nowrap;
  }
  .lineas-tabla tbody td {
    padding: 0.55rem 0.5rem; border-bottom: 1px solid var(--line);
    vertical-align: top;
  }
  .lineas-tabla tbody tr:last-child td { border-bottom: none; }
  .lineas-tabla .input-cell .form-control,
  .lineas-tabla .input-cell .form-select {
    padding: 0.55rem 0.7rem; font-size: 0.85rem;
  }

  .linea-subtotal {
    font-family: var(--font-display); font-weight: 700;
    color: var(--cyan); font-size: 0.9rem; min-width: 90px;
    display: inline-block; text-align: right;
  }

  .total-base {
    display: block;
    font-size: 0.72rem;
    color: var(--ink-muted);
    margin-top: 0.2rem;
    font-style: italic;
  }

  .detalle-linea {
    font-size: 0.7rem;
    color: var(--ink-faint);
    margin-top: 0.2rem;
    display: block;
  }

  .btn-remove-line {
    width: 32px; height: 32px; border-radius: var(--r-sm);
    border: 1px solid var(--line); background: var(--surface-alpha);
    color: var(--ink-muted); cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    transition: all 0.25s var(--ease);
    margin-top: 0.15rem;
  }
  .btn-remove-line:hover {
    color: var(--error); border-color: color-mix(in srgb, var(--error) 50%, transparent);
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

  /* ==================== TOTALES ==================== */
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

  /* ==================== SWEETALERT2 ==================== */
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
      <h3>Nueva Compra</h3>
      <p>Registra una orden de compra a un proveedor</p>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-danger mb-3">
      <i class="mdi mdi-alert-circle me-1"></i>
      Corrige los errores marcados abajo.
    </div>
  @endif

  <form action="{{ route('admin.compras.store') }}" method="POST" id="formCompra" novalidate>
    @csrf

    {{-- ============ DATOS GENERALES ============ --}}
    <div class="card-form mb-4">
      <div class="card-head">
        <i class="mdi mdi-file-document-outline"></i> Datos generales
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Proveedor <span class="required">*</span></label>
            <select name="proveedor_id" class="form-select @error('proveedor_id') is-invalid @enderror" required>
              <option value="">Seleccione un proveedor...</option>
              @foreach($proveedores as $p)
                <option value="{{ $p->id }}" {{ old('proveedor_id') == $p->id ? 'selected' : '' }}>
                  {{ $p->nombre }} — NIT: {{ $p->nit }}
                </option>
              @endforeach
            </select>
            @error('proveedor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Registrado por <span class="required">*</span></label>
            <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
              <option value="">Seleccione un administrador...</option>
              @foreach($admins as $a)
                <option value="{{ $a->id }}" {{ old('user_id', auth()->id()) == $a->id ? 'selected' : '' }}>
                  {{ $a->name }} — {{ $a->email }}
                </option>
              @endforeach
            </select>
            @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Fecha de compra <span class="required">*</span></label>
            <input type="date" name="fecha_compra" class="form-control @error('fecha_compra') is-invalid @enderror"
                   value="{{ old('fecha_compra', now()->format('Y-m-d')) }}" required>
            @error('fecha_compra') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">N° Orden</label>
            <input type="text" class="form-control" value="AMERI-{{ now()->format('Ymd') }}-####" readonly
                   style="font-family:var(--font-display);font-weight:600;color:var(--cyan);">
            <small class="text-muted">Se genera automáticamente al guardar.</small>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Descuento (Bs.)</label>
            <input type="number" name="descuento" min="0" step="0.01" id="inputDescuento"
                   class="form-control" value="{{ old('descuento', 0) }}">
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" rows="2" class="form-control"
                      placeholder="Notas internas sobre esta compra...">{{ old('observaciones') }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- ============ LÍNEAS ============ --}}
    <div class="card-form mb-4">
      <div class="card-head">
        <i class="mdi mdi-format-list-bulleted"></i> Productos de la compra
      </div>
      <div class="card-body">

        @error('items') <div class="alert alert-danger mb-3">{{ $message }}</div> @enderror

        <div class="table-responsive mb-3">
          <table class="lineas-tabla" id="tablaLineas">
            <thead>
              <tr>
                <th style="width: 28%;">Producto</th>
                <th style="width: 11%;">Unidad</th>
                <th style="width: 11%;">Cantidad</th>
                <th style="width: 14%;">Precio Unit.</th>
                <th style="width: 14%;">Total base</th>
                <th style="width: 15%;">Subtotal</th>
                <th style="width: 7%;"></th>
              </tr>
            </thead>
            <tbody id="cuerpoLineas">
              {{-- JS llena --}}
            </tbody>
          </table>
        </div>

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
          <a href="{{ route('admin.compras.index') }}" class="btn-secondary-app">
            <i class="mdi mdi-close"></i> Cancelar
          </a>
          <button type="submit" class="btn-primary-app">
            <i class="mdi mdi-content-save"></i> Guardar compra
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

{{-- Template de línea --}}
<template id="templateLinea">
  <tr class="linea">
    <td class="input-cell">
      <select name="items[__IDX__][producto_id]" class="form-select producto-select" required>
        <option value="">Seleccione un producto...</option>
        @foreach($productos as $prod)
          <option value="{{ $prod->id }}"
                  data-unidad="{{ $prod->unidad_compra }}"
                  data-upp="{{ $prod->unidades_por_paquete }}"
                  data-unidad-base="{{ $prod->unidad_base }}"
                  data-detalle="{{ trim(($prod->marca ?? '') . ' ' . ($prod->medida ?? '')) }}">
            {{ $prod->nombre }} @if($prod->marca) · {{ $prod->marca }} @endif
          </option>
        @endforeach
      </select>
      <small class="detalle-linea"></small>
    </td>

    <td class="input-cell">
      <select name="items[__IDX__][unidad_compra]" class="form-select unidad-select">
        <option value="caja">Caja</option>
        <option value="bolsa">Bolsa</option>
        <option value="pieza">Pieza</option>
        <option value="unidad">Unidad</option>
        <option value="kg">Kg</option>
        <option value="m2">m²</option>
      </select>
    </td>

    <td class="input-cell">
      <input type="number" name="items[__IDX__][cantidad]" min="0.01" step="0.01" value="1"
             class="form-control cantidad-input" required>
    </td>

    <td class="input-cell">
      <input type="number" name="items[__IDX__][precio_unitario]" min="0.01" step="0.01"
             class="form-control precio-input" required placeholder="0.00">
    </td>

    <td>
      <input type="hidden" name="items[__IDX__][unidades_por_paquete]" class="upp-input" value="1">
      <span class="total-base">—</span>
    </td>

    <td>
      <span class="linea-subtotal">Bs. 0.00</span>
    </td>

    <td class="text-end">
      <button type="button" class="btn-remove-line" title="Quitar">
        <i class="mdi mdi-close"></i>
      </button>
    </td>
  </tr>
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const cuerpo      = document.getElementById('cuerpoLineas');
  const template    = document.getElementById('templateLinea');
  const btnAgregar  = document.getElementById('btnAgregarLinea');
  const inputDesc   = document.getElementById('inputDescuento');
  const txtSubtotal = document.getElementById('txtSubtotal');
  const txtDesc     = document.getElementById('txtDescuento');
  const txtTotal    = document.getElementById('txtTotal');

  let idx = 0;

  function fmt(n) {
    return 'Bs. ' + Number(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  function actualizarTotalBase(fila, unidadBase) {
    const cantidad = parseFloat(fila.querySelector('.cantidad-input').value) || 0;
    const upp      = parseFloat(fila.querySelector('.upp-input').value) || 1;
    const total    = cantidad * upp;

    const span = fila.querySelector('.total-base');
    if (span) {
      const totalStr = total % 1 === 0 ? total : total.toFixed(2);
      span.textContent = totalStr + ' ' + (unidadBase || 'unidad');
    }
  }

  function recalcular() {
    let subtotal = 0;

    cuerpo.querySelectorAll('.linea').forEach(function (linea) {
      const cant   = parseFloat(linea.querySelector('.cantidad-input').value) || 0;
      const precio = parseFloat(linea.querySelector('.precio-input').value) || 0;
      const st     = cant * precio;
      subtotal += st;
      linea.querySelector('.linea-subtotal').textContent = fmt(st);

      const sel = linea.querySelector('.producto-select');
      const opt = sel.options[sel.selectedIndex];
      const unidadBase = opt?.dataset.unidadBase || 'unidad';
      actualizarTotalBase(linea, unidadBase);
    });

    const desc  = parseFloat(inputDesc.value) || 0;
    const total = Math.max(0, subtotal - desc);

    txtSubtotal.textContent = fmt(subtotal);
    txtDesc.textContent     = fmt(desc);
    txtTotal.textContent    = fmt(total);
  }

  function agregarLinea() {
    const html = template.innerHTML.replace(/__IDX__/g, idx++);
    const tmp  = document.createElement('tbody');
    tmp.innerHTML = html.trim();
    const fila = tmp.firstElementChild;

    // Producto → autollenar unidad, upp, detalle
    fila.querySelector('.producto-select').addEventListener('change', function () {
      const opt = this.options[this.selectedIndex];

      if (!this.value) {
        fila.querySelector('.detalle-linea').textContent = '';
        return;
      }

      const unidad     = opt.dataset.unidad     || 'unidad';
      const upp        = opt.dataset.upp        || 1;
      const detalle    = opt.dataset.detalle    || '';

      fila.querySelector('.unidad-select').value = unidad;
      fila.querySelector('.upp-input').value     = upp;

      if (detalle) {
        fila.querySelector('.detalle-linea').textContent = detalle;
      }

      recalcular();
    });

    fila.querySelector('.cantidad-input').addEventListener('input', recalcular);
    fila.querySelector('.precio-input').addEventListener('input', recalcular);
    fila.querySelector('.unidad-select').addEventListener('change', recalcular);

    fila.querySelector('.btn-remove-line').addEventListener('click', function () {
      fila.remove();
      recalcular();
    });

    cuerpo.appendChild(fila);
    recalcular();
  }

  btnAgregar.addEventListener('click', agregarLinea);
  inputDesc.addEventListener('input', recalcular);

  agregarLinea();

  // ========== CONFIRMAR AL GUARDAR ==========
  document.getElementById('formCompra').addEventListener('submit', function (e) {
    if (this.dataset.confirmado === '1') return;

    e.preventDefault();

    const total    = txtTotal.textContent;
    const cantidad = cuerpo.querySelectorAll('.linea').length;

    Swal.fire({
      title: '¿Registrar compra?',
      html: `
        <p style="margin:0 0 0.5rem 0;">Estás a punto de registrar una orden con:</p>
        <p style="margin:0 0 0.5rem 0;">
          <strong style="color:var(--cyan);font-size:1.05rem;">${cantidad}</strong>
          producto${cantidad !== 1 ? 's' : ''}
        </p>
        <p style="margin:0;font-size:0.9rem;">Total: <strong style="color:var(--cyan);">${total}</strong></p>
        <p style="margin:0.75rem 0 0;font-size:0.82rem;color:var(--ink-muted);">
          Se creará con estado <strong style="color:var(--warning);">pendiente</strong>.
          El stock se sumará al confirmarla como recibida.
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
        this.dataset.confirmado = '1';
        this.submit();
      }
    });
  });
});
</script>
@endpush