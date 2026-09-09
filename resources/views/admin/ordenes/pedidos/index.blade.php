{{-- resources/views/admin/ordenes/pedidos/index.blade.php --}}
@extends('layouts.plantilla_maestra')

@section('title', 'Gestión de Pedidos')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .estado-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .estado-pendiente { background: #F59E0B; color: white; }
    .estado-listo { background: #10B981; color: white; }
    .estado-entregado { background: #3B82F6; color: white; }
    .table tbody tr { cursor: pointer; transition: all 0.2s ease; }
    .table tbody tr:hover { background: var(--gray-light); }
    .btn-icon { padding: 0.25rem 0.5rem; margin: 0 2px; border-radius: 8px; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1">
                            <i class="mdi mdi-package-variant me-2" style="color: #10B981;"></i>
                            Gestión de Pedidos
                        </h4>
                        <p class="text-muted mb-0">Administra todos los pedidos realizados por los clientes</p>
                    </div>
                    <div>
                        <button class="btn" style="background: #10B981; color: white;" id="btn-exportar">
                            <i class="mdi mdi-download"></i> Exportar
                        </button>
                    </div>
                </div>

                <hr class="my-3">

                <div class="table-responsive">
                    <table class="table table-hover" id="pedidos-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th style="width: 100px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $pedido)
                            <tr data-pedido-id="{{ $pedido->id }}" style="cursor: pointer;">
                                <td>{{ $pedido->id }}</td>
                                <td><span class="fw-semibold">{{ $pedido->codigo }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="mdi mdi-account-circle" style="font-size: 20px; color: #10B981;"></i>
                                        <div>
                                            <span class="fw-semibold">{{ $pedido->user->name }}</span><br>
                                            <small class="text-muted">{{ $pedido->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold" style="color: #10B981;">Bs {{ number_format($pedido->total, 2, ',', '.') }}</td>
                                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <select class="form-select form-select-sm estado-select" style="width: 130px;" data-id="{{ $pedido->id }}" data-estado="{{ $pedido->estado }}">
                                        <option value="pendiente" {{ $pedido->estado == 'pendiente' ? 'selected' : '' }} style="color: #F59E0B;">⚠ Pendiente</option>
                                        <option value="listo" {{ $pedido->estado == 'listo' ? 'selected' : '' }} style="color: #10B981;">✓ Listo</option>
                                        <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }} style="color: #3B82F6;">✈ Entregado</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-ver-pedido" style="color: #3B82F6; background: rgba(59, 130, 246, 0.1);" data-id="{{ $pedido->id }}">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-delete-pedido" style="color: #EF4444; background: rgba(239, 68, 68, 0.1);" data-id="{{ $pedido->id }}" data-codigo="{{ $pedido->codigo }}">
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
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // DataTable en español
        $('#pedidos-table').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            pageLength: 10,
            order: [[0, 'desc']],
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [6] }
            ]
        });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 4000
        };

        // Ver detalle del pedido
        $('.btn-ver-pedido').on('click', function(e) {
            e.stopPropagation();
            let id = $(this).data('id');
            window.location.href = `/admin/pedidos/${id}`;
        });

        // Hacer clic en la fila para ver detalle
        $('.table tbody tr').on('click', function(e) {
            if (!$(e.target).closest('.btn-ver-pedido, .btn-delete-pedido, .estado-select').length) {
                let id = $(this).find('.btn-ver-pedido').data('id');
                if (id) window.location.href = `/admin/pedidos/${id}`;
            }
        });

        // Cambiar estado del pedido
        $('.estado-select').on('change', function(e) {
            e.stopPropagation();
            let id = $(this).data('id');
            let estado = $(this).val();
            let select = $(this);
            
            $.ajax({
                url: `/admin/pedidos/${id}/estado`,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    estado: estado
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        // Actualizar color del select
                        select.css('border-color', 
                            estado === 'pendiente' ? '#F59E0B' : 
                            estado === 'listo' ? '#10B981' : '#3B82F6'
                        );
                    }
                },
                error: function() {
                    toastr.error('Error al actualizar el estado');
                    select.val(select.data('estado'));
                }
            });
        });

        // Eliminar pedido
        $('.btn-delete-pedido').on('click', function(e) {
            e.stopPropagation();
            let id = $(this).data('id');
            let codigo = $(this).data('codigo');
            
            Swal.fire({
                title: '¿Eliminar pedido?',
                html: `Estás por eliminar el pedido <strong>${codigo}</strong><br>Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/pedidos/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => location.reload(), 1200);
                            }
                        },
                        error: function() {
                            toastr.error('Error al eliminar el pedido');
                        }
                    });
                }
            });
        });

        // Exportar
        $('#btn-exportar').on('click', function() {
            toastr.info('Funcionalidad de exportación en desarrollo');
        });
    });
</script>
@endsection