
@extends('layouts.plantilla_maestra_rep')

@section('title', 'Detalle del Pedido - ' . $pedido->codigo)

@section('css')
<style>
    .estado-pendiente { background: #FEF3C7; color: #D97706; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; display: inline-block; }
    .estado-listo { background: #D1FAE5; color: #059669; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; display: inline-block; }
    .estado-entregado { background: #DBEAFE; color: #2563EB; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; display: inline-block; }
    .info-card { background: #F8FAFC; border-radius: 12px; padding: 1rem; margin-bottom: 1rem; }
    .info-label { font-size: 0.7rem; color: #6B7280; text-transform: uppercase; }
    .info-value { font-size: 0.9rem; font-weight: 600; color: #1F2937; }
    .btn-listo { background: #10B981; color: white; border: none; padding: 0.5rem 1.5rem; border-radius: 8px; transition: all 0.3s ease; margin-right: 0.5rem; }
    .btn-entregar { background: #3B82F6; color: white; border: none; padding: 0.5rem 1.5rem; border-radius: 8px; transition: all 0.3s ease; }
    .btn-listo:hover, .btn-entregar:hover { transform: translateY(-2px); }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-package-variant me-2" style="color: #10B981;"></i>
                        Pedido #{{ $pedido->codigo }}
                    </h4>
                    <a href="{{ route('vendedor.ordenes') }}" class="btn btn-secondary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6><i class="mdi mdi-information"></i> Información del Pedido</h6>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="info-label">Fecha</div>
                                    <div class="info-value">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="info-label">Estado</div>
                                    <div class="info-value">
                                        @if($pedido->estado == 'pendiente')
                                            <span class="estado-pendiente">Pendiente</span>
                                        @elseif($pedido->estado == 'listo')
                                            <span class="estado-listo">Listo para entregar</span>
                                        @else
                                            <span class="estado-entregado">Entregado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6><i class="mdi mdi-account"></i> Datos del Cliente</h6>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="info-label">Nombre</div>
                                    <div class="info-value">{{ $pedido->nombre_completo }}</div>
                                </div>
                                <div class="col-6 mt-2">
                                    <div class="info-label">Teléfono</div>
                                    <div class="info-value">{{ $pedido->telefono }}</div>
                                </div>
                                <div class="col-6 mt-2">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $pedido->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="info-card">
                            <h6><i class="mdi mdi-map-marker"></i> Dirección de Entrega</h6>
                            <div class="info-value mt-2">{{ $pedido->direccion }}, {{ $pedido->departamento }}</div>
                            @if($pedido->observaciones)
                                <div class="mt-2"><small class="text-muted">Observaciones:</small> {{ $pedido->observaciones }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Productos del Pedido</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>Producto</th><th>Color</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto_nombre }}</td>
                                <td>{{ $detalle->color_nombre ?? '-' }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>Bs {{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                                <td class="fw-bold" style="color: #10B981;">Bs {{ number_format($detalle->subtotal, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <td><th colspan="4" class="text-end">Total</th><th class="fw-bold" style="color: #10B981;">Bs {{ number_format($pedido->total, 2, ',', '.') }}</th></tr>
                        </tfoot>
                    </table>
                </div>

                @if($pedido->estado == 'pendiente')
                <div class="text-center mt-4">
                    <button class="btn-listo" onclick="marcarListo({{ $pedido->id }}, '{{ $pedido->codigo }}')">
                        <i class="mdi mdi-check-circle"></i> Marcar como Listo
                    </button>
                </div>
                @endif

                @if($pedido->estado == 'listo')
                <div class="text-center mt-4">
                    <button class="btn-entregar" onclick="marcarEntregado({{ $pedido->id }}, '{{ $pedido->codigo }}')">
                        <i class="mdi mdi-truck"></i> Marcar como Entregado
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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