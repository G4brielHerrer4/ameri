@extends('layouts.plantilla_maestra')

@section('title', 'Registro del camión - AMERI')

@push('styles')
<style>
    /* CONTENEDOR */
    .registro-camion {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0.5rem 0.75rem;
        color: var(--ink);
        box-sizing: border-box;
    }

    /* ENCABEZADO */
    .registro-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .registro-header h1 {
        margin: 0 0 .35rem;
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--ink);
    }

    .registro-header h1 .mdi {
        color: var(--cyan);
    }

    .registro-header p {
        margin: 0;
        color: var(--ink-muted);
        font-size: .88rem;
    }

    /* BOTONES */
    .registro-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: .45rem;
        padding: .7rem 1.2rem;
        border: none;
        border-radius: var(--r-pill);
        background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
        color: var(--cyan-ink);
        font-family: var(--font-display);
        font-size: .88rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all .3s var(--ease);
        box-shadow: 0 6px 20px -6px var(--glow);
    }

    .registro-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px -6px var(--glow);
        color: var(--cyan-ink);
    }

    .registro-btn.secundario {
        background: var(--bg-2);
        color: var(--ink);
        border: 1px solid var(--line);
        box-shadow: none;
    }

    .registro-btn.secundario:hover {
        border-color: var(--line-strong);
        color: var(--cyan);
    }

    /* OPCIONES DE REGISTRO */
    .registro-opciones {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .registro-opcion {
        display: flex;
        align-items: center;
        gap: .8rem;
        padding: 1.1rem;
        border: 1px solid var(--line);
        border-radius: var(--r-lg);
        background: var(--bg-1);
        color: var(--ink);
        text-decoration: none;
        transition: all .3s var(--ease);
    }

    .registro-opcion:hover {
        border-color: var(--cyan);
        background: var(--bg-2);
        transform: translateY(-2px);
        color: var(--ink);
        box-shadow: 0 8px 24px -12px var(--glow);
    }

    .registro-opcion.activa {
        border-color: var(--cyan);
        background: color-mix(
            in srgb,
            var(--cyan) 7%,
            var(--bg-1)
        );
        box-shadow: 0 0 0 1px var(--cyan);
    }

    .registro-opcion .opcion-icono {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 46px;
        height: 46px;
        border-radius: var(--r-md);
        background: var(--bg-2);
        color: var(--cyan);
        font-size: 1.5rem;
    }

    .registro-opcion.activa .opcion-icono {
        background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
        color: var(--cyan-ink);
        box-shadow: 0 6px 20px -6px var(--glow);
    }

    .registro-opcion strong,
    .registro-opcion small {
        display: block;
    }

    .registro-opcion strong {
        font-family: var(--font-display);
        font-size: .9rem;
        font-weight: 600;
        color: var(--ink);
    }

    .registro-opcion small {
        margin-top: .25rem;
        color: var(--ink-muted);
        font-size: .78rem;
    }

    /* PANEL DEL FORMULARIO */
    .registro-panel {
        padding: 1.4rem;
        border: 1px solid var(--line);
        border-radius: var(--r-lg);
        background: var(--bg-1);
        box-shadow: 0 20px 40px -28px var(--shadow);
    }

    .registro-panel h2 {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0 0 .4rem;
        font-family: var(--font-display);
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--ink);
    }

    .registro-panel h2 .mdi {
        color: var(--cyan);
    }

    .registro-descripcion {
        margin: 0 0 1.4rem;
        color: var(--ink-muted);
        font-size: .88rem;
    }

    /* DISTRIBUCIÓN DE CAMPOS */
    .registro-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .registro-campo {
        min-width: 0;
    }

    .registro-campo.completo {
        grid-column: 1 / -1;
    }

    /* ETIQUETAS */
    .registro-campo label {
        display: block;
        margin-bottom: .45rem;
        font-family: var(--font-display);
        font-size: .74rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--ink-muted);
    }

    /* INPUTS, SELECTS Y TEXTAREAS */
    .registro-campo input,
    .registro-campo select,
    .registro-campo textarea {
        display: block;
        width: 100%;
        min-height: 43px;
        padding: .7rem .9rem;
        border: 1px solid var(--line);
        border-radius: var(--r-md);
        background: var(--bg-2);
        color: var(--ink);
        font-family: inherit;
        font-size: .9rem;
        box-sizing: border-box;
        transition:
            border-color .2s var(--ease),
            box-shadow .2s var(--ease),
            background .2s var(--ease);
    }

    .registro-campo input::placeholder,
    .registro-campo textarea::placeholder {
        color: var(--ink-faint);
    }

    .registro-campo input:focus,
    .registro-campo select:focus,
    .registro-campo textarea:focus {
        outline: none;
        border-color: var(--cyan);
        background: var(--bg-3);
        box-shadow:
            0 0 0 3px var(--glow-soft),
            0 0 18px -8px var(--glow);
    }

    .registro-campo input[type="file"] {
        padding: .55rem .75rem;
    }

    .registro-campo input[type="file"]::file-selector-button {
        margin-right: .8rem;
        padding: .4rem .75rem;
        border: 1px solid var(--line-strong);
        border-radius: var(--r-sm);
        background: var(--bg-3);
        color: var(--ink);
        cursor: pointer;
    }

    .registro-campo textarea {
        min-height: 95px;
        resize: vertical;
    }

    /* CAMPO DE COMBUSTIBLE */
    .registro-campo[hidden] {
        display: none !important;
    }

    /* AYUDAS */
    .registro-ayuda {
        display: block;
        margin-top: .35rem;
        color: var(--ink-muted);
        font-size: .78rem;
    }

    /* ACCIONES */
    .registro-acciones {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: .7rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--line);
    }

    /* ALERTAS */
    .registro-alerta {
        margin-bottom: 1rem;
        padding: .85rem 1rem;
        border: 1px solid color-mix(
            in srgb,
            var(--error) 40%,
            transparent
        );
        border-radius: var(--r-md);
        background: color-mix(
            in srgb,
            var(--error) 9%,
            var(--bg-1)
        );
        color: var(--error);
        font-size: .88rem;
    }

    /* RESPONSIVE */
    @media (max-width: 760px) {
        .registro-opciones {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 560px) {
        .registro-grid {
            grid-template-columns: 1fr;
        }

        .registro-campo.completo {
            grid-column: auto;
        }

        .registro-panel {
            padding: 1rem;
        }

        .registro-header h1 {
            font-size: 1.25rem;
        }

        .registro-acciones .registro-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
@php
    $tipoActual = in_array($tipo, ['chofer', 'viaje', 'gasto'], true)
        ? $tipo
        : 'chofer';
@endphp

<div class="registro-camion">
    <div class="registro-header">
        <div>
            <h1><i class="mdi mdi-truck-outline"></i> Registrar en Camión</h1>
            <p>Selecciona qué información deseas registrar.</p>
        </div>

        <a href="{{ route('admin.camion.index') }}"
           class="registro-btn secundario">
            <i class="mdi mdi-arrow-left"></i>
            Volver al panel
        </a>
    </div>

    @if(session('error'))
        <div class="registro-alerta">
            <i class="mdi mdi-alert-circle-outline"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="registro-alerta">
            <strong>Revisa los siguientes datos:</strong>
            <ul style="margin: .5rem 0 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="registro-opciones">
        <a href="{{ route('admin.camion.create', ['tipo' => 'chofer']) }}"
           class="registro-opcion {{ $tipoActual === 'chofer' ? 'activa' : '' }}">
            <span class="opcion-icono">
                <i class="mdi mdi-account-plus-outline"></i>
            </span>
            <span>
                <strong>Registrar chofer</strong>
                <small>Datos del conductor</small>
            </span>
        </a>

        <a href="{{ route('admin.camion.create', ['tipo' => 'viaje']) }}"
           class="registro-opcion {{ $tipoActual === 'viaje' ? 'activa' : '' }}">
            <span class="opcion-icono">
                <i class="mdi mdi-map-marker-path"></i>
            </span>
            <span>
                <strong>Registrar viaje</strong>
                <small>Rutas, cobros y mercadería</small>
            </span>
        </a>

        <a href="{{ route('admin.camion.create', ['tipo' => 'gasto']) }}"
           class="registro-opcion {{ $tipoActual === 'gasto' ? 'activa' : '' }}">
            <span class="opcion-icono">
                <i class="mdi mdi-cash-minus"></i>
            </span>
            <span>
                <strong>Registrar gasto</strong>
                <small>Combustible y otros gastos</small>
            </span>
        </a>
    </div>

    {{-- FORMULARIO DE CHOFER --}}
    @if($tipoActual === 'chofer')
        <section class="registro-panel">
            <h2>
                <i class="mdi mdi-account-plus-outline"></i>
                Nuevo chofer
            </h2>
            <p class="registro-descripcion">
                Introduce los datos de la persona que conducirá el camión.
            </p>

            <form method="POST"
                  action="{{ route('admin.camion.choferes.store') }}">
                @csrf

                <div class="registro-grid">
                    <div class="registro-campo">
                        <label for="nombre">Nombre completo *</label>
                        <input id="nombre"
                               name="nombre"
                               value="{{ old('nombre') }}"
                               maxlength="150"
                               placeholder="Nombre y apellidos"
                               required>
                    </div>

                    <div class="registro-campo">
                        <label for="telefono">Teléfono</label>
                        <input id="telefono"
                               name="telefono"
                               value="{{ old('telefono') }}"
                               maxlength="30"
                               placeholder="Número de contacto">
                    </div>
                </div>

                <div class="registro-acciones">
                    <a href="{{ route('admin.camion.index') }}"
                       class="registro-btn secundario">Cancelar</a>

                    <button type="submit" class="registro-btn">
                        <i class="mdi mdi-content-save-outline"></i>
                        Guardar chofer
                    </button>
                </div>
            </form>
        </section>
    @endif

    {{-- FORMULARIO DE VIAJE --}}
    @if($tipoActual === 'viaje')
        <section class="registro-panel">
            <h2>
                <i class="mdi mdi-map-marker-path"></i>
                Registrar viaje
            </h2>
            <p class="registro-descripcion">
                Registra el servicio realizado y el importe que corresponde cobrar.
            </p>

            <form method="POST"
                  action="{{ route('admin.camion.viajes.store') }}">
                @csrf

                <div class="registro-grid">
                    <div class="registro-campo">
                        <label for="fecha_viaje">Fecha del viaje *</label>
                        <input type="date"
                               id="fecha_viaje"
                               name="fecha"
                               value="{{ old('fecha', now()->format('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="registro-campo">
                        <label for="chofer_id">Chofer *</label>
                        <select id="chofer_id" name="chofer_id" required>
                            <option value="">Seleccionar chofer</option>
                            @foreach($choferes->where('activo', true) as $chofer)
                                <option value="{{ $chofer->id }}"
                                    @selected((string) old('chofer_id') === (string) $chofer->id)>
                                    {{ $chofer->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="registro-campo">
                        <label for="origen">Origen *</label>
                        <input id="origen"
                               name="origen"
                               value="{{ old('origen') }}"
                               maxlength="150"
                               placeholder="Lugar de salida"
                               required>
                    </div>

                    <div class="registro-campo">
                        <label for="destino">Destino *</label>
                        <input id="destino"
                               name="destino"
                               value="{{ old('destino') }}"
                               maxlength="150"
                               placeholder="Lugar de llegada"
                               required>
                    </div>

                    <div class="registro-campo">
                        <label for="monto_cobrado">Importe a cobrar *</label>
                        <input type="number"
                               id="monto_cobrado"
                               name="monto_cobrado"
                               value="{{ old('monto_cobrado') }}"
                               min="0.01"
                               step="0.01"
                               placeholder="0.00"
                               required>
                    </div>

                    <div class="registro-campo">
                        <label for="estado_pago">Estado del cobro *</label>
                        <select id="estado_pago" name="estado_pago" required>
                            <option value="pendiente"
                                @selected(old('estado_pago', 'pendiente') === 'pendiente')>
                                Por cobrar
                            </option>
                            <option value="pagado"
                                @selected(old('estado_pago') === 'pagado')>
                                Cobrado
                            </option>
                        </select>
                    </div>

                    <div class="registro-campo completo">
                        <label for="mercaderia">Mercadería o servicio</label>
                        <input id="mercaderia"
                               name="mercaderia"
                               value="{{ old('mercaderia') }}"
                               maxlength="255"
                               placeholder="Descripción de la carga o servicio">
                    </div>

                    <div class="registro-campo completo">
                        <label for="observaciones_viaje">Observaciones</label>
                        <textarea id="observaciones_viaje"
                                  name="observaciones"
                                  placeholder="Detalles adicionales del viaje">{{ old('observaciones') }}</textarea>
                    </div>
                </div>

                <div class="registro-acciones">
                    <a href="{{ route('admin.camion.index') }}"
                       class="registro-btn secundario">Cancelar</a>

                    <button type="submit" class="registro-btn">
                        <i class="mdi mdi-content-save-outline"></i>
                        Guardar viaje
                    </button>
                </div>
            </form>
        </section>
    @endif

    {{-- FORMULARIO DE GASTO --}}
    @if($tipoActual === 'gasto')
        <section class="registro-panel">
            <h2>
                <i class="mdi mdi-cash-minus"></i>
                Registrar gasto
            </h2>
            <p class="registro-descripcion">
                Registra los gastos del camión y adjunta el comprobante si lo tienes.
            </p>

            <form method="POST"
                  action="{{ route('admin.camion.gastos.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="registro-grid">
                    <div class="registro-campo">
                        <label for="fecha_gasto">Fecha del gasto *</label>
                        <input type="date"
                               id="fecha_gasto"
                               name="fecha"
                               value="{{ old('fecha', now()->format('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="registro-campo">
                        <label for="tipo_gasto">Tipo de gasto *</label>
                        <select id="tipo_gasto" name="tipo_gasto" required>
                            <option value="combustible"
                                @selected(old('tipo_gasto', 'combustible') === 'combustible')>
                                Combustible
                            </option>
                            <option value="aceite"
                                @selected(old('tipo_gasto') === 'aceite')>
                                Aceite
                            </option>
                            <option value="mantenimiento"
                                @selected(old('tipo_gasto') === 'mantenimiento')>
                                Mantenimiento
                            </option>
                            <option value="repuestos"
                                @selected(old('tipo_gasto') === 'repuestos')>
                                Repuestos
                            </option>
                            <option value="otros"
                                @selected(old('tipo_gasto') === 'otros')>
                                Otros
                            </option>
                        </select>
                    </div>

                    <div class="registro-campo"
                         id="campo_tipo_combustible">
                        <label for="tipo_combustible">Tipo de combustible</label>
                        <select id="tipo_combustible" name="tipo_combustible">
                            <option value="">Seleccionar</option>
                            <option value="gasolina"
                                @selected(old('tipo_combustible') === 'gasolina')>
                                Gasolina
                            </option>
                            <option value="gnv"
                                @selected(old('tipo_combustible') === 'gnv')>
                                GNV
                            </option>
                        </select>
                    </div>

                    <div class="registro-campo">
                        <label for="monto_gasto">Importe del gasto *</label>
                        <input type="number"
                               id="monto_gasto"
                               name="monto"
                               value="{{ old('monto') }}"
                               min="0.01"
                               step="0.01"
                               placeholder="0.00"
                               required>
                    </div>

                    <div class="registro-campo">
                        <label for="kilometraje">Kilometraje</label>
                        <input type="number"
                               id="kilometraje"
                               name="kilometraje"
                               value="{{ old('kilometraje') }}"
                               min="0"
                               step="1"
                               placeholder="Kilómetros del camión">
                    </div>

                    <div class="registro-campo completo">
                        <label for="descripcion">Descripción</label>
                        <input id="descripcion"
                               name="descripcion"
                               value="{{ old('descripcion') }}"
                               maxlength="255"
                               placeholder="Detalle del gasto">
                    </div>

                    <div class="registro-campo completo">
                        <label for="comprobante">Comprobante o factura</label>
                        <input type="file"
                               id="comprobante"
                               name="comprobante"
                               accept=".jpg,.jpeg,.png,.pdf">
                        <small class="registro-ayuda">
                            Puedes adjuntar una imagen o PDF si corresponde.
                        </small>
                    </div>

                    <div class="registro-campo completo">
                        <label for="observaciones_gasto">Observaciones</label>
                        <textarea id="observaciones_gasto"
                                  name="observaciones"
                                  placeholder="Información adicional">{{ old('observaciones') }}</textarea>
                    </div>
                </div>

                <div class="registro-acciones">
                    <a href="{{ route('admin.camion.index') }}"
                       class="registro-btn secundario">Cancelar</a>

                    <button type="submit" class="registro-btn">
                        <i class="mdi mdi-content-save-outline"></i>
                        Guardar gasto
                    </button>
                </div>
            </form>
        </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipoGasto = document.getElementById('tipo_gasto');
        const campoCombustible = document.getElementById('campo_tipo_combustible');
        const tipoCombustible = document.getElementById('tipo_combustible');

        function actualizarCampoCombustible() {
            if (!tipoGasto || !campoCombustible || !tipoCombustible) {
                return;
            }

            const esCombustible = tipoGasto.value === 'combustible';

            campoCombustible.hidden = !esCombustible;
            tipoCombustible.required = esCombustible;

            if (!esCombustible) {
                tipoCombustible.value = '';
            }
        }

        if (tipoGasto) {
            tipoGasto.addEventListener('change', actualizarCampoCombustible);
            actualizarCampoCombustible();
        }
    });
</script>
@endpush
