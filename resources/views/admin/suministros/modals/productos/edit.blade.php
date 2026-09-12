<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <form id="formEditarProducto" action="" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">
            <i class="mdi mdi-pencil-outline me-2"></i> Editar producto
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Nombre *</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Modelo</label>
              <input type="text" name="modelo" class="form-control">
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Medida *</label>
              <input type="text" name="medida" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Color</label>
              <input type="text" name="color" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Acabado</label>
              <input type="text" name="acabado" class="form-control">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Presentación</label>
            <input type="text" name="presentacion" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-content-save"></i> Actualizar
          </button>
        </div>
      </form>

    </div>
  </div>
</div>