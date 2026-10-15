@extends('layouts.plantilla_maestra_ven')

@section('title', 'Editar Venta')

{{-- Mensajes de éxito, error y validación --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-circle-outline"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="mdi mdi-alert-circle-outline"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <strong>Revisa los siguientes errores:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@push('styles')
@include('vendedor.ventas.styles_create')
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

  <form action="{{ route('vendedor.ventas.update', $venta) }}" method="POST" id="formVenta" novalidate>
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
          <a href="{{ route('vendedor.ventas.index') }}" class="btn-secondary-app">
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

                @php
                    $tipo = mb_strtolower(
                        trim((string) ($s->producto->tipoProducto?->nombre ?? '')),
                        'UTF-8'
                    );

                    $esCeramica = in_array(
                        $tipo,
                        ['cerámica', 'ceramica', 'porcelanato'],
                        true
                    );

                    $totalPiezas = (int) $s->cantidad;
                    $piezasPorCaja = (int) ($s->producto->piezas_por_caja ?? 0);

                    if ($esCeramica && $piezasPorCaja > 0) {
                        $cajas = intdiv($totalPiezas, $piezasPorCaja);
                        $piezasSueltas = $totalPiezas % $piezasPorCaja;

                        $existencia = $cajas . ' cajas y ' .
                            $piezasSueltas . ' piezas';
                    } else {
                        $existencia = rtrim(
                            rtrim(number_format((float) $s->cantidad, 2, '.', ''), '0'),
                            '.'
                        ) . ' ' . $s->producto->unidad_base;
                    }
                @endphp

                <option
                    value="{{ $s->producto->id }}"
                    data-stock-propio="{{ (float) $s->cantidad }}"
                    data-unidad-base="{{ $s->producto->unidad_base }}"
                    data-piezas-por-caja="{{ $s->producto->piezas_por_caja ?? 0 }}"
                >
                    {{ $s->producto->nombre }}
                    @if($s->producto->marca)
                        · {{ $s->producto->marca }}
                    @endif
                    — {{ $existencia }} disponibles
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

    const res = await fetch(`{{ url('vendedor/ventas/stock-producto') }}/${productoId}`);
    const data = await res.json();

    lista.innerHTML = '';

    // Unidad original de la venta: caja, m2 o pieza.
    const unidadVenta = linea.dataset.unidadVenta || 'pieza';

    const esCeramica = ['cerámica', 'ceramica', 'porcelanato']
        .includes((data.producto.tipo || '').trim().toLowerCase());

    const piezasPorCaja = parseInt(data.producto.piezas_por_caja || 0, 10);

    // Mostrar cajas cuando la venta se hizo por cajas.
    const mostrarCajas = esCeramica &&
        unidadVenta === 'caja' &&
        piezasPorCaja > 0;

    const unidadMostrar = mostrarCajas
        ? 'cajas'
        : data.producto.unidad_base;

    data.stocks.forEach(s => {
        // El stock real de cada administrador continúa en piezas.
        const cantidadFisica = parseFloat(s.cantidad) || 0;

        // Cantidad física original asignada a este administrador.
        const cantidadPreviaFisica = parseFloat(
            cantidadesPrevias[s.user_id] || 0
        );

        // Para mostrar cajas, convertimos las piezas a cajas equivalentes.
        const cantidadDisponibleMostrar = mostrarCajas
            ? cantidadFisica / piezasPorCaja
            : cantidadFisica;

        const cantidadPreviaMostrar = mostrarCajas
            ? cantidadPreviaFisica / piezasPorCaja
            : cantidadPreviaFisica;

        const item = document.createElement('div');
        item.className = 'origen-item' + (s.es_propio ? ' es-propio' : '');
        item.dataset.userId = s.user_id;
        item.dataset.unidadMostrar = unidadMostrar;
        item.dataset.mostrarCajas = mostrarCajas ? '1' : '0';
        item.dataset.piezasPorCaja = piezasPorCaja;

        item.innerHTML = `
            <div class="info">
                <i class="mdi mdi-account-circle"></i>
                <span class="name">${s.user_name}</span>
                ${s.es_propio ? '<span class="label">Mi stock</span>' : ''}
            </div>

            <div class="disponible">
                <strong>${Number(cantidadDisponibleMostrar.toFixed(2))}</strong>
                ${unidadMostrar} disponibles
            </div>

            <input
                type="number"
                class="cantidad-origen"
                min="0"
                step="${mostrarCajas ? '1' : '0.01'}"
                value="${Number(cantidadPreviaMostrar.toFixed(2))}"
                data-max="${cantidadDisponibleMostrar}"
                data-max-fisico="${cantidadFisica}"
                data-user-id="${s.user_id}"
            >
        `;

        lista.appendChild(item);
    });

    lista.querySelectorAll('.cantidad-origen').forEach(input => {
        if (parseFloat(input.value) > 0) {
            input.classList.add('tiene-valor');
        }

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
        const cantidad = parseFloat(
            linea.querySelector('.cantidad-input').value
        ) || 0;

        const sel = linea.querySelector('.producto-select');
        const opcion = sel.options[sel.selectedIndex];

        const unidad = linea.dataset.unidadVenta || 'pieza';
        const unidadBase = opcion?.dataset.unidadBase || 'unidades';

        // Convertir la cantidad vendida a unidades físicas de inventario.
        let cantidadRequerida = cantidad;

        if (unidad === 'caja') {
            const piezasPorCaja = parseInt(
                opcion?.dataset.piezasPorCaja || '0',
                10
            );

            if (piezasPorCaja > 0) {
                cantidadRequerida = cantidad * piezasPorCaja;
            }
        }

        let total = 0;

        linea.querySelectorAll('.cantidad-origen').forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        const alerta = linea.querySelector('.alerta-stock');

        if (!alerta) return;

        const diferencia = Math.round(
            (total - cantidadRequerida) * 100
        ) / 100;

        if (Math.abs(diferencia) < 0.01) {
            alerta.className = 'alerta-stock ok';
            alerta.innerHTML = `
                <i class="mdi mdi-check-circle"></i>
                <span>
                    Stock asignado:
                    <strong>${total} ${unidadBase}</strong>
                </span>
            `;
        } else if (diferencia < 0) {
            alerta.className = 'alerta-stock warn';
            alerta.innerHTML = `
                <i class="mdi mdi-alert"></i>
                <span>
                    Falta asignar:
                    <strong>${Math.abs(diferencia)} ${unidadBase}</strong>
                </span>
            `;
        } else {
            alerta.className = 'alerta-stock danger';
            alerta.innerHTML = `
                <i class="mdi mdi-alert-circle"></i>
                <span>
                    Exceso de:
                    <strong>${diferencia} ${unidadBase}</strong>
                </span>
            `;
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
   const wrapper = document.createElement('div');
    wrapper.innerHTML = template.innerHTML.trim();
    const linea = wrapper.firstElementChild;

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

        // Conservar la unidad original de la venta.
        linea.dataset.unidadVenta = datosPre.unidad || 'pieza';

        const cantidades = {};
        (datosPre.movimientos || []).forEach(m => {
            cantidades[m.stock_user_id] = m.cantidad;
        });

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

        // Conservar la unidad de venta de la línea editada.
    const unidad = linea.dataset.unidadVenta || 'pieza';

    addH(`items[${idx}][unidad]`, unidad);

    let m = 0;

    linea.querySelectorAll('.cantidad-origen').forEach(inp => {
        const cantidadIngresada = parseFloat(inp.value) || 0;

        if (cantidadIngresada > 0) {
            const itemOrigen = inp.closest('.origen-item');

            // Si la interfaz muestra cajas, convertirlas a piezas físicas.
            const esCaja = itemOrigen?.dataset.mostrarCajas === '1';
            const piezasPorCaja = parseInt(
                itemOrigen?.dataset.piezasPorCaja || '0',
                10
            );

            const cantidadFisica = esCaja && piezasPorCaja > 0
                ? cantidadIngresada * piezasPorCaja
                : cantidadIngresada;

            addH(
                `items[${idx}][movimientos][${m}][stock_user_id]`,
                inp.dataset.userId
            );

            addH(
                `items[${idx}][movimientos][${m}][cantidad]`,
                cantidadFisica
            );
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