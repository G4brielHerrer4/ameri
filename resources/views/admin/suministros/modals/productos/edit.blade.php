{{-- resources/views/admin/suministros/modals/productos/edit.blade.php --}}

<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered modal-lg">

    <div class="modal-content ameri-modal">

      <form id="formEditarProducto" action="" method="POST">
        @csrf
        @method('PUT')

        {{-- ==================== HEADER ==================== --}}
        <div class="modal-header ameri-modal-header">

          <div class="d-flex align-items-center gap-3">

            <div class="ameri-modal-icon">
              <i class="mdi mdi-pencil-outline"></i>
            </div>

            <div>
              <h5 class="modal-title">Editar producto</h5>
              <p class="ameri-modal-sub">Actualiza los datos del producto</p>
            </div>

          </div>

          <button type="button"
                  class="btn-close btn-close-white"
                  data-bs-dismiss="modal"
                  aria-label="Cerrar"></button>

        </div>


        {{-- ==================== BODY ==================== --}}
        <div class="modal-body ameri-modal-body">

          {{-- NOMBRE --}}
          <div class="mb-3">
            <label class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" class="form-control" required>
          </div>


          {{-- CATEGORÍA Y TIPO --}}
          <div class="row">

            <div class="col-md-6 mb-3">
              <label class="form-label">Categoría <span class="text-danger">*</span></label>
              <select id="editar_categoria_producto" class="form-select" required>
                <option value="">Seleccione una categoría</option>
                @foreach($categoriasProductos as $categoria)
                  <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Tipo de producto <span class="text-danger">*</span></label>
              <select name="tipo_producto_id"
                      id="editar_tipo_producto"
                      class="form-select"
                      required>
                <option value="">Seleccione un tipo</option>
                @foreach($tiposProductos as $tipo)
                  <option value="{{ $tipo->id }}"
                          data-categoria="{{ $tipo->categoria_id }}">
                    {{ $tipo->nombre }}
                  </option>
                @endforeach
              </select>
            </div>

          </div>


          {{-- CERÁMICA / PORCELANATO --}}
          <div id="editar_campos_ceramica" class="editar-campos-tipo" style="display:none;">

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Marca <span class="text-danger">*</span></label>
                <input type="text" name="marca" class="form-control" data-required="true">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Medida <span class="text-danger">*</span></label>
                <input type="text" name="medida" class="form-control" data-required="true">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Piezas por caja <span class="text-danger">*</span></label>
                <input type="number" name="piezas_por_caja" class="form-control" min="1" data-required="true">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">m² por caja <span class="text-danger">*</span></label>
                <input type="number" name="m2_por_caja" class="form-control" min="0" step="0.01" data-required="true">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Color</label>
                <input type="text" name="color" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Acabado</label>
                <input type="text" name="acabado" class="form-control">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Presentación <span class="text-danger">*</span></label>
              <input type="text" name="presentacion" class="form-control" data-required="true">
            </div>

          </div>


          {{-- CEMENTO COLA --}}
          <div id="editar_campos_cemento_cola" class="editar-campos-tipo" style="display:none;">

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Color</label>
                <input type="text" name="color" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Peso <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="number" name="peso" class="form-control" min="0" step="0.01" data-required="true">
                  <span class="input-group-text">kg</span>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Presentación <span class="text-danger">*</span></label>
              <input type="text" name="presentacion" class="form-control" data-required="true">
            </div>

          </div>


          {{-- LISTELO / RANDA / PASTINAS --}}
          <div id="editar_campos_decorativo" class="editar-campos-tipo" style="display:none;">

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Modelo</label>
                <input type="text" name="modelo" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Medida</label>
                <input type="text" name="medida" class="form-control">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Piezas por caja <span class="text-danger">*</span></label>
                <input type="number" name="piezas_por_caja" class="form-control" min="1" data-required="true">
              </div>
              <div id="editar_color_decorativo" class="col-md-6 mb-3">
                <label class="form-label">Color</label>
                <input type="text" name="color" class="form-control">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Presentación <span class="text-danger">*</span></label>
              <input type="text" name="presentacion" class="form-control" data-required="true">
            </div>

          </div>


          {{-- ESQUINEROS --}}
          <div id="editar_campos_esquinero" class="editar-campos-tipo" style="display:none;">

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Medida <span class="text-danger">*</span></label>
                <input type="text" name="medida" class="form-control" data-required="true">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Piezas por caja <span class="text-danger">*</span></label>
                <input type="number" name="piezas_por_caja" class="form-control" min="1" data-required="true">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Color <span class="text-danger">*</span></label>
              <input type="text" name="color" class="form-control" data-required="true">
            </div>

          </div>

        </div>


        {{-- ==================== FOOTER ==================== --}}
        <div class="modal-footer ameri-modal-footer">

          <button type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal">
            Cancelar
          </button>

          <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-content-save"></i>
            Actualizar
          </button>

        </div>

      </form>

    </div>

  </div>

</div>