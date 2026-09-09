{{-- resources/views/admin/ordenes/pedidos/show.blade.php --}}
@extends('layouts.plantilla_maestra')

@section('title', 'Detalle del Pedido - ' . $pedido->codigo)

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .estado-pendiente { background: #F59E0B; color: white; padding: 0.35rem 1rem; border-radius: 20px; font-size: 0.8rem; }
    .estado-listo { background: #10B981; color: white; padding: 0.35rem 1rem; border-radius: 20px; font-size: 0.8rem; }
    .estado-entregado { background: #3B82F6; color: white; padding: 0.35rem 1rem; border-radius: 20px; font-size: 0.8rem; }
    .info-card { background: var(--gray-light); border-radius: 16px; padding: 1rem; margin-bottom: 1rem; }
    .info-label { font-size: 0.7rem; text-transform: uppercase; color: var(--gray-medium); }
    .info-value { font-size: 0.9rem; font-weight: 600; color: var(--primary-black); }
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
                            Pedido #{{ $pedido->codigo }}
                        </h4>
                        <p class="text-muted mb-0">Detalle completo del pedido</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <!-- Información del cliente -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6><i class="mdi mdi-account"></i> Información del Cliente</h6>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="info-label">Nombre</div>
                                    <div class="info-value">{{ $pedido->nombre_completo }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $pedido->email }}</div>
                                </div>
                                <div class="col-6 mt-2">
                                    <div class="info-label">Teléfono</div>
                                    <div class="info-value">{{ $pedido->telefono }}</div>
                                </div>
                                <div class="col-6 mt-2">
                                    <div class="info-label">Usuario Registrado</div>
                                    <div class="info-value">{{ $pedido->user->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del envío -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6><i class="mdi mdi-truck-delivery"></i> Información de Envío</h6>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="info-label">Departamento</div>
                                    <div class="info-value">{{ $pedido->departamento }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="info-label">Fecha del Pedido</div>
                                    <div class="info-value">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="info-label">Dirección</div>
                                    <div class="info-value">{{ $pedido->direccion }}</div>
                                </div>
                                @if($pedido->observaciones)
                                <div class="col-12 mt-2">
                                    <div class="info-label">Observaciones</div>
                                    <div class="info-value">{{ $pedido->observaciones }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado del pedido -->
                <div class="info-card mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="info-label">Estado Actual</span>
                            <div class="mt-1">
                                <span class="estado-{{ $pedido->estado }}">{{ ucfirst($pedido->estado) }}</span>
                            </div>
                        </div>
                        <div>
                            <select id="cambiar-estado" class="form-select" style="width: 150px;">
                                <option value="pendiente" {{ $pedido->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="listo" {{ $pedido->estado == 'listo' ? 'selected' : '' }}>Listo</option>
                                <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Productos del pedido -->
                <h5 class="mt-4 mb-3">
                    <i class="mdi mdi-cart me-2" style="color: #10B981;"></i>
                    Productos del Pedido
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Color</th>
                                <th>Medida/Material</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->detalles as $detalle)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <strong>{{ $detalle->producto_nombre }}</strong>
                                    </div>
                                </td>
                                <td>{{ $detalle->color_nombre ?? '-' }}</td>
                                <td>
                                    @if($detalle->medida) <small>{{ $detalle->medida }}</small> @endif
                                    @if($detalle->material) <small class="text-muted"> / {{ $detalle->material }}</small> @endif
                                </td>
                                <td>Bs {{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td class="fw-bold" style="color: #10B981;">Bs {{ number_format($detalle->subtotal, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><th colspan="5" class="text-end">Total:</th><th class="fw-bold" style="color: #10B981;">Bs {{ number_format($pedido->total, 2, ',', '.') }}</th></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 4000
        };

        // Cambiar estado
        $('#cambiar-estado').on('change', function() {
            let estado = $(this).val();
            let id = {{ $pedido->id }};
            
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
                        // Actualizar badge de estado
                        $('.estado-pendiente, .estado-listo, .estado-entregado').removeClass('estado-pendiente estado-listo estado-entregado').addClass(`estado-${estado}`).text(estado.charAt(0).toUpperCase() + estado.slice(1));
                    }
                },
                error: function() {
                    toastr.error('Error al actualizar el estado');
                }
            });
        });
    });
</script>
@endsection