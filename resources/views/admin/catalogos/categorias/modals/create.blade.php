{{-- resources/views/admin/catalogos/categoria/modals/create.blade.php --}}
<div class="modal fade" id="modalCategoriaCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(16, 185, 129, 0.15);">
                        <i class="mdi mdi-folder-plus" style="font-size: 24px; color: #10B981;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white mb-0">Nueva Categoría</h5>
                        <p class="text-white-50 small mb-0">Crea una nueva categoría para productos</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="form_create_categoria" action="{{ url('admin/categorias') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descripción</label>
                        <textarea name="descripcion" class="form-control rounded-3" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Imagen</label>
                        <input type="file" name="imagen" class="form-control rounded-3" accept="image/*">
                        <small class="text-muted">Formatos: JPG, PNG, GIF (Max 2MB)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Estado</label>
                        <select name="estado" class="form-select rounded-3">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn px-4 rounded-3" style="background: #F3F4F6; color: #6B7280;" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn px-4 rounded-3" style="background: #10B981; color: white;">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#form_create_categoria').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#modalCategoriaCreate').modal('hide');
                    showToast(response.message);
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                if (errors) {
                    let msg = Object.values(errors).flat().join('\n');
                    showToast(msg, 'error');
                }
            }
        });
    });
</script>