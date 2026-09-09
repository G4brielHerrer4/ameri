{{-- resources/views/admin/usuarios/administracion/modals/edit.blade.php --}}

<!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0A2647;">
                <h5 class="modal-title text-white" id="modalEditUserLabel">
                    <i class="mdi mdi-account-edit me-2"></i> Editar Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditUser" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Nueva Contraseña <span class="text-muted">(Opcional)</span></label>
                        <input type="password" class="form-control" id="edit_password" name="password" placeholder="Dejar en blanco para mantener actual">
                        <small class="text-muted">Solo llenar si desea cambiar la contraseña</small>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation" placeholder="Repite la nueva contraseña">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role_id" class="form-label">Rol <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_role_id" name="role_id" required>
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" style="color: {{ $role->slug == 'admin' ? '#10B981' : '#3B82F6' }}">
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn" style="background-color: #10B981; border-color: #10B981; color: white;">
                        <i class="mdi mdi-content-save"></i> Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>