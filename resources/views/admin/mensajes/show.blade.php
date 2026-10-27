@extends('layouts.plantilla_maestra')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Detalle del mensaje</h3>

        <a href="{{ route('admin.mensajes.index') }}"
           class="btn btn-outline-secondary">
            Volver a mensajes
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            <p><strong>Nombre:</strong> {{ $mensaje->nombre }}</p>

            <p>
                <strong>Correo:</strong>
                <a href="mailto:{{ $mensaje->email }}">
                    {{ $mensaje->email }}
                </a>
            </p>

            <p>
                <strong>Teléfono:</strong>
                @if ($mensaje->telefono)
                    <a href="tel:{{ $mensaje->telefono }}">
                        {{ $mensaje->telefono }}
                    </a>
                @else
                    No indicado
                @endif
            </p>

            <p>
                <strong>Fecha de envío:</strong>
                {{ $mensaje->created_at->format('d/m/Y H:i') }}
            </p>

            <hr>

            <h5>Mensaje del cliente</h5>

            <div class="p-3 bg-light rounded"
                 style="white-space: pre-wrap; overflow-wrap: anywhere;">{{ $mensaje->mensaje }}</div>

        </div>
    </div>

</div>
@endsection