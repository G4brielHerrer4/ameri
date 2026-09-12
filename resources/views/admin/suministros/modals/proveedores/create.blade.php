<div class="modal fade" id="modalCrearProveedor" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form action="{{ route('admin.suministros.proveedores.store') }}" method="POST" novalidate>
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">
            <i class="mdi mdi-plus-circle-outline me-2"></i> Nuevo proveedor
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          {{-- Alerta general cuando hay errores --}}
          @if ($errors->any() && session('open_modal') === 'crear-proveedor')
            <div class="alert alert-danger mb-3">
              <i class="mdi mdi-alert-circle-outline me-1"></i>
              Por favor corrige los errores marcados abajo.
            </div>
          @endif

          <div class="mb-3">
            <label class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text"
                   name="nombre"
                   class="form-control @if(session('open_modal') === 'crear-proveedor' && $errors->has('nombre')) is-invalid @endif"
                   value="{{ session('open_modal') === 'crear-proveedor' ? old('nombre') : '' }}"
                   maxlength="150">
            @if (session('open_modal') === 'crear-proveedor')
              @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            @endif
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">NIT <span class="text-danger">*</span></label>
              <input type="text"
                     name="nit"
                     class="form-control @if(session('open_modal') === 'crear-proveedor' && $errors->has('nit')) is-invalid @endif"
                     value="{{ session('open_modal') === 'crear-proveedor' ? old('nit') : '' }}"
                     maxlength="20">
              @if (session('open_modal') === 'crear-proveedor')
                @error('nit')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              @endif
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Teléfono <span class="text-danger">*</span></label>
              <input type="text"
                     name="telefono"
                     class="form-control @if(session('open_modal') === 'crear-proveedor' && $errors->has('telefono')) is-invalid @endif"
                     value="{{ session('open_modal') === 'crear-proveedor' ? old('telefono') : '' }}"
                     maxlength="20">
              @if (session('open_modal') === 'crear-proveedor')
                @error('telefono')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              @endif
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Ciudad <span class="text-danger">*</span></label>
            <input type="text"
                   name="ciudad"
                   class="form-control @if(session('open_modal') === 'crear-proveedor' && $errors->has('ciudad')) is-invalid @endif"
                   value="{{ session('open_modal') === 'crear-proveedor' ? old('ciudad') : '' }}"
                   maxlength="100">
            @if (session('open_modal') === 'crear-proveedor')
              @error('ciudad')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            @endif
          </div>

          <div class="mb-3">
            <label class="form-label">Dirección <span class="text-danger">*</span></label>
            <input type="text"
                   name="direccion"
                   class="form-control @if(session('open_modal') === 'crear-proveedor' && $errors->has('direccion')) is-invalid @endif"
                   value="{{ session('open_modal') === 'crear-proveedor' ? old('direccion') : '' }}"
                   maxlength="255">
            @if (session('open_modal') === 'crear-proveedor')
              @error('direccion')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
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