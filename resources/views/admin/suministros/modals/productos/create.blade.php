<div class="modal fade" id="modalCrearProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <form action="{{ route('admin.suministros.productos.store') }}" method="POST" novalidate>
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">
            <i class="mdi mdi-plus-circle-outline me-2"></i> Nuevo producto
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          @if ($errors->any() && session('open_modal') === 'crear-producto')
            <div class="alert alert-danger mb-3">
              <i class="mdi mdi-alert-circle-outline me-1"></i>
              Por favor corrige los errores marcados abajo.
            </div>
          @endif

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Nombre <span class="text-danger">*</span></label>
              <input type="text"
                     name="nombre"
                     class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('nombre')) is-invalid @endif"
                     value="{{ session('open_modal') === 'crear-producto' ? old('nombre') : '' }}">
              @if (session('open_modal') === 'crear-producto')
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
              @endif
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Modelo</label>
              <input type="text"
                     name="modelo"
                     class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('modelo')) is-invalid @endif"
                     value="{{ session('open_modal') === 'crear-producto' ? old('modelo') : '' }}">
              @if (session('open_modal') === 'crear-producto')
                @error('modelo') <div class="invalid-feedback">{{ $message }}</div> @enderror
              @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Medida <span class="text-danger">*</span></label>
              <input type="text"
                     name="medida"
                     class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('medida')) is-invalid @endif"
                     value="{{ session('open_modal') === 'crear-producto' ? old('medida') : '' }}"
                     placeholder="Ej: 60x60 cm">
              @if (session('open_modal') === 'crear-producto')
                @error('medida') <div class="invalid-feedback">{{ $message }}</div> @enderror
              @endif
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Color</label>
              <input type="text"
                     name="color"
                     class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('color')) is-invalid @endif"
                     value="{{ session('open_modal') === 'crear-producto' ? old('color') : '' }}"
                     placeholder="Ej: Blanco">
              @if (session('open_modal') === 'crear-producto')
                @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
              @endif
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Acabado</label>
              <input type="text"
                     name="acabado"
                     class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('acabado')) is-invalid @endif"
                     value="{{ session('open_modal') === 'crear-producto' ? old('acabado') : '' }}"
                     placeholder="Ej: Mate, Brillante">
              @if (session('open_modal') === 'crear-producto')
                @error('acabado') <div class="invalid-feedback">{{ $message }}</div> @enderror
              @endif
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Presentación</label>
            <input type="text"
                   name="presentacion"
                   class="form-control @if(session('open_modal') === 'crear-producto' && $errors->has('presentacion')) is-invalid @endif"
                   value="{{ session('open_modal') === 'crear-producto' ? old('presentacion') : '' }}"
                   placeholder="Ej: Caja x 4 piezas, Bolsa 25kg">
            @if (session('open_modal') === 'crear-producto')
              @error('presentacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @endif
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-content-save"></i> Guardar
          </button>
        </div>
      </form>

    </div>
  </div>
</div>