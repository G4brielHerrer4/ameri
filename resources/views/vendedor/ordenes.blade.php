
@extends('layouts.plantilla_maestra_rep')

@section('title', 'Órdenes - Vendedor')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    .estado-pendiente { background: #FEF3C7; color: #D97706; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; display: inline-block; }
    .estado-listo { background: #D1FAE5; color: #059669; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; display: inline-block; }
    .estado-entregado { background: #DBEAFE; color: #2563EB; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; display: inline-block; }
    .btn-listo { background: #10B981; color: white; border: none; padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.7rem; transition: all 0.3s ease; }
    .btn-listo:hover { background: #059669; transform: translateY(-2px); }
    .btn-entregar { background: #3B82F6; color: white; border: none; padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.7rem; transition: all 0.3s ease; }
    .btn-entregar:hover { background: #2563EB; transform: translateY(-2px); }
    .btn-detalle { background: #6B7280; color: white; border: none; padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.7rem; transition: all 0.3s ease; }
    .btn-detalle:hover { background: #4B5563; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-format-list-bulleted me-2" style="color: #10B981;"></i>
                        Todas las Órdenes
                    </h4>
                    <a href="{{ route('vendedor.dashboard') }}" class="btn btn-secondary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="ordenes-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $pedido)
                            <tr>
                                <td><strong>{{ $pedido->codigo }}</strong></td>
                                <td>{{ $pedido->nombre_completo }}</td>
                                <td>{{ $pedido->telefono }}</td>
                                <td>{{ $pedido->direccion }} ({{ $pedido->departamento }})</td>
                                <td class="fw-bold" style="color: #10B981;">Bs {{ number_format($pedido->total, 2, ',', '.') }}</td>
                                <td>
                                    @if($pedido->estado == 'pendiente')
                                        <span class="estado-pendiente">Pendiente</span>
                                    @elseif($pedido->estado == 'listo')
                                        <span class="estado-listo">Listo para entregar</span>
                                    @else
                                        <span class="estado-entregado">Entregado</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('vendedor.pedido.show', $pedido->id) }}" class="btn-detalle">
                                        <i class="mdi mdi-eye"></i> Ver
                                    </a>
                                    @if($pedido->estado == 'pendiente')
                                        <button class="btn-listo ms-1" onclick="marcarListo({{ $pedido->id }}, '{{ $pedido->codigo }}')">
                                            <i class="mdi mdi-check"></i> Listo
                                        </button>
                                    @endif
                                    @if($pedido->estado == 'listo')
                                        <button class="btn-entregar ms-1" onclick="marcarEntregado({{ $pedido->id }}, '{{ $pedido->codigo }}')">
                                            <i class="mdi mdi-truck"></i> Entregar
                                        </button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#ordenes-table').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
            pageLength: 10,
            order: [[0, 'desc']]
        });
    });

    function marcarListo(id, codigo) {
        Swal.fire({
            title: '¿Marcar como listo?',
            html: `Marcar el pedido <strong>${codigo}</strong> como listo para entregar.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#EF4444',
            confirmButtonText: 'Sí, marcar como listo',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('/vendedor/pedido') }}/${id}/listo`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('¡Listo!', data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Error de conexión', 'error');
                });
            }
        });
    }

    function marcarEntregado(id, codigo) {
        Swal.fire({
            title: '¿Confirmar entrega?',
            html: `Marcar el pedido <strong>${codigo}</strong> como entregado.<br>Esta acción descontará el stock.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#EF4444',
            confirmButtonText: 'Sí, entregar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('/vendedor/pedido') }}/${id}/entregar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('¡Entregado!', data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Error de conexión', 'error');
                });
            }
        });
    }
</script>
@endsection