{{-- resources/views/admin/usuarios/administracion/modals/create.blade.php --}}

<div class="modal fade" id="modalCreateUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #0A2647 0%, #1F2937 100%);">
        <div class="d-flex align-items-center gap-3">
          <div class="rounded-circle d-flex align-items-center justify-content-center" 
               style="width: 48px; height: 48px; background-color: rgba(16, 185, 129, 0.15);">
            <i class="mdi mdi-account-plus" style="font-size: 24px; color: #10B981;"></i>
          </div>
          <div>
            <h5 class="modal-title text-white mb-0">Crear Usuario</h5>
            <p class="text-white-50 small mb-0">Ingresa los datos del nuevo usuario</p>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <form id="formCreateUser" action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="color: #1F2937;">
              <i class="mdi mdi-account me-1" style="color: #10B981;"></i> Nombre Completo
            </label>
            <input type="text" class="form-control rounded-3" id="name" name="name" 
                   placeholder="Ej: Juan Pérez" style="border-color: #e5e7eb;" required>
            <div class="invalid-feedback"></div>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-semibold" style="color: #1F2937;">
              <i class="mdi mdi-email me-1" style="color: #10B981;"></i> Correo Electrónico
            </label>
            <input type="email" class="form-control rounded-3" id="email" name="email" 
                   placeholder="ejemplo@empresa.com" style="border-color: #e5e7eb;" required>
            <div class="invalid-feedback"></div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold" style="color: #1F2937;">
                <i class="mdi mdi-lock me-1" style="color: #10B981;"></i> Contraseña
              </label>
              <input type="password" class="form-control rounded-3" id="password" name="password" 
                     placeholder="********" style="border-color: #e5e7eb;" required>
              <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold" style="color: #1F2937;">
                <i class="mdi mdi-lock-check me-1" style="color: #10B981;"></i> Confirmar
              </label>
              <input type="password" class="form-control rounded-3" id="password_confirmation" name="password_confirmation" 
                     placeholder="********" style="border-color: #e5e7eb;" required>
              <div class="invalid-feedback"></div>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-semibold" style="color: #1F2937;">
              <i class="mdi mdi-shield-account me-1" style="color: #10B981;"></i> Rol
            </label>
            <select class="form-select rounded-3" id="role_id" name="role_id" style="border-color: #e5e7eb;" required>
              <option value="">Selecciona un rol</option>
              @foreach($roles as $role)
                <option value="{{ $role->id }}" style="color: {{ $role->slug == 'admin' ? '#10B981' : '#3B82F6' }}">
                  {{ $role->name }}
                </option>
              @endforeach
            </select>
            <div class="invalid-feedback"></div>
          </div>
        </div>
        
        <div class="modal-footer border-0 p-4 pt-0">
          <button type="button" class="btn px-4 rounded-3" data-bs-dismiss="modal" 
                  style="background-color: #F3F4F6; color: #6B7280;">Cancelar</button>
          <button type="submit" class="btn px-4 rounded-3" 
                  style="background-color: #10B981; border-color: #10B981; color: white;">
            <i class="mdi mdi-content-save me-2"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>