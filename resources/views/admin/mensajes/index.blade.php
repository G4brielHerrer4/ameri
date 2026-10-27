@extends('layouts.plantilla_maestra')

@section('content')
<div class="container-fluid">

    <h3 class="mb-4">Mensajes de contacto</h3>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            @if ($mensajes->isEmpty())
                <div class="text-center py-5">
                    <h5>No hay mensajes todavía</h5>
                    <p class="text-muted">
                        Los mensajes enviados desde la página pública aparecerán aquí.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Fecha</th>
                                <th>Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($mensajes as $mensaje)
                                <tr>
                                    <td>
                                        @if ($mensaje->leido)
                                            <span class="badge bg-secondary">Leído</span>
                                        @else
                                            <span class="badge bg-success">Nuevo</span>
                                        @endif
                                    </td>

                                    <td>{{ $mensaje->nombre }}</td>
                                    <td>{{ $mensaje->email }}</td>
                                    <td>{{ $mensaje->telefono ?: 'No indicado' }}</td>
                                    <td>{{ $mensaje->created_at->format('d/m/Y H:i') }}</td>

                                    <td>
                                        <a
                                            href="{{ route('admin.mensajes.show', $mensaje) }}"
                                            class="btn btn-sm btn-primary">
                                            Ver mensaje
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $mensajes->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection