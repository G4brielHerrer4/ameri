@extends('layouts.plantilla_maestra')

@section('title', 'Agregar proveedor')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="mdi mdi-truck-plus-outline"></i>
                Agregar nuevo proveedor
            </h3>

            <p class="text-muted mb-0">
                Registra los datos del nuevo proveedor
            </p>
        </div>

        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left"></i>
            Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            <h5 class="card-title mb-4">
                Datos del proveedor
            </h5>

            <form action="{{ route('proveedores.store') }}" method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Nombre del proveedor
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre') }}"
                            placeholder="Ingrese el nombre del proveedor"
                            required
                        >

                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            NIT
                        </label>

                        <input
                            type="text"
                            name="nit"
                            class="form-control @error('nit') is-invalid @enderror"
                            value="{{ old('nit') }}"
                            placeholder="Ingrese el NIT"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            maxlength="20"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            required
                        >

                        @error('nit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Teléfono
                        </label>

                        <input
                            type="text"
                            name="telefono"
                            class="form-control @error('telefono') is-invalid @enderror"
                            value="{{ old('telefono') }}"
                            placeholder="Ingrese el teléfono"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            maxlength="20"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            required
                        >

                        @error('telefono')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Ciudad
                        </label>

                        <input
                            type="text"
                            name="ciudad"
                            class="form-control @error('ciudad') is-invalid @enderror"
                            value="{{ old('ciudad') }}"
                            placeholder="Ingrese la ciudad"
                            required
                        >

                        @error('ciudad')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label">
                            Dirección
                        </label>

                        <input
                            type="text"
                            name="direccion"
                            class="form-control @error('direccion') is-invalid @enderror"
                            value="{{ old('direccion') }}"
                            placeholder="Ingrese la dirección"
                            required
                        >

                        @error('direccion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('proveedores.index') }}"
                       class="btn btn-light">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-content-save"></i>
                        Guardar proveedor
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection