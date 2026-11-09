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

<style>
    /* Buscador de productos */
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

  <form action="{{ route('vendedor.ventas.update', $venta) }}" method="POST" id="formVenta" novalidate>
    @csrf @method('PUT')
    <input type="hidden" name="admin_id" id="admin_id" value="{{ old('admin_id', $adminId) }}">

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
        @if($adminId)
            @php
                $adminSeleccionado = $admins->firstWhere('id', $adminId);
            @endphp

            <div class="alert alert-info mb-3">
                <i class="mdi mdi-account-circle me-1"></i>
                Inventario del administrador:
                <strong>{{ $adminSeleccionado?->name ?? 'Administrador #' . $adminId }}</strong>
            </div>
        @endif
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

            <div class="buscador-producto-wrap">
                <i class="mdi mdi-magnify"></i>

                <input
                    type="text"
                    class="form-control buscador-producto"
                    placeholder="Buscar por nombre, marca o medida..."
                    autocomplete="off"
                >
            </div>

            <select class="form-select producto-select" required>
                <option value="">Seleccionar producto...</option>
            </select>
        </div>

        <div class="col-md-2 mb-2">
          <label class="form-label">Unidad <span class="required">*</span></label>
          <select class="form-select unidad-select" required disabled>
            <option value="">Unidad</option>
          </select>
        </div>

        <div class="col-md-2 mb-2">
          <label class="form-label">Cantidad <span class="required">*</span></label>
          <input
            type="number"
            class="form-control cantidad-input"
            min="0.01"
            step="0.01"
            value="1"
            required
          >
        </div>

        <div class="col-md-3 mb-2">
          <label class="form-label">Precio unitario (Bs.)</label>
          <input
            type="number"
            class="form-control precio-input"
            readonly
          >
        </div>
      </div>

      <div class="conversion-info small text-muted mt-1" style="display:none;">
        <i class="mdi mdi-information-outline"></i>
        <span class="conversion-detalle"></span>
      </div>

      <div class="origenes-section mt-3" style="display:none;">
        <div class="origenes-title">
          <i class="mdi mdi-source-branch"></i>
          Asignación de inventario por depósito
        </div>

        <div class="origenes-lista"></div>

        <div class="alerta-stock mt-2"></div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-2">
        <small class="text-muted">Subtotal línea:</small>
        <span
          class="linea-subtotal"
          style="font-family:var(--font-display);font-weight:700;color:var(--cyan);font-size:1rem;"
        >
          Bs. 0.00
        </span>
      </div>
    </div>
  </template>

<script id="lineasExistentes" type="application/json">{!! json_encode($lineasExistentes ?? []) !!}</script>
@endsection

@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', async function () {
    const contenedor = document.getElementById('contenedorLineas');
    const template = document.getElementById('templateLinea');
    const btnAgregar = document.getElementById('btnAgregarLinea');
    const inputDesc = document.getElementById('inputDescuento');
    const txtSubtotal = document.getElementById('txtSubtotal');
    const txtDesc = document.getElementById('txtDescuento');
    const txtTotal = document.getElementById('txtTotal');
    const form = document.getElementById('formVenta');

    const lineasExistentes = JSON.parse(
        document.getElementById('lineasExistentes').textContent
    ) || [];

    const adminId = document.getElementById('admin_id').value;
    let productosAdminCache = [];
    let numLinea = 0;

    function fmt(n) {
        return 'Bs. ' + Number(n || 0).toFixed(2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function esCeramica(producto) {
        const tipo = (producto?.tipo || '').trim().toLowerCase();
        return ['cerámica', 'ceramica', 'porcelanato'].includes(tipo);
    }
    function usaCantidadEntera(unidad) {
        return ['caja', 'pieza', 'bolsa'].includes(
            String(unidad || '').trim().toLowerCase()
        );
    }

    function redondear(n) {
        return Math.round((Number(n) + Number.EPSILON) * 100) / 100;
    }
    function formatoCajasPiezas(cantidad, piezasPorCaja) {
        cantidad = Math.round(Number(cantidad) || 0);
        piezasPorCaja = Math.floor(Number(piezasPorCaja) || 0);

        if (piezasPorCaja <= 0) {
            return `${cantidad} piezas`;
        }

        const cajas = Math.floor(cantidad / piezasPorCaja);
        const piezas = cantidad % piezasPorCaja;

        if (cajas > 0 && piezas > 0) {
            return `${cajas} cajas + ${piezas} piezas`;
        }

        if (cajas > 0) {
            return `${cajas} cajas`;
        }

        return `${piezas} piezas`;
    }

    function obtenerCantidadFisica(producto, cantidad, unidad) {
        cantidad = Number(cantidad) || 0;

        if (unidad === 'caja') {
            return cantidad * Number(producto.piezas_por_caja || 0);
        }

        if (unidad === 'm2' && esCeramica(producto)) {
            const m2Caja = Number(producto.m2_por_caja || 0);
            const piezasCaja = Number(producto.piezas_por_caja || 0);

            if (m2Caja <= 0 || piezasCaja <= 0) return 0;

            const m2Pieza = m2Caja / piezasCaja;
            return Math.ceil((cantidad / m2Pieza) - 0.000000001);
        }

        return cantidad;
    }

    function calcularPrecio(producto, cantidad, unidad) {
        cantidad = Number(cantidad) || 0;

        if (cantidad <= 0) {
            return { precio: 0, subtotal: 0 };
        }

        if (esCeramica(producto)) {
            const precios = producto.precios_venta || {};
            const precioCaja = Number(precios.caja || 0);
            const m2Caja = Number(producto.m2_por_caja || 0);

            if (unidad === 'caja') {
                return {
                    precio: precioCaja,
                    subtotal: redondear(cantidad * precioCaja)
                };
            }

            if (unidad === 'm2') {
                if (precioCaja <= 0 || m2Caja <= 0) {
                    return { precio: 0, subtotal: 0 };
                }

                const precioM2 = precioCaja / m2Caja;

                return {
                    precio: precioM2,
                    subtotal: redondear(cantidad * precioM2)
                };
            }

            if (unidad === 'pieza') {
                const precioPieza = Number(precios.pieza || 0);

                return {
                    precio: precioPieza,
                    subtotal: redondear(cantidad * precioPieza)
                };
            }

            return { precio: 0, subtotal: 0 };
        }

        const precio = Number(producto.precios_venta?.[unidad] || 0);

        return {
            precio: precio,
            subtotal: redondear(cantidad * precio)
        };
    }

    async function cargarProductosAdmin() {
        if (!adminId) {
            throw new Error('No se encontró el administrador de esta venta.');
        }

        const res = await fetch(
            `{{ url('vendedor/ventas/productos-admin') }}/${adminId}`
        );

        if (!res.ok) {
            throw new Error('No se pudieron cargar los productos del administrador.');
        }

        const data = await res.json();
        productosAdminCache = data.productos || [];

        if (!productosAdminCache.length) {
            throw new Error('El administrador no tiene productos disponibles actualmente.');
        }
    }

    function llenarSelectProductos(select, productoSeleccionado = '') {

        select.innerHTML = '<option value="">Seleccionar producto...</option>';

        productosAdminCache.forEach(producto => {

            const option = document.createElement('option');

            option.value = producto.id;
            option.textContent = producto.nombre;

            option.dataset.tipo = producto.tipo || '';
            option.dataset.unidadBase = producto.unidad_base || 'unidad';
            option.dataset.piezasPorCaja = producto.piezas_por_caja || 0;
            option.dataset.m2PorCaja = producto.m2_por_caja || 0;

            // Datos utilizados por el buscador.
            option.dataset.search = [
                producto.nombre,
                producto.tipo,
                producto.marca,
                producto.medida,
                producto.modelo,
                producto.color,
                producto.acabado
            ].filter(Boolean).join(' ').toLowerCase();

            select.appendChild(option);
        });

        if (productoSeleccionado) {
            select.value = String(productoSeleccionado);
        }
    }

    function obtenerAsignacionesFisicas(linea) {
        const asignaciones = {};

        const producto = linea._productoData;
        const unidad = linea.querySelector('.unidad-select').value;
        const esCeramicaActual = esCeramica(producto);
        const piezasCaja = Number(producto?.piezas_por_caja || 0);

        linea.querySelectorAll('.cantidad-origen').forEach(input => {
            const item = input.closest('.origen-item');
            const depositoId = item.dataset.depositoId;

            let cantidad = Number(input.value) || 0;

            // Si se está trabajando por cajas, convertir a piezas físicas.
            if (esCeramicaActual && unidad === 'caja') {
                cantidad *= piezasCaja;
            }

            asignaciones[depositoId] = cantidad;
        });

        return asignaciones;
    }
   function construirOrigenes(linea, stocks, asignaciones = {}) {
        const lista = linea.querySelector('.origenes-lista');
        const producto = linea._productoData;

        lista.innerHTML = '';

        const esCeramicaActual = esCeramica(producto);
        const piezasCaja = Number(producto.piezas_por_caja || 0);
        const unidadActual = linea.querySelector('.unidad-select').value;

        const asignacionPorCaja =
            esCeramicaActual &&
            unidadActual === 'caja' &&
            piezasCaja > 0;

        const depositos = [...stocks];

        // Conservar asignaciones anteriores aunque un depósito ya no tenga stock.
        Object.keys(asignaciones).forEach(depositoId => {
            const existe = depositos.some(
                s => String(s.deposito_id) === String(depositoId)
            );

            if (!existe && Number(asignaciones[depositoId]) > 0) {
                depositos.push({
                    user_id: Number(adminId),
                    user_name: 'Administrador',
                    deposito_id: Number(depositoId),
                    deposito_nombre: `Depósito #${depositoId} (sin stock disponible)`,
                    cantidad: 0
                });
            }
        });

        depositos.forEach(stock => {
            const cantidadFisica = Number(stock.cantidad || 0);

            // Las asignaciones recibidas siempre están expresadas en piezas físicas.
            const cantidadPreviaFisica = Number(
                asignaciones[stock.deposito_id] || 0
            );

            // Convertir a cajas solamente para mostrar y editar.
            const cantidadPrevia = asignacionPorCaja
                ? Math.floor(cantidadPreviaFisica / piezasCaja)
                : cantidadPreviaFisica;

            const maximoAsignable = asignacionPorCaja
                ? Math.floor(cantidadFisica / piezasCaja)
                : cantidadFisica;

            const item = document.createElement('div');
            item.className = 'origen-item';

            item.dataset.userId = stock.user_id;
            item.dataset.depositoId = stock.deposito_id;
            item.dataset.piezasPorCaja = piezasCaja;

            const info = document.createElement('div');
            info.className = 'info';

            const nombre = document.createElement('span');
            nombre.className = 'name';
            nombre.textContent = stock.deposito_nombre || 'Depósito';

            info.appendChild(nombre);

            const disponible = document.createElement('div');
            disponible.className = 'disponible';

            if (asignacionPorCaja) {
                disponible.innerHTML =
                    `<strong>${maximoAsignable}</strong> cajas disponibles`;
            } else if (esCeramicaActual && piezasCaja > 0) {
                disponible.innerHTML =
                    `<strong>${formatoCajasPiezas(cantidadFisica, piezasCaja)}</strong> disponibles`;
            } else {
                disponible.innerHTML =
                    `<strong>${cantidadFisica}</strong> ${producto.unidad_base || 'unidades'} disponibles`;
            }

            const input = document.createElement('input');
            input.type = 'number';
            input.className = 'cantidad-origen';
            input.min = '0';
            input.step = '1';

            input.value = String(
                Math.min(Math.round(cantidadPrevia), maximoAsignable)
            );

            input.dataset.userId = stock.user_id;
            input.dataset.max = maximoAsignable;
            input.dataset.maxFisico = cantidadFisica;

            if (Number(input.value) > 0) {
                input.classList.add('tiene-valor');
            }

            const equivalencia = document.createElement('small');
            equivalencia.className =
                'asignacion-equivalencia text-muted';

            equivalencia.style.display = 'block';
            equivalencia.style.marginTop = '4px';

            function actualizarEquivalencia() {
                const valor = Number(input.value) || 0;

                if (asignacionPorCaja) {
                    equivalencia.textContent =
                        `Asignación: ${valor} cajas (${valor * piezasCaja} piezas)`;
                } else if (esCeramicaActual && piezasCaja > 0) {
                    equivalencia.textContent =
                        `Asignación: ${formatoCajasPiezas(valor, piezasCaja)}`;
                } else {
                    equivalencia.textContent =
                        `Asignación: ${valor} ${producto.unidad_base || 'unidades'}`;
                }
            }

            actualizarEquivalencia();

            input.addEventListener('input', function () {
                const max = Number(this.dataset.max || 0);
                let valor = Number(this.value) || 0;

                valor = Math.round(valor);

                if (valor < 0) valor = 0;
                if (valor > max) valor = max;

                this.value = String(valor);

                this.classList.toggle('tiene-valor', valor > 0);

                actualizarEquivalencia();
                validarOrigenes(linea);
                recalcular();
            });

            item.appendChild(info);
            item.appendChild(disponible);
            item.appendChild(input);
            item.appendChild(equivalencia);

            lista.appendChild(item);
        });

        validarOrigenes(linea);
    }

    function validarOrigenes(linea) {
        const cantidad = Number(
            linea.querySelector('.cantidad-input').value
        ) || 0;

        const unidad = linea.querySelector('.unidad-select').value;
        const producto = linea._productoData;

        const esCeramicaActual = esCeramica(producto);
        const piezasCaja = Number(producto?.piezas_por_caja || 0);

        const requerida = obtenerCantidadFisica(
            producto,
            cantidad,
            unidad
        );

        let asignada = 0;

        linea.querySelectorAll('.cantidad-origen').forEach(input => {
            let valor = Number(input.value) || 0;

            // Las cajas se convierten a piezas para comparar con el inventario.
            if (esCeramicaActual && unidad === 'caja') {
                valor *= piezasCaja;
            }

            asignada += valor;
        });

        const alerta = linea.querySelector('.alerta-stock');

        if (!alerta) return false;

        const diferencia = Math.round(asignada - requerida);

        let unidadMensaje = 'unidades físicas';
        let asignadaMostrar = asignada;
        let diferenciaMostrar = Math.abs(diferencia);

        if (esCeramicaActual && unidad === 'caja' && piezasCaja > 0) {
            unidadMensaje = 'cajas';
            asignadaMostrar = asignada / piezasCaja;
            diferenciaMostrar = Math.abs(diferencia) / piezasCaja;
        } else if (esCeramicaActual) {
            unidadMensaje = 'piezas';
        }

        if (diferencia === 0) {
            alerta.className = 'alerta-stock ok';

            if (esCeramicaActual && unidad === 'caja') {
                alerta.textContent =
                    `Stock asignado correctamente: ${asignadaMostrar} cajas (${asignada} piezas).`;
            } else {
                alerta.textContent =
                    `Stock asignado correctamente: ${asignada} ${unidadMensaje}.`;
            }

            return true;

        } else if (diferencia < 0) {
            alerta.className = 'alerta-stock warn';

            alerta.textContent =
                `Falta asignar: ${diferenciaMostrar} ${unidadMensaje}.`;

        } else {
            alerta.className = 'alerta-stock danger';

            alerta.textContent =
                `Hay un exceso de: ${diferenciaMostrar} ${unidadMensaje}.`;
        }

        return false;
    }

    function recalcularLinea(linea) {
        const cantidadInput = linea.querySelector('.cantidad-input');

        cantidadInput.step = '1';
        cantidadInput.min = '1';

        // Permitir que el campo quede vacío mientras el vendedor escribe.
        if (cantidadInput.value.trim() === '') {
            linea.querySelector('.precio-input').value = '0.00';
            linea.querySelector('.linea-subtotal').textContent = fmt(0);
            linea.querySelector('.conversion-info').style.display = 'none';
            validarOrigenes(linea);
            return 0;
        }

        const cantidad = Number(cantidadInput.value);

        if (!Number.isFinite(cantidad) || cantidad < 1) {
            return 0;
        }

        const unidad = linea.querySelector('.unidad-select').value;
        const producto = linea._productoData;

        if (!producto) return 0;

        const calculo = calcularPrecio(producto, cantidad, unidad);

        linea.querySelector('.precio-input').value =
            calculo.precio.toFixed(2);

        linea.querySelector('.linea-subtotal').textContent =
            fmt(calculo.subtotal);

        const conversionInfo = linea.querySelector('.conversion-info');
        const conversionDetalle = linea.querySelector('.conversion-detalle');

        if (esCeramica(producto) && unidad === 'm2') {
            const piezas = obtenerCantidadFisica(producto, cantidad, unidad);

            conversionInfo.style.display = '';
            conversionDetalle.textContent =
                `${cantidad} m² requieren ${piezas} piezas físicas.`;
        } else if (esCeramica(producto) && unidad === 'caja') {
            const piezas = obtenerCantidadFisica(producto, cantidad, unidad);

            conversionInfo.style.display = '';
            conversionDetalle.textContent =
                `${cantidad} cajas equivalen a ${piezas} piezas físicas.`;
        } else {
            conversionInfo.style.display = 'none';
            conversionDetalle.textContent = '';
        }

        validarOrigenes(linea);

        return calculo.subtotal;
    }

    function recalcular() {
        let subtotal = 0;

        contenedor.querySelectorAll('.linea-venta').forEach(linea => {
            subtotal += recalcularLinea(linea);
        });

        const descuento = Number(inputDesc.value) || 0;

        txtSubtotal.textContent = fmt(subtotal);
        txtDesc.textContent = fmt(descuento);
        txtTotal.textContent = fmt(Math.max(0, subtotal - descuento));
    }

    async function cargarStockProducto(linea, productoId, datosPre = null) {
        const origenesSection = linea.querySelector('.origenes-section');
        const lista = linea.querySelector('.origenes-lista');
        const unidadSelect = linea.querySelector('.unidad-select');

        if (!productoId) {
            origenesSection.style.display = 'none';
            unidadSelect.innerHTML = '<option value="">Unidad</option>';
            unidadSelect.disabled = true;
            linea._productoData = null;
            recalcular();
            return;
        }

        origenesSection.style.display = '';
        lista.innerHTML = '<div class="text-muted p-2">Cargando inventario...</div>';

        const res = await fetch(
            `{{ url('vendedor/ventas/stock-producto') }}/${productoId}?admin_id=${encodeURIComponent(adminId)}`
        );

        if (!res.ok) {
            throw new Error('No se pudo consultar el inventario del producto.');
        }

        const data = await res.json();

        if (!data.producto) {
            throw new Error('No se recibieron los datos del producto.');
        }

        linea._productoData = data.producto;
        linea._stocks = data.stocks || [];

        // Construir unidades permitidas por el controlador.
        unidadSelect.innerHTML = '';

        (data.producto.unidades_venta || []).forEach(unidad => {
            const option = document.createElement('option');
            option.value = unidad;

            const nombres = {
                caja: 'Caja',
                m2: 'm²',
                pieza: 'Pieza',
                bolsa: 'Bolsa'
            };

            option.textContent = nombres[unidad] || unidad;
            unidadSelect.appendChild(option);
        });

        unidadSelect.disabled = false;

        const unidadAnterior = datosPre?.unidad || unidadSelect.options[0]?.value;

        if ([...unidadSelect.options].some(o => o.value === unidadAnterior)) {
            unidadSelect.value = unidadAnterior;
        }

        const asignaciones = {};

        (datosPre?.movimientos || []).forEach(m => {
            const depositoId = String(m.deposito_id);

            asignaciones[depositoId] =
                (asignaciones[depositoId] || 0) + Number(m.cantidad || 0);
        });

        construirOrigenes(linea, linea._stocks, asignaciones);

        origenesSection.style.display = '';
        recalcular();
    }

    function agregarLinea(datosPre = null) {
        numLinea++;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = template.innerHTML.trim();

        const linea = wrapper.firstElementChild;
        linea.querySelector('[data-num]').textContent = numLinea;

        const productoSelect = linea.querySelector('.producto-select');
        const unidadSelect = linea.querySelector('.unidad-select');
        const cantidadInput = linea.querySelector('.cantidad-input');
        const buscadorProducto = linea.querySelector('.buscador-producto');

        llenarSelectProductos(productoSelect, datosPre?.producto_id);


        // ==========================================
        // BUSCADOR DE PRODUCTOS
        // ==========================================

        buscadorProducto.addEventListener('input', function () {

            const busqueda = this.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim();

            Array.from(productoSelect.options).forEach(option => {

                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                const texto = (option.dataset.search || '')
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '');

                const coincide = texto.includes(busqueda);

                // Mantener visible el producto seleccionado.
                option.hidden = !coincide &&
                    option.value !== productoSelect.value;
            });
        });

        productoSelect.addEventListener('change', async function () {

            // Limpiar el buscador.
            buscadorProducto.value = '';

            // Volver a mostrar todos los productos.
            Array.from(productoSelect.options).forEach(option => {
                option.hidden = false;
            });

            try {
                await cargarStockProducto(linea, this.value);

            } catch (error) {
                alert(error.message);
                console.error(error);
            }
        });

        unidadSelect.addEventListener('change', function () {
            const asignaciones = obtenerAsignacionesFisicas(linea);

            construirOrigenes(
                linea,
                linea._stocks || [],
                asignaciones
            );

            recalcular();
        });

        cantidadInput.addEventListener('input', recalcular);

        linea.querySelector('[data-remove]').addEventListener('click', function () {
            linea.remove();
            recalcular();
        });

        contenedor.appendChild(linea);

        if (datosPre) {
            cantidadInput.value = datosPre.cantidad;

            cargarStockProducto(
                linea,
                datosPre.producto_id,
                datosPre
            ).catch(error => {
                alert(error.message);
                console.error(error);
            });
        }

        recalcular();
    }

    // Cargar productos antes de crear las líneas existentes.
    try {
        await cargarProductosAdmin();

        if (lineasExistentes.length > 0) {
            for (const linea of lineasExistentes) {
                agregarLinea(linea);
            }
        } else {
            agregarLinea();
        }
    } catch (error) {
        console.error(error);
        alert(error.message);

        btnAgregar.disabled = true;
    }

    btnAgregar.addEventListener('click', function () {
        agregarLinea();
    });

    inputDesc.addEventListener('input', recalcular);

    form.addEventListener('submit', function (e) {
        if (form.dataset.confirmado === '1') return;

        e.preventDefault();

        const lineas = [...contenedor.querySelectorAll('.linea-venta')];

        if (!lineas.length) {
            alert('Debes agregar al menos un producto.');
            return;
        }

        for (const linea of lineas) {
            const productoId = linea.querySelector('.producto-select').value;
            const unidad = linea.querySelector('.unidad-select').value;
            const cantidad = Number(linea.querySelector('.cantidad-input').value);

            if (!productoId || !unidad || cantidad <= 0) {
                alert('Completa el producto, la unidad y la cantidad de cada línea.');
                return;
            }

            if (!validarOrigenes(linea)) {
                alert('La cantidad asignada por depósito no coincide con la cantidad física requerida.');
                return;
            }
        }

        // Eliminar los campos ocultos generados anteriormente.
        form.querySelectorAll('input[name^="items["]').forEach(el => el.remove());

        function agregarHidden(nombre, valor) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = nombre;
            input.value = valor;
            form.appendChild(input);
        }

        lineas.forEach((linea, idx) => {
            const productoId = linea.querySelector('.producto-select').value;
            const cantidad = Number(linea.querySelector('.cantidad-input').value);
            const unidad = linea.querySelector('.unidad-select').value;
            const producto = linea._productoData;

            const calculo = calcularPrecio(producto, cantidad, unidad);

            agregarHidden(`items[${idx}][producto_id]`, productoId);
            agregarHidden(`items[${idx}][cantidad]`, cantidad);
            agregarHidden(`items[${idx}][unidad]`, unidad);
            agregarHidden(`items[${idx}][precio_unitario]`, calculo.precio);

            let movimientoIndex = 0;

            linea.querySelectorAll('.cantidad-origen').forEach(input => {
                const cantidadIngresada = Number(input.value) || 0;

                if (cantidadIngresada <= 0) return;

               const item = input.closest('.origen-item');

                let cantidadFisica = cantidadIngresada;

                // Si la venta es por cajas, convertir la asignación a piezas físicas.
                if (esCeramica(producto) && unidad === 'caja') {
                    cantidadFisica *= Number(producto.piezas_por_caja || 0);
                }
                agregarHidden(
                    `items[${idx}][movimientos][${movimientoIndex}][stock_user_id]`,
                    item.dataset.userId
                );

                agregarHidden(
                    `items[${idx}][movimientos][${movimientoIndex}][deposito_id]`,
                    item.dataset.depositoId
                );

                agregarHidden(
                    `items[${idx}][movimientos][${movimientoIndex}][cantidad]`,
                    cantidadFisica
                );

                movimientoIndex++;
            });
        });

        form.dataset.confirmado = '1';
        form.submit();
    });
});
</script>

</script>

</script>
@endpush