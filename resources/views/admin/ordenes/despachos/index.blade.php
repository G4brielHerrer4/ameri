{{-- resources/views/admin/ordenes/despachos/index.blade.php --}}
@extends('layouts.plantilla_maestra')

@section('title', 'Gestión de Despachos')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .estado-listo { background: #10B981; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; }
    .estado-entregado { background: #3B82F6; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; }
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
                            <i class="mdi mdi-truck-delivery me-2" style="color: #10B981;"></i>
                            Gestión de Despachos
                        </h4>
                        <p class="text-muted mb-0">Pedidos listos para entregar y ya entregados</p>
                    </div>
                </div>

                <hr class="my-3">

                <div class="table-responsive">
                    <table class="table table-hover" id="despachos-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th style="width: 100px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $pedido)
                            <tr>
                                <td>{{ $pedido->id }}</td>
                                <td><span class="fw-semibold">{{ $pedido->codigo }}</span></td>
                                <td>{{ $pedido->nombre_completo }}</td>
                                <td>{{ $pedido->telefono }}</td>
                                <td>{{ $pedido->direccion }} ({{ $pedido->departamento }})</td>
                                <td class="fw-bold" style="color: #10B981;">Bs {{ number_format($pedido->total, 2, ',', '.') }}</td>
                                <td>
                                    @if($pedido->estado == 'listo')
                                        <span class="estado-listo">✓ Listo para entregar</span>
                                    @else
                                        <span class="estado-entregado">✈ Entregado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pedido->estado == 'listo')
                                        <button class="btn btn-sm btn-success btn-entregar" data-id="{{ $pedido->id }}" data-codigo="{{ $pedido->codigo }}">
                                            <i class="mdi mdi-check"></i> Marcar Entregado
                                        </button>
                                    @else
                                        <span class="text-muted">Completado</span>
                                    @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#despachos-table').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
            pageLength: 10,
            order: [[0, 'desc']]
        });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 4000
        };

        // Marcar como entregado
        $('.btn-entregar').on('click', function() {
            let id = $(this).data('id');
            let codigo = $(this).data('codigo');
            
            Swal.fire({
                title: '¿Marcar como entregado?',
                html: `El pedido <strong>${codigo}</strong> será marcado como entregado.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Sí, entregar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/despachos/${id}/entregado`,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => location.reload(), 1200);
                            }
                        },
                        error: function() {
                            toastr.error('Error al marcar como entregado');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection