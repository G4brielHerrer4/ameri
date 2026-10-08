{{-- resources/views/admin/usuarios/administracion/index.blade.php --}}
@extends('layouts.plantilla_maestra')

@section('title', 'Administración de Usuarios')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        {{-- ==================== HEADER ==================== --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
          <div class="min-w-0">
            <h4 class="card-title d-flex align-items-center gap-2 mb-1">
              <i class="mdi mdi-account-group text-primary" style="font-size:1.4rem;"></i>
              <span>Administración de Usuarios</span>
            </h4>
            <p class="card-subtitle mb-0">Gestiona los usuarios del sistema y sus roles</p>
          </div>

          <button type="button"
                  class="btn btn-primary"
                  data-bs-toggle="modal"
                  data-bs-target="#modalCreateUser">
            <i class="mdi mdi-account-plus"></i>
            <span class="d-none d-sm-inline">Nuevo Usuario</span>
            <span class="d-inline d-sm-none">Nuevo</span>
          </button>
        </div>

        {{-- ==================== TABLA ==================== --}}
        <div class="table-responsive">
          <table class="table table-hover align-middle w-100" id="users-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Registro</th>
                <th class="text-center" style="width: 120px;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <tr>
                  <td class="fw-semibold">#{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</td>
                  <td>
                    <img src="{{ $user->profile_photo_url }}"
                         alt="{{ $user->name }}"
                         class="rounded-circle"
                         width="40" height="40"
                         style="object-fit:cover;border:2px solid var(--line-strong);">
                  </td>
                  <td><div class="fw-semibold">{{ $user->name }}</div></td>
                  <td><span class="text-muted" style="font-size:0.88rem;">{{ $user->email }}</span></td>
                  <td>
                    @if($user->role)
                      <span class="badge {{ $user->role->slug === 'admin' ? 'badge-primary' : 'badge-success' }}">
                        <i class="mdi {{ $user->role->slug === 'admin' ? 'mdi-shield-account' : 'mdi-account' }} me-1"></i>
                        {{ $user->role->name }}
                      </span>
                    @else
                      <span class="badge badge-warning">
                        <i class="mdi mdi-alert-circle-outline me-1"></i>
                        Sin rol
                      </span>
                    @endif
                  </td>
                  <td>
                    <span class="text-muted" style="font-size:0.82rem;">
                      <i class="mdi mdi-calendar-outline me-1"></i>
                      {{ $user->created_at->format('d/m/Y') }}
                    </span>
                  </td>
                  <td class="text-center">
                    <div class="d-inline-flex gap-2">
                      <button type="button"
                              class="btn btn-icon btn-outline-primary btn-edit"
                              data-id="{{ $user->id }}"
                              data-name="{{ $user->name }}"
                              data-email="{{ $user->email }}"
                              data-role="{{ $user->role_id }}"
                              data-url="{{ route('admin.users.update', $user) }}"
                              title="Editar usuario"
                              aria-label="Editar usuario">
                        <i class="mdi mdi-pencil"></i>
                      </button>

                      <button type="button"
                              class="btn btn-icon btn-danger btn-delete"
                              data-id="{{ $user->id }}"
                              data-name="{{ $user->name }}"
                              data-url="{{ route('admin.users.destroy', $user) }}"
                              title="Eliminar usuario"
                              aria-label="Eliminar usuario">
                        <i class="mdi mdi-delete"></i>
                      </button>
                    </div>
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

{{-- Modales --}}
@include('admin.usuarios.administracion.modals.create')
@include('admin.usuarios.administracion.modals.edit')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
  /* =====================================================
     DATATABLES — Layout + Dark mode fix
     ===================================================== */

  /* ⬇ FORZAR fondo y color del wrapper completo */
  .dataTables_wrapper,
  .dataTables_wrapper * {
    color: var(--ink) !important;
  }

  /* Wrapper principal */
  .dataTables_wrapper {
    padding: 0.25rem 0 0;
    color: var(--ink) !important;
    background: transparent !important;
  }

  /* ⬇⬇⬇ LA CLAVE: fila superior con FLEX bien alineado ⬇⬇⬇ */
  .dataTables_wrapper .row:first-child {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 0.75rem 1rem;
    margin: 0 0 1rem 0 !important;
    padding: 0 !important;
  }
  .dataTables_wrapper .row:first-child > [class*="col-"] {
    display: flex !important;
    align-items: center !important;
    flex: 0 0 auto !important;
    width: auto !important;
    max-width: none !important;
    padding: 0 !important;
  }

  /* ⬇⬇⬇ LA CLAVE: fila inferior con FLEX bien alineado ⬇⬇⬇ */
  .dataTables_wrapper .row:last-child {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 0.75rem 1rem;
    margin: 1rem 0 0 0 !important;
    padding: 0 !important;
  }
  .dataTables_wrapper .row:last-child > [class*="col-"] {
    display: flex !important;
    align-items: center !important;
    flex: 0 0 auto !important;
    width: auto !important;
    max-width: none !important;
    padding: 0 !important;
  }

  /* Etiquetas de "Mostrar X" y "Buscar" */
  .dataTables_wrapper .dataTables_length label,
  .dataTables_wrapper .dataTables_filter label {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.5rem;
    font-weight: 500;
    font-size: 0.86rem;
    color: var(--ink-muted) !important;
    margin: 0 !important;
    line-height: 1;
  }

  .dataTables_wrapper .dataTables_length,
  .dataTables_wrapper .dataTables_filter {
    color: var(--ink-muted) !important;
    font-size: 0.86rem;
    margin: 0 !important;
    padding: 0 !important;
    float: none !important;   /* ⬅ anula floats de Bootstrap 4/5 */
    text-align: left !important;
  }

  /* Select de "Mostrar 5" */
  .dataTables_wrapper .dataTables_length select {
    background-color: var(--bg-2) !important;
    background-image: none !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-md);
    padding: 0.4rem 2rem 0.4rem 0.75rem;
    font-size: 0.85rem;
    min-width: 72px;
    margin: 0 !important;
    height: auto;
    line-height: 1.4;
    appearance: none;
    -webkit-appearance: none;
    background-image:
      linear-gradient(45deg, transparent 50%, var(--ink-muted) 50%),
      linear-gradient(135deg, var(--ink-muted) 50%, transparent 50%) !important;
    background-position:
      calc(100% - 14px) 50%,
      calc(100% - 9px) 50% !important;
    background-size: 5px 5px, 5px 5px !important;
    background-repeat: no-repeat !important;
    transition: border-color .18s ease, box-shadow .18s ease;
  }
  .dataTables_wrapper .dataTables_length select:focus {
    border-color: var(--cyan) !important;
    box-shadow: 0 0 0 3px var(--glow-soft) !important;
    outline: none;
  }

  /* ⬇ Select options en modo oscuro (por si el SO aplica estilos) */
  .dataTables_wrapper .dataTables_length select option {
    background: var(--bg-2);
    color: var(--ink);
  }

  /* Input de búsqueda */
  .dataTables_wrapper .dataTables_filter input {
    background-color: var(--bg-2) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-md);
    padding: 0.42rem 0.75rem;
    font-size: 0.85rem;
    margin: 0 0 0 0.5rem !important;
    min-width: 200px;
    height: auto;
    line-height: 1.4;
    transition: border-color .18s ease, box-shadow .18s ease;
  }
  .dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--cyan) !important;
    box-shadow: 0 0 0 3px var(--glow-soft) !important;
    outline: none;
    background-color: var(--bg-2) !important;
  }
  .dataTables_wrapper .dataTables_filter input::placeholder {
    color: var(--ink-faint) !important;
  }

  /* Info text — "Mostrando 1 a 5 de 20 usuarios" */
  .dataTables_wrapper .dataTables_info {
    color: var(--ink-muted) !important;
    font-size: 0.84rem;
    padding: 0 !important;
    margin: 0 !important;
  }

  /* Paginación */
  .dataTables_wrapper .dataTables_paginate {
    padding: 0 !important;
    margin: 0 !important;
    float: none !important;
  }
  .dataTables_wrapper .dataTables_paginate ul.pagination {
    margin: 0 !important;
    gap: 2px;
    display: flex;
    flex-wrap: wrap;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button {
    background-color: var(--bg-1) !important;
    background-image: none !important;
    border: 1px solid var(--line) !important;
    color: var(--ink-muted) !important;
    border-radius: var(--r-sm) !important;
    padding: 0.4rem 0.7rem !important;
    margin: 0 !important;
    min-width: 34px;
    text-align: center;
    font-size: 0.85rem;
    line-height: 1;
    transition: all .18s ease;
    cursor: pointer;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled):not(.current) {
    border-color: var(--line-strong) !important;
    color: var(--cyan) !important;
    background-color: var(--bg-1) !important;
    background-image: none !important;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.current,
  .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background-color: var(--cyan) !important;
    background-image: none !important;
    border-color: var(--cyan) !important;
    color: var(--cyan-ink) !important;
    font-weight: 700;
    box-shadow: 0 0 12px var(--glow);
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
  .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
    color: var(--ink-faint) !important;
    border-color: var(--line) !important;
    background-color: transparent !important;
    opacity: 0.45;
    cursor: not-allowed;
  }

  /* =====================================================
     TABLA — fondo y colores coherentes con el tema
     ===================================================== */
  table.dataTable {
    background: transparent !important;
    border-collapse: separate !important;
    border-spacing: 0;
    width: 100% !important;
    margin: 0 !important;
    color: var(--ink) !important;
  }

  /* Header */
  table.dataTable thead th {
    background: var(--bg-2) !important;
    color: var(--ink-muted) !important;
    border-bottom: 1px solid var(--line) !important;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 0.85rem 0.9rem !important;
    white-space: nowrap;
  }
  table.dataTable thead th:first-child { border-top-left-radius: var(--r-md); }
  table.dataTable thead th:last-child  { border-top-right-radius: var(--r-md); }

  /* Ordenamiento */
  table.dataTable thead .sorting:before,
  table.dataTable thead .sorting:after,
  table.dataTable thead .sorting_asc:before,
  table.dataTable thead .sorting_asc:after,
  table.dataTable thead .sorting_desc:before,
  table.dataTable thead .sorting_desc:after,
  table.dataTable thead .sorting_asc_disabled:before,
  table.dataTable thead .sorting_asc_disabled:after,
  table.dataTable thead .sorting_desc_disabled:before,
  table.dataTable thead .sorting_desc_disabled:after {
    color: var(--ink-faint) !important;
    opacity: 0.6;
  }
  table.dataTable thead .sorting_asc:before,
  table.dataTable thead .sorting_desc:after {
    color: var(--cyan) !important;
    opacity: 1;
  }

  /* Celdas */
  table.dataTable tbody td {
    background: transparent !important;
    color: var(--ink) !important;
    border-top: 1px solid var(--line) !important;
    padding: 0.85rem 0.9rem !important;
    font-size: 0.88rem;
    vertical-align: middle;
  }

  /* Hover de fila */
  table.dataTable tbody tr:hover > td {
    background: var(--surface-alpha) !important;
  }

  /* Striped */
  table.dataTable.table-striped > tbody > tr:nth-of-type(odd) > * {
    background-color: color-mix(in srgb, var(--bg-2) 45%, transparent) !important;
    color: var(--ink) !important;
  }

  /* Evita el "flash blanco" mientras carga */
  table.dataTable.dataTable tbody tr {
    background: transparent !important;
  }

  /* =====================================================
     RESPONSIVE — botón "+" para expandir filas
     ===================================================== */
  table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
  table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
    background-color: var(--cyan) !important;
    border: 1px solid var(--cyan) !important;
    color: var(--cyan-ink) !important;
    box-shadow: 0 0 10px var(--glow);
  }
  table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td.dtr-control:before,
  table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th.dtr-control:before {
    background-color: var(--error) !important;
    border-color: var(--error) !important;
    box-shadow: 0 0 10px rgba(255, 77, 109, 0.35);
  }

  /* Fila hija expandida en móvil */
  table.dataTable > tbody > tr.child {
    background: var(--bg-2) !important;
  }
  table.dataTable > tbody > tr.child ul.dtr-details {
    padding: 0.5rem 0.75rem;
    margin: 0;
  }
  table.dataTable > tbody > tr.child ul.dtr-details > li {
    border-bottom: 1px solid var(--line);
    padding: 0.55rem 0;
    background: transparent;
  }
  table.dataTable > tbody > tr.child ul.dtr-details > li:last-child {
    border-bottom: none;
  }
  table.dataTable > tbody > tr.child span.dtr-title {
    font-weight: 700;
    color: var(--ink-muted);
    min-width: 100px;
    display: inline-block;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }
  table.dataTable > tbody > tr.child span.dtr-data {
    color: var(--ink);
  }

  /* =====================================================
     RESPONSIVE — ajustes de layout por tamaño
     ===================================================== */

  /* Tablet y abajo */
  @media (max-width: 767.98px) {
    .dataTables_wrapper .row:first-child,
    .dataTables_wrapper .row:last-child {
      flex-direction: column !important;
      align-items: stretch !important;
    }
    .dataTables_wrapper .row:first-child > [class*="col-"],
    .dataTables_wrapper .row:last-child > [class*="col-"] {
      width: 100% !important;
      display: block !important;
    }
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_filter label {
      width: 100%;
      display: flex !important;
      justify-content: flex-start;
    }
    .dataTables_wrapper .dataTables_filter input {
      width: 100%;
      margin: 0.35rem 0 0 0 !important;
      min-width: 0;
    }
    .dataTables_wrapper .dataTables_length label {
      width: 100%;
      justify-content: flex-start;
    }
    .dataTables_wrapper .dataTables_paginate {
      justify-content: center;
      display: flex;
    }
  }

  /* Móvil pequeño */
  @media (max-width: 575.98px) {
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      padding: 0.35rem 0.55rem !important;
      min-width: 30px;
      font-size: 0.8rem;
    }
    .dataTables_wrapper .dataTables_info {
      text-align: center;
      width: 100%;
    }
  }
</style>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {

  /* =====================================================
     DATATABLE — español, 5 registros, responsive
     ===================================================== */
  const table = $('#users-table').DataTable({
    language: {
      "decimal": "",
      "emptyTable": "No hay usuarios registrados",
      "info": "Mostrando _START_ a _END_ de _TOTAL_ usuarios",
      "infoEmpty": "Mostrando 0 a 0 de 0 usuarios",
      "infoFiltered": "(filtrado de _MAX_ usuarios totales)",
      "lengthMenu": "Mostrar _MENU_",
      "loadingRecords": "Cargando...",
      "processing": "Procesando...",
      "search": "Buscar:",
      "zeroRecords": "No se encontraron usuarios",
      "paginate": {
        "first": '<i class="mdi mdi-chevron-double-left"></i>',
        "last": '<i class="mdi mdi-chevron-double-right"></i>',
        "next": '<i class="mdi mdi-chevron-right"></i>',
        "previous": '<i class="mdi mdi-chevron-left"></i>'
      },
      "aria": {
        "sortAscending": ": activar para ordenar ascendente",
        "sortDescending": ": activar para ordenar descendente"
      }
    },
    pageLength: 5,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
    order: [[0, 'desc']],
    responsive: {
      details: {
        type: 'inline',
        target: 0
      }
    },
    autoWidth: false,
    columnDefs: [
      { orderable: false, targets: [1, 6] },
      { responsivePriority: 1, targets: [0, 2, 6] },
      { responsivePriority: 2, targets: [4] },
      { responsivePriority: 3, targets: [3] },
      { responsivePriority: 4, targets: [5] },
      { responsivePriority: 5, targets: [1] }
    ]
  });

  /* =====================================================
     TOASTR
     ===================================================== */
  toastr.options = {
    closeButton: true,
    progressBar: true,
    newestOnTop: true,
    positionClass: 'toast-top-right',
    preventDuplicates: true,
    timeOut: 4000
  };

  @if(session('success'))
    toastr.success(@json(session('success')), '¡Éxito!');
  @endif
  @if(session('error'))
    toastr.error(@json(session('error')), '¡Error!');
  @endif

  /* =====================================================
     EDITAR
     ===================================================== */
  $('#users-table').on('click', '.btn-edit', function() {
    const $btn   = $(this);
    $('#edit_name').val($btn.data('name'));
    $('#edit_email').val($btn.data('email'));
    $('#edit_role_id').val($btn.data('role'));
    $('#formEditUser').attr('action', $btn.data('url'));
    $('#edit_password, #edit_password_confirmation').val('');
    $('#formEditUser .is-invalid').removeClass('is-invalid');
    $('#formEditUser .invalid-feedback').hide();
    new bootstrap.Modal(document.getElementById('modalEditUser')).show();
  });

  /* =====================================================
     ELIMINAR
     ===================================================== */
  $('#users-table').on('click', '.btn-delete', function() {
    const $btn = $(this);
    const name = $btn.data('name');
    const url  = $btn.data('url');

    Swal.fire({
      title: '¿Eliminar usuario?',
      html: `Estás por eliminar a <strong>${name}</strong>.<br>Esta acción no se puede deshacer.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ff4d6d',
      cancelButtonColor: '#55617d',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      reverseButtons: true,
      focusCancel: true,
      background: getComputedStyle(document.documentElement).getPropertyValue('--bg-1').trim() || '#0a0f1c',
      color: getComputedStyle(document.documentElement).getPropertyValue('--ink').trim() || '#e6ecff'
    }).then((result) => {
      if (!result.isConfirmed) return;

      const $row = $btn.closest('tr');
      $btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>');

      $.ajax({
        url: url,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function(response) {
          if (response.success) {
            toastr.success(response.message, '¡Eliminado!');
            table.row($row).remove().draw(false);
          } else {
            toastr.error(response.message || 'No se pudo eliminar', '¡Error!');
            $btn.prop('disabled', false).html('<i class="mdi mdi-delete"></i>');
          }
        },
        error: function(xhr) {
          toastr.error(xhr.responseJSON?.message || 'Error al eliminar', '¡Error!');
          $btn.prop('disabled', false).html('<i class="mdi mdi-delete"></i>');
        }
      });
    });
  });

  /* =====================================================
     CREAR
     ===================================================== */
  $('#formCreateUser').on('submit', function(e) {
    e.preventDefault();
    const $form = $(this);
    const $btn  = $form.find('button[type="submit"]');
    const html  = $btn.html();

    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').hide();
    $btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Creando...');

    $.ajax({
      url: $form.attr('action'),
      type: 'POST',
      data: $form.serialize(),
      success: function(response) {
        if (response.success) {
          bootstrap.Modal.getInstance(document.getElementById('modalCreateUser')).hide();
          toastr.success(response.message, '¡Usuario creado!');
          setTimeout(() => location.reload(), 1200);
        }
      },
      error: function(xhr) {
        const errors = xhr.responseJSON?.errors;
        if (errors) {
          $.each(errors, function(key, value) {
            const $input = $form.find(`[name="${key}"]`);
            $input.addClass('is-invalid');
            $input.siblings('.invalid-feedback').text(value[0]).show();
          });
          toastr.error('Corrige los errores del formulario', 'Validación');
        } else {
          toastr.error('Error al crear el usuario', '¡Error!');
        }
      },
      complete: function() { $btn.prop('disabled', false).html(html); }
    });
  });

  /* =====================================================
     EDITAR (submit)
     ===================================================== */
  $('#formEditUser').on('submit', function(e) {
    e.preventDefault();
    const $form = $(this);
    const $btn  = $form.find('button[type="submit"]');
    const html  = $btn.html();

    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').hide();
    $btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Guardando...');

    $.ajax({
      url: $form.attr('action'),
      type: 'POST',
      data: $form.serialize(),
      success: function(response) {
        if (response.success) {
          bootstrap.Modal.getInstance(document.getElementById('modalEditUser')).hide();
          toastr.success(response.message, '¡Actualizado!');
          setTimeout(() => location.reload(), 1200);
        }
      },
      error: function(xhr) {
        const errors = xhr.responseJSON?.errors;
        if (errors) {
          $.each(errors, function(key, value) {
            const $input = $form.find(`[name="${key}"]`);
            $input.addClass('is-invalid');
            $input.siblings('.invalid-feedback').text(value[0]).show();
          });
          toastr.error('Corrige los errores del formulario', 'Validación');
        } else {
          toastr.error('Error al actualizar el usuario', '¡Error!');
        }
      },
      complete: function() { $btn.prop('disabled', false).html(html); }
    });
  });

  /* =====================================================
     LIMPIAR MODALES
     ===================================================== */
  document.getElementById('modalCreateUser')?.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('formCreateUser');
    form.reset();
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
  });

  document.getElementById('modalEditUser')?.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('formEditUser');
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
  });

});
</script>
@endsection