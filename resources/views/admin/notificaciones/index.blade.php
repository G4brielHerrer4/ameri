@extends('layouts.plantilla_maestra')

@section('title', 'Notificaciones')

@push('styles')
<style>
  /* ==================== HEADER ==================== */
  .notif-header {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; flex-wrap: wrap; margin-bottom: 1.75rem;
  }
  .notif-header h3 {
    font-family: var(--font-display); font-weight: 600; color: var(--ink);
    display: flex; align-items: center; gap: 0.65rem; margin: 0;
  }
  .notif-header h3 .icon-wrap {
    width: 44px; height: 44px; border-radius: var(--r-md);
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); display: inline-flex; align-items: center;
    justify-content: center; font-size: 1.35rem;
    box-shadow: 0 6px 20px -6px var(--glow);
    position: relative;
  }
  .notif-header h3 .icon-wrap .header-badge {
    position: absolute;
    top: -4px; right: -4px;
    min-width: 20px; height: 20px;
    padding: 0 5px;
    border-radius: var(--r-pill);
    background: linear-gradient(135deg, #ff5570, #e63e5a);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    font-family: var(--font-display);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--bg);
    box-shadow: 0 4px 12px -4px rgba(255, 85, 112, 0.6);
  }

  .notif-header p { color: var(--ink-muted); margin: 0.25rem 0 0; font-size: 0.88rem; }

  .btn-secundario {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.6rem 1.15rem; border-radius: var(--r-pill);
    border: 1px solid var(--line); background: var(--surface-alpha);
    color: var(--ink); font-size: 0.82rem; font-weight: 700;
    cursor: pointer; transition: all 0.3s var(--ease);
    text-decoration: none;
    font-family: var(--font-body);
  }
  .btn-secundario:hover {
    border-color: var(--line-strong); color: var(--cyan);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px -6px var(--glow);
  }
  .btn-secundario .mdi { font-size: 1.05rem; color: var(--cyan); }

  /* ==================== FILTROS ==================== */
  .filter-bar {
    display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;
  }
  .filter-chip {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.45rem 1rem; border-radius: var(--r-pill);
    border: 1px solid var(--line); background: var(--surface-alpha);
    color: var(--ink-muted); font-size: 0.82rem; font-weight: 600;
    cursor: pointer; transition: all 0.25s var(--ease);
    text-decoration: none;
  }
  .filter-chip:hover {
    border-color: var(--line-strong); color: var(--cyan);
  }
  .filter-chip.active {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); border-color: transparent;
    box-shadow: 0 4px 14px -4px var(--glow);
  }
  .filter-chip .mdi { font-size: 1rem; }
  .filter-chip .count {
    background: rgba(0,0,0,0.15);
    padding: 0.1rem 0.4rem;
    border-radius: var(--r-pill);
    font-size: 0.7rem;
    font-weight: 700;
  }
  .filter-chip.active .count { background: rgba(0,0,0,0.2); }

  /* ==================== PANEL ==================== */
  .panel {
    background: var(--bg-1); border: 1px solid var(--line);
    border-radius: var(--r-lg); overflow: hidden;
    box-shadow: 0 20px 40px -28px var(--shadow);
  }
  .panel-body { padding: 0; }

  /* ==================== ITEM NOTIFICACIÓN ==================== */
  .notif-item {
    display: flex; align-items: flex-start; gap: 1rem;
    padding: 1.15rem 1.35rem;
    border-bottom: 1px solid var(--line);
    transition: all 0.25s var(--ease);
    position: relative;
  }
  .notif-item:last-child { border-bottom: none; }
  .notif-item:hover { background: var(--surface-alpha); }

  .notif-item.no-leida {
    background: color-mix(in srgb, var(--cyan) 4%, transparent);
  }
  .notif-item.no-leida::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, var(--cyan), var(--cyan-2));
    box-shadow: 0 0 12px var(--glow);
  }
  .notif-item.no-leida:hover {
    background: color-mix(in srgb, var(--cyan) 7%, transparent);
  }

  /* Ícono según tipo */
  .notif-icon {
    width: 44px; height: 44px; border-radius: var(--r-md);
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.35rem; flex-shrink: 0;
    border: 1px solid var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 8%, transparent);
    color: var(--cyan);
    transition: transform 0.3s var(--ease);
  }
  .notif-item:hover .notif-icon { transform: scale(1.05); }

  .notif-icon.tipo-prestado {
    background: color-mix(in srgb, var(--warning) 12%, transparent);
    border-color: color-mix(in srgb, var(--warning) 40%, transparent);
    color: var(--warning);
  }
  .notif-icon.tipo-devuelto {
    background: color-mix(in srgb, var(--cyan) 8%, transparent);
    border-color: var(--line-strong);
    color: var(--cyan);
  }
  .notif-icon.tipo-info {
    background: var(--surface-alpha);
    border-color: var(--line);
    color: var(--ink-muted);
  }

  /* Cuerpo */
  .notif-body { flex: 1; min-width: 0; }

  .notif-titulo {
    font-family: var(--font-display);
    font-weight: 600; color: var(--ink);
    font-size: 0.95rem; margin: 0 0 0.35rem;
    display: flex; align-items: center; gap: 0.5rem;
    flex-wrap: wrap;
    line-height: 1.25;
  }
  .notif-titulo .badge-nueva {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); font-size: 0.6rem; font-weight: 700;
    padding: 0.15rem 0.5rem; border-radius: var(--r-pill);
    text-transform: uppercase; letter-spacing: 0.05em;
    box-shadow: 0 4px 10px -4px var(--glow);
  }

  .notif-mensaje {
    font-size: 0.86rem; color: var(--ink-muted);
    line-height: 1.55; margin: 0 0 0.65rem;
  }

  .notif-meta {
    display: flex; align-items: center; gap: 0.85rem;
    font-size: 0.73rem; color: var(--ink-faint);
    flex-wrap: wrap;
  }
  .notif-meta .mdi { font-size: 0.85rem; }
  .notif-meta > span {
    display: inline-flex; align-items: center; gap: 0.3rem;
  }
  .notif-meta .from {
    color: var(--cyan); font-weight: 600;
  }
  .notif-meta .venta-link {
    color: var(--cyan);
    text-decoration: none;
    font-weight: 600;
    padding: 0.15rem 0.5rem;
    border-radius: var(--r-pill);
    background: var(--glow-soft);
    transition: all 0.25s var(--ease);
  }
  .notif-meta .venta-link:hover {
    background: color-mix(in srgb, var(--cyan) 18%, transparent);
    color: var(--cyan);
    transform: translateY(-1px);
  }

  /* Acciones */
  .notif-actions {
    display: flex; flex-direction: column; gap: 0.35rem;
    flex-shrink: 0; align-self: flex-start;
  }
  .notif-action-btn {
    width: 36px; height: 36px; border-radius: var(--r-sm);
    border: 1px solid var(--line); background: var(--surface-alpha);
    color: var(--ink-muted); cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    transition: all 0.25s var(--ease); font-size: 1.05rem;
    padding: 0;
    text-decoration: none;
  }
  .notif-action-btn:hover { transform: translateY(-2px); }

  .notif-action-btn.ver:hover {
    color: var(--cyan);
    border-color: var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    box-shadow: 0 0 14px -4px var(--glow);
  }
  .notif-action-btn.leer:hover {
    color: var(--cyan);
    border-color: var(--line-strong);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    box-shadow: 0 0 14px -4px var(--glow);
  }
  .notif-action-btn.eliminar:hover {
    color: var(--error);
    border-color: color-mix(in srgb, var(--error) 50%, transparent);
    background: color-mix(in srgb, var(--error) 10%, transparent);
    box-shadow: 0 0 14px -4px rgba(255, 85, 112, 0.4);
  }

  /* Empty state */
  .empty-state {
    padding: 4rem 2rem;
    text-align: center;
  }
  .empty-state .mdi {
    font-size: 4rem; color: var(--ink-faint);
    opacity: 0.4;
  }
  .empty-state h5 {
    font-family: var(--font-display);
    font-weight: 600; color: var(--ink);
    margin: 1rem 0 0.35rem;
    font-size: 1.05rem;
  }
  .empty-state p {
    color: var(--ink-muted); font-size: 0.88rem;
    margin: 0;
  }

  /* Responsive */
  @media (max-width: 767.98px) {
    .notif-item { padding: 1rem; gap: 0.75rem; }
    .notif-icon { width: 38px; height: 38px; font-size: 1.15rem; }
    .notif-actions {
      flex-direction: row;
      gap: 0.3rem;
      margin-top: 0.5rem;
      width: 100%;
      justify-content: flex-start;
    }
    .notif-body { width: 100%; }
    .notif-item {
      flex-wrap: wrap;
    }
    .notif-actions {
      order: 3;
    }
    .notif-action-btn { width: 34px; height: 34px; }
  }
</style>
@endpush

@section('content')
<div class="container-fluid">

  @php
    $totalNotif    = $notificaciones->count();
    $noLeidasTotal = $notificaciones->where('leida', false)->count();
    $leidasTotal   = $notificaciones->where('leida', true)->count();
  @endphp

  {{-- ==================== HEADER ==================== --}}
  <div class="notif-header">
    <div>
      <h3>
        <span class="icon-wrap">
          <i class="mdi mdi-bell-outline"></i>
          @if($noLeidasTotal > 0)
            <span class="header-badge">{{ $noLeidasTotal > 99 ? '99+' : $noLeidasTotal }}</span>
          @endif
        </span>
        Notificaciones
      </h3>
      <p>
        @if($noLeidasTotal > 0)
          Tienes <strong style="color:var(--cyan);">{{ $noLeidasTotal }}</strong>
          notificación{{ $noLeidasTotal !== 1 ? 'es' : '' }} sin leer
        @else
          Todo al día, no tienes notificaciones pendientes
        @endif
      </p>
    </div>

    @if($noLeidasTotal > 0)
      <form action="{{ route('admin.notificaciones.todas-leidas') }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn-secundario">
          <i class="mdi mdi-check-all"></i> Marcar todas como leídas
        </button>
      </form>
    @endif
  </div>

  {{-- ==================== FILTROS ==================== --}}
  <div class="filter-bar">
    <button type="button" class="filter-chip active" data-filter="all">
      <i class="mdi mdi-view-list"></i> Todas
      <span class="count">{{ $totalNotif }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="unread">
      <i class="mdi mdi-email-outline"></i> Sin leer
      <span class="count">{{ $noLeidasTotal }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="read">
      <i class="mdi mdi-email-open-outline"></i> Leídas
      <span class="count">{{ $leidasTotal }}</span>
    </button>
    <button type="button" class="filter-chip" data-filter="prestado">
      <i class="mdi mdi-arrow-down-bold-circle-outline"></i> Préstamos
    </button>
    <button type="button" class="filter-chip" data-filter="devuelto">
      <i class="mdi mdi-arrow-up-bold-circle-outline"></i> Devoluciones
    </button>
  </div>

  {{-- ==================== PANEL ==================== --}}
  <div class="panel">
    <div class="panel-body">
      @forelse($notificaciones as $n)
        <div class="notif-item {{ $n->leida ? '' : 'no-leida' }}"
             data-leida="{{ $n->leida ? '1' : '0' }}"
             data-tipo="{{ $n->tipo }}">

          {{-- Ícono --}}
          <div class="notif-icon tipo-{{ $n->tipo === 'stock_prestado' ? 'prestado' : ($n->tipo === 'stock_devuelto' ? 'devuelto' : 'info') }}">
            <i class="mdi
              @if($n->tipo === 'stock_prestado') mdi-arrow-down-bold-circle-outline
              @elseif($n->tipo === 'stock_devuelto') mdi-arrow-up-bold-circle-outline
              @else mdi-information-outline
              @endif"></i>
          </div>

          {{-- Cuerpo --}}
          <div class="notif-body">
            <h6 class="notif-titulo">
              {{ $n->titulo }}
              @if(!$n->leida)
                <span class="badge-nueva">Nueva</span>
              @endif
            </h6>

            <p class="notif-mensaje">{{ $n->mensaje }}</p>

            <div class="notif-meta">
              @if($n->fromUser)
                <span class="from">
                  <i class="mdi mdi-account"></i>
                  De: {{ $n->fromUser->name }}
                </span>
              @endif

              <span>
                <i class="mdi mdi-clock-outline"></i>
                {{ $n->created_at->diffForHumans() }}
              </span>

              @if($n->venta)
                <a href="{{ route('admin.ventas.show', $n->venta) }}" class="venta-link">
                  <i class="mdi mdi-file-document-outline"></i>
                  {{ $n->venta->numero_venta }}
                </a>
              @endif
            </div>
          </div>

          {{-- Acciones --}}
          <div class="notif-actions">
            {{-- Ver venta --}}
            @if($n->venta)
              <a href="{{ route('admin.ventas.show', $n->venta) }}"
                 class="notif-action-btn ver"
                 title="Ver venta">
                <i class="mdi mdi-file-eye-outline"></i>
              </a>
            @endif

            {{-- Marcar como leída --}}
            @if(!$n->leida)
              <form action="{{ route('admin.notificaciones.leida', $n) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="notif-action-btn leer" title="Marcar como leída">
                  <i class="mdi mdi-email-check-outline"></i>
                </button>
              </form>
            @endif

            {{-- Eliminar --}}
            <button type="button"
                    class="notif-action-btn eliminar"
                    title="Eliminar"
                    onclick='eliminarNotif({
                      id: {{ $n->id }},
                      titulo: @json($n->titulo)
                    })'>
              <i class="mdi mdi-delete-outline"></i>
            </button>

            {{-- Form oculto de eliminar --}}
            <form id="form-delete-notif-{{ $n->id }}"
                  action="{{ route('admin.notificaciones.destroy', $n) }}"
                  method="POST" class="d-none">
              @csrf
              @method('DELETE')
            </form>
          </div>
        </div>
      @empty
        <div class="empty-state">
          <i class="mdi mdi-bell-sleep-outline"></i>
          <h5>Sin notificaciones</h5>
          <p>Cuando otro administrador use tu stock, aparecerá aquí.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  /* ==================== SWEETALERT2 CONFIG ==================== */
  const swalConfig = {
    buttonsStyling: false,
    reverseButtons: true,
    focusCancel: true,
    customClass: {
      confirmButton: 'swal2-confirm',
      cancelButton: 'swal2-cancel',
    },
  };

  /* ==================== ELIMINAR NOTIFICACIÓN ==================== */
  function eliminarNotif({ id, titulo }) {
    Swal.fire({
      ...swalConfig,
      title: '¿Eliminar notificación?',
      html: `
        <p style="margin:0 0 0.5rem 0;">Vas a eliminar:</p>
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:0.95rem;color:var(--ink);margin:0 0 0.75rem 0;">
          "${titulo}"
        </p>
        <p style="font-size:0.82rem;color:var(--error);margin:0;">Esta acción no se puede deshacer.</p>
      `,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<i class="mdi mdi-delete"></i> Sí, eliminar',
      cancelButtonText: '<i class="mdi mdi-close"></i> Cancelar',
      customClass: {
        confirmButton: 'swal2-confirm btn-danger-swal',
        cancelButton: 'swal2-cancel',
      },
    }).then(r => {
      if (r.isConfirmed) {
        document.getElementById('form-delete-notif-' + id)?.submit();
      }
    });
  }

  /* ==================== FILTROS ==================== */
  document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.notif-item');
    const chips = document.querySelectorAll('.filter-chip');

    chips.forEach(chip => {
      chip.addEventListener('click', function () {
        chips.forEach(c => c.classList.remove('active'));
        this.classList.add('active');

        const filtro = this.dataset.filter;

        items.forEach(item => {
          const leida = item.dataset.leida === '1';
          const tipo  = item.dataset.tipo;

          let visible = true;

          if (filtro === 'unread')   visible = !leida;
          else if (filtro === 'read')     visible = leida;
          else if (filtro === 'prestado') visible = tipo === 'stock_prestado';
          else if (filtro === 'devuelto') visible = tipo === 'stock_devuelto';

          item.style.display = visible ? '' : 'none';
        });
      });
    });
  });

  /* ==================== TOASTS FLASH ==================== */
  toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 4500,
    extendedTimeOut: 1500,
    newestOnTop: true,
    preventDuplicates: true,
  };

  @if(session('success'))
    toastr.success('{{ session('success') }}', '¡Listo!');
  @endif

  @if(session('error'))
    toastr.error('{{ session('error') }}', 'Error');
  @endif
</script>

<style>
  /* Estilos de SweetAlert2 dentro de la vista */
  .swal2-popup {
    background: var(--bg-1) !important; color: var(--ink) !important;
    border: 1px solid var(--line-strong) !important;
    border-radius: var(--r-lg) !important;
    font-family: var(--font-body) !important;
  }
  .swal2-title {
    font-family: var(--font-display) !important;
    color: var(--ink) !important; font-weight: 600 !important;
  }
  .swal2-html-container { color: var(--ink-muted) !important; }
  .swal2-confirm, .swal2-cancel {
    font-weight: 700 !important;
    padding: 0.7rem 1.5rem !important;
    border-radius: var(--r-pill) !important;
    border: none !important;
    font-family: var(--font-body) !important;
  }
  .swal2-confirm {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
    color: var(--cyan-ink) !important;
  }
  .swal2-confirm.btn-danger-swal {
    background: linear-gradient(135deg, #ff5570, #e63e5a) !important;
    color: #fff !important;
  }
  .swal2-cancel {
    background: var(--bg-2) !important; color: var(--ink) !important;
    border: 1px solid var(--line) !important;
  }
  .swal2-container.swal2-backdrop-show {
    background: rgba(5, 8, 10, 0.65) !important;
    backdrop-filter: blur(4px);
  }
  .swal2-icon.swal2-warning {
    border-color: var(--warning) !important;
    color: var(--warning) !important;
  }
  .swal2-icon.swal2-success {
    border-color: var(--cyan) !important;
    color: var(--cyan) !important;
  }
  .swal2-icon.swal2-question {
    border-color: var(--cyan) !important;
    color: var(--cyan) !important;
  }
</style>
@endpush