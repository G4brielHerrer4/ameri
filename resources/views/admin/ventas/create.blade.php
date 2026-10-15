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

        <strong style="font-family:var(--font-display);color:var(--ink);font-size:0.9rem;">
          Producto
        </strong>
      </div>

      <button type="button"
              class="btn-remove-line"
              data-remove
              title="Quitar">
        <i class="mdi mdi-close"></i>
      </button>
    </div>


    <div class="row">

      {{-- PRODUCTO --}}
      <div class="col-md-5 mb-2">

        <label class="form-label">
          Producto <span class="required">*</span>
        </label>

        <select class="form-select producto-select" required>

          <option value="">
            Seleccione un producto...
          </option>

          @foreach($stocks as $s)

            <option
              value="{{ $s->producto->id }}"
              data-stock-propio="{{ (float) $s->cantidad }}"
              data-unidad-base="{{ $s->producto->unidad_base }}"
            >

              {{ $s->producto->nombre }}

              @if($s->producto->marca)
                · {{ $s->producto->marca }}
              @endif

              — {{ (float) $s->cantidad }}
              {{ $s->producto->unidad_base }} disponibles

            </option>

          @endforeach

        </select>

      </div>


      {{-- UNIDAD --}}
      <div class="col-md-2 mb-2">

        <label class="form-label">
          Unidad de venta <span class="required">*</span>
        </label>

        <select
          class="form-select unidad-select"
          required
          disabled
        >
          <option value="">
            Seleccione...
          </option>
        </select>

      </div>


      {{-- CANTIDAD --}}
      <div class="col-md-2 mb-2">

        <label class="form-label">
          Cantidad <span class="required">*</span>
        </label>

        <input
          type="number"
          class="form-control cantidad-input"
          min="0.01"
          step="0.01"
          value="1"
          required
          disabled
        >

        <small
          class="cantidad-ayuda text-muted"
          style="display:block;margin-top:0.25rem;font-size:0.68rem;"
        ></small>

      </div>


      {{-- PRECIO --}}
      <div class="col-md-3 mb-2">

        <label class="form-label">
          Precio por unidad <span class="required">*</span>
        </label>

        
        <input
          type="number"
          class="form-control precio-input"
          min="0.01"
          step="0.01"
          required
          placeholder="Selecciona un producto"
          readonly
          disabled
        >

        <small
          class="precio-ayuda text-muted"
          style="display:block;margin-top:0.25rem;font-size:0.72rem;"
        >
          El precio se obtiene del inventario.
        </small>

      </div>

    </div>


    {{-- INFORMACIÓN DE CONVERSIÓN --}}
    <div class="conversion-info" style="display:none;">

      <div class="conversion-titulo">
        <i class="mdi mdi-calculator-variant-outline"></i>
        Conversión de venta
      </div>

      <div class="conversion-linea conversion-text"></div>

    </div>


    {{-- ORÍGENES DE STOCK --}}
    <div
      class="origenes-section"
      style="display:none;"
    >

      <div class="origenes-title">

        <i class="mdi mdi-source-branch"></i>

        Selecciona de qué admin tomar el stock

      </div>


      <div
        class="origenes-subtitle"
        style="
          font-size:0.75rem;
          color:var(--ink-muted);
          margin-bottom:0.7rem;
        "
      >
        La cantidad que selecciones aquí se mostrará en
        <strong class="unidad-origen-texto">
          piezas
        </strong>.
        El sistema convertirá automáticamente la venta a stock físico.
      </div>


      <div class="origenes-lista"></div>


      <div class="alerta-stock"></div>

    </div>


    {{-- SUBTOTAL --}}
    <div class="d-flex justify-content-between align-items-center mt-2">

      <small class="text-muted">
        Subtotal línea:
      </small>

      <span
        class="linea-subtotal"
        style="
          font-family:var(--font-display);
          font-weight:700;
          color:var(--cyan);
          font-size:1rem;
        "
      >
        Bs. 0.00
      </span>

    </div>

  </div>
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  const contenedor  = document.getElementById('contenedorLineas');
  const template    = document.getElementById('templateLinea');
  const btnAgregar  = document.getElementById('btnAgregarLinea');
  const inputDesc   = document.getElementById('inputDescuento');

  const txtSubtotal = document.getElementById('txtSubtotal');
  const txtDesc     = document.getElementById('txtDescuento');
  const txtTotal    = document.getElementById('txtTotal');

  let numLinea = 0;

  /* ============================================================
   * FORMATO MONEDA
   * ============================================================ */

  function fmt(n) {

    return 'Bs. ' + Number(n)
      .toFixed(2)
      .replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }
  
/* ============================================================
 * PRECIO DE VENTA SEGÚN PRODUCTO Y UNIDAD
 * ============================================================ */
  
  function actualizarPrecio(linea) {
    const precioInput = linea.querySelector('.precio-input');
    const ayuda = linea.querySelector('.precio-ayuda');
    const unidad = linea.querySelector('.unidad-select').value;
    const producto = linea._producto;

    if (!producto || !unidad) {
      precioInput.value = '';
      precioInput.disabled = true;
      ayuda.textContent = 'Selecciona un producto y una unidad.';
      recalcular();
      return;
    }

    const precios = producto.precios_venta || {};
    const tipo = String(producto.tipo || '')
      .trim()
      .toLocaleLowerCase('es');

    const esCeramicaProducto = [
      'cerámica',
      'ceramica',
      'porcelanato'
    ].includes(tipo);

    let precio = null;

    if (esCeramicaProducto) {
      const precioCaja = Number(precios.caja || 0);
      const m2PorCaja = Number(producto.m2_por_caja || 0);
      const piezasPorCaja = Number(producto.piezas_por_caja || 0);

      if (precioCaja <= 0) {
        precioInput.value = '';
        precioInput.disabled = true;
        ayuda.textContent =
          'Configura el precio por caja en el inventario.';
        recalcular();
        return;
      }

      if (unidad === 'caja') {
        precio = precioCaja;
      } else if (
        (unidad === 'm2' || unidad === 'pieza') &&
        m2PorCaja > 0 &&
        piezasPorCaja > 0
      ) {
        // Para m² se muestra el precio proporcional por pieza,
        // porque el total se calcula según las piezas necesarias.
        precio = precioCaja / piezasPorCaja;
      }
    } else {
      precio = precios[unidad];
    }

    if (
      precio === null ||
      precio === undefined ||
      precio === '' ||
      !Number.isFinite(Number(precio)) ||
      Number(precio) <= 0
    ) {
      precioInput.value = '';
      precioInput.disabled = true;
      ayuda.textContent =
        'No hay un precio válido configurado para esta unidad.';
    } else {
      precioInput.value = Number(precio).toFixed(2);
      precioInput.disabled = false;

      if (esCeramicaProducto) {
        ayuda.textContent = unidad === 'caja'
          ? 'Precio de venta por caja.'
          : 'Precio proporcional por pieza, calculado desde el precio por caja.';
      } else {
        ayuda.textContent =
          'Precio configurado en el inventario: ' + fmt(Number(precio));
      }
    }

    recalcular();
  }

  /* ============================================================
   * NOMBRE DE UNIDAD
   * ============================================================ */

  function nombreUnidad(unidad) {

    const nombres = {

      caja: 'Caja',
      m2: 'm²',
      pieza: 'Pieza',
      bolsa: 'Bolsa',
      unidad: 'Unidad'

    };

    return nombres[unidad] || unidad;
  }


  function nombreUnidadPlural(unidad) {

    const nombres = {

      caja: 'cajas',
      m2: 'm²',
      pieza: 'piezas',
      bolsa: 'bolsas',
      unidad: 'unidades'

    };

    return nombres[unidad] || unidad;
  }


  /* ============================================================
   * DETECTAR SI ES CERÁMICA / PORCELANATO
   * ============================================================ */

  
  function esCeramica(producto) {
    if (!producto) {
      return false;
    }

    const tipo = String(producto.tipo || '')
      .trim()
      .toLocaleLowerCase('es');

    return [
      'cerámica',
      'ceramica',
      'porcelanato'
    ].includes(tipo);
  }


  /* ============================================================
   * CONFIGURAR CAMPO CANTIDAD
   * ============================================================ */

  function configurarCantidad(linea) {

    const cantidadInput =
      linea.querySelector('.cantidad-input');

    const unidadSelect =
      linea.querySelector('.unidad-select');

    const ayuda =
      linea.querySelector('.cantidad-ayuda');

    const unidad =
      unidadSelect.value;

    cantidadInput.step = '0.01';
    cantidadInput.min = '0.01';

    ayuda.textContent = '';

    if (!unidad) {
      return;
    }


    /* CAJAS */

    if (unidad === 'caja') {

      cantidadInput.step = '1';
      cantidadInput.min = '1';

      ayuda.textContent =
        'Las cajas se venden completas.';

      return;
    }


    /* METROS CUADRADOS */

    if (unidad === 'm2') {

      cantidadInput.step = '0.01';
      cantidadInput.min = '0.01';

      ayuda.textContent =
        'Puedes ingresar los m² que solicita el cliente.';

      return;
    }


    /* PIEZAS */

    if (unidad === 'pieza') {

      cantidadInput.step = '1';
      cantidadInput.min = '1';

      ayuda.textContent =
        'Cantidad de piezas que solicita el cliente.';

      return;
    }


    /* BOLSAS */

    if (unidad === 'bolsa') {

      cantidadInput.step = '1';
      cantidadInput.min = '1';

      ayuda.textContent =
        'Las bolsas se venden por unidad.';

      return;
    }
  }


  /* ============================================================
   * CALCULAR STOCK FÍSICO
   * ============================================================ */

  function calcularConversion(linea) {

    const producto =
      linea._producto;

    const unidad =
      linea.querySelector('.unidad-select').value;

    const cantidad =
      parseFloat(
        linea.querySelector('.cantidad-input').value
      ) || 0;


    if (
      !producto ||
      !unidad ||
      cantidad <= 0
    ) {

      return {
        cantidadStock: 0,
        cajas: 0,
        piezas: 0,
        m2Reales: 0,
        m2Solicitados: 0
      };
    }


    /* ========================================================
     * CERÁMICA / PORCELANATO
     * ======================================================== */

    if (esCeramica(producto)) {

      const piezasPorCaja =
        parseInt(producto.piezas_por_caja) || 0;

      const m2PorCaja =
        parseFloat(producto.m2_por_caja) || 0;


      if (
        piezasPorCaja <= 0 ||
        m2PorCaja <= 0
      ) {

        return {
          cantidadStock: 0,
          cajas: 0,
          piezas: 0,
          m2Reales: 0,
          m2Solicitados: 0
        };
      }


      /* ------------------------------------------------------
       * VENTA POR CAJAS
       * ------------------------------------------------------ */

      if (unidad === 'caja') {

        const cajas =
          Math.ceil(cantidad);

        const piezas =
          cajas * piezasPorCaja;

        const m2 =
          cajas * m2PorCaja;

        return {

          cantidadStock: piezas,

          cajas: cajas,

          piezas: piezas,

          m2Reales: m2,

          m2Solicitados: m2
        };
      }


      /* ------------------------------------------------------
       * VENTA POR METROS CUADRADOS
       * ------------------------------------------------------ */
      
    if (unidad === 'm2') {
      const m2PorPieza = m2PorCaja / piezasPorCaja;

      const piezas = Math.ceil(
        (cantidad / m2PorPieza) - 0.000000001
      );

      const cajas = Math.floor(piezas / piezasPorCaja);
      const piezasSueltas = piezas % piezasPorCaja;
      const m2Reales = piezas * m2PorPieza;

      return {
        cantidadStock: piezas,
        cajas: cajas,
        piezas: piezas,
        piezasSueltas: piezasSueltas,
        m2Reales: m2Reales,
        m2Solicitados: cantidad
      };
    }

      /* ------------------------------------------------------
       * VENTA POR PIEZAS
       * ------------------------------------------------------ */

      if (unidad === 'pieza') {

        const piezas =
          Math.ceil(cantidad);

        const cajasExactas =
          piezas / piezasPorCaja;

        const cajasNecesarias =
          Math.ceil(cajasExactas);

        const m2Equivalentes =
          cajasExactas * m2PorCaja;

        return {

          cantidadStock: piezas,

          cajas: cajasExactas,

          cajasNecesarias: cajasNecesarias,

          piezas: piezas,

          m2Reales: m2Equivalentes,

          m2Solicitados: m2Equivalentes
        };
      }
    }


    /* ========================================================
     * BOLSAS
     * ======================================================== */

    if (unidad === 'bolsa') {

      return {

        cantidadStock: cantidad,

        cajas: 0,

        piezas: cantidad,

        m2Reales: 0,

        m2Solicitados: 0
      };
    }


    /* ========================================================
     * PIEZAS GENÉRICAS
     * ======================================================== */

    return {

      cantidadStock: cantidad,

      cajas: 0,

      piezas: cantidad,

      m2Reales: 0,

      m2Solicitados: 0
    };
  }


  /* ============================================================
   * MOSTRAR CONVERSIÓN
   * ============================================================ */

  function actualizarConversion(linea) {

    const info =
      linea.querySelector('.conversion-info');

    const texto =
      linea.querySelector('.conversion-text');

    const producto =
      linea._producto;

    const unidad =
      linea.querySelector('.unidad-select').value;

    const cantidad =
      parseFloat(
        linea.querySelector('.cantidad-input').value
      ) || 0;


    if (
      !producto ||
      !unidad ||
      cantidad <= 0
    ) {

      info.style.display = 'none';
      texto.innerHTML = '';

      return;
    }


    /* ========================================================
     * CERÁMICA / PORCELANATO
     * ======================================================== */

    if (esCeramica(producto)) {

      const piezasPorCaja =
        parseInt(producto.piezas_por_caja) || 0;

      const m2PorCaja =
        parseFloat(producto.m2_por_caja) || 0;


      if (
        piezasPorCaja <= 0 ||
        m2PorCaja <= 0
      ) {

        info.style.display = 'block';

        texto.innerHTML = `
          <span style="color:var(--error);">
            <i class="mdi mdi-alert-circle"></i>
            Este producto no tiene configuradas
            correctamente las piezas o los m² por caja.
          </span>
        `;

        return;
      }


      const conversion =
        calcularConversion(linea);


      /* ------------------------------------------------------
       * CAJA
       * ------------------------------------------------------ */

      if (unidad === 'caja') {

        texto.innerHTML = `

          <span class="conversion-badge">
             ${conversion.cajas} cajas
          </span>

          <span>→</span>

          <span class="conversion-badge">
             ${conversion.piezas} piezas
          </span>

          <span>→</span>

          <span class="conversion-badge">
            📐 ${conversion.m2Reales.toFixed(2)} m²
          </span>

        `;
      }


      /* ------------------------------------------------------
       * M²
       * ------------------------------------------------------ */

      
      else if (unidad === 'm2') {
        texto.innerHTML = `
          <span class="conversion-badge">
            📐 ${cantidad.toFixed(2)} m² solicitados
          </span>

          <span>→</span>

          <span class="conversion-badge">
            ${conversion.cajas} cajas completas
          </span>

          <span>+</span>

          <span class="conversion-badge">
            ${conversion.piezasSueltas} piezas sueltas
          </span>

          <span>→</span>

          <span class="conversion-badge">
            ${conversion.piezas} piezas en total
          </span>

          <span>→</span>

          <span class="conversion-badge">
            📐 ${conversion.m2Reales.toFixed(2)} m² reales
          </span>
        `;
      }


      /* ------------------------------------------------------
       * PIEZAS
       * ------------------------------------------------------ */

      else if (unidad === 'pieza') {

        const cajasNecesarias =
          conversion.cajasNecesarias;

        const cajasExactas =
          conversion.cajas;

        texto.innerHTML = `

          <span class="conversion-badge">
             ${conversion.piezas} piezas solicitadas
          </span>

          <span>→</span>

          <span class="conversion-badge">
             ${cajasNecesarias} cajas necesarias
          </span>

          <span>→</span>

          <span class="conversion-badge">
            📐 ${conversion.m2Reales.toFixed(2)} m²
          </span>

          <div style="width:100%;margin-top:0.3rem;color:var(--ink-muted);">
            ${conversion.piezas} piezas equivalen a
            ${cajasExactas.toFixed(2)} cajas.
          </div>

        `;
      }


      info.style.display = 'block';

      return;
    }


    /* ========================================================
     * PRODUCTOS POR BOLSA
     * ======================================================== */

    if (unidad === 'bolsa') {

      texto.innerHTML = `

        <span class="conversion-badge">
           ${cantidad} ${cantidad === 1 ? 'bolsa' : 'bolsas'}
        </span>

        <span>
          → stock físico:
        </span>

        <strong>
          ${cantidad} bolsas
        </strong>

      `;

      info.style.display = 'block';

      return;
    }


    /* ========================================================
     * PRODUCTOS POR PIEZA
     * ======================================================== */

    texto.innerHTML = `

      <span class="conversion-badge">
         ${cantidad} ${cantidad === 1 ? 'pieza' : 'piezas'}
      </span>

      <span>
        → stock físico:
      </span>

      <strong>
        ${cantidad} piezas
      </strong>

    `;

    info.style.display = 'block';
  }


  /* ============================================================
   * UNIDAD DE STOCK PARA MOSTRAR
   * ============================================================ */

  function unidadStockPrincipal(producto) {

    if (esCeramica(producto)) {
      return 'piezas';
    }

    if (
      producto.unidad_base === 'bolsas'
    ) {
      return 'bolsas';
    }

    return 'piezas';
  }


  /* ============================================================
   * OBTENER CANTIDAD PARA ASIGNAR POR ADMIN
   *
   * IMPORTANTE:
   *
   * El usuario selecciona:
   *
   * Caja -> cajas
   * m²   -> cajas
   * Pieza -> piezas
   * Bolsa -> bolsas
   *
   * Pero después lo convertimos a stock físico.
   * ============================================================ */

  
  function obtenerCantidadAsignacion(linea) {
    const unidad = linea.querySelector('.unidad-select').value;
    const conversion = calcularConversion(linea);

    if (esCeramica(linea._producto)) {
      if (unidad === 'caja') {
        return conversion.cajas;
      }

      if (unidad === 'm2' || unidad === 'pieza') {
        return conversion.piezas;
      }
    }

    return conversion.cantidadStock;
  }


  /* ============================================================
   * STOCK DISPONIBLE PARA UN ADMIN
   * ============================================================ */

  function obtenerMaximoOrigen(linea, stock) {
    const producto = linea._producto;
    const unidad = linea.querySelector('.unidad-select').value;

    if (!producto) {
      return 0;
    }

    if (esCeramica(producto)) {
      const piezasPorCaja =
        parseInt(producto.piezas_por_caja) || 1;

      // Solo las ventas por caja se asignan en cajas.
      if (unidad === 'caja') {
        return Math.floor(Number(stock.cantidad) / piezasPorCaja);
      }

      // Para m² y piezas, el stock disponible se expresa en piezas.
      if (unidad === 'm2' || unidad === 'pieza') {
        return Number(stock.cantidad);
      }
    }

    return Number(stock.cantidad);
  }


  /* ============================================================
   * CONSTRUIR TEXTO DE STOCK
   * ============================================================ */

  function construirTextoStock(
    linea,
    stock
  ) {

    const producto =
      linea._producto;

    if (
      !producto
    ) {
      return '';
    }


    if (
      esCeramica(producto)
    ) {

      const piezas =
        Number(stock.cantidad);

      const piezasPorCaja =
        parseInt(
          producto.piezas_por_caja
        ) || 1;

      const m2PorCaja =
        parseFloat(
          producto.m2_por_caja
        ) || 0;


      const cajas =
        Math.floor(
          piezas /
          piezasPorCaja
        );

      const m2 =
        (piezas / piezasPorCaja)
        * m2PorCaja;


      return `

        <div class="stock-conversion">

          <span class="stock-principal">
             ${piezas} piezas
          </span>

          <span class="stock-equivalencia">
             ${cajas} cajas completas
          </span>

          <span class="stock-equivalencia">
            📐 ${m2.toFixed(2)} m² aprox.
          </span>

        </div>

      `;
    }


    if (
      producto.unidad_base === 'bolsas'
    ) {

      return `

        <div class="stock-conversion">

          <span class="stock-principal">
             ${stock.cantidad} bolsas
          </span>

        </div>

      `;
    }


    return `

      <div class="stock-conversion">

        <span class="stock-principal">
           ${stock.cantidad} piezas
        </span>

      </div>

    `;
  }

  /* ============================================================
   * CARGAR STOCK DEL PRODUCTO
   * ============================================================ */

  async function cargarStockProducto(
    linea,
    productoId
  ) {

    const origenesSection =
      linea.querySelector(
        '.origenes-section'
      );

    const lista =
      linea.querySelector(
        '.origenes-lista'
      );

    const alerta =
      linea.querySelector(
        '.alerta-stock'
      );

    const unidadSelect =
      linea.querySelector(
        '.unidad-select'
      );

    const cantidadInput =
      linea.querySelector(
        '.cantidad-input'
      );

    const precioInput =
      linea.querySelector(
        '.precio-input'
      );


    if (!productoId) {

      origenesSection.style.display =
        'none';

      unidadSelect.innerHTML =
        '<option value="">Seleccione...</option>';

      unidadSelect.disabled =
        true;

      cantidadInput.disabled = true;

      precioInput.disabled = true;
      precioInput.value = '';

      const ayudaPrecio = linea.querySelector('.precio-ayuda');

      if (ayudaPrecio) {
        ayudaPrecio.textContent =
          'Selecciona un producto y una unidad.';
      }

      linea._producto = null;

      actualizarPrecio(linea);

      return;
    }


    lista.innerHTML = `

      <div style="
        padding:1rem;
        text-align:center;
      ">

        <span class="spinner-inline"></span>

        Cargando stock...

      </div>

    `;

    origenesSection.style.display =
      '';


    try {

      const res =
        await fetch(
          `{{ url('admin/ventas/stock-producto') }}/${productoId}`
        );


      if (!res.ok) {
        throw new Error(
          'Error HTTP ' + res.status
        );
      }


      const data =
        await res.json();


      linea._producto =
        data.producto;


      /* ======================================================
       * CARGAR UNIDADES
       * ====================================================== */

      unidadSelect.innerHTML =
        '<option value="">Seleccione...</option>';


      const unidades =
        data.producto.unidades_venta || [];


      unidades.forEach(unidad => {

        const option =
          document.createElement(
            'option'
          );

        option.value =
          unidad;

        option.textContent =
          nombreUnidad(unidad);

        unidadSelect.appendChild(
          option
        );
      });


      unidadSelect.disabled =
        unidades.length === 0;


     cantidadInput.disabled = unidades.length === 0;

      // El precio se habilitará únicamente si existe
      // un precio válido para la unidad seleccionada.
      precioInput.disabled = true;
      precioInput.value = '';

      /*
       * Para cerámica comenzamos
       * mostrando Caja.
       */

      if (
        esCeramica(data.producto)
      ) {

        unidadSelect.value =
          'caja';

      } else {

        unidadSelect.value =
          unidades[0] || '';

      }


      configurarCantidad(linea);
      actualizarPrecio(linea);


      /* ======================================================
       * CONSTRUIR STOCK
       * ====================================================== */

      lista.innerHTML = '';


      if (!data.stocks.length) {

        lista.innerHTML = `

          <div style="
            padding:0.8rem;
            color:var(--warning);
          ">

            <i class="mdi mdi-alert"></i>

            No hay stock disponible
            para este producto.

          </div>

        `;

      }


      data.stocks.forEach(stock => {

        const item =
          document.createElement(
            'div'
          );


        item.className =
          'origen-item' +
          (
            stock.es_propio
              ? ' es-propio'
              : ''
          );


        item.dataset.userId =
          stock.user_id;


        const maximo =
          obtenerMaximoOrigen(
            linea,
            stock
          );


        const unidad =
          linea.querySelector(
            '.unidad-select'
          ).value;


        let textoDisponible =
          construirTextoStock(
            linea,
            stock
          );
       
        let etiquetaAsignacion;

        if (esCeramica(data.producto)) {
          etiquetaAsignacion =
            unidad === 'caja' ? 'cajas' : 'piezas';
        } else {
          etiquetaAsignacion =
            unidad === 'bolsa' ? 'bolsas' : 'piezas';
        }

        item.innerHTML = `

          <div class="info">

            <i class="mdi mdi-account-circle"></i>

            <div style="min-width:0;">

              <div
                class="name"
              >
                ${stock.user_name}

                ${
                  stock.es_propio
                    ? '<span class="label">Mi stock</span>'
                    : ''
                }
              </div>

            </div>

          </div>


          <div class="disponible">

            ${textoDisponible}

          </div>


          <div>

            <input
              type="number"
              class="cantidad-origen"
              min="0"
              step="${unidad === 'pieza' || unidad === 'bolsa' ? '1' : '1'}"
              value="0"
              data-max="${maximo}"
              data-stock-fisico="${stock.cantidad}"
              data-user-id="${stock.user_id}"
            >

            <span class="origen-unidad-label">
              Tomar ${etiquetaAsignacion}
            </span>

          </div>

        `;


        lista.appendChild(item);
      });


      /* ======================================================
       * EVENTOS ORIGEN
       * ====================================================== */

      lista
        .querySelectorAll(
          '.cantidad-origen'
        )
        .forEach(input => {

          input.addEventListener(
            'input',
            function () {

              const max =
                parseFloat(
                  this.dataset.max
                ) || 0;


              let val =
                parseFloat(
                  this.value
                ) || 0;


              if (val < 0) {
                val = 0;
              }


              if (val > max) {

                val = max;

                this.value =
                  max;
              }


              /*
               * Para cajas siempre entero.
               */

              if (
                esCeramica(linea._producto) &&
                linea.querySelector(
                  '.unidad-select'
                ).value !== 'pieza'
              ) {

                val =
                  Math.floor(val);

                this.value =
                  val;
              }


              if (val > 0) {

                this.classList.add(
                  'tiene-valor'
                );

              } else {

                this.classList.remove(
                  'tiene-valor'
                );
              }


              validarOrigenes(linea);
              recalcular();
            }
          );
        });


      /* ======================================================
       * CAMBIO DE UNIDAD
       * ====================================================== */

      unidadSelect.onchange =
        function () {

          configurarCantidad(linea);


          /*
           * Limpiar asignaciones anteriores
           * porque podrían estar en otra unidad.
           */

          linea
            .querySelectorAll(
              '.cantidad-origen'
            )
            .forEach(input => {

              input.value = 0;

              input.classList.remove(
                'tiene-valor'
              );
            });


          /*
           * Cambiar máximo de cada admin
           * según la nueva unidad.
           */

          data.stocks.forEach(
            stock => {

              const input =
                lista.querySelector(
                  `.cantidad-origen[data-user-id="${stock.user_id}"]`
                );

              if (!input) {
                return;
              }


              const nuevoMax =
                obtenerMaximoOrigen(
                  linea,
                  stock
                );


              input.dataset.max =
                nuevoMax;


              input.step =
                (
                  this.value === 'pieza' ||
                  this.value === 'bolsa'
                )
                  ? '1'
                  : '1';


              const item =
                input.closest(
                  '.origen-item'
                );


              const etiqueta =
                item.querySelector(
                  '.origen-unidad-label'
                );


              if (
                esCeramica(
                  linea._producto
                )
              ) 

              if (esCeramica(linea._producto)) {
                etiqueta.textContent =
                  this.value === 'caja'
                    ? 'Tomar cajas'
                    : 'Tomar piezas';
              } else {
                etiqueta.textContent =
                  this.value === 'bolsa'
                    ? 'Tomar bolsas'
                    : 'Tomar piezas';
              }
            }
          );


          actualizarPrecio(linea);
          actualizarConversion(linea);
          validarOrigenes(linea);
          recalcular();
        };


      actualizarPrecio(linea);
      actualizarConversion(linea);
      validarOrigenes(linea);
      recalcular();


    } catch (err) {

      console.error(err);


      lista.innerHTML = `

        <div style="
          padding:1rem;
          color:var(--error);
        ">

          <i class="mdi mdi-alert-circle"></i>

          Error al cargar el stock.

        </div>

      `;

    }
  }


  /* ============================================================
   * VALIDAR ORÍGENES
   * ============================================================ */

  function validarOrigenes(linea) {

    const alerta =
      linea.querySelector(
        '.alerta-stock'
      );


    const inputs =
      linea.querySelectorAll(
        '.cantidad-origen'
      );


    let totalAsignado =
      0;


    inputs.forEach(
      input => {

        totalAsignado +=
          parseFloat(
            input.value
          ) || 0;

      }
    );


    const unidad =
      linea.querySelector(
        '.unidad-select'
      ).value;


    const conversion =
      calcularConversion(linea);


    const requerido =
      obtenerCantidadAsignacion(
        linea
      );


    if (
      requerido <= 0
    ) {

      alerta.className =
        'alerta-stock';

      alerta.innerHTML =
        '';

      return;
    }


    const diferencia =
      totalAsignado -
      requerido;


    let textoUnidad;


    if (
      esCeramica(
        linea._producto
      )
    ) {

      textoUnidad =
        unidad === 'cajas'
          ? 'cajas'
          : 'pieza';

    } else {

      textoUnidad =
        unidad === 'bolsa'
          ? 'bolsas'
          : 'piezas';

    }


    /* CORRECTO */

    if (
      Math.abs(
        diferencia
      ) <= 0.01
    ) {

      alerta.className =
        'alerta-stock ok';


      let detalleFisico = '';


      if (
        esCeramica(
          linea._producto
        )
      ) {

        detalleFisico =
          `
            <br>
            <span style="opacity:0.8;">
              Stock físico que se descontará:
              <strong>
                ${conversion.cantidadStock} piezas
              </strong>
            </span>
          `;
      }


      alerta.innerHTML = `

        <i class="mdi mdi-check-circle"></i>

        <span>

          Stock asignado correctamente:

          <strong>
            ${totalAsignado} ${textoUnidad}
          </strong>

          ${detalleFisico}

        </span>

      `;

      return;
    }


    /* FALTA */

    if (
      diferencia < 0
    ) {

      const falta =
        Math.abs(
          diferencia
        );


      alerta.className =
        'alerta-stock warn';


      alerta.innerHTML = `

        <i class="mdi mdi-alert"></i>

        <span>

          Falta asignar:

          <strong>
            ${falta.toFixed(2)}
            ${textoUnidad}
          </strong>

          <br>

          Total requerido:

          <strong>
            ${requerido}
            ${textoUnidad}
          </strong>

        </span>

      `;

      return;
    }


    /* EXCESO */

    const exceso =
      diferencia;


    alerta.className =
      'alerta-stock danger';


    alerta.innerHTML = `

      <i class="mdi mdi-alert-circle"></i>

      <span>

        Te pasaste por:

        <strong>
          ${exceso.toFixed(2)}
          ${textoUnidad}
        </strong>

      </span>

    `;
  }


  /* ============================================================
   * RECALCULAR TOTALES
   * ============================================================ */
  
  function recalcular() {
    let subtotal = 0;

    contenedor
      .querySelectorAll('.linea-venta')
      .forEach(linea => {
        const producto = linea._producto;
        const unidad = linea.querySelector('.unidad-select').value;

        const cantidad = parseFloat(
          linea.querySelector('.cantidad-input').value
        ) || 0;

        const precio = parseFloat(
          linea.querySelector('.precio-input').value
        ) || 0;

        let subtotalLinea = 0;

        if (producto && cantidad > 0 && precio > 0) {
          if (esCeramica(producto)) {
            const precioCaja = Number(
              producto.precios_venta?.caja || 0
            );

            const m2PorCaja = Number(
              producto.m2_por_caja || 0
            );

            const piezasPorCaja = Number(
              producto.piezas_por_caja || 0
            );

            if (
              precioCaja > 0 &&
              m2PorCaja > 0 &&
              piezasPorCaja > 0
            ) {
              if (unidad === 'caja') {
                subtotalLinea = cantidad * precioCaja;
              } else if (unidad === 'm2') {
                const m2PorPieza = m2PorCaja / piezasPorCaja;

                const piezasNecesarias = Math.ceil(
                  (cantidad / m2PorPieza) - 0.000000001
                );

                subtotalLinea =
                  piezasNecesarias * (precioCaja / piezasPorCaja);
              } else if (unidad === 'pieza') {
                const piezasNecesarias = Math.ceil(cantidad);

                subtotalLinea =
                  piezasNecesarias * (precioCaja / piezasPorCaja);
              }
            }
          } else {
            subtotalLinea = cantidad * precio;
          }
        }

        subtotalLinea = Math.round(
          (subtotalLinea + Number.EPSILON) * 100
        ) / 100;

        subtotal += subtotalLinea;

        linea.querySelector('.linea-subtotal').textContent =
          fmt(subtotalLinea);

        actualizarConversion(linea);
        validarOrigenes(linea);
      });

    subtotal = Math.round(
      (subtotal + Number.EPSILON) * 100
    ) / 100;

    const descuento = Math.max(
      0,
      parseFloat(inputDesc.value) || 0
    );

    const total = Math.max(
      0,
      Math.round(
        (subtotal - descuento + Number.EPSILON) * 100
      ) / 100
    );

    txtSubtotal.textContent = fmt(subtotal);
    txtDesc.textContent = fmt(descuento);
    txtTotal.textContent = fmt(total);
  }

  /* ============================================================
   * AGREGAR LÍNEA
   * ============================================================ */

  function agregarLinea() {

    numLinea++;


    const tmp =
      document.createElement(
        'div'
      );


    tmp.innerHTML =
      template.innerHTML.trim();


    const linea =
      tmp.firstElementChild;


    linea
      .querySelector(
        '[data-num]'
      )
      .textContent =
      numLinea;


    /* ----------------------------------------------------------
     * PRODUCTO
     * ---------------------------------------------------------- */

    linea
      .querySelector(
        '.producto-select'
      )
      .addEventListener(
        'change',
        function () {

          cargarStockProducto(
            linea,
            this.value
          );

        }
      );


    /* ----------------------------------------------------------
     * CANTIDAD
     * ---------------------------------------------------------- */

    linea
      .querySelector(
        '.cantidad-input'
      )
      .addEventListener(
        'input',
        function () {

          actualizarConversion(
            linea
          );

          validarOrigenes(
            linea
          );

          recalcular();

        }
      );


    /* ----------------------------------------------------------
     * PRECIO
     * ---------------------------------------------------------- */

    linea
      .querySelector(
        '.precio-input'
      )
      .addEventListener(
        'input',
        recalcular
      );


    /* ----------------------------------------------------------
     * QUITAR
     * ---------------------------------------------------------- */

    linea
      .querySelector(
        '[data-remove]'
      )
      .addEventListener(
        'click',
        function () {

          linea.remove();

          recalcular();

          renumerar();

        }
      );


    contenedor.appendChild(
      linea
    );


    recalcular();
  }


  /* ============================================================
   * RENUMERAR
   * ============================================================ */

  function renumerar() {

    contenedor
      .querySelectorAll(
        '.linea-venta'
      )
      .forEach(
        (linea, index) => {

          linea
            .querySelector(
              '[data-num]'
            )
            .textContent =
            index + 1;

        }
      );


    numLinea =
      contenedor
        .querySelectorAll(
          '.linea-venta'
        )
        .length;
  }


  /* ============================================================
   * EVENTOS GENERALES
   * ============================================================ */

  btnAgregar.addEventListener(
    'click',
    agregarLinea
  );


  inputDesc.addEventListener(
    'input',
    recalcular
  );


  /* Primera línea */

  agregarLinea();


  /* ============================================================
   * GUARDAR VENTA
   * ============================================================ */

  document
    .getElementById(
      'formVenta'
    )
    .addEventListener(
      'submit',
      function (e) {

        if (
          this.dataset.confirmado === '1'
        ) {

          return;
        }


        e.preventDefault();


        let hayErrores =
          false;

        let mensajeError =
          '';


        /* ======================================================
         * VALIDAR CADA LÍNEA
         * ====================================================== */

        contenedor
          .querySelectorAll(
            '.linea-venta'
          )
          .forEach(
            (linea, index) => {

              const producto =
                linea._producto;


              const cantidad =
                parseFloat(
                  linea.querySelector(
                    '.cantidad-input'
                  ).value
                ) || 0;


              const unidad =
                linea.querySelector(
                  '.unidad-select'
                ).value;


              const conversion =
                calcularConversion(
                  linea
                );


              const cantidadStock =
                conversion.cantidadStock;


              /* PRODUCTO */

              if (!producto) {

                hayErrores = true;

                mensajeError =
                  `Línea ${index + 1}: ` +
                  `selecciona un producto.`;

                return;
              }


              /* UNIDAD */

              if (!unidad) {

                hayErrores = true;

                mensajeError =
                  `Línea ${index + 1}: ` +
                  `selecciona una unidad de venta.`;

                return;
              }


              /* CANTIDAD */

              if (
                cantidad <= 0
              ) {

                hayErrores = true;

                mensajeError =
                  `Línea ${index + 1}: ` +
                  `la cantidad debe ser mayor a 0.`;

                return;
              }
              
              /* PRECIO CONFIGURADO */

              const precioUnitario = Number(
                linea.querySelector('.precio-input').value
              );

              if (
                !Number.isFinite(precioUnitario) ||
                precioUnitario <= 0
              ) {
                hayErrores = true;

                mensajeError =
                  `Línea ${index + 1}: ` +
                  'el producto no tiene un precio de venta configurado ' +
                  'para la unidad seleccionada.';

                return;
              }


              /*
               * CAJAS ENTERAS
               */

              if (
                (
                  unidad === 'caja'
                ) &&
                !Number.isInteger(
                  cantidad
                )
              ) {

                hayErrores = true;

                mensajeError =
                  `Línea ${index + 1}: ` +
                  `la cantidad de cajas debe ser un número entero.`;

                return;
              }


              /*
               * VALIDAR STOCK ASIGNADO
               */

              const inputs =
                linea.querySelectorAll(
                  '.cantidad-origen'
                );


              let totalAsignado =
                0;


              inputs.forEach(
                input => {

                  totalAsignado +=
                    parseFloat(
                      input.value
                    ) || 0;

                }
              );


              const requerido =
                obtenerCantidadAsignacion(
                  linea
                );


              if (
                Math.abs(
                  totalAsignado -
                  requerido
                ) > 0.01
              ) {

                hayErrores = true;

                const unidadOrigen =
                  esCeramica(
                    producto
                  )
                    ? (
                        unidad === 'pieza'
                          ? 'piezas'
                          : 'cajas'
                      )
                    : (
                        unidad === 'bolsa'
                          ? 'bolsas'
                          : 'piezas'
                      );


                mensajeError =
                  `Línea ${index + 1}: ` +
                  `debes asignar ` +
                  `${requerido} ` +
                  `${unidadOrigen} ` +
                  `de stock. ` +
                  `Actualmente asignaste ` +
                  `${totalAsignado}.`;

                return;
              }


              /*
               * EVITAR STOCK CERO
               */

              if (
                cantidadStock <= 0
              ) {

                hayErrores = true;

                mensajeError =
                  `Línea ${index + 1}: ` +
                  `no se pudo calcular correctamente el stock físico.`;

                return;
              }

            }
          );


        if (
          hayErrores
        ) {

          Swal.fire({

            title:
              'Revisa los productos',

            html:
              `<p style="color:var(--error);">
                ${mensajeError}
              </p>`,

            icon:
              'warning',

            confirmButtonText:
              'Entendido',

            buttonsStyling:
              false,

            customClass: {

              confirmButton:
                'swal2-confirm'

            }

          });

          return;
        }


        /* ======================================================
         * CONSTRUIR INPUTS OCULTOS
         * ====================================================== */

        const form =
          this;


        form
          .querySelectorAll(
            'input[name^="items"], select[name^="items"]'
          )
          .forEach(
            el => el.remove()
          );


        let idx =
          0;


        contenedor
          .querySelectorAll(
            '.linea-venta'
          )
          .forEach(
            linea => {

              const productoId =
                linea.querySelector(
                  '.producto-select'
                ).value;


              const cantidad =
                linea.querySelector(
                  '.cantidad-input'
                ).value;


              const unidad =
                linea.querySelector(
                  '.unidad-select'
                ).value;


              const precio =
                linea.querySelector(
                  '.precio-input'
                ).value;


              const addHidden =
                (
                  name,
                  value
                ) => {

                  const hidden =
                    document.createElement(
                      'input'
                    );


                  hidden.type =
                    'hidden';

                  hidden.name =
                    name;

                  hidden.value =
                    value;


                  form.appendChild(
                    hidden
                  );

                };


              /* PRODUCTO */

              addHidden(
                `items[${idx}][producto_id]`,
                productoId
              );


              /* CANTIDAD DE VENTA */

              addHidden(
                `items[${idx}][cantidad]`,
                cantidad
              );


              /* UNIDAD */

              addHidden(
                `items[${idx}][unidad]`,
                unidad
              );


              /* PRECIO */

              addHidden(
                `items[${idx}][precio_unitario]`,
                precio
              );


              /*
               * MOVIMIENTOS
               *
               * MUY IMPORTANTE:
               *
               * Aquí enviamos SIEMPRE
               * cantidad física.
               *
               * Si el usuario seleccionó:
               *
               * 4 cajas
               * 11 piezas por caja
               *
               * y asignó:
               *
               * Admin Juan -> 2 cajas
               * Admin María -> 2 cajas
               *
               * al servidor llegará:
               *
               * Juan -> 22 piezas
               * María -> 22 piezas
               *
               * Total -> 44 piezas
               */

              let mIdx =
                0;


              linea
                .querySelectorAll(
                  '.cantidad-origen'
                )
                .forEach(
                  input => {

                    const cantidadOrigen =
                      parseFloat(
                        input.value
                      ) || 0;


                    if (
                      cantidadOrigen <= 0
                    ) {

                      return;
                    }


                    const userId =
                      input.dataset.userId;


                    const unidadVenta =
                      linea.querySelector(
                        '.unidad-select'
                      ).value;


                    let cantidadFisica =
                      cantidadOrigen;


                    /*
                     * CERÁMICA:
                     *
                     * Caja -> cajas × piezas
                     * m²   -> cajas × piezas
                     * pieza -> piezas
                     */

                    if (
                      esCeramica(
                        linea._producto
                      )
                    ) {

                      const piezasPorCaja =
                        parseInt(
                          linea._producto
                            .piezas_por_caja
                        ) || 1;


                     if (unidadVenta === 'caja') {
                      cantidadFisica =
                        cantidadOrigen * piezasPorCaja;
                    }

                    }


                    addHidden(
                      `items[${idx}][movimientos][${mIdx}][stock_user_id]`,
                      userId
                    );


                    addHidden(
                      `items[${idx}][movimientos][${mIdx}][cantidad]`,
                      cantidadFisica
                    );


                    mIdx++;

                  }
                );


              idx++;

            }
          );


        /* ======================================================
         * CONFIRMACIÓN
         * ====================================================== */

        const total =
          txtTotal.textContent;


        const cantidadLineas =
          contenedor
            .querySelectorAll(
              '.linea-venta'
            )
            .length;


        Swal.fire({

          title:
            '¿Registrar venta?',

          html: `

            <p style="margin:0 0 0.5rem 0;">
              Vas a registrar una venta con:
            </p>

            <p style="margin:0 0 0.5rem 0;">

              <strong
                style="
                  color:var(--cyan);
                  font-size:1.05rem;
                "
              >
                ${cantidadLineas}
              </strong>

              producto${cantidadLineas !== 1 ? 's' : ''}

            </p>

            <p style="
              margin:0;
              font-size:0.9rem;
            ">

              Total:

              <strong
                style="
                  color:var(--cyan);
                "
              >
                ${total}
              </strong>

            </p>

            <p
              style="
                margin:0.75rem 0 0;
                font-size:0.82rem;
                color:var(--ink-muted);
              "
            >

              Se guardará como

              <strong
                style="
                  color:var(--warning);
                "
              >
                pendiente
              </strong>.

              El stock se descontará
              al confirmarla.

            </p>

          `,

          icon:
            'question',

          showCancelButton:
            true,

          confirmButtonText:
            '<i class="mdi mdi-check"></i> Sí, registrar',

          cancelButtonText:
            '<i class="mdi mdi-close"></i> Revisar',

          buttonsStyling:
            false,

          reverseButtons:
            true,

          focusCancel:
            true,

          customClass: {

            confirmButton:
              'swal2-confirm',

            cancelButton:
              'swal2-cancel'

          }

        }).then(
          result => {

            if (
              result.isConfirmed
            ) {

              form.dataset.confirmado =
                '1';

              form.submit();

            }

          }
        );

      }
    );

});
</script>
@endpush