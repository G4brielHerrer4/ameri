{{-- resources/views/admin/catalogos/index.blade.php --}}
@extends('layouts.plantilla_maestra')

@section('title', 'Catálogo - AMERI')

@section('css')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 16px;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.1) !important;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .badge {
        font-weight: 500;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
    }
    .modal-header {
        background: linear-gradient(135deg, #0A2647 0%, #1E3A5F 100%);
    }
    .dataTables_wrapper {
        padding: 20px 0;
    }
    .dataTables_length select,
    .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding: 6px 12px;
    }
    .dataTables_filter input:focus {
        border-color: #10B981;
        outline: none;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
    }
    .paginate_button {
        border-radius: 8px !important;
        margin: 0 3px !important;
    }
    .paginate_button.current {
        background: #10B981 !important;
        border-color: #10B981 !important;
        color: white !important;
    }
    .table th {
        font-weight: 600;
        color: #1F2937;
        border-bottom: 2px solid #E5E7EB;
    }
    .table td {
        vertical-align: middle;
        color: #374151;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin: 0 2px;
    }
    .categoria-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .categoria-card.active {
        border: 2px solid #10B981;
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.2);
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Header con botones -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h4 class="card-title mb-1">
                            <i class="mdi mdi-view-dashboard me-2" style="color: #10B981;"></i>
                            Catálogo de Productos
                        </h4>
                        <p class="text-muted mb-0">Gestiona categorías, productos y colores</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn" style="background: #10B981; color: white;" data-bs-toggle="modal" data-bs-target="#modalCategoriaCreate">
                            <i class="mdi mdi-folder-plus me-1"></i> Nueva Categoría
                        </button>
                        <button type="button" class="btn" style="background: #0A2647; color: white;" data-bs-toggle="modal" data-bs-target="#modalProductoCreate">
                            <i class="mdi mdi-package-variant-closed me-1"></i> Nuevo Producto
                        </button>
                    </div>
                </div>

                <hr class="my-3">

                <!-- ==================== CATEGORÍAS (3 por fila) ==================== -->
                <h5 class="mt-3 mb-3">
                    <i class="mdi mdi-folder-outline me-2" style="color: #10B981;"></i>
                    Categorías
                    <span class="badge ms-2" style="background: #E5E7EB; color: #1F2937;">{{ $categorias->count() }} total</span>
                </h5>
                <div class="row g-4" id="categorias-container">
                    @foreach($categorias as $categoria)
                    <div class="col-md-6 col-lg-4" data-categoria-id="{{ $categoria->id }}">
                        <div class="card h-100 shadow-sm border-0 categoria-card">
                            <div class="position-relative">
                                <img src="{{ $categoria->imagen_url }}" class="card-img-top" alt="{{ $categoria->nombre }}" style="height: 160px; object-fit: cover; border-radius: 16px 16px 0 0;">
                                <span class="position-absolute top-0 end-0 m-2 badge" style="background: {{ $categoria->estado ? '#10B981' : '#6B7280' }};">
                                    {{ $categoria->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-1">{{ $categoria->nombre }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($categoria->descripcion, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge" style="background: #E5E7EB; color: #1F2937;">
                                        <i class="mdi mdi-package-variant me-1"></i>{{ $categoria->productos_count }} productos
                                    </span>
                                    <div>
                                        <button class="btn btn-sm btn-icon btn-edit-categoria border-0" style="color: #3B82F6; background: rgba(59, 130, 246, 0.1);" title="Editar"
                                            data-id="{{ $categoria->id }}" 
                                            data-nombre="{{ $categoria->nombre }}" 
                                            data-descripcion="{{ $categoria->descripcion }}" 
                                            data-imagen="{{ $categoria->imagen }}" 
                                            data-estado="{{ $categoria->estado }}">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-delete-categoria border-0" style="color: #EF4444; background: rgba(239, 68, 68, 0.1);" title="Eliminar"
                                            data-id="{{ $categoria->id }}" 
                                            data-nombre="{{ $categoria->nombre }}">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- ==================== PRODUCTOS (Tabla DataTable) ==================== -->
                <h5 class="mt-5 mb-3">
                    <i class="mdi mdi-package-variant me-2" style="color: #10B981;"></i>
                    Productos
                    <span class="badge ms-2" style="background: #E5E7EB; color: #1F2937;">{{ $productos->count() }} total</span>
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover" id="productos-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Medida/Material</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Destacado</th>
                                <th>Colores</th>
                                <th style="width: 100px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos as $producto)
                            <tr data-producto-id="{{ $producto->id }}">
                                <td>{{ $producto->id }}</td>
                                <td>
                                    <img src="{{ $producto->imagen_principal_url }}" class="rounded" style="width: 45px; height: 45px; object-fit: cover;">
                                </td>
                                <td class="fw-semibold">{{ Str::limit($producto->nombre, 40) }}</td>
                                <td>
                                    <span class="badge" style="background: #E5E7EB; color: #0A2647;">
                                        {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $producto->medida ?? '-' }}</small><br>
                                    <small class="text-muted">{{ $producto->material ?? '-' }}</small>
                                </td>
                                <td class="fw-bold" style="color: #10B981;">{{ $producto->precio_base_formateado }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $producto->estado ? '#10B981' : '#6B7280' }};">
                                        {{ $producto->estado ? 'Disponible' : 'Oculto' }}
                                    </span>
                                </td>
                                <td>
                                    @if($producto->destacado)
                                        <span class="badge" style="background: #F59E0B;">★ Destacado</span>
                                    @else
                                        <span class="badge" style="background: #9CA3AF;">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-colores-producto border-0" style="color: #8B5CF6; background: rgba(139, 92, 246, 0.1);" 
                                            data-id="{{ $producto->id }}" 
                                            data-nombre="{{ $producto->nombre }}" 
                                            data-colores='@json($producto->colores)'
                                            title="Gestionar colores">
                                        <i class="mdi mdi-palette"></i> {{ $producto->colores->count() }}
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-edit-producto border-0" style="color: #3B82F6; background: rgba(59, 130, 246, 0.1);" title="Editar"
                                        data-id="{{ $producto->id }}"
                                        data-nombre="{{ $producto->nombre }}"
                                        data-descripcion="{{ $producto->descripcion }}"
                                        data-categoria_id="{{ $producto->categoria_id }}"
                                        data-medida="{{ $producto->medida }}"
                                        data-material="{{ $producto->material }}"
                                        data-precio_base="{{ $producto->precio_base }}"
                                        data-stock="{{ $producto->stock }}"
                                        data-estado="{{ $producto->estado }}"
                                        data-destacado="{{ $producto->destacado }}"
                                        data-imagen="{{ $producto->imagen_principal }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-delete-producto border-0" style="color: #EF4444; background: rgba(239, 68, 68, 0.1);" title="Eliminar"
                                        data-id="{{ $producto->id }}" 
                                        data-nombre="{{ $producto->nombre }}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODALES ==================== -->
@include('admin.catalogos.categorias.modals.create')
@include('admin.catalogos.categorias.modals.edit')
@include('admin.catalogos.productos.modals.create')
@include('admin.catalogos.productos.modals.edit')

<!-- Modal Lista de Colores -->
<div class="modal fade" id="modalColoresList" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #0A2647 0%, #1E3A5F 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(139, 92, 246, 0.15);">
                        <i class="mdi mdi-palette" style="font-size: 24px; color: #8B5CF6;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white mb-0">Colores del Producto</h5>
                        <p class="text-white-50 small mb-0">Producto: <span id="colores_producto_nombre"></span></p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" id="btn-add-color" class="btn btn-sm" style="background: #10B981; color: white;">
                        <i class="mdi mdi-plus"></i> Agregar Color
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="colores-table">
                        <thead>
                            <tr>
                                <th>Color</th>
                                <th>Stock</th>
                                <th>Precio +</th>
                                <th>Estado</th>
                                <th style="width: 80px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="colores-table-body">
                            <tr><td colspan="5" class="text-center">Cargando...</td</tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="colores_producto_id">
            </div>
        </div>
    </div>
</div>

@include('admin.catalogos.productos.modals.colores.create')
@include('admin.catalogos.productos.modals.colores.edit')
@endsection

@section('js')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // ==================== TOASTR CONFIGURACIÓN ====================
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // ==================== DATATABLES EN ESPAÑOL ====================
        $('#productos-table').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay productos registrados",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ productos",
                "infoEmpty": "Mostrando 0 a 0 de 0 productos",
                "infoFiltered": "(filtrado de _MAX_ productos totales)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ productos",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron resultados",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": activar para ordenar ascendente",
                    "sortDescending": ": activar para ordenar descendente"
                }
            },
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            order: [[0, 'desc']],
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [1, 8, 9] }
            ]
        });

        // ==================== CSRF TOKEN ====================
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // ==================== FUNCIONES GLOBALES ====================
        function showSuccess(message) {
            toastr.success(message, '¡Éxito!');
        }

        function showError(message) {
            toastr.error(message, '¡Error!');
        }

        function reloadPage() {
            setTimeout(() => {
                location.reload();
            }, 1500);
        }

        // ==================== CATEGORÍAS - CREAR ====================
        $('#form_create_categoria').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('admin.categorias.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#modalCategoriaCreate').modal('hide');
                        showSuccess(response.message);
                        reloadPage();
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        let msg = Object.values(errors).flat().join('\n');
                        showError(msg);
                    } else {
                        showError('Error al crear la categoría');
                    }
                }
            });
        });

        // ==================== CATEGORÍAS - EDITAR ====================
        $('.btn-edit-categoria').on('click', function() {
            let data = $(this).data();
            $('#edit_categoria_id').val(data.id);
            $('#edit_categoria_nombre').val(data.nombre);
            $('#edit_categoria_descripcion').val(data.descripcion);
            $('#edit_categoria_estado').val(data.estado ? 1 : 0);
            
            if (data.imagen) {
                $('#edit_categoria_imagen_preview').html(`<img src="/storage/${data.imagen}" class="img-fluid rounded" style="max-height: 100px;">`);
            } else {
                $('#edit_categoria_imagen_preview').html('<span class="text-muted">Sin imagen</span>');
            }
            $('#modalCategoriaEdit').modal('show');
        });

        $('#form_edit_categoria').on('submit', function(e) {
            e.preventDefault();
            let id = $('#edit_categoria_id').val();
            let formData = new FormData(this);
            formData.append('_method', 'PUT');
            
            $.ajax({
                url: `/admin/categorias/${id}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#modalCategoriaEdit').modal('hide');
                        showSuccess(response.message);
                        reloadPage();
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        let msg = Object.values(errors).flat().join('\n');
                        showError(msg);
                    } else {
                        showError('Error al actualizar la categoría');
                    }
                }
            });
        });

        // ==================== CATEGORÍAS - ELIMINAR ====================
        $('.btn-delete-categoria').on('click', function() {
            let id = $(this).data('id');
            let nombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Eliminar categoría?',
                html: `Estás por eliminar <strong>${nombre}</strong><br>Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/categorias/${id}`,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                showSuccess(response.message);
                                reloadPage();
                            }
                        },
                        error: function() {
                            showError('Error al eliminar la categoría');
                        }
                    });
                }
            });
        });

        // ==================== PRODUCTOS - CREAR ====================
        $('#form_create_producto').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('admin.productos.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#modalProductoCreate').modal('hide');
                        showSuccess(response.message);
                        reloadPage();
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        let msg = Object.values(errors).flat().join('\n');
                        showError(msg);
                    } else {
                        showError('Error al crear el producto');
                    }
                }
            });
        });

        // ==================== PRODUCTOS - EDITAR ====================
        // $('.btn-edit-producto').on('click', function() {
        $(document).on('click', '.btn-edit-producto', function() {
            let data = $(this).data();
            
            $('#edit_producto_id').val(data.id);
            $('#edit_producto_categoria_id').val(data.categoria_id);
            $('#edit_producto_nombre').val(data.nombre);
            $('#edit_producto_descripcion').val(data.descripcion);
            $('#edit_producto_medida').val(data.medida);
            $('#edit_producto_material').val(data.material);
            $('#edit_producto_precio_base').val(data.precio_base);
            $('#edit_producto_stock').val(data.stock);
            $('#edit_producto_estado').val(data.estado ? 1 : 0);
            $('#edit_producto_destacado').val(data.destacado ? 1 : 0);
            
            if (data.imagen) {
                $('#edit_producto_imagen_preview').html(`<img src="/storage/${data.imagen}" class="img-fluid rounded" style="max-height: 100px;">`);
            } else {
                $('#edit_producto_imagen_preview').html('<span class="text-muted">Sin imagen</span>');
            }
            $('#modalProductoEdit').modal('show');
        });

        $('#form_edit_producto').on('submit', function(e) {
            e.preventDefault();
            let id = $('#edit_producto_id').val();
            let formData = new FormData(this);
            formData.append('_method', 'PUT');
            
            $.ajax({
                url: `/admin/productos/${id}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#modalProductoEdit').modal('hide');
                        showSuccess(response.message);
                        reloadPage();
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        let msg = Object.values(errors).flat().join('\n');
                        showError(msg);
                    } else {
                        showError('Error al actualizar el producto');
                    }
                }
            });
        });

        // ==================== PRODUCTOS - ELIMINAR ====================
        $('.btn-delete-producto').on('click', function() {
            let id = $(this).data('id');
            let nombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Eliminar producto?',
                html: `Estás por eliminar <strong>${nombre}</strong><br>Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/productos/${id}`,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                showSuccess(response.message);
                                reloadPage();
                            }
                        },
                        error: function() {
                            showError('Error al eliminar el producto');
                        }
                    });
                }
            });
        });

        // ==================== COLORES ====================
        let currentProductoId = null;
        let currentColorId = null;
        
        // Abrir modal de colores
        $('.btn-colores-producto').on('click', function() {
            currentProductoId = $(this).data('id');
            let nombreProducto = $(this).data('nombre');
            let colores = $(this).data('colores');
            
            $('#colores_producto_nombre').text(nombreProducto);
            $('#colores_producto_id').val(currentProductoId);
            
            let tbody = $('#colores-table-body');
            tbody.empty();
            
            if (colores && colores.length > 0) {
                colores.forEach(color => {
                    tbody.append(`
                        <tr data-color-id="${color.id}">
                            <td>
                                ${color.codigo_color ? `<span class="d-inline-block rounded-circle me-2" style="width: 20px; height: 20px; background-color: ${color.codigo_color}; border: 1px solid #ddd;"></span>` : ''}
                                ${color.nombre_color}
                            </td>
                            <td>${color.stock || 0}</td>
                            <td>$${parseFloat(color.precio_adicional || 0).toFixed(2)}</span></td>
                            <td>
                                <span class="badge" style="background: ${color.disponible ? '#10B981' : '#6B7280'}">${color.disponible ? 'Disponible' : 'No disponible'}</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-edit-color border-0" style="color: #3B82F6; background: rgba(59, 130, 246, 0.1);" 
                                    data-id="${color.id}" 
                                    data-nombre="${color.nombre_color}" 
                                    data-codigo="${color.codigo_color || ''}" 
                                    data-precio="${color.precio_adicional || 0}" 
                                    data-stock="${color.stock || 0}" 
                                    data-disponible="${color.disponible ? 1 : 0}" 
                                    data-imagen="${color.imagen || ''}">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-delete-color border-0" style="color: #EF4444; background: rgba(239, 68, 68, 0.1);" 
                                    data-id="${color.id}" 
                                    data-nombre="${color.nombre_color}">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                tbody.append('<tr><td colspan="5" class="text-center">No hay colores registrados para este producto</td></tr>');
            }
            
            $('#modalColoresList').modal('show');
        });
        
        // Abrir modal crear color
        $('#btn-add-color').on('click', function() {
            $('#form_create_color')[0].reset();
            $('#create_color_imagen_preview').html('');
            $('#create_color_producto_nombre').text($('#colores_producto_nombre').text());
            $('#modalColorCreate').modal('show');
        });
        
        // Crear color
        $('#form_create_color').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            $.ajax({
                url: `/admin/productos/${currentProductoId}/colores`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#modalColorCreate').modal('hide');
                        showSuccess(response.message);
                        reloadPage();
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        let msg = Object.values(errors).flat().join('\n');
                        showError(msg);
                    } else {
                        showError('Error al crear el color');
                    }
                }
            });
        });
        
        // Editar color
        $(document).on('click', '.btn-edit-color', function() {
            currentColorId = $(this).data('id');
            $('#edit_color_id').val(currentColorId);
            $('#edit_color_nombre').val($(this).data('nombre'));
            $('#edit_color_codigo').val($(this).data('codigo'));
            $('#edit_color_precio').val($(this).data('precio'));
            $('#edit_color_stock').val($(this).data('stock'));
            $('#edit_color_disponible').val($(this).data('disponible'));
            
            let imagen = $(this).data('imagen');
            if (imagen) {
                $('#edit_color_imagen_preview').html(`<img src="/storage/${imagen}" class="img-fluid rounded" style="max-height: 100px;">`);
            } else {
                $('#edit_color_imagen_preview').html('<span class="text-muted">Sin imagen</span>');
            }
            
            $('#modalColorEdit').modal('show');
        });
        
        // Actualizar color
        $('#form_edit_color').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('_method', 'PUT');
            
            $.ajax({
                url: `/admin/colores/${currentColorId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#modalColorEdit').modal('hide');
                        showSuccess(response.message);
                        reloadPage();
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        let msg = Object.values(errors).flat().join('\n');
                        showError(msg);
                    } else {
                        showError('Error al actualizar el color');
                    }
                }
            });
        });
        
        // Eliminar color
        $(document).on('click', '.btn-delete-color', function() {
            let id = $(this).data('id');
            let nombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Eliminar color?',
                html: `Estás por eliminar <strong>${nombre}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/colores/${id}`,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                showSuccess(response.message);
                                reloadPage();
                            }
                        },
                        error: function() {
                            showError('Error al eliminar el color');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection