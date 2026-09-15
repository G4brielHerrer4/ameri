<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form id="formEditarProducto" action="" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="mdi mdi-pencil-outline me-2"></i>
                        Editar producto
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>
                </div>

                <div class="modal-body">

                    {{-- NOMBRE --}}
                    <div class="mb-3">
                        <label class="form-label">
                            Nombre *
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            required
                        >
                    </div>


                    {{-- CATEGORÍA Y TIPO --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Categoría *
                            </label>

                            <select
                                id="editar_categoria_producto"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Seleccione una categoría
                                </option>

                                @foreach($categoriasProductos as $categoria)
                                    <option
                                        value="{{ $categoria->id }}"
                                    >
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Tipo de producto *
                            </label>

                            <select
                                name="tipo_producto_id"
                                id="editar_tipo_producto"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Seleccione un tipo
                                </option>

                                @foreach($tiposProductos as $tipo)
                                    <option
                                        value="{{ $tipo->id }}"
                                        data-categoria="{{ $tipo->categoria_id }}"
                                    >
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>


                    {{-- ========================= --}}
                    {{-- CERÁMICA / PORCELANATO --}}
                    {{-- ========================= --}}

                    <div
                        id="editar_campos_ceramica"
                        class="editar-campos-tipo"
                        style="display:none;"
                    >

                        <div class="row">

                            {{-- MARCA --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Marca *
                                </label>

                                <input
                                    type="text"
                                    name="marca"
                                    class="form-control"
                                    data-required="true"
                                >
                            </div>

                            {{-- MEDIDA --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Medida *
                                </label>

                                <input
                                    type="text"
                                    name="medida"
                                    class="form-control"
                                    data-required="true"
                                >
                            </div>

                        </div>


                        <div class="row">

                            {{-- PIEZAS POR CAJA --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Piezas por caja *
                                </label>

                                <input
                                    type="number"
                                    name="piezas_por_caja"
                                    class="form-control"
                                    min="1"
                                    data-required="true"
                                >
                            </div>

                            {{-- M2 POR CAJA --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    m² por caja *
                                </label>

                                <input
                                    type="number"
                                    name="m2_por_caja"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    data-required="true"
                                >
                            </div>

                        </div>


                        <div class="row">

                            {{-- COLOR --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Color
                                </label>

                                <input
                                    type="text"
                                    name="color"
                                    class="form-control"
                                >
                            </div>

                            {{-- ACABADO --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Acabado
                                </label>

                                <input
                                    type="text"
                                    name="acabado"
                                    class="form-control"
                                >
                            </div>

                        </div>


                        {{-- PRESENTACIÓN --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Presentación *
                            </label>

                            <input
                                type="text"
                                name="presentacion"
                                class="form-control"
                                data-required="true"
                            >
                        </div>

                    </div>


                    {{-- ========================= --}}
                    {{-- CEMENTO COLA --}}
                    {{-- ========================= --}}

                    <div
                        id="editar_campos_cemento_cola"
                        class="editar-campos-tipo"
                        style="display:none;"
                    >

                        <div class="row">

                            {{-- COLOR --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Color
                                </label>

                                <input
                                    type="text"
                                    name="color"
                                    class="form-control"
                                >
                            </div>

                            {{-- PESO --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Peso *
                                </label>

                                <div class="input-group">
                                    <input
                                        type="number"
                                        name="peso"
                                        class="form-control"
                                        min="0"
                                        step="0.01"
                                        data-required="true"
                                    >

                                    <span class="input-group-text">
                                        kg
                                    </span>
                                </div>
                            </div>

                        </div>


                        {{-- PRESENTACIÓN --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Presentación *
                            </label>

                            <input
                                type="text"
                                name="presentacion"
                                class="form-control"
                                data-required="true"
                            >
                        </div>

                    </div>


                    {{-- ========================= --}}
                    {{-- LISTELO / RANDA / PASTINAS --}}
                    {{-- ========================= --}}

                    <div
                        id="editar_campos_decorativo"
                        class="editar-campos-tipo"
                        style="display:none;"
                    >

                        <div class="row">

                            {{-- MODELO --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Modelo
                                </label>

                                <input
                                    type="text"
                                    name="modelo"
                                    class="form-control"
                                >
                            </div>

                            {{-- MEDIDA --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Medida
                                </label>

                                <input
                                    type="text"
                                    name="medida"
                                    class="form-control"
                                >
                            </div>

                        </div>


                        <div class="row">

                            {{-- PIEZAS --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Piezas por caja *
                                </label>

                                <input
                                    type="number"
                                    name="piezas_por_caja"
                                    class="form-control"
                                    min="1"
                                    data-required="true"
                                >
                            </div>

                           {{-- COLOR --}}
                          <div
                              id="editar_color_decorativo"
                              class="col-md-6 mb-3"
                          >
                              <label class="form-label">
                                  Color
                              </label>

                              <input
                                  type="text"
                                  name="color"
                                  class="form-control"
                              >
                          </div>

                        </div>


                        {{-- PRESENTACIÓN --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Presentación *
                            </label>

                            <input
                                type="text"
                                name="presentacion"
                                class="form-control"
                                data-required="true"
                            >
                        </div>

                    </div>


                    {{-- ========================= --}}
                    {{-- ESQUINEROS --}}
                    {{-- ========================= --}}

                    <div
                        id="editar_campos_esquinero"
                        class="editar-campos-tipo"
                        style="display:none;"
                    >

                        <div class="row">

                            {{-- MEDIDA --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Medida *
                                </label>

                                <input
                                    type="text"
                                    name="medida"
                                    class="form-control"
                                    data-required="true"
                                >
                            </div>

                            {{-- PIEZAS --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Piezas por caja *
                                </label>

                                <input
                                    type="number"
                                    name="piezas_por_caja"
                                    class="form-control"
                                    min="1"
                                    data-required="true"
                                >
                            </div>

                        </div>


                        {{-- COLOR --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Color *
                            </label>

                            <input
                                type="text"
                                name="color"
                                class="form-control"
                                data-required="true"
                            >
                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="mdi mdi-content-save"></i>
                        Actualizar
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>