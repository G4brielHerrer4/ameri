@extends('layouts.plantilla_maestra')

@section('title', 'Editar Compra')

@push('styles')
@include('admin.compras.partials._styles_form')
@endpush

@section('content')
<div class="container-fluid">

  <div class="compra-header">
    <span class="icon-wrap"><i class="mdi mdi-pencil-outline"></i></span>
    <div>
      <h3>Editar Compra <span style="color:var(--cyan);font-family:var(--font-display);">#{{ $compra->numero_orden }}</span></h3>
      <p>Modifica los datos de la orden (solo si está pendiente)</p>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-danger mb-3">
      <i class="mdi mdi-alert-circle me-1"></i>
      Corrige los errores marcados abajo.
    </div>
  @endif

  <form action="{{ route('admin.compras.update', $compra) }}" method="POST" id="formCompra" novalidate>
    @csrf
    @method('PUT')

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
                <option value="{{ $p->id }}" {{ old('proveedor_id', $compra->proveedor_id) == $p->id ? 'selected' : '' }}>
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
                <option value="{{ $a->id }}" {{ old('user_id', $compra->user_id) == $a->id ? 'selected' : '' }}>
                  {{ $a->name }} — {{ $a->email }}
                </option>
              @endforeach
            </select>
            @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Fecha de compra <span class="required">*</span></label>
            <input type="date" name="fecha_compra" class="form-control @error('fecha_compra') is-invalid @enderror"
                   value="{{ old('fecha_compra', $compra->fecha_compra->format('Y-m-d')) }}" required>
            @error('fecha_compra') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">N° Orden</label>
            <input type="text" class="form-control" value="{{ $compra->numero_orden }}" readonly
                   style="font-family:var(--font-display);font-weight:600;color:var(--cyan);">
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Descuento (Bs.)</label>
            <input type="number" name="descuento" min="0" step="0.01" id="inputDescuento"
                   class="form-control" value="{{ old('descuento', $compra->descuento) }}">
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" rows="2" class="form-control"
                      placeholder="Notas internas sobre esta compra...">{{ old('observaciones', $compra->observaciones) }}</textarea>
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
            <tbody id="cuerpoLineas"></tbody>
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
            <i class="mdi mdi-content-save"></i> Actualizar compra
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

{{-- Datos de las líneas actuales para precargar --}}
<script id="lineasExistentes" type="application/json">
  @json($compra->detalles->map(function($d) {
      return [
          'producto_id'          => $d->producto_id,
          'cantidad'             => (float) $d->cantidad,
          'unidad_compra'        => $d->unidad_compra,
          'unidades_por_paquete' => (float) $d->unidades_por_paquete,
          'precio_unitario'      => (float) $d->precio_unitario,
      ];
  }))
</script>
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

  const lineasExistentes = JSON.parse(document.getElementById('lineasExistentes').textContent);

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

  function agregarLinea(datosPre = null) {
    const html = template.innerHTML.replace(/__IDX__/g, idx++);
    const tmp  = document.createElement('tbody');
    tmp.innerHTML = html.trim();
    const fila = tmp.firstElementChild;

    fila.querySelector('.producto-select').addEventListener('change', function () {
      const opt = this.options[this.selectedIndex];
      if (!this.value) {
        fila.querySelector('.detalle-linea').textContent = '';
        return;
      }

      const unidad   = opt.dataset.unidad     || 'unidad';
      const upp      = opt.dataset.upp        || 1;
      const detalle  = opt.dataset.detalle    || '';

      fila.querySelector('.unidad-select').value = unidad;
      fila.querySelector('.upp-input').value     = upp;
      if (detalle) fila.querySelector('.detalle-linea').textContent = detalle;
      recalcular();
    });

    fila.querySelector('.cantidad-input').addEventListener('input', recalcular);
    fila.querySelector('.precio-input').addEventListener('input', recalcular);
    fila.querySelector('.unidad-select').addEventListener('change', recalcular);

    fila.querySelector('.btn-remove-line').addEventListener('click', function () {
      fila.remove();
      recalcular();
    });

    // Precargar si vienen datos (modo edición)
    if (datosPre) {
      fila.querySelector('.producto-select').value = datosPre.producto_id;
      fila.querySelector('.unidad-select').value   = datosPre.unidad_compra || 'unidad';
      fila.querySelector('.cantidad-input').value  = datosPre.cantidad;
      fila.querySelector('.upp-input').value       = datosPre.unidades_por_paquete;
      fila.querySelector('.precio-input').value    = datosPre.precio_unitario;

      // Actualizar detalle del producto
      const opt = fila.querySelector('.producto-select').options[
        fila.querySelector('.producto-select').selectedIndex
      ];
      if (opt?.dataset.detalle) {
        fila.querySelector('.detalle-linea').textContent = opt.dataset.detalle;
      }
    }

    cuerpo.appendChild(fila);
    recalcular();
  }

  btnAgregar.addEventListener('click', () => agregarLinea());
  inputDesc.addEventListener('input', recalcular);

  // Precargar líneas existentes o crear 1 vacía
  if (lineasExistentes.length > 0) {
    lineasExistentes.forEach(l => agregarLinea(l));
  } else {
    agregarLinea();
  }

  // Confirmación al guardar
  document.getElementById('formCompra').addEventListener('submit', function (e) {
    if (this.dataset.confirmado === '1') return;
    e.preventDefault();

    const total    = txtTotal.textContent;
    const cantidad = cuerpo.querySelectorAll('.linea').length;

    Swal.fire({
      title: '¿Actualizar compra?',
      html: `
        <p style="margin:0 0 0.5rem 0;">Vas a actualizar la orden con:</p>
        <p style="margin:0 0 0.5rem 0;">
          <strong style="color:var(--cyan);font-size:1.05rem;">${cantidad}</strong>
          producto${cantidad !== 1 ? 's' : ''}
        </p>
        <p style="margin:0;font-size:0.9rem;">Total: <strong style="color:var(--cyan);">${total}</strong></p>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-check"></i> Sí, actualizar',
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