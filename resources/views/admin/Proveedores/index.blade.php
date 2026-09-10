@extends('layouts.plantilla_maestra')

@section('title', 'Proveedores')

@section('content')

<div class="container-fluid">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="mdi mdi-truck-outline"></i>
                Proveedores
            </h3>

            <p class="text-muted mb-0">
                Listado de proveedores registrados
            </p>
        </div>

        <a href="{{ route('proveedores.create') }}"
           class="btn btn-success">
            <i class="mdi mdi-plus"></i>
            Agregar nuevo proveedor
        </a>

    </div>


    {{-- Mensaje de éxito --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="mdi mdi-check-circle"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Panel --}}
    <div class="card">

        <div class="card-body">

            {{-- Título y buscador --}}
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h5 class="card-title mb-1">
                        Lista de proveedores
                    </h5>

                    <span class="text-muted">
                        Total: <span id="totalProveedores">{{ $proveedores->count() }}</span>
                    </span>
                </div>


                {{-- Buscador --}}
                <div style="width: 350px;">

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="mdi mdi-magnify"></i>
                        </span>

                        <input
                            type="text"
                            id="buscarProveedor"
                            class="form-control"
                            placeholder="Buscar proveedor..."
                        >

                    </div>

                </div>

            </div>


            {{-- Tabla --}}
            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>NIT</th>
                            <th>Teléfono</th>
                            <th>Ciudad</th>
                            <th>Dirección</th>
                            <th>Estado</th>
                            <th>Fecha de creación</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>


                    <tbody id="tablaProveedores">

                        @forelse($proveedores as $proveedor)

                            <tr class="fila-proveedor">

                                <td>
                                    {{ $proveedor->id }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $proveedor->nombre }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $proveedor->nit }}
                                </td>

                                <td>
                                    {{ $proveedor->telefono }}
                                </td>

                                <td>
                                    {{ $proveedor->ciudad }}
                                </td>

                                <td>
                                    {{ $proveedor->direccion }}
                                </td>

                                <td>

                                    @if($proveedor->estado)

                                        <span class="badge bg-success">
                                            Activo
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Inactivo
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $proveedor->created_at?->format('d/m/Y H:i') }}
                                </td>

                                <td>

                                    {{-- Editar --}}
                                    <a href="{{ route('proveedores.edit', $proveedor->id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Editar">

                                        <i class="mdi mdi-pencil"></i>

                                    </a>


                                    {{-- Activar / Inactivar --}}
                                    <form
                                        action="{{ route('proveedores.estado', $proveedor->id) }}"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if($proveedor->estado)

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Inactivar"
                                            >
                                                <i class="mdi mdi-account-off"></i>
                                            </button>

                                        @else

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-success"
                                                title="Activar"
                                            >
                                                <i class="mdi mdi-account-check"></i>
                                            </button>

                                        @endif

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr id="sinProveedores">

                                <td colspan="9"
                                    class="text-center py-5">

                                    <i class="mdi mdi-truck-outline"
                                       style="font-size: 45px; color: #999;">
                                    </i>

                                    <p class="mt-2 mb-0">
                                        No hay proveedores registrados.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Mensaje cuando no encuentra resultados --}}
            <div id="sinResultados"
                 class="text-center py-5"
                 style="display: none;">

                <i class="mdi mdi-magnify-close"
                   style="font-size: 45px; color: #999;">
                </i>

                <p class="mt-2 mb-0">
                    No se encontraron proveedores.
                </p>

            </div>

        </div>

    </div>

</div>


{{-- Buscador --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const buscador = document.getElementById('buscarProveedor');
    const filas = document.querySelectorAll('.fila-proveedor');
    const sinResultados = document.getElementById('sinResultados');
    const totalProveedores = document.getElementById('totalProveedores');

    buscador.addEventListener('keyup', function () {

        const texto = this.value.toLowerCase().trim();

        let encontrados = 0;

        filas.forEach(function (fila) {

            const contenido = fila.textContent.toLowerCase();

            if (contenido.includes(texto)) {

                fila.style.display = '';
                encontrados++;

            } else {

                fila.style.display = 'none';

            }

        });

        totalProveedores.textContent = encontrados;

        if (encontrados === 0 && filas.length > 0) {

            sinResultados.style.display = 'block';

        } else {

            sinResultados.style.display = 'none';

        }

    });

});

</script>

@endsection