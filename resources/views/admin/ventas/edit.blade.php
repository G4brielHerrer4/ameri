@extends('layouts.plantilla_maestra')

@section('title', 'Editar Venta')

@push('styles')
@include('admin.ventas.styles_create')

<style>
    /* Buscador de productos en editar venta */
    .buscador-producto-wrap {
        position: relative;
        margin-bottom: 0.55rem;
    }

    .buscador-producto-wrap .mdi-magnify {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink-muted);
        font-size: 1.15rem;
        pointer-events: none;
    }

    .buscador-producto {
        padding-left: 2.35rem !important;
        height: 42px;
        font-size: 0.85rem !important;
    }

    .buscador-producto:focus {
        border-color: var(--primary);
    }

    .producto-select {
        min-height: 42px;
    }
</style>
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
              <label class="form-label">
                  CI / NIT <small class="text-muted">(Opcional)</small>
              </label>
              <input
                  type="text"
                  name="cliente_documento"
                  class="form-control @error('cliente_documento') is-invalid @enderror"
                  value="{{ old('cliente_documento', $venta->cliente_documento) }}"
                  placeholder="Ej: 12345678"
              >
              @error('cliente_documento')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
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
  <div class="linea-venta" data-linea>
    <div class="linea-head">
      <div class="d-flex align-items-center gap-2">
        <span class="linea-num" data-num>1</span>
        <strong style="font-family:var(--font-display);color:var(--ink);font-size:0.9rem;">
          Producto
        </strong>
      </div>
      <button type="button" class="btn-remove-line" data-remove>
        <i class="mdi mdi-close"></i>
      </button>
    </div>

    <div class="row">
      <div class="col-md-5 mb-2">
        <label class="form-label">Producto <span class="required">*</span></label>

        {{-- Buscador --}}
        <div class="buscador-producto-wrap">
            <i class="mdi mdi-magnify"></i>

            <input
                type="text"
                class="form-control buscador-producto"
                placeholder="Buscar por nombre, marca o medida..."
                autocomplete="off"
            >
        </div>

        {{-- Lista de productos --}}
        <select class="form-select producto-select" required>
            <option value="">Seleccione un producto...</option>

            @foreach($stocks as $s)
                @if($s->producto)
                    <option
                        value="{{ $s->producto->id }}"
                        data-unidad-base="{{ $s->producto->unidad_base }}"
                        data-search="{{ strtolower(implode(' ', array_filter([
                            $s->producto->nombre,
                            $s->producto->tipoProducto?->nombre,
                            $s->producto->marca,
                            $s->producto->medida,
                            $s->producto->modelo,
                            $s->producto->color,
                            $s->producto->acabado
                        ]))) }}"
                    >
                        {{ $s->producto->nombre }}

                        @if($s->producto->marca)
                            · {{ $s->producto->marca }}
                        @endif
                    </option>
                @endif
            @endforeach
        </select>
    </div>

      <div class="col-md-2 mb-2">
        <label class="form-label">Unidad <span class="required">*</span></label>
        <select class="form-select unidad-select" required disabled>
          <option value="">Unidad...</option>
        </select>
      </div>

      <div class="col-md-2 mb-2">
        <label class="form-label">Cantidad <span class="required">*</span></label>
        <input type="number"
               class="form-control cantidad-input"
               min="1" step="1" value="1" required>
      </div>

      <div class="col-md-3 mb-2">
        <label class="form-label">Precio unitario (Bs.)</label>
        <input type="number"
               class="form-control precio-input"
               min="0.01" step="0.01" readonly required>
      </div>
    </div>

    <div class="conversion-info small text-muted mb-2"></div>

    <div class="origenes-section" style="display:none;">
      <div class="origenes-title">
        <i class="mdi mdi-source-branch"></i>
        Selecciona de qué administrador y depósito tomar el stock
      </div>

      <div class="origenes-lista"></div>
      <div class="alerta-stock"></div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-2">
      <small class="text-muted">Subtotal línea:</small>
      <span class="linea-subtotal"
            style="font-family:var(--font-display);font-weight:700;color:var(--cyan);font-size:1rem;">
        Bs. 0.00
      </span>
    </div>
  </div>
</template>

<script id="lineasExistentes" type="application/json">{!! json_encode($lineasExistentes ?? []) !!}</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const contenedor = document.getElementById('contenedorLineas');
    const template = document.getElementById('templateLinea');
    const btnAgregar = document.getElementById('btnAgregarLinea');
    const inputDesc = document.getElementById('inputDescuento');

    const txtSubtotal = document.getElementById('txtSubtotal');
    const txtDesc = document.getElementById('txtDescuento');
    const txtTotal = document.getElementById('txtTotal');

    const lineasExistentes = JSON.parse(
        document.getElementById('lineasExistentes').textContent || '[]'
    );

    const urlStock = @json(url('admin/ventas/stock-producto'));

    let numLinea = 0;

    function fmt(n) {
        return 'Bs. ' + Number(n || 0).toFixed(2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function tipoCeramica(producto) {
        const tipo = (producto.tipo || '').trim().toLowerCase();

        return ['cerámica', 'ceramica', 'porcelanato'].includes(tipo);
    }

    function calcularCantidadFisica(producto, cantidad, unidad) {

        cantidad = Number(cantidad) || 0;

        if (tipoCeramica(producto)) {

            const piezasCaja = Number(producto.piezas_por_caja || 0);
            const m2Caja = Number(producto.m2_por_caja || 0);

            if (unidad === 'caja') {
                return cantidad * piezasCaja;
            }

            if (unidad === 'm2') {
                if (piezasCaja <= 0 || m2Caja <= 0) return 0;

                const m2Pieza = m2Caja / piezasCaja;

                return Math.ceil((cantidad / m2Pieza) - 0.000000001);
            }

            if (unidad === 'pieza') {
                return cantidad;
            }
        }

        return cantidad;
    }

    function obtenerUnidades(producto) {

        const tipo = (producto.tipo || '').trim().toLowerCase();

        if (['cerámica', 'ceramica', 'porcelanato'].includes(tipo)) {
            return ['caja', 'm2', 'pieza'];
        }

        if (['cemento cola', 'pastinas'].includes(tipo)) {
            return ['bolsa'];
        }

        return ['pieza'];
    }

    function actualizarUnidades(linea, producto, unidadAnterior = '') {

        const select = linea.querySelector('.unidad-select');

        select.innerHTML = '<option value="">Seleccione...</option>';

        obtenerUnidades(producto).forEach(unidad => {

            const option = document.createElement('option');

            option.value = unidad;
            option.textContent = unidad.toUpperCase();

            select.appendChild(option);
        });

        select.disabled = false;

        if (unidadAnterior && obtenerUnidades(producto).includes(unidadAnterior)) {
            select.value = unidadAnterior;
        } else {
            select.selectedIndex = 1;
        }
    }

    function obtenerPrecio(producto, unidad) {

        const precios = producto.precios_venta || {};

        if (unidad === 'caja') {
            return Number(precios.caja || 0);
        }

        if (unidad === 'm2') {
            const precioCaja = Number(precios.caja || 0);
            const m2Caja = Number(producto.m2_por_caja || 0);

            return m2Caja > 0 ? precioCaja / m2Caja : 0;
        }

        if (unidad === 'pieza') {
            return Number(precios.pieza || 0);
        }

        if (unidad === 'bolsa') {
            return Number(precios.bolsa || 0);
        }

        return 0;
    }

    function actualizarPrecio(linea) {

        const producto = linea._producto;

        if (!producto) return;

        const unidad = linea.querySelector('.unidad-select').value;
        const precio = obtenerPrecio(producto, unidad);

        linea.querySelector('.precio-input').value =
            precio > 0 ? precio.toFixed(2) : '';

        actualizarConversion(linea);
        validarOrigenes(linea);
        recalcular();
    }

    function actualizarConversion(linea) {

        const producto = linea._producto;

        if (!producto) return;

        const cantidad = Number(
            linea.querySelector('.cantidad-input').value
        ) || 0;

        const unidad = linea.querySelector('.unidad-select').value;

        const fisica = calcularCantidadFisica(
            producto,
            cantidad,
            unidad
        );

        const info = linea.querySelector('.conversion-info');

        if (tipoCeramica(producto) && unidad) {

            info.textContent =
                'Equivale a ' + fisica + ' piezas físicas de inventario.';

        } else if (unidad) {

            info.textContent =
                'Cantidad física requerida: ' + fisica + ' ' +
                (producto.unidad_base || 'unidades') + '.';

        } else {

            info.textContent = '';
        }
    }

    function claveMovimiento(userId, depositoId) {
        return String(userId) + '-' + String(depositoId);
    }

    async function cargarStockProducto(
        linea,
        productoId,
        movimientosPrevios = [],
        unidadAnterior = ''
    ) {

        const seccion = linea.querySelector('.origenes-section');
        const lista = linea.querySelector('.origenes-lista');
        const alerta = linea.querySelector('.alerta-stock');

        linea._producto = null;

        if (!productoId) {
            seccion.style.display = 'none';
            return;
        }

        seccion.style.display = '';
        lista.innerHTML = '<div class="p-3 text-center">Cargando inventario...</div>';

        try {

            const response = await fetch(`${urlStock}/${productoId}`);

            if (!response.ok) {
                throw new Error('No se pudo consultar el inventario.');
            }

            const data = await response.json();

            const producto = data.producto;

            linea._producto = producto;

            actualizarUnidades(linea, producto, unidadAnterior);

            const movimientosMap = {};

            movimientosPrevios.forEach(m => {
                const key = claveMovimiento(m.stock_user_id, m.deposito_id);

                movimientosMap[key] =
                    (movimientosMap[key] || 0) + Number(m.cantidad || 0);
            });

            lista.innerHTML = '';

            (data.stocks || []).forEach(s => {

                const key = claveMovimiento(s.user_id, s.deposito_id);

                const cantidadPrevia = movimientosMap[key] || 0;

                const item = document.createElement('div');
                item.className = 'origen-item' + (s.es_propio ? ' es-propio' : '');

                const info = document.createElement('div');
                info.className = 'info';

                const nombre = document.createElement('span');
                nombre.className = 'name';
                nombre.textContent = s.user_name || 'Administrador';

                const deposito = document.createElement('small');
                deposito.className = 'text-muted d-block';
                deposito.textContent = s.deposito_nombre || 'Depósito';

                info.appendChild(nombre);
                info.appendChild(deposito);

                if (s.es_propio) {
                    const etiqueta = document.createElement('span');
                    etiqueta.className = 'label';
                    etiqueta.textContent = 'Mi stock';
                    info.appendChild(etiqueta);
                }

               const disponible = document.createElement('div');
              disponible.className = 'disponible';

              const cantidadDisponible = Number(s.cantidad) || 0;

              if (tipoCeramica(data.producto)) {

                  const piezasPorCaja = Number(data.producto.piezas_por_caja || 0);

                  if (piezasPorCaja > 0) {

                      const cajas = Math.floor(cantidadDisponible / piezasPorCaja);
                      const piezas = cantidadDisponible % piezasPorCaja;

                      let equivalencia = '';

                      if (cajas > 0) {
                          equivalencia += cajas + (cajas === 1 ? ' caja' : ' cajas');
                      }

                      if (piezas > 0) {
                          equivalencia += (cajas > 0 ? ' y ' : '') +
                              piezas + (piezas === 1 ? ' pieza' : ' piezas');
                      }

                      disponible.innerHTML =
                          '<strong>' + cantidadDisponible.toLocaleString() +
                          ' piezas</strong>' +
                          '<small class="d-block text-muted">' +
                          (equivalencia || '0 piezas') +
                          '</small>';

                  } else {

                      disponible.textContent = cantidadDisponible + ' piezas';

                  }

              } else {

                  disponible.innerHTML =
                      '<strong>' + cantidadDisponible.toLocaleString() +
                      '</strong> ' + (data.producto.unidad_base || 'unidades');
              }

                const input = document.createElement('input');

                input.type = 'number';
                input.className = 'cantidad-origen';
                input.min = '0';
                input.step = '1';
                input.value = cantidadPrevia;
                input.dataset.max = s.cantidad;
                input.dataset.userId = s.user_id;
                input.dataset.depositoId = s.deposito_id;

                item.appendChild(info);
                item.appendChild(disponible);
                item.appendChild(input);

                lista.appendChild(item);

                delete movimientosMap[key];
            });

            // Si había movimientos anteriores cuyo origen ya no aparece
            // en el inventario disponible, informamos en vez de perderlos.
            const movimientosNoDisponibles = Object.keys(movimientosMap);

            if (movimientosNoDisponibles.length > 0) {

                alerta.className = 'alerta-stock danger';
                alerta.textContent =
                    'Hay asignaciones anteriores cuyo stock ya no está disponible. Revisa los depósitos antes de actualizar.';

            }

            lista.querySelectorAll('.cantidad-origen').forEach(input => {

                input.addEventListener('input', function () {

                    const max = Number(this.dataset.max) || 0;
                    let valor = Number(this.value) || 0;

                    if (valor > max) {
                        valor = max;
                        this.value = max;
                    }

                    validarOrigenes(linea);
                    recalcular();
                });

                if (Number(input.value) > 0) {
                    input.classList.add('tiene-valor');
                }
            });

            if (unidadAnterior) {
                linea.querySelector('.unidad-select').value = unidadAnterior;
            }

            actualizarPrecio(linea);
            validarOrigenes(linea);

        } catch (error) {

            console.error(error);

            lista.innerHTML =
                '<div class="alert alert-danger">No se pudo cargar el inventario del producto.</div>';
        }
    }

    function validarOrigenes(linea) {

        const alerta = linea.querySelector('.alerta-stock');

        if (!linea._producto) return;

        const cantidad = Number(
            linea.querySelector('.cantidad-input').value
        ) || 0;

        const unidad = linea.querySelector('.unidad-select').value;

        const requerido = calcularCantidadFisica(
            linea._producto,
            cantidad,
            unidad
        );

        let total = 0;

        linea.querySelectorAll('.cantidad-origen').forEach(input => {
            total += Number(input.value) || 0;
        });

        const unidadBase = linea._producto.unidad_base || 'unidades';

        if (Math.abs(total - requerido) < 0.001 && requerido > 0) {

            alerta.className = 'alerta-stock ok';
            alerta.innerHTML =
                '<i class="mdi mdi-check-circle"></i> Stock asignado: <strong>' +
                total + ' ' + unidadBase + '</strong>';

        } else if (total < requerido) {

            alerta.className = 'alerta-stock warn';
            alerta.innerHTML =
                '<i class="mdi mdi-alert"></i> Falta asignar <strong>' +
                (requerido - total) + ' ' + unidadBase + '</strong>';

        } else {

            alerta.className = 'alerta-stock danger';
            alerta.innerHTML =
                '<i class="mdi mdi-alert-circle"></i> Exceso de <strong>' +
                (total - requerido) + ' ' + unidadBase + '</strong>';
        }
    }

    function recalcular() {

        let subtotal = 0;

        contenedor.querySelectorAll('.linea-venta').forEach(linea => {

            const cantidad = Number(
                linea.querySelector('.cantidad-input').value
            ) || 0;

            const precio = Number(
                linea.querySelector('.precio-input').value
            ) || 0;

            const total = cantidad * precio;

            subtotal += total;

            linea.querySelector('.linea-subtotal').textContent = fmt(total);
        });

        const descuento = Number(inputDesc.value) || 0;

        txtSubtotal.textContent = fmt(subtotal);
        txtDesc.textContent = fmt(descuento);
        txtTotal.textContent = fmt(Math.max(0, subtotal - descuento));
    }

    function agregarLinea(datosPre = null) {

        numLinea++;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = template.innerHTML.trim();

        const linea = wrapper.firstElementChild;

        linea.querySelector('[data-num]').textContent = numLinea;

        const selectProducto = linea.querySelector('.producto-select');
        const selectUnidad = linea.querySelector('.unidad-select');
        const inputCantidad = linea.querySelector('.cantidad-input');
        const buscadorProducto = linea.querySelector('.buscador-producto');
        // ==========================================
// BUSCADOR DE PRODUCTOS
// ==========================================

        buscadorProducto.addEventListener('input', function () {

            const busqueda = this.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim();

            Array.from(selectProducto.options).forEach(option => {

                // Mantener visible la opción inicial.
                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                const texto = (option.dataset.search || '')
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '');

                const coincide = texto.includes(busqueda);

                // No ocultar el producto que ya está seleccionado.
                option.hidden = !coincide && option.value !== selectProducto.value;
            });
        });
        selectProducto.addEventListener('change', function () {

            // Limpiar el buscador.
            buscadorProducto.value = '';

            // Mostrar nuevamente todos los productos.
            Array.from(selectProducto.options).forEach(option => {
                option.hidden = false;
            });

            linea.querySelector('.precio-input').value = '';
            linea.querySelector('.unidad-select').disabled = true;

            cargarStockProducto(linea, this.value);
        });

        selectUnidad.addEventListener('change', function () {
            actualizarPrecio(linea);
        });

        inputCantidad.addEventListener('input', function () {

            if (Number(this.value) < 0) {
                this.value = 0;
            }

            actualizarConversion(linea);
            validarOrigenes(linea);
            recalcular();
        });

        linea.querySelector('[data-remove]').addEventListener('click', function () {
            linea.remove();
            recalcular();
        });

        contenedor.appendChild(linea);

        if (datosPre) {

            selectProducto.value = datosPre.producto_id;
            inputCantidad.value = datosPre.cantidad;

            cargarStockProducto(
                linea,
                datosPre.producto_id,
                datosPre.movimientos || [],
                datosPre.unidad || ''
            );

        }

        recalcular();
    }

    btnAgregar.addEventListener('click', function () {
        agregarLinea();
    });

    inputDesc.addEventListener('input', recalcular);

    if (lineasExistentes.length > 0) {
        lineasExistentes.forEach(linea => agregarLinea(linea));
    } else {
        agregarLinea();
    }

    document.getElementById('formVenta').addEventListener('submit', function (e) {

        e.preventDefault();

        const form = this;

        if (form.dataset.confirmado === '1') return;

        form.querySelectorAll('input[name^="items"]').forEach(el => el.remove());

        const lineas = contenedor.querySelectorAll('.linea-venta');

        if (lineas.length === 0) {
            alert('Debes agregar al menos un producto.');
            return;
        }

        let valido = true;

        lineas.forEach((linea, idx) => {

            const productoId = linea.querySelector('.producto-select').value;
            const unidad = linea.querySelector('.unidad-select').value;

            const cantidad = Number(
                linea.querySelector('.cantidad-input').value
            ) || 0;

            const precio = Number(
                linea.querySelector('.precio-input').value
            ) || 0;

            if (!productoId || !unidad || cantidad <= 0 || precio <= 0) {
                valido = false;
                return;
            }

            const producto = linea._producto;

            if (!producto) {
                valido = false;
                return;
            }

            const requerido = calcularCantidadFisica(
                producto,
                cantidad,
                unidad
            );

            let totalMovimientos = 0;

            linea.querySelectorAll('.cantidad-origen').forEach(input => {
                totalMovimientos += Number(input.value) || 0;
            });

            if (Math.abs(totalMovimientos - requerido) > 0.001) {
                valido = false;
            }
        });

        if (!valido) {
            alert('Revisa los productos, las unidades y las cantidades asignadas por depósito. El stock asignado debe coincidir con la cantidad física requerida.');
            return;
        }

        function agregarHidden(nombre, valor) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = nombre;
            input.value = valor;
            form.appendChild(input);
        }

        lineas.forEach((linea, idx) => {

            agregarHidden(
                `items[${idx}][producto_id]`,
                linea.querySelector('.producto-select').value
            );

            agregarHidden(
                `items[${idx}][cantidad]`,
                linea.querySelector('.cantidad-input').value
            );

            agregarHidden(
                `items[${idx}][unidad]`,
                linea.querySelector('.unidad-select').value
            );

            agregarHidden(
                `items[${idx}][precio_unitario]`,
                linea.querySelector('.precio-input').value
            );

            let movimientoIndex = 0;

            linea.querySelectorAll('.cantidad-origen').forEach(input => {

                const cantidad = Number(input.value) || 0;

                if (cantidad <= 0) return;

                agregarHidden(
                    `items[${idx}][movimientos][${movimientoIndex}][stock_user_id]`,
                    input.dataset.userId
                );

                agregarHidden(
                    `items[${idx}][movimientos][${movimientoIndex}][deposito_id]`,
                    input.dataset.depositoId
                );

                agregarHidden(
                    `items[${idx}][movimientos][${movimientoIndex}][cantidad]`,
                    cantidad
                );

                movimientoIndex++;
            });
        });

        form.dataset.confirmado = '1';
        form.submit();
    });

});
</script>
@endpush