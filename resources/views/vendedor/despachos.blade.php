
@extends('layouts.plantilla_maestra')

@section('title', 'Despachos - Vendedor')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    .estado-listo { background: #D1FAE5; color: #059669; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; display: inline-block; }
    .estado-entregado { background: #DBEAFE; color: #2563EB; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; display: inline-block; }
    .btn-entregar { background: #10B981; color: white; border: none; padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.7rem; transition: all 0.3s ease; }
    .btn-entregar:hover { background: #059669; transform: translateY(-2px); }
    .btn-detalle { background: #3B82F6; color: white; border: none; padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.7rem; transition: all 0.3s ease; }
    .btn-detalle:hover { background: #2563EB; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-truck-delivery me-2" style="color: #10B981;"></i>
                        Gestión de Despachos
                    </h4>
                    <a href="{{ route('vendedor.dashboard') }}" class="btn btn-secondary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="despachos-table">
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
                                    @if($pedido->estado == 'listo')
                                        <span class="estado-listo">✓ Listo para entregar</span>
                                    @else
                                        <span class="estado-entregado">✈ Entregado</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('vendedor.pedido.show', $pedido->id) }}" class="btn-detalle">
                                        <i class="mdi mdi-eye"></i> Ver
                                    </a>
                                    @if($pedido->estado == 'listo')
                                        <button class="btn-entregar ms-1" onclick="marcarEntregado({{ $pedido->id }}, '{{ $pedido->codigo }}')">
                                            <i class="mdi mdi-check"></i> Entregar
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
        $('#despachos-table').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
            pageLength: 10,
            order: [[0, 'desc']]
        });
    });

    function marcarEntregado(id, codigo) {
        Swal.fire({
            title: '¿Confirmar entrega?',
            html: `Marcar el pedido <strong>${codigo}</strong> como entregado.`,
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