{{-- resources/views/admin/catalogos/productos/modals/colores/create.blade.php --}}
<div class="modal fade" id="modalColorCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #0A2647 0%, #1E3A5F 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(16, 185, 129, 0.15);">
                        <i class="mdi mdi-palette" style="font-size: 24px; color: #10B981;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white mb-0">Agregar Color</h5>
                        <p class="text-white-50 small mb-0">Producto: <span id="create_color_producto_nombre" class="fw-semibold"></span></p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="form_create_color" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <!-- Información del producto -->
                    <div class="mb-3 p-3 rounded-3" style="background: #F0FDF4; border: 1px solid #D1FAE5;">
                        <div class="row">
                            <div class="col-6"><small class="text-muted">Precio Base</small><p class="fw-bold mb-0" style="color: #10B981;" id="info_precio_base">Bs 0.00</p></div>
                            <div class="col-6"><small class="text-muted">Stock Total</small><p class="fw-bold mb-0" id="info_stock_total">0 unidades</p></div>
                            <div class="col-12 mt-2"><small class="text-muted">Stock Disponible para Colores</small><p class="fw-bold mb-0 text-danger" id="info_stock_restante">0 unidades</p></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Nombre del Color <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_color" class="form-control rounded-3" style="border-color: #E2E8F0; padding: 0.75rem;" required placeholder="Ej: Rojo, Azul, Verde">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Selector de Color</label>
                        <div class="d-flex gap-3 align-items-center">
                            <div style="width: 50px; height: 50px; border-radius: 12px; overflow: hidden; border: 2px solid #E2E8F0;">
                                <input type="color" name="codigo_color" id="codigo_picker" style="width: 100%; height: 100%; border: none; cursor: pointer;">
                            </div>
                            <input type="text" id="codigo_text" class="form-control rounded-3" style="border-color: #E2E8F0; padding: 0.75rem;" placeholder="#RRGGBB">
                        </div>
                        <small class="text-muted">Código hexadecimal para mostrar el color en la tienda</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Imagen del Color</label>
                        <div class="border-2 rounded-3 p-3 text-center" style="border: 2px dashed #E2E8F0; background: #F8FAFC; cursor: pointer;" id="color_imagen_upload">
                            <i class="mdi mdi-cloud-upload" style="font-size: 32px; color: #10B981;"></i>
                            <p class="mb-0 text-muted small mt-1">Haz clic para subir una imagen</p>
                            <input type="file" name="imagen" id="color_imagen" class="d-none" accept="image/*">
                        </div>
                        <div id="create_color_imagen_preview" class="mt-2 text-center"></div>
                    </div>

                    <!-- Toggle Precio -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Configuración de Precio</label>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                            <div>
                                <span class="fw-semibold">Usar precio base</span>
                                <p class="text-muted small mb-0">El color tendrá el mismo precio que el producto base</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="toggle_precio_adicional" style="width: 50px; height: 25px; cursor: pointer;">
                                <label class="form-check-label text-muted small" id="toggle_precio_label">Desactivado</label>
                            </div>
                        </div>
                    </div>

                    <!-- Precio Adicional (oculto por defecto) -->
                    <div class="mb-3" id="campo_precio_adicional" style="display: none;">
                        <label class="form-label fw-semibold text-dark">Precio Adicional</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: #10B981; color: white;">+ Bs</span>
                            <input type="number" step="0.01" name="precio_adicional" id="precio_adicional_input" class="form-control rounded-3" style="border-color: #E2E8F0; padding: 0.75rem;" placeholder="0.00" min="0" value="0">
                        </div>
                        <small class="text-muted">Este valor se sumará al precio base del producto</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-dark">Stock <span class="text-danger">*</span></label>
                            <input type="number" name="stock" id="stock_color" class="form-control rounded-3" style="border-color: #E2E8F0; padding: 0.75rem;" required min="0" placeholder="0">
                            <small class="text-muted">Stock máximo: <span id="max_stock">0</span> unidades</small>
                            <div id="stock_error" class="text-danger small mt-1" style="display: none;"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-dark">Disponible</label>
                            <select name="disponible" class="form-select rounded-3" style="border-color: #E2E8F0; padding: 0.75rem;">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn px-4 py-2 rounded-3" style="background: #F1F5F9; color: #475569; font-weight: 500;" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn px-4 py-2 rounded-3" style="background: #10B981; color: white; font-weight: 500;" id="btn_submit_color">Agregar Color</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Sincronizar selector de color
    $('#codigo_text').on('input', function() { $('#codigo_picker').val($(this).val()); });
    $('#codigo_picker').on('change', function() { $('#codigo_text').val($(this).val()); });
    
    // Upload imagen
    $('#color_imagen_upload').click(function() { $('#color_imagen').click(); });
    $('#color_imagen').change(function() {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) { $('#create_color_imagen_preview').html(`<img src="${e.target.result}" class="img-fluid rounded-3" style="max-height: 100px; border: 2px solid #10B981;">`); }
            reader.readAsDataURL(file);
        }
    });
    
    // Toggle precio
    let toggle = document.getElementById('toggle_precio_adicional');
    let campoPrecio = document.getElementById('campo_precio_adicional');
    let toggleLabel = document.getElementById('toggle_precio_label');
    
    toggle.checked = false;
    campoPrecio.style.display = 'none';
    toggleLabel.textContent = 'Desactivado';
    
    toggle.addEventListener('change', function() {
        if (this.checked) {
            campoPrecio.style.display = 'block';
            toggleLabel.textContent = 'Activado';
        } else {
            campoPrecio.style.display = 'none';
            toggleLabel.textContent = 'Desactivado';
            document.getElementById('precio_adicional_input').value = 0;
        }
    });
    
    // Validación stock
    let maxStock = 0;
    function validarStock() {
        let stock = parseInt(document.getElementById('stock_color').value) || 0;
        let errorDiv = document.getElementById('stock_error');
        let submitBtn = document.getElementById('btn_submit_color');
        if (stock > maxStock) {
            errorDiv.style.display = 'block';
            errorDiv.innerHTML = `El stock no puede exceder ${maxStock} unidades`;
            submitBtn.disabled = true;
            return false;
        } else {
            errorDiv.style.display = 'none';
            submitBtn.disabled = false;
            return true;
        }
    }
    document.getElementById('stock_color').addEventListener('input', validarStock);
    window.updateMaxStock = function(valor) { maxStock = valor; document.getElementById('max_stock').innerText = valor; validarStock(); };
</script>