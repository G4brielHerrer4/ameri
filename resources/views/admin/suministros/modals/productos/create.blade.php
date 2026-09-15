<div class="modal fade" id="modalCrearProducto" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <form action="{{ route('admin.suministros.productos.store') }}"
                  method="POST"
                  novalidate>

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="mdi mdi-plus-circle-outline me-2"></i>
                        Nuevo producto
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    @if ($errors->any() && session('open_modal') === 'crear-producto')

                        <div class="alert alert-danger mb-3">

                            <i class="mdi mdi-alert-circle-outline me-1"></i>

                            Por favor corrige los errores marcados abajo.

                        </div>

                    @endif


                    {{-- =====================================================
                         CATEGORÍA Y TIPO
                    ====================================================== --}}

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label for="categoriaProducto" class="form-label">

                                Categoría
                                <span class="text-danger">*</span>

                            </label>

                            <select id="categoriaProducto"
                                    class="form-select">

                                <option value="">
                                    Seleccione una categoría
                                </option>

                                @foreach($categoriasProductos as $categoria)

                                    <option value="{{ $categoria->id }}"
                                        {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>

                                        {{ $categoria->nombre }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label for="tipoProducto" class="form-label">

                                Tipo de producto
                                <span class="text-danger">*</span>

                            </label>

                            <select name="tipo_producto_id"
                                    id="tipoProducto"
                                    class="form-select @if(session('open_modal') === 'crear-producto' && $errors->has('tipo_producto_id')) is-invalid @endif"
                                    disabled>

                                <option value="">
                                    Seleccione primero una categoría
                                </option>

                            </select>

                            @if (session('open_modal') === 'crear-producto')

                                @error('tipo_producto_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            @endif

                        </div>

                    </div>


                    {{-- =====================================================
                         NOMBRE Y MARCA
                    ====================================================== --}}

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Nombre
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="nombre"
                                   class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('nombre')) is-invalid @endif"
                                   value="{{ session('open_modal') === 'crear-producto' ? old('nombre') : '' }}"
                                   placeholder="Ej: Porcelanato Gris">

                            @if (session('open_modal') === 'crear-producto')

                                @error('nombre')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            @endif

                        </div>


                        <div class="col-md-6 mb-3"
                             id="bloqueMarca">

                            <label class="form-label">

                                Marca
                                <span class="text-danger"
                                      id="asteriscoMarca">*</span>

                            </label>

                            <input type="text"
                                   name="marca"
                                   class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('marca')) is-invalid @endif"
                                   value="{{ session('open_modal') === 'crear-producto' ? old('marca') : '' }}"
                                   placeholder="Ej: Corona">

                            @if (session('open_modal') === 'crear-producto')

                                @error('marca')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            @endif

                        </div>

                    </div>


                    {{-- =====================================================
                         MODELO
                    ====================================================== --}}

                    <div class="row"
                         id="bloqueModelo">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Modelo
                            </label>

                            <input type="text"
                                   name="modelo"
                                   class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('modelo')) is-invalid @endif"
                                   value="{{ session('open_modal') === 'crear-producto' ? old('modelo') : '' }}"
                                   placeholder="Ej: Baño, Cocina, GRIS-60">

                            @if (session('open_modal') === 'crear-producto')

                                @error('modelo')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            @endif

                        </div>

                    </div>


                    {{-- =====================================================
                         MEDIDA / PIEZAS / M2 / PESO
                    ====================================================== --}}

                    <div class="row"
                         id="bloqueCaracteristicas">


                        {{-- Medida --}}

                        <div class="col-md-6 mb-3"
                             id="bloqueMedida">

                            <label class="form-label">

                                Medida
                                <span class="text-danger"
                                      id="asteriscoMedida">*</span>

                            </label>

                            <input type="text"
                                   name="medida"
                                   id="medidaProducto"
                                   class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('medida')) is-invalid @endif"
                                   value="{{ session('open_modal') === 'crear-producto' ? old('medida') : '' }}"
                                   placeholder="Ej: 60x60 cm, 3M">

                            @if (session('open_modal') === 'crear-producto')

                                @error('medida')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            @endif

                        </div>


                        {{-- Piezas por caja --}}

                        <div class="col-md-6 mb-3"
                             id="bloquePiezasCaja">

                            <label class="form-label">

                                Piezas por caja
                                <span class="text-danger"
                                      id="asteriscoPiezas">*</span>

                            </label>

                            <input type="number"
                                   name="piezas_por_caja"
                                   min="1"
                                   class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('piezas_por_caja')) is-invalid @endif"
                                   value="{{ session('open_modal') === 'crear-producto' ? old('piezas_por_caja') : '' }}"
                                   placeholder="Ej: 4">

                            @if (session('open_modal') === 'crear-producto')

                                @error('piezas_por_caja')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            @endif

                        </div>


                        {{-- M2 por caja --}}

                        <div class="col-md-6 mb-3"
                             id="bloqueM2Caja">

                            <label class="form-label">

                                m² por caja
                                <span class="text-danger">*</span>

                            </label>

                            <input type="number"
                                   name="m2_por_caja"
                                   min="0.01"
                                   step="0.01"
                                   class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('m2_por_caja')) is-invalid @endif"
                                   value="{{ session('open_modal') === 'crear-producto' ? old('m2_por_caja') : '' }}"
                                   placeholder="Ej: 1.44">

                            @if (session('open_modal') === 'crear-producto')

                                @error('m2_por_caja')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            @endif

                        </div>


                        {{-- Peso --}}

                        <div class="col-md-6 mb-3"
                             id="bloquePeso">

                            <label class="form-label">

                                Peso
                                <span class="text-danger"
                                      id="asteriscoPeso">*</span>

                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="peso"
                                       min="0.01"
                                       step="0.01"
                                       class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('peso')) is-invalid @endif"
                                       value="{{ session('open_modal') === 'crear-producto' ? old('peso') : '' }}"
                                       placeholder="Ej: 25">

                                <span class="input-group-text">
                                    kg
                                </span>

                            </div>

                            @if (session('open_modal') === 'crear-producto')

                                @error('peso')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            @endif

                        </div>

                    </div>


                    {{-- =====================================================
                         COLOR / ACABADO
                    ====================================================== --}}

                    <div class="row">

                        <div class="col-md-6 mb-3"
                             id="bloqueColor">

                            <label class="form-label">

                                Color
                                <span class="text-danger"
                                      id="asteriscoColor">*</span>

                            </label>

                            <input type="text"
                                   name="color"
                                   class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('color')) is-invalid @endif"
                                   value="{{ session('open_modal') === 'crear-producto' ? old('color') : '' }}"
                                   placeholder="Ej: Gris">

                            @if (session('open_modal') === 'crear-producto')

                                @error('color')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            @endif

                        </div>


                        <div class="col-md-6 mb-3"
                             id="bloqueAcabado">

                            <label class="form-label">

                                Acabado

                            </label>

                            <input type="text"
                                   name="acabado"
                                   class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('acabado')) is-invalid @endif"
                                   value="{{ session('open_modal') === 'crear-producto' ? old('acabado') : '' }}"
                                   placeholder="Ej: Mate, Brillante">

                            @if (session('open_modal') === 'crear-producto')

                                @error('acabado')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            @endif

                        </div>

                    </div>


                    {{-- =====================================================
                         PRESENTACIÓN
                    ====================================================== --}}

                    <div class="mb-3"
                         id="bloquePresentacion">

                        <label class="form-label">

                            Presentación
                            <span class="text-danger"
                                  id="asteriscoPresentacion">*</span>

                        </label>

                        <input type="text"
                               name="presentacion"
                               class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('presentacion')) is-invalid @endif"
                               value="{{ session('open_modal') === 'crear-producto' ? old('presentacion') : '' }}"
                               placeholder="Ej: Caja x 4 piezas, Bolsa 25kg">

                        @if (session('open_modal') === 'crear-producto')

                            @error('presentacion')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        @endif

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="mdi mdi-content-save"></i>
                        Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const categoriaSelect = document.getElementById('categoriaProducto');
    const tipoSelect = document.getElementById('tipoProducto');

    if (!categoriaSelect || !tipoSelect) {
        return;
    }


    const categorias = @json($categoriasProductos);


    /*
    |--------------------------------------------------------------------------
    | Elementos
    |--------------------------------------------------------------------------
    */

    const bloqueMarca = document.getElementById('bloqueMarca');
    const bloqueModelo = document.getElementById('bloqueModelo');
    const bloqueMedida = document.getElementById('bloqueMedida');
    const bloquePiezasCaja = document.getElementById('bloquePiezasCaja');
    const bloqueM2Caja = document.getElementById('bloqueM2Caja');
    const bloquePeso = document.getElementById('bloquePeso');
    const bloqueColor = document.getElementById('bloqueColor');
    const bloqueAcabado = document.getElementById('bloqueAcabado');
    const bloquePresentacion = document.getElementById('bloquePresentacion');

    const marcaInput = document.querySelector('[name="marca"]');
    const medidaInput = document.querySelector('[name="medida"]');
    const piezasInput = document.querySelector('[name="piezas_por_caja"]');
    const m2Input = document.querySelector('[name="m2_por_caja"]');
    const pesoInput = document.querySelector('[name="peso"]');
    const colorInput = document.querySelector('[name="color"]');
    const presentacionInput = document.querySelector('[name="presentacion"]');


    /*
    |--------------------------------------------------------------------------
    | Utilidad para normalizar texto
    |--------------------------------------------------------------------------
    */

    function normalizar(texto) {

        return texto
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');

    }


    /*
    |--------------------------------------------------------------------------
    | Cargar tipos
    |--------------------------------------------------------------------------
    */

    function cargarTipos(categoriaId, tipoSeleccionado = '') {

        tipoSelect.innerHTML = '';

        if (!categoriaId) {

            tipoSelect.innerHTML =
                '<option value="">Seleccione primero una categoría</option>';

            tipoSelect.disabled = true;

            actualizarCampos('');

            return;
        }


        const categoria = categorias.find(function (categoria) {

            return String(categoria.id) === String(categoriaId);

        });


        if (!categoria || !categoria.tipos) {

            tipoSelect.innerHTML =
                '<option value="">No hay tipos disponibles</option>';

            tipoSelect.disabled = true;

            actualizarCampos('');

            return;
        }


        tipoSelect.disabled = false;

        tipoSelect.innerHTML =
            '<option value="">Seleccione un tipo de producto</option>';


        categoria.tipos.forEach(function (tipo) {

            const option = document.createElement('option');

            option.value = tipo.id;

            option.textContent = tipo.nombre;

            if (String(tipo.id) === String(tipoSeleccionado)) {

                option.selected = true;

            }

            tipoSelect.appendChild(option);

        });


        actualizarCampos(tipoSeleccionado);
    }


    /*
    |--------------------------------------------------------------------------
    | Ocultar todos los campos
    |--------------------------------------------------------------------------
    */

    function ocultarTodos() {

        bloqueMarca.style.display = 'none';
        bloqueModelo.style.display = 'none';
        bloqueMedida.style.display = 'none';
        bloquePiezasCaja.style.display = 'none';
        bloqueM2Caja.style.display = 'none';
        bloquePeso.style.display = 'none';
        bloqueColor.style.display = 'none';
        bloqueAcabado.style.display = 'none';
        bloquePresentacion.style.display = 'none';


        marcaInput.required = false;
        medidaInput.required = false;
        piezasInput.required = false;
        m2Input.required = false;
        pesoInput.required = false;
        colorInput.required = false;
        presentacionInput.required = false;
    }


    /*
    |--------------------------------------------------------------------------
    | Configurar campos según tipo
    |--------------------------------------------------------------------------
    */

    function actualizarCampos(tipoId) {

        ocultarTodos();

        if (!tipoId) {
            return;
        }


        let tipoEncontrado = null;


        categorias.forEach(function (categoria) {

            if (!categoria.tipos) {
                return;
            }

            const tipo = categoria.tipos.find(function (tipo) {

                return String(tipo.id) === String(tipoId);

            });

            if (tipo) {
                tipoEncontrado = tipo;
            }

        });


        if (!tipoEncontrado) {
            return;
        }


        const tipo = normalizar(tipoEncontrado.nombre);


        /*
        |--------------------------------------------------------------------------
        | CERÁMICA / PORCELANATO
        |--------------------------------------------------------------------------
        */

        if (
            tipo === 'ceramica' ||
            tipo === 'porcelanato'
        ) {

            bloqueMarca.style.display = '';
            bloqueMedida.style.display = '';
            bloquePiezasCaja.style.display = '';
            bloqueM2Caja.style.display = '';
            bloqueColor.style.display = '';
            bloqueAcabado.style.display = '';
            bloquePresentacion.style.display = '';

            marcaInput.required = true;
            medidaInput.required = true;
            piezasInput.required = true;
            m2Input.required = true;
            presentacionInput.required = true;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CEMENTO COLA
        |--------------------------------------------------------------------------
        */

        if (tipo === 'cemento cola') {

            bloqueColor.style.display = '';
            bloquePeso.style.display = '';
            bloquePresentacion.style.display = '';

            pesoInput.required = true;
            presentacionInput.required = true;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | LISTELO / RANDA / PASTINAS
        |--------------------------------------------------------------------------
        */

        if (
            tipo === 'listelo' ||
            tipo === 'randa decorativa' ||
            tipo === 'pastinas'
        ) {
            bloqueModelo.style.display = '';
            bloqueMedida.style.display = '';
            bloquePiezasCaja.style.display = '';
            bloquePresentacion.style.display = '';

            piezasInput.required = true;
            presentacionInput.required = true;

            // Color solamente para Listelo y Randa decorativa
            if (
                tipo === 'listelo' ||
                tipo === 'randa decorativa'
            ) {
                bloqueColor.style.display = '';
            }

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | ESQUINERO DE ALUMINIO / GOMA
        |--------------------------------------------------------------------------
        */

        if (
            tipo === 'esquinero de aluminio' ||
            tipo === 'esquinero de goma'
        ) {

            bloqueMedida.style.display = '';
            bloquePiezasCaja.style.display = '';
            bloqueColor.style.display = '';

            medidaInput.required = true;
            piezasInput.required = true;
            colorInput.required = true;

            return;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Eventos
    |--------------------------------------------------------------------------
    */

    categoriaSelect.addEventListener('change', function () {

        cargarTipos(this.value);

    });


    tipoSelect.addEventListener('change', function () {

        actualizarCampos(this.value);

    });


    /*
    |--------------------------------------------------------------------------
    | Recuperar formulario después de error
    |--------------------------------------------------------------------------
    */

    const categoriaAnterior = @json(old('categoria_id'));
    const tipoAnterior = @json(old('tipo_producto_id'));


    if (categoriaAnterior) {

        categoriaSelect.value = categoriaAnterior;

        cargarTipos(
            categoriaAnterior,
            tipoAnterior
        );

    } else {

        tipoSelect.disabled = true;

        ocultarTodos();

    }

});
</script>