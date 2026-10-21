
@extends('layouts.plantilla_maestra')

@section('title', 'Editar registro del camión - AMERI')

@push('styles')
<style>
    .camion-edit {
        width: 100%;
        padding: .5rem .75rem;
        color: var(--ink);
    }

    .camion-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .camion-edit-header h1 {
        margin: 0 0 .35rem;
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--ink);
    }

    .camion-edit-header h1 .mdi {
        color: var(--cyan);
    }

    .camion-edit-header p {
        margin: 0;
        color: var(--ink-muted);
        font-size: .88rem;
    }

    .camion-edit-panel {
        padding: 1.4rem;
        border: 1px solid var(--line);
        border-radius: var(--r-lg);
        background: var(--bg-1);
        box-shadow: 0 20px 40px -28px var(--shadow);
    }

    .camion-edit-panel h2 {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0 0 .4rem;
        font-family: var(--font-display);
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--ink);
    }

    .camion-edit-panel h2 .mdi {
        color: var(--cyan);
    }

    .camion-edit-descripcion {
        margin-bottom: 1.4rem;
        color: var(--ink-muted);
        font-size: .88rem;
    }

    .camion-edit-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .camion-edit-campo {
        min-width: 0;
    }

    .camion-edit-campo.completo {
        grid-column: 1 / -1;
    }

    .camion-edit-campo label {
        display: block;
        margin-bottom: .45rem;
        color: var(--ink-muted);
        font-family: var(--font-display);
        font-size: .74rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .camion-edit-campo input,
    .camion-edit-campo select,
    .camion-edit-campo textarea {
        display: block;
        width: 100%;
        min-height: 43px;
        padding: .7rem .9rem;
        border: 1px solid var(--line);
        border-radius: var(--r-md);
        background: var(--bg-2);
        color: var(--ink);
        font: inherit;
        font-size: .9rem;
        box-sizing: border-box;
        transition: border-color .2s var(--ease),
                    box-shadow .2s var(--ease);
    }

    .camion-edit-campo input:focus,
    .camion-edit-campo select:focus,
    .camion-edit-campo textarea:focus {
        outline: none;
        border-color: var(--cyan);
        background: var(--bg-3);
        box-shadow: 0 0 0 3px var(--glow-soft);
    }

    .camion-edit-campo textarea {
        min-height: 100px;
        resize: vertical;
    }

    .camion-edit-campo input::placeholder,
    .camion-edit-campo textarea::placeholder {
        color: var(--ink-faint);
    }

    .camion-edit-campo[hidden] {
        display: none !important;
    }

    .camion-edit-ayuda {
        display: block;
        margin-top: .4rem;
        color: var(--ink-muted);
        font-size: .78rem;
    }

    .camion-edit-acciones {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: .75rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--line);
    }

    .camion-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        padding: .7rem 1.3rem;
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

    .camion-edit-btn:hover {
        color: var(--cyan-ink);
        transform: translateY(-2px);
        box-shadow: 0 12px 28px -6px var(--glow);
    }

    .camion-edit-btn.secundario {
        background: var(--bg-2);
        color: var(--ink);
        border: 1px solid var(--line);
        box-shadow: none;
    }

    .camion-edit-btn.secundario:hover {
        color: var(--cyan);
        border-color: var(--line-strong);
    }

    .camion-edit-alerta {
        margin-bottom: 1rem;
        padding: .85rem 1rem;
        border: 1px solid color-mix(
            in srgb, var(--error) 40%, transparent
        );
        border-radius: var(--r-md);
        background: color-mix(
            in srgb, var(--error) 9%, var(--bg-1)
        );
        color: var(--error);
    }

    .camion-edit-comprobante {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        margin-top: .5rem;
        color: var(--cyan);
        font-weight: 600;
        text-decoration: none;
    }

    @media (max-width: 600px) {
        .camion-edit-grid {
            grid-template-columns: 1fr;
        }

        .camion-edit-campo.completo {
            grid-column: auto;
        }

        .camion-edit-panel {
            padding: 1rem;
        }

        .camion-edit-acciones .camion-edit-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="camion-edit">

    <div class="camion-edit-header">
        <div>
            <h1>
                <i class="mdi mdi-pencil-outline"></i>
                @if($tipo === 'chofer')
                    Editar chofer
                @elseif($tipo === 'viaje')
                    Editar viaje
                @else
                    Editar gasto
                @endif
            </h1>

            <p>Actualiza los datos del registro seleccionado.</p>
        </div>

        <a href="{{ route('admin.camion.index') }}"
           class="camion-edit-btn secundario">
            <i class="mdi mdi-arrow-left"></i>
            Volver al panel
        </a>
    </div>

    @if($errors->any())
        <div class="camion-edit-alerta">
            <strong>Corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- =====================================
         EDITAR CHOFER
    ===================================== --}}
    @if($tipo === 'chofer')
        <section class="camion-edit-panel">
            <h2>
                <i class="mdi mdi-account-edit-outline"></i>
                Datos del chofer
            </h2>

            <p class="camion-edit-descripcion">
                Modifica el nombre o teléfono del conductor.
            </p>

            <form method="POST"
                  action="{{ route('admin.camion.choferes.update', $registro) }}">
                @csrf
                @method('PUT')

                <div class="camion-edit-grid">
                    <div class="camion-edit-campo">
                        <label for="nombre">Nombre completo *</label>
                        <input id="nombre"
                               name="nombre"
                               maxlength="150"
                               value="{{ old('nombre', $registro->nombre) }}"
                               required>
                    </div>

                    <div class="camion-edit-campo">
                        <label for="telefono">Teléfono</label>
                        <input id="telefono"
                               name="telefono"
                               maxlength="30"
                               value="{{ old('telefono', $registro->telefono) }}">
                    </div>
                </div>

                <div class="camion-edit-acciones">
                    <a href="{{ route('admin.camion.index') }}"
                       class="camion-edit-btn secundario">
                        Cancelar
                    </a>

                    <button type="submit" class="camion-edit-btn">
                        <i class="mdi mdi-content-save-outline"></i>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </section>
    @endif

    {{-- =====================================
         EDITAR VIAJE
    ===================================== --}}
    @if($tipo === 'viaje')
        <section class="camion-edit-panel">
            <h2>
                <i class="mdi mdi-truck-edit-outline"></i>
                Datos del viaje
            </h2>

            <p class="camion-edit-descripcion">
                Modifica la ruta, el chofer, el monto o el estado de cobro.
            </p>

            <form method="POST"
                  action="{{ route('admin.camion.viajes.update', $registro) }}">
                @csrf
                @method('PUT')

                <div class="camion-edit-grid">
                    <div class="camion-edit-campo">
                        <label for="fecha_viaje">Fecha *</label>
                        <input type="date"
                               id="fecha_viaje"
                               name="fecha"
                               value="{{ old('fecha', $registro->fecha->format('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="camion-edit-campo">
                        <label for="chofer_id">Chofer *</label>
                        <select id="chofer_id" name="chofer_id" required>
                            <option value="">Seleccionar chofer</option>

                            @foreach($choferes as $chofer)
                                @if($chofer->activo || $chofer->id == $registro->chofer_id)
                                    <option value="{{ $chofer->id }}"
                                        @selected(old('chofer_id', $registro->chofer_id) == $chofer->id)>
                                        {{ $chofer->nombre }}
                                        {{ $chofer->activo ? '' : '(Inactivo)' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="camion-edit-campo">
                        <label for="origen">Origen *</label>
                        <input id="origen"
                               name="origen"
                               maxlength="200"
                               value="{{ old('origen', $registro->origen) }}"
                               required>
                    </div>

                    <div class="camion-edit-campo">
                        <label for="destino">Destino *</label>
                        <input id="destino"
                               name="destino"
                               maxlength="200"
                               value="{{ old('destino', $registro->destino) }}"
                               required>
                    </div>

                    <div class="camion-edit-campo">
                        <label for="monto_cobrado">Monto del viaje (Bs) *</label>
                        <input type="number"
                               id="monto_cobrado"
                               name="monto_cobrado"
                               min="0"
                               max="9999999999.99"
                               step="0.01"
                               value="{{ old('monto_cobrado', $registro->monto_cobrado) }}"
                               required>
                    </div>

                    <div class="camion-edit-campo">
                        <label for="estado_pago">Estado del cobro *</label>
                        <select id="estado_pago" name="estado_pago" required>
                            <option value="pendiente"
                                @selected(old('estado_pago', $registro->estado_pago) === 'pendiente')>
                                Pendiente de pago
                            </option>

                            <option value="pagado"
                                @selected(old('estado_pago', $registro->estado_pago) === 'pagado')>
                                Pagado
                            </option>
                        </select>
                    </div>

                    <div class="camion-edit-campo completo">
                        <label for="mercaderia">Mercadería transportada</label>
                        <textarea id="mercaderia"
                                  name="mercaderia"
                                  maxlength="5000">{{ old('mercaderia', $registro->mercaderia) }}</textarea>
                    </div>

                    <div class="camion-edit-campo completo">
                        <label for="observaciones_viaje">Observaciones</label>
                        <textarea id="observaciones_viaje"
                                  name="observaciones"
                                  maxlength="5000">{{ old('observaciones', $registro->observaciones) }}</textarea>
                    </div>
                </div>

                <div class="camion-edit-acciones">
                    <a href="{{ route('admin.camion.index') }}"
                       class="camion-edit-btn secundario">
                        Cancelar
                    </a>

                    <button type="submit" class="camion-edit-btn">
                        <i class="mdi mdi-content-save-outline"></i>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </section>
    @endif

    {{-- =====================================
         EDITAR GASTO
    ===================================== --}}
    @if($tipo === 'gasto')
        <section class="camion-edit-panel">
            <h2>
                <i class="mdi mdi-receipt-text-edit-outline"></i>
                Datos del gasto
            </h2>

            <p class="camion-edit-descripcion">
                Actualiza los detalles del gasto del camión.
            </p>

            <form method="POST"
                  action="{{ route('admin.camion.gastos.update', $registro) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="camion-edit-grid">
                    <div class="camion-edit-campo">
                        <label for="fecha_gasto">Fecha *</label>
                        <input type="date"
                               id="fecha_gasto"
                               name="fecha"
                               value="{{ old('fecha', $registro->fecha->format('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="camion-edit-campo">
                        <label for="tipo_gasto">Tipo de gasto *</label>
                        <select id="tipo_gasto" name="tipo_gasto" required>
                            @foreach([
                                'combustible' => 'Combustible',
                                'aceite' => 'Aceite',
                                'mantenimiento' => 'Mantenimiento',
                                'repuestos' => 'Repuestos',
                                'otros' => 'Otros'
                            ] as $valor => $nombre)
                                <option value="{{ $valor }}"
                                    @selected(old('tipo_gasto', $registro->tipo_gasto) === $valor)>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="camion-edit-campo" id="campo_tipo_combustible">
                        <label for="tipo_combustible">Combustible *</label>
                        <select id="tipo_combustible" name="tipo_combustible">
                            <option value="">Seleccionar</option>
                            <option value="gasolina"
                                @selected(old('tipo_combustible', $registro->tipo_combustible) === 'gasolina')>
                                Gasolina
                            </option>
                            <option value="gnv"
                                @selected(old('tipo_combustible', $registro->tipo_combustible) === 'gnv')>
                                GNV
                            </option>
                        </select>
                    </div>

                    <div class="camion-edit-campo">
                        <label for="monto">Monto (Bs) *</label>
                        <input type="number"
                               id="monto"
                               name="monto"
                               min="0.01"
                               max="9999999999.99"
                               step="0.01"
                               value="{{ old('monto', $registro->monto) }}"
                               required>
                    </div>

                    <div class="camion-edit-campo">
                        <label for="kilometraje">Kilometraje</label>
                        <input type="number"
                               id="kilometraje"
                               name="kilometraje"
                               min="0"
                               step="1"
                               value="{{ old('kilometraje', $registro->kilometraje) }}">
                    </div>

                    <div class="camion-edit-campo completo">
                        <label for="descripcion">Descripción</label>
                        <input id="descripcion"
                               name="descripcion"
                               maxlength="255"
                               value="{{ old('descripcion', $registro->descripcion) }}">
                    </div>

                    <div class="camion-edit-campo completo">
                        <label for="comprobante">Reemplazar comprobante</label>
                        <input type="file"
                               id="comprobante"
                               name="comprobante"
                               accept=".jpg,.jpeg,.png,.webp,.pdf">

                        <small class="camion-edit-ayuda">
                            Déjalo vacío para conservar el comprobante actual.
                        </small>

                        @if($registro->comprobante)
                            <a href="{{ route('admin.camion.gastos.comprobante', $registro) }}"
                               target="_blank"
                               rel="noopener"
                               class="camion-edit-comprobante">
                                <i class="mdi mdi-file-eye-outline"></i>
                                Ver comprobante actual
                            </a>
                        @endif
                    </div>

                    <div class="camion-edit-campo completo">
                        <label for="observaciones_gasto">Observaciones</label>
                        <textarea id="observaciones_gasto"
                                  name="observaciones"
                                  maxlength="5000">{{ old('observaciones', $registro->observaciones) }}</textarea>
                    </div>
                </div>

                <div class="camion-edit-acciones">
                    <a href="{{ route('admin.camion.index') }}"
                       class="camion-edit-btn secundario">
                        Cancelar
                    </a>

                    <button type="submit" class="camion-edit-btn">
                        <i class="mdi mdi-content-save-outline"></i>
                        Guardar cambios
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

    if (!tipoGasto || !campoCombustible || !tipoCombustible) {
        return;
    }

    function actualizarCombustible() {
        const esCombustible = tipoGasto.value === 'combustible';

        campoCombustible.hidden = !esCombustible;
        tipoCombustible.required = esCombustible;

        if (!esCombustible) {
            tipoCombustible.value = '';
        }
    }

    tipoGasto.addEventListener('change', actualizarCombustible);
    actualizarCombustible();
});
</script>
@endpush
