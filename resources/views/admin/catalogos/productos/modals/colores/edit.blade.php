{{-- resources/views/admin/catalogos/productos/modals/colores/edit.blade.php --}}
<div class="modal fade" id="modalColorEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #0A2647 0%, #1E3A5F 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(59, 130, 246, 0.15);">
                        <i class="mdi mdi-palette" style="font-size: 24px; color: #3B82F6;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white mb-0">Editar Color</h5>
                        <p class="text-white-50 small mb-0">Modifica los datos del color</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="form_edit_color" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_color_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre del Color <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_color" id="edit_color_nombre" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Código de Color</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="color" name="codigo_color" id="edit_color_codigo" class="form-control rounded-3" style="width: 60px; height: 45px;">
                            <input type="text" class="form-control rounded-3" placeholder="#RRGGBB" value="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Imagen Actual</label>
                        <div id="edit_color_imagen_preview" class="mb-2"></div>
                        <input type="file" name="imagen" class="form-control rounded-3" accept="image/*">
                        <small class="text-muted">Dejar vacío para mantener la imagen actual</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Precio Adicional</label>
                            <input type="number" step="0.01" name="precio_adicional" id="edit_color_precio" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Stock</label>
                            <input type="number" name="stock" id="edit_color_stock" class="form-control rounded-3">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Disponible</label>
                        <select name="disponible" id="edit_color_disponible" class="form-select rounded-3">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn px-4 rounded-3" style="background: #F3F4F6; color: #6B7280;" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn px-4 rounded-3" style="background: #3B82F6; color: white;">Actualizar Color</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Previsualización de imagen en edición
    $('input[name="imagen"]').on('change', function() {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#edit_color_imagen_preview').html(`<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 100px;">`);
            }
            reader.readAsDataURL(file);
        }
    });
</script>