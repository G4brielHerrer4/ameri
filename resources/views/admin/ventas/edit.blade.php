@extends('layouts.plantilla_maestra')

@section('title', 'Editar Venta')

@push('styles')
@include('admin.ventas._styles_create')
@endpush

@section('content')
<div class="container-fluid">

  <div class="compra-header">
    <span class="icon-wrap"><i class="mdi mdi-pencil-outline"></i></span>
    <div>
      <h3>Editar Venta <span style="color:var(--cyan);font-family:var(--font-display);">#{{ $venta->numero_venta }}</span></h3>
      <p>Modifica los datos (solo si está pendiente)</p>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-danger mb-3">
      <i class="mdi mdi-alert-circle me-1"></i> Corrige los errores marcados abajo.
    </div>
  @endif

  <form action="{{ route('admin.ventas.update', $venta) }}" method="POST" id="formVenta" novalidate>
    @csrf @method('PUT')

    {{-- Datos del cliente --}}
    <div class="card-form mb-4">
      <div class="card-head"><i class="mdi mdi-account-box-outline"></i> Datos del cliente</div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-5 mb-3">
            <label class="form-label">Nombre del cliente <span class="required">*</span></label>
            <input type="text" name="cliente_nombre" class="form-control"
                   value="{{ old('cliente_nombre', $venta->cliente_nombre) }}" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">CI / NIT <span class="required">*</span></label>
            <input type="text" name="cliente_documento" class="form-control"
                   value="{{ old('cliente_documento', $venta->cliente_documento) }}" required>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="cliente_telefono" class="form-control"
                   value="{{ old('cliente_telefono', $venta->cliente_telefono) }}">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Fecha de venta <span class="required">*</span></label>
            <input type="date" name="fecha_venta" class="form-control"
                   value="{{ old('fecha_venta', $venta->fecha_venta->format('Y-m-d')) }}" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">N° Venta</label>
            <input type="text" class="form-control" value="{{ $venta->numero_venta }}" readonly
                   style="font-family:var(--font-display);font-weight:600;color:var(--cyan);">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Descuento (Bs.)</label>
            <input type="number" name="descuento" min="0" step="0.01" id="inputDescuento"
                   class="form-control" value="{{ old('descuento', $venta->descuento) }}">
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" rows="2" class="form-control">{{ old('observaciones', $venta->observaciones) }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- Productos --}}
    <div class="card-form mb-4">
      <div class="card-head"><i class="mdi mdi-format-list-bulleted"></i> Productos de la venta</div>
      <div class="card-body">
        <div id="contenedorLineas"></div>
        <button type="button" class="btn-add-line" id="btnAgregarLinea">
          <i class="mdi mdi-plus"></i> Agregar producto
        </button>
      </div>
    </div>

    {{-- Totales --}}
    <div class="row">
      <div class="col-md-6 offset-md-6">
        <div class="totales-panel">
          <div class="total-row"><span>Subtotal</span><span id="txtSubtotal">Bs. 0.00</span></div>
          <div class="total-row"><span>Descuento</span><span id="txtDescuento">Bs. 0.00</span></div>
          <div class="total-row grand"><span>Total a pagar</span><span id="txtTotal">Bs. 0.00</span></div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-3">
          <a href="{{ route('admin.ventas.index') }}" class="btn-secondary-app">
            <i class="mdi mdi-close"></i> Cancelar
          </a>
          <button type="submit" class="btn-primary-app">
            <i class="mdi mdi-content-save"></i> Actualizar venta
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

<template id="templateLinea">
  {{-- mismo template que en create --}}
  <div class="linea-venta" data-linea>
    <div class="linea-head">
      <div class="d-flex align-items-center gap-2">
        <span class="linea-num" data-num>1</span>
        <strong style="font-family:var(--font-display);color:var(--ink);font-size:0.9rem;">Producto</strong>
      </div>
      <button type="button" class="btn-remove-line" data-remove><i class="mdi mdi-close"></i></button>
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
              — <strong>{{ (float) $s->cantidad }}</strong> {{ $s->producto->unidad_base }}
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
        <input type="number" class="form-control precio-input" min="0.01" step="0.01" required>
      </div>
    </div>
    <div class="origenes-section" style="display:none;">
      <div class="origenes-title"><i class="mdi mdi-source-branch"></i> Selecciona de qué admin tomar el stock</div>
      <div class="origenes-lista"></div>
      <div class="alerta-stock"></div>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-2">
      <small class="text-muted">Subtotal línea:</small>
      <span class="linea-subtotal" style="font-family:var(--font-display);font-weight:700;color:var(--cyan);font-size:1rem;">Bs. 0.00</span>
    </div>
  </div>
</template>

<script id="lineasExistentes" type="application/json">{!! json_encode($lineasExistentes ?? []) !!}</script>
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

  const lineasExistentes = JSON.parse(document.getElementById('lineasExistentes').textContent) || [];

  let numLinea = 0;

  function fmt(n) {
    return 'Bs. ' + Number(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  async function cargarStockProducto(linea, productoId, cantidadesPrevias = {}) {
    const origenesSection = linea.querySelector('.origenes-section');
    const lista = linea.querySelector('.origenes-lista');

    if (!productoId) {
      origenesSection.style.display = 'none';
      return;
    }

    lista.innerHTML = '<div style="padding:1rem;text-align:center;"><span class="spinner-inline"></span></div>';
    origenesSection.style.display = '';

    const res = await fetch(`{{ url('admin/ventas/stock-producto') }}/${productoId}`);
    const data = await res.json();

    lista.innerHTML = '';

    data.stocks.forEach(s => {
      const cantidadPrevia = cantidadesPrevias[s.user_id] || 0;

      const item = document.createElement('div');
      item.className = 'origen-item' + (s.es_propio ? ' es-propio' : '');
      item.dataset.userId = s.user_id;
      item.innerHTML = `
        <div class="info">
          <i class="mdi mdi-account-circle"></i>
          <span class="name">${s.user_name}</span>
          ${s.es_propio ? '<span class="label">Mi stock</span>' : ''}
        </div>
        <div class="disponible"><strong>${s.cantidad}</strong> ${data.producto.unidad_base}</div>
        <input type="number" class="cantidad-origen" min="0" step="0.01"
               value="${cantidadPrevia}" data-max="${s.cantidad}" data-user-id="${s.user_id}">
      `;
      lista.appendChild(item);
    });

    lista.querySelectorAll('.cantidad-origen').forEach(input => {
      if (parseFloat(input.value) > 0) input.classList.add('tiene-valor');

      input.addEventListener('input', function () {
        const max = parseFloat(this.dataset.max) || 0;
        const val = parseFloat(this.value) || 0;
        if (val > max) this.value = max;
        if (val > 0) this.classList.add('tiene-valor');
        else this.classList.remove('tiene-valor');
        validarOrigenes(linea);
        recalcular();
      });
    });

    validarOrigenes(linea);
    recalcular();
  }

  function validarOrigenes(linea) {
    const alerta = linea.querySelector('.alerta-stock');
    const cantidad = parseFloat(linea.querySelector('.cantidad-input').value) || 0;
    let total = 0;
    linea.querySelectorAll('.cantidad-origen').forEach(i => total += parseFloat(i.value) || 0);

    const sel = linea.querySelector('.producto-select');
    const unidadBase = sel.options[sel.selectedIndex]?.dataset.unidadBase || 'unidades';

    if (total === cantidad) {
      alerta.className = 'alerta-stock ok';
      alerta.innerHTML = `<i class="mdi mdi-check-circle"></i> <span>Stock asignado: <strong>${total} ${unidadBase}</strong></span>`;
    } else if (total < cantidad) {
      alerta.className = 'alerta-stock warn';
      alerta.innerHTML = `<i class="mdi mdi-alert"></i> <span>Falta asignar <strong>${cantidad - total} ${unidadBase}</strong></span>`;
    } else {
      alerta.className = 'alerta-stock danger';
      alerta.innerHTML = `<i class="mdi mdi-alert-circle"></i> <span>Exceso de <strong>${total - cantidad} ${unidadBase}</strong></span>`;
    }
  }

  function recalcular() {
    let subtotal = 0;
    contenedor.querySelectorAll('.linea-venta').forEach(linea => {
      const c = parseFloat(linea.querySelector('.cantidad-input').value) || 0;
      const p = parseFloat(linea.querySelector('.precio-input').value) || 0;
      subtotal += c * p;
      linea.querySelector('.linea-subtotal').textContent = fmt(c * p);
    });
    const d = parseFloat(inputDesc.value) || 0;
    txtSubtotal.textContent = fmt(subtotal);
    txtDesc.textContent = fmt(d);
    txtTotal.textContent = fmt(Math.max(0, subtotal - d));
  }

  function agregarLinea(datosPre = null) {
    numLinea++;
    const linea = document.createElement('div');
    linea.innerHTML = template.innerHTML.trim();
    linea = linea.firstElementChild;

    linea.querySelector('[data-num]').textContent = numLinea;

    linea.querySelector('.producto-select').addEventListener('change', function () {
      cargarStockProducto(linea, this.value);
    });

    linea.querySelector('.cantidad-input').addEventListener('input', () => { validarOrigenes(linea); recalcular(); });
    linea.querySelector('.precio-input').addEventListener('input', recalcular);
    linea.querySelector('[data-remove]').addEventListener('click', () => {
      linea.remove(); recalcular();
    });

    contenedor.appendChild(linea);

    if (datosPre) {
      linea.querySelector('.producto-select').value = datosPre.producto_id;
      linea.querySelector('.cantidad-input').value = datosPre.cantidad;
      linea.querySelector('.precio-input').value = datosPre.precio_unitario;

      const cantidades = {};
      (datosPre.movimientos || []).forEach(m => cantidades[m.stock_user_id] = m.cantidad);

      cargarStockProducto(linea, datosPre.producto_id, cantidades);
    }

    recalcular();
  }

  btnAgregar.addEventListener('click', () => agregarLinea());
  inputDesc.addEventListener('input', recalcular);

  if (lineasExistentes.length > 0) {
    lineasExistentes.forEach(l => agregarLinea(l));
  } else {
    agregarLinea();
  }

  document.getElementById('formVenta').addEventListener('submit', function (e) {
    if (this.dataset.confirmado === '1') return;
    e.preventDefault();

    const form = this;
    form.querySelectorAll('input[name^="items"]').forEach(el => el.remove());

    let idx = 0;
    contenedor.querySelectorAll('.linea-venta').forEach(linea => {
      const addH = (n, v) => {
        const h = document.createElement('input');
        h.type = 'hidden'; h.name = n; h.value = v;
        form.appendChild(h);
      };

      addH(`items[${idx}][producto_id]`, linea.querySelector('.producto-select').value);
      addH(`items[${idx}][cantidad]`, linea.querySelector('.cantidad-input').value);
      addH(`items[${idx}][precio_unitario]`, linea.querySelector('.precio-input').value);

      let m = 0;
      linea.querySelectorAll('.cantidad-origen').forEach(inp => {
        const val = parseFloat(inp.value) || 0;
        if (val > 0) {
          addH(`items[${idx}][movimientos][${m}][stock_user_id]`, inp.dataset.userId);
          addH(`items[${idx}][movimientos][${m}][cantidad]`, val);
          m++;
        }
      });
      idx++;
    });

    form.dataset.confirmado = '1';
    form.submit();
  });
});
</script>
@endpush