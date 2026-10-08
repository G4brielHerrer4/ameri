{{-- resources/views/admin/suministros/modals/proveedores/create.blade.php --}}

@once
@push('styles')
<style>
  /* =====================================================
     MODALES AMERI (se cargan una sola vez)
     ===================================================== */
  .ameri-modal {
    background: var(--bg-1) !important;
    border: 1px solid var(--line) !important;
    border-radius: var(--r-lg) !important;
    overflow: hidden;
    color: var(--ink) !important;
    box-shadow: 0 40px 80px -40px var(--shadow), 0 0 0 1px var(--line) !important;
  }

  .ameri-modal-header {
    background: linear-gradient(135deg,
      color-mix(in srgb, var(--cyan) 18%, var(--bg-1)) 0%,
      var(--bg-2) 100%) !important;
    border-bottom: 1px solid var(--line) !important;
    padding: 1.25rem 1.5rem !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
  }

  .ameri-modal-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--r-md);
    background: color-mix(in srgb, var(--cyan) 15%, transparent);
    border: 1px solid var(--line-strong);
    color: var(--cyan);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 0 16px -4px var(--glow);
  }

  .ameri-modal-header .modal-title {
    color: var(--ink) !important;
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
    line-height: 1.2;
  }

  .ameri-modal-sub {
    color: var(--ink-muted) !important;
    font-size: 0.8rem;
    margin: 0.2rem 0 0;
    line-height: 1.2;
  }

  .ameri-modal-header .btn-close {
    opacity: 0.7;
    transition: opacity .2s ease, transform .2s ease;
  }
  .ameri-modal-header .btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
  }

  .ameri-modal-body {
    padding: 1.5rem !important;
    background: var(--bg-1) !important;
    color: var(--ink) !important;
    max-height: 70vh;
    overflow-y: auto;
  }

  .ameri-modal-footer {
    background: var(--bg-2) !important;
    border-top: 1px solid var(--line) !important;
    padding: 1rem 1.5rem !important;
    display: flex;
    justify-content: flex-end;
    gap: 0.65rem;
  }

  @media (max-width: 575.98px) {
    .ameri-modal-header,
    .ameri-modal-body,
    .ameri-modal-footer {
      padding: 1rem !important;
    }
    .ameri-modal-icon {
      width: 40px;
      height: 40px;
      font-size: 1.25rem;
    }
    .ameri-modal-header .modal-title { font-size: 1rem; }
    .ameri-modal-sub { font-size: 0.75rem; }
    .ameri-modal-footer {
      flex-direction: column-reverse;
    }
    .ameri-modal-footer .btn { width: 100%; }
  }
</style>
@endpush
@endonce


<div class="modal fade" id="modalCrearProveedor" tabindex="-1" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content ameri-modal">

      <form action="{{ route('admin.suministros.proveedores.store') }}"
            method="POST"
            novalidate>

        @csrf

        {{-- ==================== HEADER ==================== --}}
        <div class="modal-header ameri-modal-header">

          <div class="d-flex align-items-center gap-3">

            <div class="ameri-modal-icon">
              <i class="mdi mdi-truck-plus-outline"></i>
            </div>

            <div>
              <h5 class="modal-title">Nuevo proveedor</h5>
              <p class="ameri-modal-sub">Registra un proveedor para el sistema</p>
            </div>

          </div>

          <button type="button"
                  class="btn-close btn-close-white"
                  data-bs-dismiss="modal"
                  aria-label="Cerrar"></button>

        </div>


        {{-- ==================== BODY ==================== --}}
        <div class="modal-body ameri-modal-body">

          @if ($errors->any() && session('open_modal') === 'crear-proveedor')

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
                   class="form-control @if(session('open_modal') === 'crear-proveedor' && $errors->has('nombre')) is-invalid @endif"
                   value="{{ session('open_modal') === 'crear-proveedor' ? old('nombre') : '' }}"
                   maxlength="150"
                   placeholder="Ej: Cerámicas del Sur SRL">

            @if (session('open_modal') === 'crear-proveedor')
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
                     class="form-control @if(session('open_modal') === 'crear-proveedor' && $errors->has('nit')) is-invalid @endif"
                     value="{{ session('open_modal') === 'crear-proveedor' ? old('nit') : '' }}"
                     maxlength="20"
                     placeholder="Ej: 1234567890">

              @if (session('open_modal') === 'crear-proveedor')
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
                     class="form-control @if(session('open_modal') === 'crear-proveedor' && $errors->has('telefono')) is-invalid @endif"
                     value="{{ session('open_modal') === 'crear-proveedor' ? old('telefono') : '' }}"
                     maxlength="20"
                     placeholder="Ej: 77712345">

              @if (session('open_modal') === 'crear-proveedor')
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
                   class="form-control @if(session('open_modal') === 'crear-proveedor' && $errors->has('ciudad')) is-invalid @endif"
                   value="{{ session('open_modal') === 'crear-proveedor' ? old('ciudad') : '' }}"
                   maxlength="100"
                   placeholder="Ej: Santa Cruz">

            @if (session('open_modal') === 'crear-proveedor')
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
                   class="form-control @if(session('open_modal') === 'crear-proveedor' && $errors->has('direccion')) is-invalid @endif"
                   value="{{ session('open_modal') === 'crear-proveedor' ? old('direccion') : '' }}"
                   maxlength="255"
                   placeholder="Ej: Av. Cristo Redentor #1234">

            @if (session('open_modal') === 'crear-proveedor')
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
            Guardar
          </button>

        </div>

      </form>

    </div>

  </div>

</div>