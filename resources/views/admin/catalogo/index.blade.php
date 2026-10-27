
@extends('layouts.plantilla_maestra')

@section('title', 'Catálogo Público - AMERI')

@push('styles')
<style>
.catalogo-header{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem}
.catalogo-header h3{font-family:var(--font-display);font-weight:600;color:var(--ink);display:flex;align-items:center;gap:.65rem;margin:0;font-size:1.35rem}
.catalogo-header .icon-wrap{width:44px;height:44px;border-radius:var(--r-md);background:linear-gradient(135deg,var(--cyan),var(--cyan-2));color:var(--cyan-ink);display:inline-flex;align-items:center;justify-content:center;font-size:1.35rem;box-shadow:0 6px 20px -6px var(--glow)}
.catalogo-header p{color:var(--ink-muted);margin:.25rem 0 0;font-size:.88rem}
.catalogo-panel{background:var(--bg-1);border:1px solid var(--line);border-radius:var(--r-lg);overflow:hidden;box-shadow:0 20px 40px -28px var(--shadow);min-width:0}
.catalogo-panel-body{padding:1.1rem 1.25rem}
.catalogo-panel .dataTables_wrapper{color:var(--ink);font-size:.85rem;width:100%}
.catalogo-panel .dt-top,.catalogo-panel .dt-bottom{display:flex;align-items:center;justify-content:space-between;gap:.75rem 1rem;flex-wrap:wrap;width:100%}
.catalogo-panel .dt-top{margin-bottom:1rem}
.catalogo-panel .dt-bottom{margin-top:1rem}
.catalogo-panel .dt-top .dataTables_length,.catalogo-panel .dt-top .dataTables_filter,.catalogo-panel .dt-bottom .dataTables_info,.catalogo-panel .dt-bottom .dataTables_paginate{float:none!important;margin:0!important;padding:0!important}
.catalogo-panel .dt-top .dataTables_filter,.catalogo-panel .dt-bottom .dataTables_paginate{margin-left:auto!important}
.catalogo-panel .dt-top label{color:var(--ink-muted);font-size:.82rem;display:inline-flex;align-items:center;gap:.5rem;margin:0}
.catalogo-panel .dt-top select,.catalogo-panel .dt-top input{height:36px;background:var(--bg-2);color:var(--ink);border:1px solid var(--line);border-radius:var(--r-md);padding:.4rem .65rem;outline:none}
.catalogo-panel .dt-top input{min-width:200px;margin-left:.4rem}
.catalogo-panel .dt-top select:focus,.catalogo-panel .dt-top input:focus{border-color:var(--cyan);box-shadow:0 0 0 3px var(--glow-soft)}
.catalogo-panel .dt-bottom .dataTables_info{color:var(--ink-faint);font-size:.78rem}
.catalogo-panel table.dataTable{width:100%!important;border-collapse:separate!important;border-spacing:0;margin:0!important;color:var(--ink)!important}
.catalogo-panel table.dataTable thead th{background:var(--bg-2)!important;color:var(--ink-muted)!important;font-family:var(--font-display);font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:.75rem!important;border-bottom:1px solid var(--line)!important;white-space:nowrap}
.catalogo-panel table.dataTable tbody td{padding:.75rem!important;border-top:1px solid var(--line)!important;color:var(--ink)!important;background:transparent!important;font-size:.85rem;vertical-align:middle}
.catalogo-panel table.dataTable tbody tr{background:transparent!important}
.catalogo-panel table.dataTable tbody tr.odd{background:color-mix(in srgb,var(--bg-2) 40%,transparent)!important}
.catalogo-panel table.dataTable tbody tr:hover{background:var(--surface-alpha)!important}
.catalogo-thumb{width:58px;height:58px;object-fit:cover;border:1px solid var(--line);border-radius:var(--r-md);display:block}
.catalogo-product-name{font-weight:600;color:var(--ink)}
.catalogo-muted{color:var(--ink-muted);font-size:.8rem}
.catalogo-status{display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .65rem;border-radius:var(--r-pill);font-size:.7rem;font-weight:700;text-transform:uppercase;white-space:nowrap}
.catalogo-status:before{content:'';width:6px;height:6px;border-radius:50%;background:currentColor}
.catalogo-status.visible{background:color-mix(in srgb,var(--success) 14%,transparent);color:var(--success);border:1px solid color-mix(in srgb,var(--success) 40%,transparent)}
.catalogo-status.oculto{background:color-mix(in srgb,var(--warning) 14%,transparent);color:var(--warning);border:1px solid color-mix(in srgb,var(--warning) 40%,transparent)}
.catalogo-actions{display:flex;align-items:center;gap:.4rem;flex-wrap:wrap}
.catalogo-action{width:34px;height:34px;border-radius:var(--r-sm);border:1px solid var(--line);background:var(--surface-alpha);color:var(--ink-muted);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:all .22s var(--ease);padding:0;font-size:1rem}
.catalogo-action:hover{transform:translateY(-2px);color:var(--cyan);border-color:var(--line-strong);background:color-mix(in srgb,var(--cyan) 10%,transparent)}
.catalogo-action.visibility:hover{color:var(--warning);border-color:var(--warning)}
.catalogo-upload{display:flex;flex-direction:column;gap:.4rem;min-width:150px}
.catalogo-upload input[type=file]{width:155px;max-width:100%;font-size:.72rem;color:var(--ink-muted)}
.catalogo-upload button{align-self:flex-start;display:inline-flex;align-items:center;gap:.3rem;padding:.35rem .65rem;border:1px solid var(--line);border-radius:var(--r-md);background:var(--bg-2);color:var(--ink);font-size:.75rem;font-weight:600;cursor:pointer}
.catalogo-upload button:hover{border-color:var(--cyan);color:var(--cyan)}
.catalogo-alert{padding:.85rem 1rem;margin-bottom:1rem;border:1px solid var(--error);border-radius:var(--r-md);background:color-mix(in srgb,var(--error) 8%,var(--bg-1));color:var(--ink)}
.catalogo-alert ul{margin:.4rem 0 0;padding-left:1.25rem}
.catalogo-panel table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before{background:var(--cyan)!important;border-color:var(--cyan)!important;color:var(--cyan-ink)!important}
.catalogo-panel table.dataTable>tbody>tr.child{background:var(--bg-2)!important}
@media(max-width:767px){
.catalogo-header h3{font-size:1.15rem}
.catalogo-panel .dt-top,.catalogo-panel .dt-bottom{flex-direction:column;align-items:stretch}
.catalogo-panel .dt-top .dataTables_filter,.catalogo-panel .dt-bottom .dataTables_paginate{margin-left:0!important}
.catalogo-panel .dt-top label{width:100%;justify-content:space-between}
.catalogo-panel .dt-top input{min-width:0;width:100%}
}
</style>
@endpush

@section('content')
<div class="container-fluid">

    <div class="catalogo-header">
        <div>
            <h3>
                <span class="icon-wrap">
                    <i class="mdi mdi-view-grid-outline"></i>
                </span>
                Catálogo público
            </h3>
            <p>Selecciona qué productos de Suministros se mostrarán a los visitantes de Cerámica América.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="catalogo-alert" role="alert">
            <strong>No se pudo actualizar el producto:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="catalogo-panel">
        <div class="catalogo-panel-body">
            <table id="tablaCatalogo" class="table dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Fotografía</th>
                        <th>Categoría</th>
                        <th>Marca / modelo</th>
                        <th>Medida</th>
                        <th>Peso</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>

                            <td>
                                <span class="catalogo-product-name">
                                    {{ $producto->nombre }}
                                </span>
                            </td>

                            <td>
                                @if($producto->imagen_catalogo)
                                    <img
                                        class="catalogo-thumb mb-2"
                                        src="{{ asset('storage/' . $producto->imagen_catalogo) }}"
                                        alt="{{ $producto->nombre }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="catalogo-muted mb-2">
                                        Sin fotografía
                                    </div>
                                @endif

                                <form
                                    method="POST"
                                    action="{{ route('admin.catalogo.update', $producto) }}"
                                    enctype="multipart/form-data"
                                    class="catalogo-upload"
                                >
                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="file"
                                        name="imagen_catalogo"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        aria-label="Fotografía de {{ $producto->nombre }}"
                                        required
                                    >

                                    <button type="submit">
                                        <i class="mdi mdi-camera-outline"></i>
                                        Guardar foto
                                    </button>

                                    <small class="catalogo-muted">
                                        JPG, PNG o WEBP. Máximo 4 MB.
                                    </small>
                                </form>
                            </td>

                            <td>
                                <span class="catalogo-muted">
                                    {{ $producto->tipoProducto?->categoria?->nombre
                                        ?? 'Sin categoría' }}
                                </span>
                            </td>

                            <td>
                                <div>{{ $producto->marca ?: '—' }}</div>
                                @if($producto->modelo)
                                    <small class="catalogo-muted">
                                        {{ $producto->modelo }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                <span class="catalogo-muted">
                                    {{ $producto->medida ?: '—' }}
                                </span>
                            </td>

                            <td>
                                <span class="catalogo-muted">
                                    @if($producto->tipoProducto?->categoria?->nombre === 'Cementos / Adhesivos'
                                        && !is_null($producto->peso))
                                        {{ $producto->peso }} kg
                                    @else
                                        —
                                    @endif
                                </span>
                            </td>

                            <td>
                                <span class="catalogo-status {{ $producto->visible_catalogo ? 'visible' : 'oculto' }}">
                                    {{ $producto->visible_catalogo ? 'Visible' : 'Oculto' }}
                                </span>
                            </td>

                            <td>
                                <div class="catalogo-actions">
                                    <form
                                        method="POST"
                                        action="{{ route('admin.catalogo.visibilidad', $producto) }}"
                                        class="catalogo-confirmar"
                                        data-titulo="{{ $producto->visible_catalogo ? '¿Ocultar producto?' : '¿Mostrar producto?' }}"
                                        data-mensaje="{{ $producto->visible_catalogo
                                            ? 'El producto dejará de aparecer en el catálogo público.'
                                            : 'El producto aparecerá en el catálogo público.' }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="catalogo-action visibility"
                                            title="{{ $producto->visible_catalogo ? 'Ocultar producto' : 'Mostrar producto' }}"
                                            aria-label="{{ $producto->visible_catalogo ? 'Ocultar' : 'Mostrar' }} {{ $producto->nombre }}"
                                        >
                                            <i class="mdi {{ $producto->visible_catalogo ? 'mdi-eye-off-outline' : 'mdi-eye-outline' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabla = document.getElementById('tablaCatalogo');

    tabla.addEventListener('submit', function (event) {
        const formulario = event.target.closest('.catalogo-confirmar');

        if (!formulario || typeof Swal === 'undefined') {
            return;
        }

        event.preventDefault();

        Swal.fire({
            title: formulario.dataset.titulo,
            text: formulario.dataset.mensaje,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            focusCancel: true,
            background: getComputedStyle(document.documentElement)
                .getPropertyValue('--bg-1').trim(),
            color: getComputedStyle(document.documentElement)
                .getPropertyValue('--ink').trim(),
            confirmButtonColor: '#00a9d6'
        }).then(function (resultado) {
            if (resultado.isConfirmed) {
                HTMLFormElement.prototype.submit.call(formulario);
            }
        });
    });

    if (typeof toastr !== 'undefined') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 4500,
            preventDuplicates: true
        };

        @if(session('success'))
            toastr.success(@json(session('success')), '¡Listo!');
        @endif

        @if(session('error'))
            toastr.error(@json(session('error')), 'Error');
        @endif
    }

    if (typeof jQuery !== 'undefined' &&
        jQuery.fn &&
        jQuery.fn.DataTable) {

        const config = {
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
            order: [[0, 'desc']],
            autoWidth: false,
            language: {
                emptyTable: 'No hay productos registrados en Suministros',
                zeroRecords: 'No se encontraron resultados',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ productos',
                infoEmpty: 'Mostrando 0 productos',
                infoFiltered: '(filtrados de _MAX_ productos)',
                lengthMenu: 'Mostrar _MENU_',
                search: 'Buscar:',
                paginate: {
                    first: 'Primero',
                    last: 'Último',
                    next: 'Siguiente',
                    previous: 'Anterior'
                }
            },
            columnDefs: [
                { orderable: false, targets: [2, 8] },
                { responsivePriority: 1, targets: [1, 8] }
            ],
            dom: '<"dt-top"lf>t<"dt-bottom"ip>'
        };

        if (jQuery.fn.dataTable.Responsive) {
            config.responsive = {
                details: { type: 'inline', target: 'tr' }
            };
        }

        jQuery('#tablaCatalogo').DataTable(config);
    }
});
</script>
@endsection
