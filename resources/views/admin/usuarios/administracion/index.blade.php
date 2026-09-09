{{-- resources/views/admin/usuarios/administracion/index.blade.php --}}
@extends('layouts.plantilla_maestra')

@section('title', 'Administración de Usuarios')

@section('content')
<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card card-rounded">
      <div class="card-body">
        <div class="d-sm-flex justify-content-between align-items-start mb-3">
          <div>
            <h4 class="card-title card-title-dash">
              <i class="mdi mdi-account-group" style="color: #10B981;"></i> 
              Administración de Usuarios
            </h4>
            <p class="card-subtitle card-subtitle-dash">Gestiona los usuarios del sistema y sus roles</p>
          </div>
          <div>
            <button type="button" class="btn btn-primary" style="background-color: #10B981; border-color: #10B981;" data-bs-toggle="modal" data-bs-target="#modalCreateUser">
              <i class="mdi mdi-account-plus"></i> Nuevo Usuario
            </button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover" id="users-table">
            <thead>
              <tr>
                <th style="color: #1F2937;">ID</th>
                <th style="color: #1F2937;">Foto</th>
                <th style="color: #1F2937;">Nombre</th>
                <th style="color: #1F2937;">Email</th>
                <th style="color: #1F2937;">Rol</th>
                <th style="color: #1F2937;">Registro</th>
                <th style="color: #1F2937; text-align: center;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
              <tr>
                <td>{{ $user->id }}</td>
                <td>
                  <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40" style="object-fit: cover; border: 2px solid #10B981;">
                </td>
                <td class="fw-semibold" style="color: #1F2937;">{{ $user->name }}</td>
                <td style="color: #6B7280;">{{ $user->email }}</td>
                <td>
                  @if($user->role)
                    <span class="badge px-3 py-2" style="background-color: {{ $user->role->slug == 'admin' ? '#10B981' : '#3B82F6' }}; border-radius: 20px;">
                      {{ $user->role->name }}
                    </span>
                  @else
                    <span class="badge bg-secondary px-3 py-2" style="border-radius: 20px;">Sin rol</span>
                  @endif
                </td>
                <td style="color: #6B7280; font-size: 13px;">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="text-center">
                  <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-edit p-2 rounded-circle d-flex align-items-center justify-content-center" 
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}"
                            data-role="{{ $user->role_id }}"
                            data-url="{{ route('admin.users.update', $user) }}"
                            style="width: 36px; height: 36px; background-color: #3B82F6; border: none; transition: all 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#2563EB'"
                            onmouseout="this.style.backgroundColor='#3B82F6'"
                            title="Editar usuario">
                      <i class="mdi mdi-pencil" style="font-size: 18px; color: white;"></i>
                    </button>
                    <button type="button" class="btn btn-delete p-2 rounded-circle d-flex align-items-center justify-content-center" 
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-url="{{ route('admin.users.destroy', $user) }}"
                            style="width: 36px; height: 36px; background-color: #EF4444; border: none; transition: all 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#DC2626'"
                            onmouseout="this.style.backgroundColor='#EF4444'"
                            title="Eliminar usuario">
                      <i class="mdi mdi-delete" style="font-size: 18px; color: white;"></i>
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

{{-- Incluir modales --}}
@include('admin.usuarios.administracion.modals.create')
@include('admin.usuarios.administracion.modals.edit')
@endsection

@section('css')
{{-- Solo DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

{{-- Toastr CSS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
  .gap-2 {
    gap: 0.5rem;
  }
  .table img {
    object-fit: cover;
  }
  .dataTables_wrapper {
    padding: 20px 0;
  }
  .dataTables_length select {
    padding: 5px 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
  }
  .dataTables_filter input {
    padding: 5px 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    margin-left: 10px;
  }
  .paginate_button {
    padding: 5px 10px !important;
    border-radius: 5px !important;
    margin: 0 2px !important;
  }
  .paginate_button.current {
    background-color: #10B981 !important;
    border-color: #10B981 !important;
    color: white !important;
  }
</style>
@endsection

@section('js')
{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- DataTables JS --}}
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

{{-- Toastr JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function() {
    // ==================== DATATABLES EN ESPAÑOL ====================
    $('#users-table').DataTable({
      language: {
        "decimal": "",
        "emptyTable": "No hay datos disponibles en la tabla",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "Mostrando 0 a 0 de 0 registros",
        "infoFiltered": "(filtrado de _MAX_ registros totales)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ registros",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "No se encontraron resultados",
        "paginate": {
          "first": "Primero",
          "last": "Último",
          "next": "Siguiente",
          "previous": "Anterior"
        },
        "aria": {
          "sortAscending": ": activar para ordenar columna ascendente",
          "sortDescending": ": activar para ordenar columna descendente"
        },
        "buttons": {
          "copy": "Copiar",
          "colvis": "Visibilidad",
          "collection": "Colección",
          "colvisRestore": "Restaurar visibilidad",
          "copySuccess": {
            "1": "Copiada 1 fila al portapapeles",
            "_": "Copiadas %d filas al portapapeles"
          },
          "copyTitle": "Copiar al portapapeles",
          "csv": "CSV",
          "excel": "Excel",
          "pageLength": {
            "-1": "Mostrar todas las filas",
            "_": "Mostrar %d filas"
          },
          "pdf": "PDF",
          "print": "Imprimir"
        }
      },
      pageLength: 10,
      lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
      order: [[0, 'desc']],
      responsive: true,
      columnDefs: [
        { orderable: false, targets: [1, 6] }
      ]
    });
    
    // ==================== TOASTR CONFIG ====================
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };
    
    // ==================== MOSTRAR MENSAJES DE SESIÓN ====================
    @if(session('success'))
      toastr.success('{{ session('success') }}', '¡Éxito!');
    @endif
    
    @if(session('error'))
      toastr.error('{{ session('error') }}', '¡Error!');
    @endif
    
    // ==================== EDITAR USUARIO ====================
    $('.btn-edit').on('click', function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var email = $(this).data('email');
      var roleId = $(this).data('role');
      var url = $(this).data('url');
      
      $('#edit_name').val(name);
      $('#edit_email').val(email);
      $('#edit_role_id').val(roleId);
      $('#formEditUser').attr('action', url);
      
      $('#edit_password').val('');
      $('#edit_password_confirmation').val('');
      $('.invalid-feedback').hide();
      $('.is-invalid').removeClass('is-invalid');
      
      $('#modalEditUser').modal('show');
    });
    
    // ==================== ELIMINAR USUARIO ====================
    $('.btn-delete').on('click', function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var url = $(this).data('url');
      
      Swal.fire({
        title: '¿Eliminar usuario?',
        html: `Estás por eliminar a <strong>${name}</strong><br>Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: url,
            type: 'DELETE',
            data: {
              _token: '{{ csrf_token() }}'
            },
            success: function(response) {
              if (response.success) {
                toastr.success(response.message, '¡Eliminado!');
                setTimeout(function() {
                  location.reload();
                }, 1500);
              } else {
                toastr.error(response.message, '¡Error!');
              }
            },
            error: function(xhr) {
              toastr.error('Error al eliminar el usuario', '¡Error!');
            }
          });
        }
      });
    });
    
    // ==================== ENVÍO DE FORMULARIO CREATE (AJAX) ====================
    $('#formCreateUser').on('submit', function(e) {
      e.preventDefault();
      
      var form = $(this);
      var url = form.attr('action');
      var formData = form.serialize();
      
      form.find('.is-invalid').removeClass('is-invalid');
      form.find('.invalid-feedback').hide();
      
      $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        success: function(response) {
          if (response.success) {
            $('#modalCreateUser').modal('hide');
            toastr.success(response.message, '¡Usuario Creado!');
            setTimeout(function() {
              location.reload();
            }, 1500);
          }
        },
        error: function(xhr) {
          var errors = xhr.responseJSON.errors;
          if (errors) {
            $.each(errors, function(key, value) {
              var input = form.find('[name="' + key + '"]');
              input.addClass('is-invalid');
              input.siblings('.invalid-feedback').text(value[0]).show();
            });
            toastr.error('Por favor corrige los errores del formulario', 'Error de validación');
          } else {
            toastr.error('Error al crear el usuario', '¡Error!');
          }
        }
      });
    });
    
    // ==================== ENVÍO DE FORMULARIO EDIT (AJAX) ====================
    $('#formEditUser').on('submit', function(e) {
      e.preventDefault();
      
      var form = $(this);
      var url = form.attr('action');
      var formData = form.serialize();
      
      form.find('.is-invalid').removeClass('is-invalid');
      form.find('.invalid-feedback').hide();
      
      $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        success: function(response) {
          if (response.success) {
            $('#modalEditUser').modal('hide');
            toastr.success(response.message, '¡Usuario Actualizado!');
            setTimeout(function() {
              location.reload();
            }, 1500);
          }
        },
        error: function(xhr) {
          var errors = xhr.responseJSON.errors;
          if (errors) {
            $.each(errors, function(key, value) {
              var input = form.find('[name="' + key + '"]');
              input.addClass('is-invalid');
              input.siblings('.invalid-feedback').text(value[0]).show();
            });
            toastr.error('Por favor corrige los errores del formulario', 'Error de validación');
          } else {
            toastr.error('Error al actualizar el usuario', '¡Error!');
          }
        }
      });
    });
    
    // ==================== LIMPIAR MODALES ====================
    $('#modalCreateUser').on('hidden.bs.modal', function() {
      $('#formCreateUser')[0].reset();
      $('#formCreateUser').find('.is-invalid').removeClass('is-invalid');
      $('#formCreateUser').find('.invalid-feedback').hide();
    });
    
    $('#modalEditUser').on('hidden.bs.modal', function() {
      $('#formEditUser').find('.is-invalid').removeClass('is-invalid');
      $('#formEditUser').find('.invalid-feedback').hide();
    });
  });
</script>
@endsection