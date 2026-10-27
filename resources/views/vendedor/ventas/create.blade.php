@extends('layouts.plantilla_maestra_ven')

@section('title', 'Nueva Venta - Vendedor')

@push('styles')
<style>
  /* ====== Header ====== */
  .compra-header { display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.75rem; }
  .compra-header .icon-wrap { width: 44px; height: 44px; border-radius: var(--r-md); background: linear-gradient(135deg, var(--primary), var(--primary-2)); color: var(--primary-ink); display: inline-flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: 0 6px 20px -6px var(--glow); flex-shrink: 0; }
  .compra-header h3 { font-family: var(--font-display); font-weight: 600; color: var(--ink); margin: 0; }
  .compra-header p { color: var(--ink-muted); margin: 0.15rem 0 0; font-size: 0.88rem; }

  /* ====== Cards ====== */
  .card-form { background: var(--bg-1); border: 1px solid var(--line); border-radius: var(--r-lg); overflow: hidden; box-shadow: 0 20px 40px -28px var(--shadow); }
  .card-form .card-head { padding: 1.1rem 1.35rem; border-bottom: 1px solid var(--line); background: linear-gradient(180deg, color-mix(in srgb, var(--primary) 4%, transparent), transparent); font-family: var(--font-display); font-weight: 600; color: var(--ink); font-size: 0.98rem; display: flex; align-items: center; gap: 0.6rem; }
  .card-form .card-head .mdi { color: var(--primary); font-size: 1.15rem; }
  .card-form .card-body { padding: 1.35rem; }

  /* ====== Form ====== */
  .form-label { display: flex; align-items: center; gap: 0.35rem; font-size: 0.76rem; font-weight: 600; color: var(--ink-muted); margin-bottom: 0.45rem; font-family: var(--font-display); letter-spacing: 0.04em; text-transform: uppercase; }
  .form-label .required { color: var(--error); }
  .form-control, .form-select { width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--line); border-radius: var(--r-md); background: var(--bg-2); color: var(--ink); font-family: inherit; font-size: 0.9rem; transition: all 0.25s var(--ease); }
  .form-control:focus, .form-select:focus { outline: none; border-color: var(--primary); background: var(--bg-3); box-shadow: 0 0 0 3px var(--glow-soft), 0 0 18px -8px var(--glow); }
  .form-control:disabled, .form-select:disabled { opacity: 0.55; cursor: not-allowed; }

  /* ====== Línea de venta ====== */
  .linea-venta { background: color-mix(in srgb, var(--bg-2) 40%, transparent); border: 1px solid var(--line); border-radius: var(--r-md); padding: 1rem; margin-bottom: 1rem; transition: all 0.3s var(--ease); }
  .linea-venta:hover { border-color: var(--line-strong); }
  .linea-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem; gap: 0.75rem; }
  .linea-num { width: 28px; height: 28px; border-radius: var(--r-sm); background: linear-gradient(135deg, var(--primary), var(--primary-2)); color: var(--primary-ink); font-family: var(--font-display); font-weight: 700; font-size: 0.82rem; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .btn-remove-line { background: transparent; border: 1px solid var(--line); color: var(--ink-muted); width: 32px; height: 32px; border-radius: var(--r-sm); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.25s var(--ease); }
  .btn-remove-line:hover { border-color: var(--error); color: var(--error); background: color-mix(in srgb, var(--error) 8%, transparent); }

  /* ====== Orígenes ====== */
  .origenes-section { border-top: 1px dashed var(--line); margin-top: 0.85rem; padding-top: 0.85rem; }
  .origenes-title { font-size: 0.72rem; font-weight: 700; color: var(--ink-muted); text-transform: uppercase; letter-spacing: 0.06em; font-family: var(--font-display); margin-bottom: 0.6rem; display: flex; align-items: center; gap: 0.4rem; }
  .origenes-title .mdi { color: var(--primary); }
  .origen-item { display: grid; grid-template-columns: 1fr 130px 110px; align-items: center; gap: 0.75rem; padding: 0.6rem 0.75rem; border-radius: var(--r-sm); background: var(--bg-1); border: 1px solid var(--line); margin-bottom: 0.5rem; }
  .origen-item .info { display: flex; align-items: center; gap: 0.55rem; min-width: 0; }
  .origen-item .info .mdi { color: var(--primary); font-size: 1.05rem; flex-shrink: 0; }
  .origen-item .info .name { font-weight: 600; color: var(--ink); font-size: 0.85rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .origen-item .disponible { font-size: 0.8rem; color: var(--ink-muted); text-align: right; font-family: var(--font-display); }
  .origen-item input.cantidad-origen { background: var(--bg-2); border: 1px solid var(--line); color: var(--ink); border-radius: var(--r-sm); padding: 0.45rem 0.6rem; font-size: 0.85rem; width: 100%; text-align: right; font-family: var(--font-display); font-weight: 600; }
  .origen-item input.cantidad-origen.tiene-valor { border-color: var(--line-strong); background: color-mix(in srgb, var(--primary) 8%, transparent); }
  .origen-unidad-label { display: block; margin-top: 0.15rem; color: var(--primary); font-size: 0.68rem; font-weight: 700; text-align: right; }

  /* ====== Alertas ====== */
  .alerta-stock { display: flex; align-items: flex-start; gap: 0.5rem; padding: 0.65rem 0.85rem; border-radius: var(--r-sm); font-size: 0.8rem; margin-top: 0.6rem; line-height: 1.4; }
  .alerta-stock:empty { display: none; }
  .alerta-stock.ok { background: color-mix(in srgb, var(--primary) 8%, transparent); color: var(--primary); border: 1px solid var(--line-strong); }
  .alerta-stock.warn { background: color-mix(in srgb, var(--warning) 12%, transparent); color: var(--warning); border: 1px solid color-mix(in srgb, var(--warning) 45%, transparent); }
  .alerta-stock.error { background: color-mix(in srgb, var(--error) 10%, transparent); color: var(--error); border: 1px solid color-mix(in srgb, var(--error) 45%, transparent); }

  /* ====== Panel faltante ====== */
  .panel-venta { border-radius: var(--r-md); padding: 0.9rem 1rem; margin-top: 0.75rem; background: var(--bg-1); }
  .panel-venta .panel-titulo { font-family: var(--font-display); font-weight: 700; font-size: 0.88rem; margin-bottom: 0.45rem; display: flex; align-items: center; gap: 0.4rem; }
  .panel-venta .panel-texto { font-size: 0.83rem; color: var(--ink-muted); line-height: 1.45; }
  .panel-venta .panel-acciones { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.8rem; }

  .panel-venta.faltante { border: 1px solid color-mix(in srgb, var(--warning) 55%, transparent); background: color-mix(in srgb, var(--warning) 8%, transparent); }
  .panel-venta.faltante .panel-titulo { color: var(--warning); }

  .panel-venta.interna { border: 1px solid color-mix(in srgb, var(--primary) 35%, transparent); background: color-mix(in srgb, var(--primary) 5%, transparent); }
  .panel-venta.interna .panel-titulo { color: var(--primary); }

  .btn-panel { padding: 0.55rem 1rem; font-size: 0.8rem; font-weight: 700; border-radius: var(--r-md); border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.25s var(--ease); white-space: nowrap; }
  .btn-panel:hover { transform: translateY(-1px); box-shadow: 0 6px 16px -8px var(--shadow); }
  .btn-panel.btn-notificar { background: var(--error); color: #fff; }
  .btn-panel.btn-notificar:hover { background: color-mix(in srgb, var(--error) 88%, #000); }

  .otros-lista { display: flex; flex-direction: column; gap: 0.45rem; margin-top: 0.6rem; }
  .otro-item { display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; background: var(--bg-2); padding: 0.55rem 0.75rem; border-radius: var(--r-sm); border: 1px solid var(--line); font-size: 0.82rem; }
  .otro-item .otro-info { min-width: 0; }
  .otro-item .otro-info strong { color: var(--ink); }
  .otro-item .otro-info small { display: block; color: var(--ink-muted); font-size: 0.72rem; margin-top: 0.1rem; }

  /* ====== Botones generales ====== */
  .btn-secondary-app { padding: 0.7rem 1.5rem; border-radius: var(--r-pill); border: 1px solid var(--line); background: var(--bg-2); color: var(--ink); font-weight: 700; font-size: 0.88rem; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.3s var(--ease); }
  .btn-secondary-app:hover { border-color: var(--line-strong); color: var(--primary); transform: translateY(-2px); }
  .btn-primary-app { padding: 0.7rem 1.75rem; border-radius: var(--r-pill); border: none; background: linear-gradient(135deg, var(--primary), var(--primary-2)); color: var(--primary-ink); font-weight: 700; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.3s var(--ease); box-shadow: 0 6px 20px -6px var(--glow); }
  .btn-primary-app:hover { transform: translateY(-2px); box-shadow: 0 12px 28px -6px var(--glow); }
  .btn-primary-app:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

  /* ====== Totales ====== */
  .totales-panel { background: var(--bg-2); border-radius: var(--r-md); padding: 1.15rem 1.35rem; border: 1px solid var(--line); }
  .total-row { display: flex; justify-content: space-between; align-items: center; padding: 0.45rem 0; font-size: 0.88rem; color: var(--ink-muted); }
  .total-row span:last-child { font-family: var(--font-display); font-weight: 600; color: var(--ink); }
  .total-row.grand { padding-top: 0.85rem; margin-top: 0.5rem; border-top: 1px solid var(--line); font-size: 1rem; color: var(--ink); }
  .total-row.grand span:last-child { font-size: 1.35rem; color: var(--primary); text-shadow: 0 0 20px var(--glow-soft); }

  /* ====== Loader ====== */
  .spinner-inline { display: inline-block; width: 14px; height: 14px; border: 2px solid var(--line-strong); border-top-color: var(--primary); border-radius: 50%; animation: spin 0.6s linear infinite; vertical-align: middle; }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ====== SweetAlert2 ====== */
  .swal2-popup { background: var(--bg-1) !important; color: var(--ink) !important; border: 1px solid var(--line-strong) !important; border-radius: var(--r-lg) !important; font-family: var(--font-body) !important; }
  .swal2-title { font-family: var(--font-display) !important; color: var(--ink) !important; font-weight: 600 !important; }
  .swal2-html-container { color: var(--ink-muted) !important; }
  .swal2-confirm, .swal2-cancel { font-weight: 700 !important; padding: 0.7rem 1.5rem !important; border-radius: var(--r-pill) !important; border: none !important; }
  .swal2-confirm { background: linear-gradient(135deg, var(--primary), var(--primary-2)) !important; color: var(--primary-ink) !important; }
  .swal2-cancel { background: var(--bg-2) !important; color: var(--ink) !important; border: 1px solid var(--line) !important; }

  /* ====== Responsive ====== */
  @media (max-width: 767.98px) {
    .compra-header h3 { font-size: 1.1rem; }
    .origen-item { grid-template-columns: 1fr 1fr; gap: 0.5rem; }
    .origen-item .disponible { text-align: left; }
    .panel-acciones .btn-panel { flex: 1 1 100%; justify-content: center; }
    .totales-panel { padding: 0.9rem 1rem; }
    .btn-primary-app, .btn-secondary-app { flex: 1 1 100%; justify-content: center; }
  }
</style>
@endpush

@section('content')
<div class="container-fluid">

  {{-- Header --}}
  <div class="compra-header">
    <span class="icon-wrap"><i class="mdi mdi-cart-plus"></i></span>
    <div>
      <h3>Nueva Venta</h3>
      <p>Selecciona el administrador y los productos a vender</p>
    </div>
  </div>

  {{-- Errores --}}
  @if($errors->any())
    <div class="alert alert-danger mb-3">
      <i class="mdi mdi-alert-circle me-1"></i> Corrige los errores marcados abajo.
      <ul class="mb-0 mt-2">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
  @endif

  <form action="{{ route('vendedor.ventas.store') }}" method="POST" id="formVenta" novalidate>
    @csrf

    {{-- Datos de la venta --}}
    <div class="card-form mb-4">
      <div class="card-head"><i class="mdi mdi-account-box-outline"></i> Datos de la Venta</div>
      <div class="card-body">
        <div class="row">

          <div class="col-12 mb-4">
            <label class="form-label">¿Para qué Administrador vendes? <span class="required">*</span></label>
            <select name="admin_id" id="admin_id" class="form-select" required>
              <option value="">-- Selecciona al Administrador (Dueño del Stock) --</option>
              @foreach($admins as $admin)
                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
              @endforeach
            </select>
            <small class="text-muted d-block mt-1">El sistema buscará los productos en el inventario de este administrador.</small>
          </div>

          <div class="col-md-5 mb-3">
            <label class="form-label">Nombre del cliente <span class="required">*</span></label>
            <input type="text" name="cliente_nombre" class="form-control" value="{{ old('cliente_nombre') }}" required placeholder="Ej: Juan Pérez">
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">CI / NIT <span class="required">*</span></label>
            <input type="text" name="cliente_documento" class="form-control" value="{{ old('cliente_documento') }}" required placeholder="Ej: 12345678">
          </div>

          <div class="col-md-3 mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="cliente_telefono" class="form-control" value="{{ old('cliente_telefono') }}" placeholder="Opcional">
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Fecha de venta <span class="required">*</span></label>
            <input type="date" name="fecha_venta" class="form-control" value="{{ old('fecha_venta', now()->format('Y-m-d')) }}" required>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Descuento (Bs.)</label>
            <input type="number" name="descuento" min="0" step="0.01" id="inputDescuento" class="form-control" value="{{ old('descuento', 0) }}">
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" rows="2" class="form-control" placeholder="Notas sobre esta venta...">{{ old('observaciones') }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- Productos --}}
    <div class="card-form mb-4">
      <div class="card-head"><i class="mdi mdi-format-list-bulleted"></i> Productos de la venta</div>
      <div class="card-body">

        <div id="mensajeAdmin" class="alert mb-3" style="background: color-mix(in srgb, var(--primary) 10%, transparent); color: var(--primary); border: 1px solid var(--line-strong);">
          <i class="mdi mdi-information-outline"></i> Selecciona un administrador primero para cargar los productos.
        </div>

        <div id="contenedorLineas"></div>

        <button type="button" class="btn-secondary-app" id="btnAgregarLinea" disabled>
          <i class="mdi mdi-plus"></i> Agregar producto
        </button>
      </div>
    </div>

    {{-- Totales --}}
    <div class="row">
      <div class="col-lg-6 offset-lg-6">
        <div class="totales-panel">
          <div class="total-row"><span>Subtotal</span><span id="txtSubtotal">Bs. 0.00</span></div>
          <div class="total-row"><span>Descuento</span><span id="txtDescuento">Bs. 0.00</span></div>
          <div class="total-row grand"><span>Total a pagar</span><span id="txtTotal">Bs. 0.00</span></div>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-3">
          <a href="{{ route('vendedor.ventas.index') }}" class="btn-secondary-app"><i class="mdi mdi-close"></i> Cancelar</a>
          <button type="submit" class="btn-primary-app" id="btnGuardar"><i class="mdi mdi-content-save"></i> Guardar Venta</button>
        </div>
      </div>
    </div>
  </form>
</div>

{{-- Template línea --}}
<template id="templateLinea">
  <div class="linea-venta" data-linea>
    <div class="linea-head">
      <div class="d-flex align-items-center gap-2">
        <span class="linea-num" data-num>1</span>
        <strong style="font-family:var(--font-display);color:var(--ink);font-size:0.9rem;">Producto</strong>
      </div>
      <button type="button" class="btn-remove-line" data-remove title="Quitar"><i class="mdi mdi-close"></i></button>
    </div>

    <div class="row">
      <div class="col-md-5 mb-2">
        <label class="form-label">Producto <span class="required">*</span></label>
        <select class="form-select producto-select" required>
          <option value="">Seleccione un producto...</option>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <label class="form-label">Unidad <span class="required">*</span></label>
        <select class="form-select unidad-select" required disabled><option value="">--</option></select>
      </div>
      <div class="col-md-2 mb-2">
        <label class="form-label">Cantidad <span class="required">*</span></label>
        <input type="number" class="form-control cantidad-input" min="0.01" step="0.01" value="1" required disabled>
      </div>
      <div class="col-md-3 mb-2">
        <label class="form-label">Precio Unit. <span class="required">*</span></label>
        <input type="number" class="form-control precio-input" min="0.01" step="0.01" required placeholder="Autocalculado" readonly disabled>
      </div>
    </div>

    <div class="origenes-section" style="display:none;">
      <div class="origenes-title"><i class="mdi mdi-source-branch"></i> Selecciona de qué tienda tomar</div>
      <div class="origenes-lista"></div>
      <div class="alerta-stock"></div>
      <div class="panel-faltante-container"></div>
      <div class="panel-interna-container"></div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-2">
      <small class="text-muted">Subtotal línea:</small>
      <span class="linea-subtotal" style="font-family:var(--font-display);font-weight:700;color:var(--primary);font-size:1rem;">Bs. 0.00</span>
    </div>
  </div>
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  /* ================== Referencias ================== */
  const adminSelect = document.getElementById('admin_id');
  const btnAgregar  = document.getElementById('btnAgregarLinea');
  const contenedor  = document.getElementById('contenedorLineas');
  const template    = document.getElementById('templateLinea');
  const msgAdmin    = document.getElementById('mensajeAdmin');

  const txtSubtotal = document.getElementById('txtSubtotal');
  const txtDesc     = document.getElementById('txtDescuento');
  const txtTotal    = document.getElementById('txtTotal');
  const inputDesc   = document.getElementById('inputDescuento');

  let numLinea = 0;
  let productosAdminCache = [];

  /* ================== Helpers ================== */
  function fmt(n) {
    return 'Bs. ' + Number(n || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  function convertirAStock(producto, cantidad, unidad) {
    if (!producto) return cantidad;
    const tipo = (producto.tipo || '').toLowerCase();
    const esCeramica = ['cerámica', 'ceramica', 'porcelanato'].includes(tipo);

    if (!esCeramica) return cantidad;

    const piezasPorCaja = Number(producto.piezas_por_caja || 0);
    const m2PorCaja     = Number(producto.m2_por_caja || 0);

    if (unidad === 'caja')  return cantidad * piezasPorCaja;
    if (unidad === 'pieza') return cantidad;
    if (unidad === 'm2') {
      if (piezasPorCaja <= 0 || m2PorCaja <= 0) return cantidad;
      const m2PorPieza = m2PorCaja / piezasPorCaja;
      return Math.ceil((cantidad / m2PorPieza) - 0.000000001);
    }
    return cantidad;
  }

  /* ================== Cambio de admin ================== */
  adminSelect.addEventListener('change', async function () {
    const adminId = this.value;

    if (!adminId) {
      msgAdmin.style.display = 'block';
      msgAdmin.innerHTML = '<i class="mdi mdi-information-outline"></i> Selecciona un administrador primero para cargar los productos.';
      btnAgregar.disabled = true;
      contenedor.innerHTML = '';
      productosAdminCache = [];
      recalcular();
      return;
    }

    msgAdmin.style.display = 'block';
    msgAdmin.innerHTML = '<span class="spinner-inline"></span> Cargando productos del administrador...';
    btnAgregar.disabled = true;

    try {
      const res = await fetch(`{{ url('vendedor/ventas/productos-admin') }}/${adminId}`);
      if (!res.ok) throw new Error('Error al cargar productos');
      const data = await res.json();
      productosAdminCache = data.productos || [];

      if (productosAdminCache.length === 0) {
        msgAdmin.innerHTML = '<i class="mdi mdi-alert"></i> Este administrador no tiene productos con stock.';
        btnAgregar.disabled = true;
        contenedor.innerHTML = '';
        return;
      }

      msgAdmin.style.display = 'none';
      btnAgregar.disabled = false;
      contenedor.innerHTML = '';
      numLinea = 0;
      agregarLinea();
      recalcular();
    } catch (err) {
      console.error(err);
      msgAdmin.innerHTML = '<i class="mdi mdi-alert-circle"></i> Error al cargar productos.';
      btnAgregar.disabled = true;
    }
  });

  /* ================== Agregar línea ================== */
  function agregarLinea() {
    numLinea++;
    const tmp = document.createElement('div');
    tmp.innerHTML = template.innerHTML.trim();
    const linea = tmp.firstElementChild;
    linea.querySelector('[data-num]').textContent = numLinea;

    const selectProd = linea.querySelector('.producto-select');
    selectProd.innerHTML = '<option value="">Seleccione un producto...</option>';
    productosAdminCache.forEach(p => {
      const opt = document.createElement('option');
      opt.value = p.id;
      opt.textContent = `${p.nombre} (${p.cantidad_total} ${p.unidad_base})`;
      selectProd.appendChild(opt);
    });

    selectProd.addEventListener('change', function () {
      cargarStockProducto(linea, this.value);
    });

    linea.querySelector('[data-remove]').addEventListener('click', function () {
      linea.remove();
      recalcular();
      renumerar();
    });

    contenedor.appendChild(linea);
    recalcular();
  }

  btnAgregar.addEventListener('click', agregarLinea);

  function renumerar() {
    contenedor.querySelectorAll('.linea-venta').forEach((linea, index) => {
      linea.querySelector('[data-num]').textContent = index + 1;
    });
    numLinea = contenedor.querySelectorAll('.linea-venta').length;
  }

  /* ================== Cargar stock producto ================== */
  async function cargarStockProducto(linea, productoId) {
    const adminId = adminSelect.value;
    const origenesSection = linea.querySelector('.origenes-section');
    const lista           = linea.querySelector('.origenes-lista');
    const alerta          = linea.querySelector('.alerta-stock');
    const panelFaltante   = linea.querySelector('.panel-faltante-container');
    const panelInterna    = linea.querySelector('.panel-interna-container');
    const unidadSelect    = linea.querySelector('.unidad-select');
    const cantidadInput   = linea.querySelector('.cantidad-input');
    const precioInput     = linea.querySelector('.precio-input');

    alerta.innerHTML = '';
    alerta.className = 'alerta-stock';
    panelFaltante.innerHTML = '';
    panelInterna.innerHTML = '';

    if (!productoId || !adminId) {
      origenesSection.style.display = 'none';
      return;
    }

    lista.innerHTML = `<div style="padding:1rem;text-align:center;"><span class="spinner-inline"></span> Cargando stock...</div>`;
    origenesSection.style.display = 'block';

    try {
      const res = await fetch(`{{ url('vendedor/ventas/stock-producto') }}/${productoId}?admin_id=${adminId}`);
      if (!res.ok) throw new Error('Error de red');
      const data = await res.json();

      linea._producto        = data.producto;
      linea._stockTotalAdmin = Number(data.stock_total_admin || 0);
      linea._adminInfo       = data.admin || { id: adminId, name: 'Administrador' };
      linea._otrosStocks     = data.otros_stocks || [];

      unidadSelect.innerHTML = '<option value="">Seleccione...</option>';
      (data.producto.unidades_venta || []).forEach(u => {
        const opt = document.createElement('option');
        opt.value = u;
        opt.textContent = u.charAt(0).toUpperCase() + u.slice(1);
        unidadSelect.appendChild(opt);
      });
      unidadSelect.disabled = false;
      cantidadInput.disabled = false;
      unidadSelect.value = (data.producto.unidades_venta || [])[0] || '';

      actualizarPrecio(linea);
      configurarCantidad(linea);

      lista.innerHTML = '';
      const stocks = data.stocks || [];

      if (stocks.length === 0) {
        lista.innerHTML = `<div style="padding:0.8rem;color:var(--warning);"><i class="mdi mdi-alert"></i> El administrador no tiene stock de este producto.</div>`;
      } else {
        stocks.forEach(stock => {
          const item = document.createElement('div');
          item.className = 'origen-item';
          item.dataset.depositoId = stock.deposito_id;
          item.innerHTML = `
            <div class="info">
              <i class="mdi mdi-storefront-outline"></i>
              <div class="name">${stock.deposito_nombre}</div>
            </div>
            <div class="disponible"><span>${stock.cantidad} ${data.producto.unidad_base}</span></div>
            <div>
              <input type="number" class="cantidad-origen" min="0" value="0" data-max="${stock.cantidad}" data-deposito-id="${stock.deposito_id}">
              <span class="origen-unidad-label">Tomar</span>
            </div>
          `;
          lista.appendChild(item);
        });

        lista.querySelectorAll('.cantidad-origen').forEach(input => {
          input.addEventListener('input', function () {
            let val = parseFloat(this.value) || 0;
            const max = parseFloat(this.dataset.max) || 0;
            if (val > max) val = max;
            if (val < 0) val = 0;
            this.value = val;
            if (val > 0) this.classList.add('tiene-valor');
            else this.classList.remove('tiene-valor');
            validarOrigenes(linea);
            recalcular();
          });
        });
      }

      unidadSelect.onchange = function () {
        configurarCantidad(linea);
        actualizarPrecio(linea);
        validarOrigenes(linea);
        recalcular();
      };

      cantidadInput.oninput = function () {
        validarOrigenes(linea);
        recalcular();
      };

      validarOrigenes(linea);
      recalcular();

    } catch (err) {
      console.error(err);
      lista.innerHTML = `<div style="padding:1rem;color:var(--error);"><i class="mdi mdi-alert-circle"></i> Error al cargar.</div>`;
    }
  }

  /* ================== Precio / cantidad ================== */
  function actualizarPrecio(linea) {
    const precioInput = linea.querySelector('.precio-input');
    const unidad = linea.querySelector('.unidad-select').value;
    const producto = linea._producto;
    if (!producto || !unidad) return;

    const precios = producto.precios_venta || {};
    const precio = precios[unidad];

    if (precio) {
      precioInput.value = Number(precio).toFixed(2);
      precioInput.disabled = false;
    } else {
      precioInput.value = '';
      precioInput.disabled = true;
    }
    recalcular();
  }

  function configurarCantidad(linea) {
    const input = linea.querySelector('.cantidad-input');
    const unidad = linea.querySelector('.unidad-select').value;
    input.step = (unidad === 'm2') ? '0.01' : '1';
    input.min  = (unidad === 'm2') ? '0.01' : '1';
  }

  /* ================== Validar orígenes ================== */
  function validarOrigenes(linea) {
    const alerta         = linea.querySelector('.alerta-stock');
    const inputs         = linea.querySelectorAll('.cantidad-origen');
    const panelFaltante  = linea.querySelector('.panel-faltante-container');
    const panelInterna   = linea.querySelector('.panel-interna-container');

    const producto      = linea._producto;
    const unidadVenta   = linea.querySelector('.unidad-select').value;
    const cantidadInput = parseFloat(linea.querySelector('.cantidad-input').value) || 0;

    alerta.innerHTML = '';
    alerta.className = 'alerta-stock';
    panelFaltante.innerHTML = '';
    panelInterna.innerHTML = '';

    if (!producto || cantidadInput <= 0) return;

    const requeridoStock  = convertirAStock(producto, cantidadInput, unidadVenta);
    const stockTotalAdmin = Number(linea._stockTotalAdmin || 0);

    let totalAsignado = 0;
    inputs.forEach(i => totalAsignado += parseFloat(i.value) || 0);

    const faltaAsignar = +(requeridoStock - totalAsignado).toFixed(2);
    const faltaReal    = Math.max(0, +(requeridoStock - stockTotalAdmin).toFixed(2));

    // 1) Todo correcto
    if (Math.abs(faltaAsignar) <= 0.01 && faltaReal <= 0.01) {
      alerta.className = 'alerta-stock ok';
      alerta.innerHTML = `<i class="mdi mdi-check-circle"></i> Stock asignado correctamente (${totalAsignado} / ${requeridoStock} ${producto.unidad_base}).`;
      return;
    }

    // 2) Falta asignar entre tiendas pero el admin sí tiene suficiente
    if (faltaReal <= 0.01 && faltaAsignar > 0) {
      alerta.className = 'alerta-stock warn';
      alerta.innerHTML = `<i class="mdi mdi-alert"></i> Te falta asignar <strong>${faltaAsignar} ${producto.unidad_base}</strong> entre las tiendas.`;
      return;
    }

    // 3) Se pasó
    if (faltaAsignar < -0.01) {
      alerta.className = 'alerta-stock warn';
      alerta.innerHTML = `<i class="mdi mdi-alert-circle"></i> Te pasaste por <strong>${Math.abs(faltaAsignar)} ${producto.unidad_base}</strong>.`;
      return;
    }

    // 4) El admin NO tiene suficiente stock total
    if (faltaReal > 0.01) {
      alerta.className = 'alerta-stock error';
      alerta.innerHTML = `
        <i class="mdi mdi-close-circle"></i>
        Necesitas <strong>${requeridoStock} ${producto.unidad_base}</strong> y
        <strong>${linea._adminInfo.name}</strong> solo tiene
        <strong>${stockTotalAdmin} ${producto.unidad_base}</strong>.
        <strong>Faltan ${faltaReal} ${producto.unidad_base}</strong>.
      `;

      panelFaltante.innerHTML = renderPanelFaltante(linea, producto, faltaReal, requeridoStock, stockTotalAdmin);
      bindPanelFaltante(linea, producto);

      if (linea._otrosStocks && linea._otrosStocks.length > 0) {
        panelInterna.innerHTML = renderPanelOtrosAdmins(linea._otrosStocks);
      }
    }
  }

  /* ================== Panel: falta stock ================== */
  function renderPanelFaltante(linea, producto, faltante, total, stock) {
    const unidad = producto.unidad_base;
    return `
      <div class="panel-venta faltante">
        <div class="panel-titulo"><i class="mdi mdi-alert-circle"></i> Falta stock del administrador</div>
        <div class="panel-texto">
          <strong>${linea._adminInfo.name}</strong> solo tiene <strong>${stock} ${unidad}</strong>,
          pero necesitas <strong>${total} ${unidad}</strong>.
          <br>Faltan <strong>${faltante} ${unidad}</strong>.
        </div>
        <div class="panel-acciones">
          <button type="button" class="btn-panel btn-notificar btn-notificar-faltante"
            data-producto="${producto.id}"
            data-faltante="${faltante}"
            data-total="${total}"
            data-stock="${stock}">
            <i class="mdi mdi-bell-ring"></i> Notificar al administrador (faltan ${faltante})
          </button>
        </div>
      </div>
    `;
  }

  function bindPanelFaltante(linea, producto) {
    linea.querySelectorAll('.btn-notificar-faltante').forEach(btn => {
      btn.addEventListener('click', function () {
        const adminId     = adminSelect.value;
        const productoId  = this.dataset.producto;
        const faltante    = this.dataset.faltante;
        const total       = this.dataset.total;
        const stockActual = this.dataset.stock;
        const unidad      = producto.unidad_base;

        Swal.fire({
          title: '¿Notificar al administrador?',
          html: `Se le avisará a <b>${linea._adminInfo.name}</b> que faltan <b>${faltante} ${unidad}</b> de <b>${producto.nombre}</b> para completar la venta.`,
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: '<i class="mdi mdi-send"></i> Sí, notificar',
          cancelButtonText: 'Cancelar',
          buttonsStyling: false,
          customClass: { confirmButton: 'swal2-confirm', cancelButton: 'swal2-cancel' }
        }).then(result => {
          if (!result.isConfirmed) return;

          fetch("{{ route('vendedor.ventas.notificar-faltante') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
              admin_id: adminId,
              producto_id: productoId,
              cantidad_faltante: faltante,
              cantidad_total: total,
              stock_actual: stockActual
            })
          })
          .then(r => r.json())
          .then(data => {
            Swal.fire({
              title: data.success ? 'Notificación enviada' : 'Error',
              text: data.message || '',
              icon: data.success ? 'success' : 'error',
              buttonsStyling: false,
              customClass: { confirmButton: 'swal2-confirm' }
            });
          })
          .catch(() => Swal.fire({ title: 'Error de red', text: 'No se pudo conectar.', icon: 'error', buttonsStyling: false, customClass: { confirmButton: 'swal2-confirm' } }));
        });
      });
    });
  }

  /* ================== Panel: otros admins (solo informativo) ================== */
  function renderPanelOtrosAdmins(otros) {
    const items = otros.map(o => {
      const tiendas = (o.detalle_tiendas || []).map(t => `${t.deposito_nombre}: ${t.cantidad}`).join(' · ');
      return `
        <div class="otro-item">
          <div class="otro-info">
            <strong>${o.user_name}</strong>
            <small>${tiendas}</small>
          </div>
        </div>
      `;
    }).join('');

    return `
      <div class="panel-venta interna">
        <div class="panel-titulo"><i class="mdi mdi-account-switch"></i> Otros administradores con stock</div>
        <div class="panel-texto">Estos administradores sí tienen stock del producto. Coordina con ellos una venta interna.</div>
        <div class="otros-lista">${items}</div>
      </div>
    `;
  }

  /* ================== Recalcular totales ================== */
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

  inputDesc.addEventListener('input', recalcular);

  /* ================== Submit ================== */
  document.getElementById('formVenta').addEventListener('submit', function (e) {
    if (this.dataset.confirmado === '1') return;
    e.preventDefault();

    if (!adminSelect.value) {
      Swal.fire({ title: 'Falta Administrador', text: 'Debes seleccionar el administrador para esta venta.', icon: 'warning', buttonsStyling: false, customClass: { confirmButton: 'swal2-confirm' } });
      return;
    }

    const lineas = contenedor.querySelectorAll('.linea-venta');
    if (lineas.length === 0) {
      Swal.fire({ title: 'Sin productos', text: 'Agrega al menos un producto a la venta.', icon: 'warning', buttonsStyling: false, customClass: { confirmButton: 'swal2-confirm' } });
      return;
    }

    let hayErrores = false;
    let mensajeError = '';

    lineas.forEach((linea, index) => {
      if (hayErrores) return;
      const producto = linea.querySelector('.producto-select').value;
      const cantidad = parseFloat(linea.querySelector('.cantidad-input').value) || 0;

      if (!producto) { hayErrores = true; mensajeError = `Línea ${index + 1}: selecciona un producto.`; return; }
      if (cantidad <= 0) { hayErrores = true; mensajeError = `Línea ${index + 1}: cantidad mayor a 0.`; return; }

      const prod = linea._producto;
      if (!prod) { hayErrores = true; mensajeError = `Línea ${index + 1}: espera a que cargue el stock del producto.`; return; }

      const unidad  = linea.querySelector('.unidad-select').value;
      const reqStock = convertirAStock(prod, cantidad, unidad);

      let totalAsignado = 0;
      linea.querySelectorAll('.cantidad-origen').forEach(i => totalAsignado += parseFloat(i.value) || 0);

      if (Math.abs(totalAsignado - reqStock) > 0.01) {
        hayErrores = true;
        mensajeError = `Línea ${index + 1}: debes asignar exactamente ${reqStock} ${prod.unidad_base} de stock (tienes ${totalAsignado}).`;
      }
    });

    if (hayErrores) {
      Swal.fire({ title: 'Revisa los productos', html: `<p style="color:var(--error);">${mensajeError}</p>`, icon: 'warning', buttonsStyling: false, customClass: { confirmButton: 'swal2-confirm' } });
      return;
    }

    const form = this;
    form.querySelectorAll('input[type="hidden"][name^="items"]').forEach(el => el.remove());

    let idx = 0;
    lineas.forEach(linea => {
      const productoId = linea.querySelector('.producto-select').value;
      const cantidad   = linea.querySelector('.cantidad-input').value;
      const unidad     = linea.querySelector('.unidad-select').value;
      const precio     = linea.querySelector('.precio-input').value;

      const add = (name, value) => {
        const h = document.createElement('input');
        h.type = 'hidden'; h.name = name; h.value = value;
        form.appendChild(h);
      };

      add(`items[${idx}][producto_id]`, productoId);
      add(`items[${idx}][cantidad]`, cantidad);
      add(`items[${idx}][unidad]`, unidad);
      add(`items[${idx}][precio_unitario]`, precio);

      let mIdx = 0;
      linea.querySelectorAll('.cantidad-origen').forEach(input => {
        const val = parseFloat(input.value) || 0;
        if (val <= 0) return;
        add(`items[${idx}][movimientos][${mIdx}][stock_user_id]`, adminSelect.value);
        add(`items[${idx}][movimientos][${mIdx}][deposito_id]`, input.dataset.depositoId);
        add(`items[${idx}][movimientos][${mIdx}][cantidad]`, val);
        mIdx++;
      });
      idx++;
    });

    Swal.fire({
      title: '¿Registrar venta?',
      html: '<p>Vas a registrar una <b>venta pendiente</b> para el administrador seleccionado.</p>',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-content-save"></i> Sí, registrar',
      cancelButtonText: 'Revisar',
      buttonsStyling: false,
      customClass: { confirmButton: 'swal2-confirm', cancelButton: 'swal2-cancel' }
    }).then(result => {
      if (result.isConfirmed) {
        form.dataset.confirmado = '1';
        form.submit();
      }
    });
  });
});
</script>
@endpush