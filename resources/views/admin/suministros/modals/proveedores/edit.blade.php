{{-- resources/views/admin/suministros/modals/proveedores/edit.blade.php --}}

<div class="modal fade" id="modalEditarProveedor" tabindex="-1" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content ameri-modal">

      <form id="formEditarProveedor" action="" method="POST" novalidate>

        @csrf
        @method('PUT')

        {{-- ==================== HEADER ==================== --}}
        <div class="modal-header ameri-modal-header">

          <div class="d-flex align-items-center gap-3">

            <div class="ameri-modal-icon">
              <i class="mdi mdi-pencil-outline"></i>
            </div>

            <div>
              <h5 class="modal-title">Editar proveedor</h5>
              <p class="ameri-modal-sub">Actualiza los datos del proveedor</p>
            </div>

          </div>

          <button type="button"
                  class="btn-close btn-close-white"
                  data-bs-dismiss="modal"
                  aria-label="Cerrar"></button>

        </div>


        {{-- ==================== BODY ==================== --}}
        <div class="modal-body ameri-modal-body">

          @if ($errors->any() && session('open_modal') === 'editar-proveedor')

            <div class="alert alert-danger mb-3">
              <i class="mdi mdi-alert-circle-outline me-1"></i>
              Por favor corrige los errores marcados abajo.
            </div>

          @endif


          {{-- NOMBRE --}}
          <div class="mb-3">

            <label class="form-label">
              Nombre <span class="text-danger">*</span>
            </label>

            <input type="text"
                   name="nombre"
                   class="form-control @if(session('open_modal') === 'editar-proveedor' && $errors->has('nombre')) is-invalid @endif"
                   value="{{ session('open_modal') === 'editar-proveedor' ? old('nombre') : '' }}"
                   maxlength="150">

            @if (session('open_modal') === 'editar-proveedor')
              @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            @endif

          </div>


          {{-- NIT / TELÉFONO --}}
          <div class="row">

            <div class="col-md-6 mb-3">

              <label class="form-label">
                NIT <span class="text-danger">*</span>
              </label>

              <input type="text"
                     name="nit"
                     class="form-control @if(session('open_modal') === 'editar-proveedor' && $errors->has('nit')) is-invalid @endif"
                     value="{{ session('open_modal') === 'editar-proveedor' ? old('nit') : '' }}"
                     maxlength="20">

              @if (session('open_modal') === 'editar-proveedor')
                @error('nit')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              @endif

            </div>


            <div class="col-md-6 mb-3">

              <label class="form-label">
                Teléfono <span class="text-danger">*</span>
              </label>

              <input type="text"
                     name="telefono"
                     class="form-control @if(session('open_modal') === 'editar-proveedor' && $errors->has('telefono')) is-invalid @endif"
                     value="{{ session('open_modal') === 'editar-proveedor' ? old('telefono') : '' }}"
                     maxlength="20">

              @if (session('open_modal') === 'editar-proveedor')
                @error('telefono')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              @endif

            </div>

          </div>


          {{-- CIUDAD --}}
          <div class="mb-3">

            <label class="form-label">
              Ciudad <span class="text-danger">*</span>
            </label>

            <input type="text"
                   name="ciudad"
                   class="form-control @if(session('open_modal') === 'editar-proveedor' && $errors->has('ciudad')) is-invalid @endif"
                   value="{{ session('open_modal') === 'editar-proveedor' ? old('ciudad') : '' }}"
                   maxlength="100">

            @if (session('open_modal') === 'editar-proveedor')
              @error('ciudad')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            @endif

          </div>


          {{-- DIRECCIÓN --}}
          <div class="mb-3">

            <label class="form-label">
              Dirección <span class="text-danger">*</span>
            </label>

            <input type="text"
                   name="direccion"
                   class="form-control @if(session('open_modal') === 'editar-proveedor' && $errors->has('direccion')) is-invalid @endif"
                   value="{{ session('open_modal') === 'editar-proveedor' ? old('direccion') : '' }}"
                   maxlength="255">

            @if (session('open_modal') === 'editar-proveedor')
              @error('direccion')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            @endif

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