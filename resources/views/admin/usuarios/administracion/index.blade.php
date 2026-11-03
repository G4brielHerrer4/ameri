{{-- resources/views/admin/usuarios/administracion/index.blade.php --}}
@extends('layouts.plantilla_maestra')

@section('title', 'Administración de Usuarios')

@section('content')
<div class="users-page">

  {{-- ===== HEADER ===== --}}
  <div class="users-header">
    <div class="users-header-info">
      <div class="users-header-icon">
        <i class="mdi mdi-account-group"></i>
      </div>
      <div class="min-w-0">
        <h1 class="users-title">Administración de Usuarios</h1>
        <p class="users-subtitle">Gestiona los usuarios del sistema y sus roles</p>
      </div>
    </div>

    <button type="button"
            class="btn-new-user"
            data-bs-toggle="modal"
            data-bs-target="#modalCreateUser">
      <i class="mdi mdi-account-plus-outline"></i>
      <span class="d-none d-sm-inline">Nuevo Usuario</span>
      <span class="d-inline d-sm-none">Nuevo</span>
    </button>
  </div>

  {{-- ===== CARD TABLA ===== --}}
  <div class="users-card">
    <div class="table-responsive">
      <table class="table users-table w-100" id="users-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Rol</th>
            <th>Registro</th>
            <th>Estado</th>
            <th class="text-center" style="width:170px;">Acciones</th>
          </tr>
        </thead>

        <tbody>
          @foreach($users as $user)
            <tr>
              <td class="fw-semibold user-id">
                #{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}
              </td>

              <td>
                <div class="user-avatar-wrap">
                  <img src="{{ $user->profile_photo_url }}"
                       alt="{{ $user->name }}"
                       class="user-avatar">
                </div>
              </td>

              <td>
                <div class="user-name">{{ $user->name }}</div>
              </td>

              <td>
                <span class="user-meta">{{ $user->email }}</span>
              </td>

              <td>
                <span class="user-meta">{{ $user->telefono ?: 'No registrado' }}</span>
              </td>

              <td>
                <span class="user-meta">{{ $user->direccion ?: 'No registrada' }}</span>
              </td>

              <td>
                @if($user->role)
                  <span class="role-badge {{ $user->role->slug === 'admin' ? 'role-admin' : 'role-user' }}">
                    <i class="mdi {{ $user->role->slug === 'admin' ? 'mdi-shield-account' : 'mdi-account' }}"></i>
                    {{ $user->role->name }}
                  </span>
                @else
                  <span class="role-badge role-none">
                    <i class="mdi mdi-alert-circle-outline"></i>
                    Sin rol
                  </span>
                @endif
              </td>

              <td>
                <span class="user-meta user-date">
                  <i class="mdi mdi-calendar-outline"></i>
                  {{ $user->created_at->format('d/m/Y') }}
                </span>
              </td>

              <td>
                @if($user->estado)
                  <span class="status-badge status-active">
                    <span class="status-dot"></span>
                    Activo
                  </span>
                @else
                  <span class="status-badge status-inactive">
                    <span class="status-dot"></span>
                    Inactivo
                  </span>
                @endif
              </td>

              <td class="text-center">
                <div class="action-group">

                  {{-- EDITAR --}}
                  <button type="button"
                          class="action-btn action-edit btn-edit"
                          data-id="{{ $user->id }}"
                          data-name="{{ $user->name }}"
                          data-email="{{ $user->email }}"
                          data-role="{{ $user->role_id }}"
                          data-telefono="{{ $user->telefono }}"
                          data-direccion="{{ $user->direccion }}"
                          data-url="{{ route('admin.users.update', $user) }}"
                          title="Editar usuario"
                          aria-label="Editar usuario">
                    <i class="mdi mdi-pencil-outline"></i>
                  </button>

                  {{-- ELIMINAR --}}
                  <button type="button"
                          class="action-btn action-delete btn-delete"
                          data-id="{{ $user->id }}"
                          data-name="{{ $user->name }}"
                          data-url="{{ route('admin.users.destroy', $user) }}"
                          title="Eliminar usuario"
                          aria-label="Eliminar usuario">
                    <i class="mdi mdi-delete-outline"></i>
                  </button>

                  {{-- ACTIVAR / INACTIVAR --}}
                  <button type="button"
                          class="action-btn {{ $user->estado ? 'action-warning' : 'action-success' }} btn-toggle-estado"
                          data-id="{{ $user->id }}"
                          data-name="{{ $user->name }}"
                          data-estado="{{ (int) $user->estado }}"
                          data-url="{{ route('admin.users.estado', $user) }}"
                          title="{{ $user->estado ? 'Inactivar usuario' : 'Activar usuario' }}"
                          aria-label="{{ $user->estado ? 'Inactivar usuario' : 'Activar usuario' }}">
                    <i class="mdi {{ $user->estado ? 'mdi-account-cancel-outline' : 'mdi-account-check-outline' }}"></i>
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

@include('admin.usuarios.administracion.modals.create')
@include('admin.usuarios.administracion.modals.edit')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
  /* ============================================================
     PÁGINA
     ============================================================ */
  .users-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    animation: usersFadeIn 0.5s cubic-bezier(0.22, 1, 0.36, 1);
  }

  @keyframes usersFadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ============================================================
     HEADER
     ============================================================ */
  .users-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1.25rem;
  }

  .users-header-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 0;
  }

  .users-header-icon {
    width: 52px;
    height: 52px;
    border-radius: var(--r-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--surface-alpha);
    border: 1px solid var(--line);
    color: var(--cyan);
    font-size: 1.5rem;
    box-shadow: 0 0 24px -10px var(--glow);
    transition: all 0.35s var(--ease);
  }

  .users-header:hover .users-header-icon {
    border-color: var(--line-strong);
    box-shadow: 0 0 28px -6px var(--glow);
    transform: scale(1.04);
  }

  .users-title {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--ink);
    margin: 0 0 0.25rem;
    letter-spacing: -0.01em;
  }

  .users-subtitle {
    font-size: 0.85rem;
    color: var(--ink-muted);
    margin: 0;
  }

  /* Botón nuevo usuario (outline premium) */
  .btn-new-user {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.7rem 1.4rem;
    border-radius: var(--r-pill);
    border: 1px solid var(--line-strong);
    background: var(--surface-alpha);
    color: var(--cyan);
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 0.88rem;
    letter-spacing: 0.02em;
    cursor: pointer;
    transition: all 0.35s var(--ease);
    position: relative;
    overflow: hidden;
  }

  .btn-new-user::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, transparent 30%, var(--glow-soft) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.7s var(--ease);
  }

  .btn-new-user:hover::before { transform: translateX(100%); }

  .btn-new-user:hover {
    border-color: var(--cyan);
    background: var(--glow-soft);
    box-shadow: 0 0 24px -6px var(--glow);
    transform: translateY(-2px);
  }

  .btn-new-user .mdi { font-size: 1.1rem; }

  /* ============================================================
     CARD
     ============================================================ */
  .users-card {
    position: relative;
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    padding: 1.5rem;
    box-shadow: 0 24px 60px -40px var(--shadow);
    overflow: hidden;
  }

  .users-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent);
    opacity: 0.5;
  }

  /* ============================================================
     TABLA
     ============================================================ */
  .users-table {
    background: transparent !important;
    border-collapse: separate !important;
    border-spacing: 0;
    color: var(--ink) !important;
    margin: 0 !important;
    width: 100% !important;
  }

  .users-table thead th {
    background: var(--bg-2) !important;
    color: var(--ink-muted) !important;
    border-bottom: 1px solid var(--line) !important;
    font-family: var(--font-display);
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 0.85rem 0.9rem !important;
    white-space: nowrap;
  }

  .users-table thead th:first-child {
    border-top-left-radius: var(--r-md);
    border-bottom-left-radius: var(--r-md);
  }

  .users-table thead th:last-child {
    border-top-right-radius: var(--r-md);
    border-bottom-right-radius: var(--r-md);
  }

  .users-table tbody td {
    background: transparent !important;
    border-top: 1px solid var(--line) !important;
    color: var(--ink) !important;
    padding: 0.9rem 0.9rem !important;
    vertical-align: middle;
    font-size: 0.88rem;
    transition: background 0.25s var(--ease);
  }

  .users-table tbody tr {
    transition: background 0.25s var(--ease);
  }

  .users-table tbody tr:hover > td {
    background: var(--surface-alpha) !important;
  }

  /* ============================================================
     CELDAS
     ============================================================ */
  .user-id {
    color: var(--ink-muted);
    font-size: 0.82rem;
    font-family: var(--font-display);
    letter-spacing: 0.02em;
  }

  .user-avatar-wrap {
    position: relative;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    padding: 2px;
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    box-shadow: 0 0 14px -6px var(--glow);
    transition: all 0.3s var(--ease);
  }

  .user-avatar-wrap:hover {
    box-shadow: 0 0 20px -4px var(--glow);
    transform: scale(1.06);
  }

  .user-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    border: 2px solid var(--bg-1);
  }

  .user-name {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.9rem;
    line-height: 1.3;
  }

  .user-meta {
    color: var(--ink-muted);
    font-size: 0.84rem;
    line-height: 1.4;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
  }

  .user-date {
    color: var(--ink-faint);
    font-size: 0.8rem;
  }

  .user-date .mdi {
    font-size: 0.9rem;
    color: var(--cyan-dim);
  }

  /* ============================================================
     BADGES DE ROL
     ============================================================ */
  .role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.7rem;
    border-radius: var(--r-pill);
    font-size: 0.75rem;
    font-weight: 600;
    font-family: var(--font-display);
    letter-spacing: 0.02em;
    white-space: nowrap;
    border: 1px solid transparent;
    transition: all 0.3s var(--ease);
  }

  .role-badge .mdi { font-size: 0.9rem; }

  .role-admin {
    background: var(--glow-soft);
    border-color: var(--line-strong);
    color: var(--cyan);
  }

  .role-user {
    background: rgba(96, 165, 250, 0.1);
    border-color: rgba(96, 165, 250, 0.35);
    color: #60a5fa;
  }

  html[data-theme="light"] .role-user {
    background: rgba(37, 99, 235, 0.08);
    border-color: rgba(37, 99, 235, 0.28);
    color: #1e40af;
  }

  .role-none {
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.35);
    color: #f59e0b;
  }

  html[data-theme="light"] .role-none {
    background: rgba(217, 119, 6, 0.08);
    border-color: rgba(217, 119, 6, 0.28);
    color: #b45309;
  }

  /* ============================================================
     BADGES DE ESTADO
     ============================================================ */
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.3rem 0.7rem 0.3rem 0.55rem;
    border-radius: var(--r-pill);
    font-size: 0.75rem;
    font-weight: 600;
    font-family: var(--font-display);
    letter-spacing: 0.02em;
    white-space: nowrap;
    border: 1px solid transparent;
  }

  .status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
    position: relative;
  }

  .status-active {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.35);
    color: #10b981;
  }

  .status-active .status-dot {
    background: #10b981;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6);
    animation: statusPulse 2s infinite;
  }

  @keyframes statusPulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5); }
    70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
  }

  .status-inactive {
    background: rgba(255, 85, 112, 0.1);
    border-color: rgba(255, 85, 112, 0.35);
    color: var(--error);
  }

  .status-inactive .status-dot {
    background: var(--error);
  }

  html[data-theme="light"] .status-active {
    background: rgba(5, 150, 105, 0.08);
    border-color: rgba(5, 150, 105, 0.3);
    color: #047857;
  }

  html[data-theme="light"] .status-inactive {
    background: rgba(217, 53, 79, 0.08);
    border-color: rgba(217, 53, 79, 0.3);
    color: #b91c1c;
  }

  /* ============================================================
     BOTONES DE ACCIÓN (outline)
     ============================================================ */
  .action-group {
    display: inline-flex;
    gap: 0.4rem;
    align-items: center;
    justify-content: center;
  }

  .action-btn {
    width: 34px;
    height: 34px;
    border-radius: var(--r-sm);
    border: 1px solid var(--line);
    background: transparent;
    color: var(--ink-muted);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s var(--ease);
    position: relative;
    overflow: hidden;
  }

  .action-btn .mdi { font-size: 1.05rem; }

  .action-btn::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle, var(--glow-soft) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s var(--ease);
  }

  .action-btn:hover::after { opacity: 1; }

  .action-btn:hover {
    transform: translateY(-2px);
  }

  .action-btn:active {
    transform: translateY(0);
  }

  /* Editar */
  .action-edit:hover {
    border-color: var(--cyan);
    color: var(--cyan);
    box-shadow: 0 0 16px -4px var(--glow);
  }

  /* Eliminar */
  .action-delete {
    border-color: rgba(255, 85, 112, 0.25);
    color: var(--error);
  }
  .action-delete:hover {
    border-color: var(--error);
    background: var(--error-bg);
    box-shadow: 0 0 16px -4px rgba(255, 85, 112, 0.5);
  }

  /* Activar (success outline) */
  .action-success {
    border-color: rgba(16, 185, 129, 0.3);
    color: #10b981;
  }
  .action-success:hover {
    border-color: #10b981;
    background: rgba(16, 185, 129, 0.1);
    box-shadow: 0 0 16px -4px rgba(16, 185, 129, 0.5);
  }

  /* Inactivar (warning outline) */
  .action-warning {
    border-color: rgba(245, 158, 11, 0.3);
    color: #f59e0b;
  }
  .action-warning:hover {
    border-color: #f59e0b;
    background: rgba(245, 158, 11, 0.1);
    box-shadow: 0 0 16px -4px rgba(245, 158, 11, 0.5);
  }

  /* ============================================================
     DATATABLES
     ============================================================ */
  .dataTables_wrapper,
  .dataTables_wrapper * {
    color: var(--ink) !important;
  }

  .dataTables_wrapper {
    padding: 0.25rem 0;
    background: transparent !important;
  }

  .dataTables_wrapper .row:first-child,
  .dataTables_wrapper .row:last-child {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 0.75rem 1rem;
    margin: 0 0 1rem !important;
    padding: 0 !important;
  }

  .dataTables_wrapper .row:last-child {
    margin: 1rem 0 0 !important;
  }

  .dataTables_wrapper .row:first-child > [class*="col-"],
  .dataTables_wrapper .row:last-child > [class*="col-"] {
    display: flex !important;
    align-items: center !important;
    flex: 0 0 auto !important;
    width: auto !important;
    max-width: none !important;
    padding: 0 !important;
  }

  .dataTables_wrapper .dataTables_length label,
  .dataTables_wrapper .dataTables_filter label {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.5rem;
    font-weight: 500;
    font-size: 0.86rem;
    color: var(--ink-muted) !important;
    margin: 0 !important;
    line-height: 1.4;
  }

  .dataTables_wrapper .dataTables_length,
  .dataTables_wrapper .dataTables_filter {
    color: var(--ink-muted) !important;
    font-size: 0.86rem;
    margin: 0 !important;
    padding: 0 !important;
    float: none !important;
    text-align: left !important;
  }

  .dataTables_wrapper .dataTables_length select {
    background-color: var(--bg-2) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-md);
    padding: 0.4rem 2rem 0.4rem 0.75rem;
    font-size: 0.85rem;
    min-width: 72px;
    margin: 0 !important;
  }

  .dataTables_wrapper .dataTables_length select option {
    background: var(--bg-2);
    color: var(--ink);
  }

  .dataTables_wrapper .dataTables_filter input {
    background-color: var(--bg-2) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-md);
    padding: 0.42rem 0.75rem;
    font-size: 0.85rem;
    margin: 0 0 0 0.5rem !important;
    min-width: 200px;
    transition: all 0.25s var(--ease);
  }

  .dataTables_wrapper .dataTables_filter input:focus,
  .dataTables_wrapper .dataTables_length select:focus {
    border-color: var(--cyan) !important;
    box-shadow: 0 0 0 3px var(--glow-soft) !important;
    outline: none;
  }

  .dataTables_wrapper .dataTables_filter input::placeholder {
    color: var(--ink-faint) !important;
  }

  .dataTables_wrapper .dataTables_info {
    color: var(--ink-muted) !important;
    font-size: 0.84rem;
    padding: 0 !important;
    margin: 0 !important;
  }

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
    background: var(--bg-1) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink-muted) !important;
    border-radius: var(--r-sm) !important;
    padding: 0.4rem 0.7rem !important;
    margin: 0 !important;
    min-width: 34px;
    text-align: center;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.25s var(--ease);
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled):not(.current) {
    border-color: var(--line-strong) !important;
    color: var(--cyan) !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.current,
  .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: var(--cyan) !important;
    border-color: var(--cyan) !important;
    color: var(--cyan-ink) !important;
    font-weight: 700;
    box-shadow: 0 0 12px var(--glow);
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
  .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
    color: var(--ink-faint) !important;
    border-color: var(--line) !important;
    background: transparent !important;
    opacity: 0.45;
    cursor: not-allowed;
  }

  /* Sort icons */
  table.dataTable thead .sorting:before,
  table.dataTable thead .sorting:after,
  table.dataTable thead .sorting_asc:before,
  table.dataTable thead .sorting_asc:after,
  table.dataTable thead .sorting_desc:before,
  table.dataTable thead .sorting_desc:after {
    color: var(--ink-faint) !important;
    opacity: 0.6;
  }

  table.dataTable thead .sorting_asc:before,
  table.dataTable thead .sorting_desc:after {
    color: var(--cyan) !important;
    opacity: 1;
  }

  /* Responsive */
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
    box-shadow: 0 0 10px rgba(255, 85, 112, 0.35);
  }

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
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-family: var(--font-display);
  }

  table.dataTable > tbody > tr.child span.dtr-data {
    color: var(--ink);
  }

  /* ============================================================
     RESPONSIVE
     ============================================================ */
  @media (max-width: 991.98px) {
    .users-card { padding: 1.25rem; }
  }

  @media (max-width: 767.98px) {
    .users-title { font-size: 1.2rem; }
    .users-subtitle { font-size: 0.8rem; }
    .users-header-icon { width: 46px; height: 46px; font-size: 1.3rem; }

    .btn-new-user {
      width: 100%;
      justify-content: center;
      padding: 0.75rem 1.25rem;
    }

    .users-card { padding: 1rem; border-radius: var(--r-md); }

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
      margin: 0.35rem 0 0 !important;
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

    .action-btn { width: 32px; height: 32px; }
  }

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

    .user-avatar-wrap { width: 38px; height: 38px; }
    .role-badge, .status-badge { font-size: 0.72rem; padding: 0.25rem 0.6rem; }
  }

  /* ============================================================
     Reduced motion
     ============================================================ */
  @media (prefers-reduced-motion: reduce) {
    .users-page,
    .users-header-icon,
    .btn-new-user,
    .action-btn,
    .user-avatar-wrap,
    .status-active .status-dot {
      animation: none !important;
      transition: none !important;
    }
  }
</style>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

{{-- Tu JS original intacto --}}
<script>
$(document).ready(function() {
  const table = $('#users-table').DataTable({
    language: {
      decimal: '',
      emptyTable: 'No hay usuarios registrados',
      info: 'Mostrando _START_ a _END_ de _TOTAL_ usuarios',
      infoEmpty: 'Mostrando 0 a 0 de 0 usuarios',
      infoFiltered: '(filtrado de _MAX_ usuarios totales)',
      lengthMenu: 'Mostrar _MENU_',
      loadingRecords: 'Cargando...',
      processing: 'Procesando...',
      search: 'Buscar:',
      zeroRecords: 'No se encontraron usuarios',
      paginate: {
        first: '<i class="mdi mdi-chevron-double-left"></i>',
        last: '<i class="mdi mdi-chevron-double-right"></i>',
        next: '<i class="mdi mdi-chevron-right"></i>',
        previous: '<i class="mdi mdi-chevron-left"></i>'
      },
      aria: {
        sortAscending: ': activar para ordenar ascendente',
        sortDescending: ': activar para ordenar descendente'
      }
    },
    pageLength: 5,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
    order: [[0, 'desc']],
    responsive: { details: { type: 'inline', target: 0 } },
    autoWidth: false,
    columnDefs: [
      { orderable: false, targets: [1, 9] },
      { responsivePriority: 1, targets: [0, 2, 9] },
      { responsivePriority: 2, targets: [6, 8] },
      { responsivePriority: 3, targets: [3, 4] },
      { responsivePriority: 4, targets: [7, 5] },
      { responsivePriority: 5, targets: [1] }
    ]
  });

  if (typeof toastr !== 'undefined') {
    toastr.options = {
      closeButton: true,
      progressBar: true,
      newestOnTop: true,
      positionClass: 'toast-top-right',
      preventDuplicates: true,
      timeOut: 4000
    };
    @if(session('success')) toastr.success(@json(session('success')), '¡Éxito!'); @endif
    @if(session('error'))   toastr.error(@json(session('error')), '¡Error!');     @endif
  }

  $('#users-table').on('click', '.btn-edit', function() {
    const $btn = $(this);
    $('#edit_name').val($btn.attr('data-name') || '');
    $('#edit_email').val($btn.attr('data-email') || '');
    $('#edit_role_id').val($btn.attr('data-role') || '');
    $('#edit_telefono').val($btn.attr('data-telefono') || '');
    $('#edit_direccion').val($btn.attr('data-direccion') || '');
    $('#formEditUser').attr('action', $btn.attr('data-url'));
    $('#edit_password, #edit_password_confirmation').val('');
    $('#formEditUser .is-invalid').removeClass('is-invalid');
    $('#formEditUser .invalid-feedback').hide();
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditUser')).show();
  });

  $('#users-table').on('click', '.btn-delete', function() {
    const $btn = $(this);
    const name = $btn.attr('data-name');
    const url = $btn.attr('data-url');

    if (typeof Swal === 'undefined') {
      alert('No se ha cargado SweetAlert2. Revisa las librerías del diseño principal.');
      return;
    }

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
        url: url, type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function(response) {
          if (response.success) {
            if (typeof toastr !== 'undefined') toastr.success(response.message, '¡Eliminado!');
            table.row($row).remove().draw(false);
          } else {
            if (typeof toastr !== 'undefined') toastr.error(response.message || 'No se pudo eliminar', '¡Error!');
            $btn.prop('disabled', false).html('<i class="mdi mdi-delete-outline"></i>');
          }
        },
        error: function(xhr) {
          const message = xhr.responseJSON?.message || 'Error al eliminar';
          if (typeof toastr !== 'undefined') toastr.error(message, '¡Error!'); else alert(message);
          $btn.prop('disabled', false).html('<i class="mdi mdi-delete-outline"></i>');
        }
      });
    });
  });

  $('#users-table').on('click', '.btn-toggle-estado', function() {
    const $btn = $(this);
    const url = $btn.attr('data-url');
    const nombre = $btn.attr('data-name');
    const estaActivo = Number($btn.attr('data-estado')) === 1;
    const accion = estaActivo ? 'inactivar' : 'activar';

    if (typeof Swal === 'undefined') {
      alert('No se ha cargado SweetAlert2. Revisa las librerías del diseño principal.');
      return;
    }

    Swal.fire({
      title: `¿${estaActivo ? 'Inactivar' : 'Activar'} usuario?`,
      html: `¿Deseas ${accion} a <strong>${nombre}</strong>?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: estaActivo ? 'Sí, inactivar' : 'Sí, activar',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: estaActivo ? '#f59e0b' : '#10b981',
      cancelButtonColor: '#55617d',
      reverseButtons: true,
      focusCancel: true,
      background: getComputedStyle(document.documentElement).getPropertyValue('--bg-1').trim() || '#0a0f1c',
      color: getComputedStyle(document.documentElement).getPropertyValue('--ink').trim() || '#e6ecff'
    }).then((result) => {
      if (!result.isConfirmed) return;
      const htmlOriginal = $btn.html();
      $btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>');
      $.ajax({
        url: url, type: 'PATCH',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function(response) {
          if (response.success) {
            if (typeof toastr !== 'undefined') toastr.success(response.message, '¡Éxito!');
            setTimeout(() => location.reload(), 800);
          } else {
            if (typeof toastr !== 'undefined') toastr.error(response.message || 'No se pudo cambiar el estado.', '¡Error!');
            $btn.prop('disabled', false).html(htmlOriginal);
          }
        },
        error: function(xhr) {
          const message = xhr.responseJSON?.message || 'No se pudo cambiar el estado del usuario.';
          if (typeof toastr !== 'undefined') toastr.error(message, '¡Error!'); else alert(message);
          $btn.prop('disabled', false).html(htmlOriginal);
        }
      });
    });
  });

  $('#formCreateUser').on('submit', function(e) {
    e.preventDefault();
    const $form = $(this);
    const $btn = $form.find('button[type="submit"]');
    const html = $btn.html();
    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').hide();
    $btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Creando...');
    $.ajax({
      url: $form.attr('action'), type: 'POST', data: $form.serialize(),
      success: function(response) {
        if (response.success) {
          bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCreateUser')).hide();
          if (typeof toastr !== 'undefined') toastr.success(response.message, '¡Usuario creado!');
          setTimeout(() => location.reload(), 1200);
        } else {
          if (typeof toastr !== 'undefined') toastr.error(response.message || 'No se pudo crear el usuario.', '¡Error!');
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
          if (typeof toastr !== 'undefined') toastr.error('Corrige los errores del formulario', 'Validación');
        } else {
          const message = xhr.responseJSON?.message || 'Error al crear el usuario.';
          if (typeof toastr !== 'undefined') toastr.error(message, '¡Error!'); else alert(message);
        }
      },
      complete: function() { $btn.prop('disabled', false).html(html); }
    });
  });

  $('#formEditUser').on('submit', function(e) {
    e.preventDefault();
    const $form = $(this);
    const $btn = $form.find('button[type="submit"]');
    const html = $btn.html();
    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').hide();
    $btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Guardando...');
    $.ajax({
      url: $form.attr('action'), type: 'POST', data: $form.serialize(),
      success: function(response) {
        if (response.success) {
          bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditUser')).hide();
          if (typeof toastr !== 'undefined') toastr.success(response.message, '¡Actualizado!');
          setTimeout(() => location.reload(), 1200);
        } else {
          if (typeof toastr !== 'undefined') toastr.error(response.message || 'No se pudo actualizar el usuario.', '¡Error!');
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
          if (typeof toastr !== 'undefined') toastr.error('Corrige los errores del formulario', 'Validación');
        } else {
          const message = xhr.responseJSON?.message || 'Error al actualizar el usuario.';
          if (typeof toastr !== 'undefined') toastr.error(message, '¡Error!'); else alert(message);
        }
      },
      complete: function() { $btn.prop('disabled', false).html(html); }
    });
  });

  document.getElementById('modalCreateUser')?.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('formCreateUser');
    if (!form) return;
    form.reset();
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
  });

  document.getElementById('modalEditUser')?.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('formEditUser');
    if (!form) return;
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
  });
});
</script>
@endsection