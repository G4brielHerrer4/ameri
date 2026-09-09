{{-- resources/views/admin/catalogos/productos/modals/edit.blade.php --}}
<div class="modal fade" id="modalProductoEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #0A2647 0%, #1E3A5F 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(59, 130, 246, 0.15);">
                        <i class="mdi mdi-package-variant" style="font-size: 24px; color: #3B82F6;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white mb-0">Editar Producto</h5>
                        <p class="text-white-50 small mb-0">Modifica los datos del producto</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="form_edit_producto" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_producto_id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Categoría <span class="text-danger">*</span></label>
                            <select name="categoria_id" id="edit_producto_categoria_id" class="form-select rounded-3" required>
                                <option value="">Seleccione...</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="edit_producto_nombre" class="form-control rounded-3" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descripción</label>
                        <textarea name="descripcion" id="edit_producto_descripcion" class="form-control rounded-3" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Medida</label>
                            <input type="text" name="medida" id="edit_producto_medida" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Material</label>
                            <input type="text" name="material" id="edit_producto_material" class="form-control rounded-3">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Precio Base</label>
                            <input type="number" step="0.01" name="precio_base" id="edit_producto_precio_base" class="form-control rounded-3">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Stock</label>
                            <input type="number" name="stock" id="edit_producto_stock" class="form-control rounded-3">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Imagen Actual</label>
                            <div id="edit_producto_imagen_preview" class="mb-2"></div>
                            <input type="file" name="imagen_principal" class="form-control rounded-3" accept="image/*">
                            <small class="text-muted">Dejar vacío para mantener la imagen actual</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="estado" id="edit_producto_estado" class="form-select rounded-3">
                                <option value="1">Disponible</option>
                                <option value="0">Oculto</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Destacado</label>
                            <select name="destacado" id="edit_producto_destacado" class="form-select rounded-3">
                                <option value="0">No</option>
                                <option value="1">Sí</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn px-4 rounded-3" style="background: #F3F4F6; color: #6B7280;" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn px-4 rounded-3" style="background: #3B82F6; color: white;">Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#form_edit_producto').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_producto_id').val();
        let formData = new FormData(this);
        
        $.ajax({
            url: `{{ url('admin/productos') }}/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#modalProductoEdit').modal('hide');
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