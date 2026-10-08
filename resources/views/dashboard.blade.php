{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.plantilla_maestra')

@section('title', 'Dashboard - AMERI')

@push('styles')
<style>
  /* =====================================================
     HEADER DE BIENVENIDA
     ===================================================== */
  .welcome-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
    padding: 1.35rem 1.5rem;
    border-radius: var(--r-lg);
    background: linear-gradient(135deg,
      color-mix(in srgb, var(--cyan) 12%, var(--bg-1)) 0%,
      var(--bg-1) 70%);
    border: 1px solid var(--line);
    position: relative;
    overflow: hidden;
  }
  .welcome-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 280px;
    height: 280px;
    border-radius: 50%;
    background: radial-gradient(circle,
      color-mix(in srgb, var(--cyan) 18%, transparent),
      transparent 70%);
    pointer-events: none;
  }
  .welcome-info { position: relative; z-index: 1; }
  .welcome-info .greeting {
    font-family: var(--font-display);
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--cyan);
    margin: 0 0 0.35rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
  }
  .welcome-info h1 {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: clamp(1.4rem, 3vw, 1.85rem);
    color: var(--ink);
    margin: 0 0 0.35rem;
    line-height: 1.15;
  }
  .welcome-info h1 .highlight { color: var(--cyan); }
  .welcome-info p {
    color: var(--ink-muted);
    margin: 0;
    font-size: 0.9rem;
  }
  .welcome-actions {
    display: flex;
    gap: 0.65rem;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
  }
  .btn-welcome {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.65rem 1.2rem;
    border-radius: var(--r-pill);
    border: none;
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink);
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    text-decoration: none;
    box-shadow: 0 4px 14px -4px var(--glow);
    white-space: nowrap;
  }
  .btn-welcome:hover {
    transform: translateY(-2px);
    color: var(--cyan-ink);
    box-shadow: 0 10px 24px -6px var(--glow), 0 0 0 1px var(--cyan);
  }
  .btn-welcome .mdi { font-size: 1rem; }

  .btn-welcome-secondary {
    background: var(--bg-2);
    color: var(--ink);
    border: 1px solid var(--line);
    box-shadow: none;
  }
  .btn-welcome-secondary:hover {
    color: var(--cyan);
    border-color: var(--line-strong);
    box-shadow: 0 6px 16px -8px var(--glow);
  }

  /* =====================================================
     SECCIÓN TÍTULOS
     ===================================================== */
  .section-title {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--ink);
    margin: 0 0 1rem;
  }
  .section-title .mdi {
    color: var(--cyan);
    font-size: 1.15rem;
  }
  .section-title .line {
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, var(--line), transparent);
  }

  /* =====================================================
     STATS CARDS
     ===================================================== */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 1rem;
    margin-bottom: 1.75rem;
  }

  .stat-card {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    padding: 1.15rem 1.25rem;
    position: relative;
    overflow: hidden;
    transition: transform .25s var(--ease), border-color .25s var(--ease), box-shadow .25s var(--ease);
    text-decoration: none;
    display: block;
    color: inherit;
    min-width: 0;
  }
  .stat-card:hover {
    transform: translateY(-3px);
    border-color: color-mix(in srgb, var(--stat-color) 50%, var(--line));
    box-shadow: 0 20px 40px -28px var(--shadow), 0 0 0 1px color-mix(in srgb, var(--stat-color) 30%, transparent);
    color: inherit;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--stat-color, var(--cyan));
    box-shadow: 0 0 20px -4px var(--stat-color);
  }

  .stat-card::after {
    content: '';
    position: absolute;
    top: -40px;
    right: -40px;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: radial-gradient(circle,
      color-mix(in srgb, var(--stat-color) 20%, transparent),
      transparent 70%);
    opacity: 0;
    transition: opacity .3s var(--ease);
    pointer-events: none;
  }
  .stat-card:hover::after { opacity: 1; }

  .stat-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    position: relative;
    z-index: 1;
  }
  .stat-card-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--ink-muted);
    margin: 0;
  }
  .stat-card-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--r-md);
    background: color-mix(in srgb, var(--stat-color) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--stat-color) 30%, transparent);
    color: var(--stat-color);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
    transition: transform .3s var(--ease);
  }
  .stat-card:hover .stat-card-icon {
    transform: scale(1.08) rotate(-5deg);
  }

  .stat-card-value {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.85rem;
    line-height: 1;
    color: var(--ink);
    margin: 0 0 0.35rem;
    position: relative;
    z-index: 1;
  }
  .stat-card-sub {
    font-size: 0.78rem;
    color: var(--ink-muted);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    position: relative;
    z-index: 1;
  }
  .stat-card-sub .mdi { font-size: 0.9rem; }
  .stat-card-sub.up { color: var(--success); }
  .stat-card-sub.down { color: var(--error); }
  .stat-card-sub.neutral { color: var(--ink-muted); }

  /* Colores por tipo */
  .stat-productos   { --stat-color: var(--cyan); }
  .stat-proveedores { --stat-color: var(--success); }
  .stat-admins      { --stat-color: #a78bfa; }
  .stat-notif       { --stat-color: var(--warning); }
  .stat-ventas      { --stat-color: #f472b6; }
  .stat-stock       { --stat-color: #ff8c42; }

  /* =====================================================
     GRID DOS COLUMNAS
     ===================================================== */
  .dashboard-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.75rem;
  }
  @media (max-width: 991.98px) {
    .dashboard-grid { grid-template-columns: 1fr; }
  }

  /* =====================================================
     CARD GENÉRICA
     ===================================================== */
  .dash-card {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: 0 20px 40px -28px var(--shadow);
    min-width: 0;
  }
  .dash-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 1.1rem 1.25rem;
    border-bottom: 1px solid var(--line);
    background: linear-gradient(180deg,
      color-mix(in srgb, var(--cyan) 4%, transparent),
      transparent);
    flex-wrap: wrap;
  }
  .dash-card-head-info {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    min-width: 0;
  }
  .dash-card-head-info .mdi {
    color: var(--cyan);
    font-size: 1.15rem;
  }
  .dash-card-head-info h6 {
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--ink);
    margin: 0;
  }
  .dash-card-head-info p {
    font-size: 0.75rem;
    color: var(--ink-muted);
    margin: 0;
  }
  .dash-card-link {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--cyan);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: gap .2s ease;
    white-space: nowrap;
  }
  .dash-card-link:hover {
    gap: 0.45rem;
    color: var(--cyan);
  }
  .dash-card-body { padding: 1.1rem 1.25rem; }

  /* =====================================================
     LISTA DE ACTIVIDAD / NOTIFICACIONES
     ===================================================== */
  .activity-list {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
  }
  .activity-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 0.5rem;
    border-radius: var(--r-sm);
    text-decoration: none;
    color: inherit;
    transition: background .2s ease;
  }
  .activity-item:hover {
    background: var(--surface-alpha);
    color: inherit;
  }
  .activity-item + .activity-item {
    border-top: 1px solid var(--line);
  }
  .activity-icon {
    width: 34px;
    height: 34px;
    border-radius: var(--r-sm);
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    background: color-mix(in srgb, var(--cyan) 12%, transparent);
    border: 1px solid var(--line-strong);
    color: var(--cyan);
  }
  .activity-icon.tipo-prestado {
    background: color-mix(in srgb, var(--warning) 12%, transparent);
    border-color: color-mix(in srgb, var(--warning) 40%, transparent);
    color: var(--warning);
  }
  .activity-body { flex: 1; min-width: 0; }
  .activity-title {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.85rem;
    margin: 0 0 0.2rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex-wrap: wrap;
  }
  .activity-title .dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--cyan);
    box-shadow: 0 0 6px var(--cyan);
    flex-shrink: 0;
  }
  .activity-msg {
    font-size: 0.78rem;
    color: var(--ink-muted);
    margin: 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .activity-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    font-size: 0.7rem;
    color: var(--ink-faint);
    margin-top: 0.35rem;
  }
  .activity-meta .mdi { font-size: 0.75rem; }
  .activity-meta .from { color: var(--cyan); font-weight: 600; }

  .activity-empty {
    text-align: center;
    padding: 2.25rem 1rem;
  }
  .activity-empty .mdi {
    font-size: 2.5rem;
    color: var(--ink-faint);
    opacity: 0.4;
    display: block;
    margin-bottom: 0.5rem;
  }
  .activity-empty p {
    color: var(--ink-muted);
    font-size: 0.85rem;
    margin: 0;
  }

  /* =====================================================
     MINI INVENTARIO STATUS
     ===================================================== */
  .inv-status-list {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
  }
  .inv-status-item {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.85rem 0.95rem;
    border-radius: var(--r-md);
    background: var(--bg-2);
    border: 1px solid var(--line);
    position: relative;
    overflow: hidden;
    transition: transform .2s ease;
  }
  .inv-status-item:hover { transform: translateX(2px); }
  .inv-status-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--inv-color, var(--cyan));
  }
  .inv-status-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--r-sm);
    background: color-mix(in srgb, var(--inv-color) 14%, transparent);
    border: 1px solid color-mix(in srgb, var(--inv-color) 35%, transparent);
    color: var(--inv-color);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
  }
  .inv-status-info { flex: 1; min-width: 0; }
  .inv-status-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--ink-muted);
    margin: 0 0 0.15rem;
  }
  .inv-status-value {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.15rem;
    color: var(--ink);
    line-height: 1;
  }
  .inv-status-value small {
    font-size: 0.7rem;
    color: var(--ink-faint);
    font-weight: 500;
    margin-left: 0.3rem;
    font-family: var(--font-body);
  }

  .inv-normal      { --inv-color: var(--success); }
  .inv-bajo        { --inv-color: var(--warning); }
  .inv-sin-stock   { --inv-color: var(--error); }
  .inv-sobre       { --inv-color: #ff8c42; }
  .inv-sin-limites { --inv-color: var(--ink-faint); }

  /* =====================================================
     QUICK ACTIONS
     ===================================================== */
  .quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 0.75rem;
  }
  .quick-action {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem 0.75rem;
    border-radius: var(--r-md);
    background: var(--bg-2);
    border: 1px solid var(--line);
    color: var(--ink);
    text-decoration: none;
    text-align: center;
    transition: all .25s var(--ease);
    min-height: 100px;
    position: relative;
    overflow: hidden;
  }
  .quick-action:hover {
    transform: translateY(-3px);
    border-color: var(--line-strong);
    color: var(--cyan);
    background: color-mix(in srgb, var(--cyan) 6%, var(--bg-2));
    box-shadow: 0 12px 24px -12px var(--glow);
  }
  .quick-action::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center top,
      color-mix(in srgb, var(--cyan) 15%, transparent),
      transparent 60%);
    opacity: 0;
    transition: opacity .3s ease;
    pointer-events: none;
  }
  .quick-action:hover::before { opacity: 1; }

  .quick-action-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--r-md);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    border: 1px solid var(--line-strong);
    color: var(--cyan);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    transition: transform .3s ease;
    position: relative;
    z-index: 1;
  }
  .quick-action:hover .quick-action-icon {
    transform: scale(1.08) rotate(-5deg);
  }
  .quick-action-label {
    font-size: 0.78rem;
    font-weight: 600;
    position: relative;
    z-index: 1;
    line-height: 1.2;
  }

  /* =====================================================
     RESPONSIVE
     ===================================================== */
  @media (max-width: 991.98px) {
    .stats-grid { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
    .dashboard-grid { grid-template-columns: 1fr; }
  }

  @media (max-width: 767.98px) {
    .welcome-section { padding: 1.1rem 1.15rem; }
    .welcome-actions { width: 100%; }
    .btn-welcome { flex: 1; justify-content: center; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
    .stat-card { padding: 1rem; }
    .stat-card-value { font-size: 1.5rem; }
    .quick-actions { grid-template-columns: repeat(2, 1fr); }
  }

  @media (max-width: 575.98px) {
    .stats-grid { grid-template-columns: 1fr; }
    .quick-actions { grid-template-columns: 1fr; }
    .welcome-info h1 { font-size: 1.35rem; }
  }
</style>
@endpush


@section('content')
@php
  /* ====================================================
     DATOS PARA EL DASHBOARD
     ==================================================== */
  $totalProductos    = \App\Models\Producto::count();
  $totalProveedores  = \App\Models\Proveedor::count();
  $totalAdmins       = \App\Models\User::whereHas('role', function ($q) {
                          $q->where('slug', 'admin');
                        })->count();
  $notifNoLeidas     = \App\Models\Notificacion::where('user_id', auth()->id())
                          ->where('leida', false)
                          ->count();
  $totalNotif        = \App\Models\Notificacion::where('user_id', auth()->id())->count();

  $ultimasNotif = \App\Models\Notificacion::with('fromUser')
                      ->where('user_id', auth()->id())
                      ->latest()
                      ->take(5)
                      ->get();

  /* Resumen de inventario (mismo criterio que la vista de stocks) */
  $sinStock = 0;
  $stockBajo = 0;
  $normal = 0;
  $sobreexistencia = 0;

  $productosInventario = \App\Models\Producto::with(['tipoProducto.categoria'])->get();
  foreach ($productosInventario as $p) {
      $stockTotal = (float) ($p->stock_total ?? 0);
      $stockMin   = (float) ($p->stock_minimo ?? 0);
      $stockMax   = (float) ($p->stock_maximo ?? 0);
      $piezasCaja = (float) ($p->piezas_por_caja ?? 0);

      $tipoNombre = mb_strtolower(trim((string) ($p->tipoProducto?->nombre ?? '')), 'UTF-8');
      $esCaja = in_array($tipoNombre, ['cerámica','ceramica','porcelanato']) && $piezasCaja > 0;
      $stockParaEstado = $esCaja ? ($stockTotal / $piezasCaja) : $stockTotal;

      if ($stockTotal <= 0) {
          $sinStock++;
      } elseif ($stockMin > 0 && $stockParaEstado < $stockMin) {
          $stockBajo++;
      } elseif ($stockMax > 0 && $stockParaEstado > $stockMax) {
          $sobreexistencia++;
      } else {
          $normal++;
      }
  }
@endphp

<div class="container-fluid">

  {{-- ==================== BIENVENIDA ==================== --}}
  <div class="welcome-section">
    <div class="welcome-info">
      <p class="greeting">
        <i class="mdi mdi-hand-wave-outline"></i>
        Bienvenido de nuevo
      </p>
      <h1>
        Hola, <span class="highlight">{{ Auth::user()->name }}</span>
      </h1>
      <p>Esto es lo que está pasando en AMERI hoy · {{ now()->translatedFormat('l, d \\d\\e F \\d\\e Y') }}</p>
    </div>

    <div class="welcome-actions">
      <a href="{{ route('admin.ventas.create') }}" class="btn-welcome">
        <i class="mdi mdi-cart-plus"></i>
        Nueva venta
      </a>
      <a href="{{ route('admin.compras.create') }}" class="btn-welcome btn-welcome-secondary">
        <i class="mdi mdi-truck-plus-outline"></i>
        Nueva compra
      </a>
    </div>
  </div>

  {{-- ==================== STATS ==================== --}}
  <h6 class="section-title">
    <i class="mdi mdi-chart-box-outline"></i>
    Resumen general
    <span class="line"></span>
  </h6>

  <div class="stats-grid">

    <a href="{{ route('admin.suministros.index') }}" class="stat-card stat-productos">
      <div class="stat-card-head">
        <p class="stat-card-label">Productos</p>
        <span class="stat-card-icon"><i class="mdi mdi-cube-outline"></i></span>
      </div>
      <p class="stat-card-value">{{ number_format($totalProductos) }}</p>
      <p class="stat-card-sub neutral">
        <i class="mdi mdi-package-variant"></i>
        en catálogo
      </p>
    </a>

    <a href="{{ route('admin.suministros.index') }}" class="stat-card stat-proveedores">
      <div class="stat-card-head">
        <p class="stat-card-label">Proveedores</p>
        <span class="stat-card-icon"><i class="mdi mdi-truck-outline"></i></span>
      </div>
      <p class="stat-card-value">{{ number_format($totalProveedores) }}</p>
      <p class="stat-card-sub neutral">
        <i class="mdi mdi-account-multiple-outline"></i>
        registrados
      </p>
    </a>

    <a href="{{ route('admin.users.index') }}" class="stat-card stat-admins">
      <div class="stat-card-head">
        <p class="stat-card-label">Administradores</p>
        <span class="stat-card-icon"><i class="mdi mdi-shield-account-outline"></i></span>
      </div>
      <p class="stat-card-value">{{ number_format($totalAdmins) }}</p>
      <p class="stat-card-sub neutral">
        <i class="mdi mdi-account-cog-outline"></i>
        activos
      </p>
    </a>

    <a href="{{ route('admin.notificaciones.index') }}" class="stat-card stat-notif">
      <div class="stat-card-head">
        <p class="stat-card-label">Notificaciones</p>
        <span class="stat-card-icon"><i class="mdi mdi-bell-outline"></i></span>
      </div>
      <p class="stat-card-value">{{ number_format($notifNoLeidas) }}</p>
      <p class="stat-card-sub {{ $notifNoLeidas > 0 ? 'down' : 'neutral' }}">
        <i class="mdi mdi-email-mark-as-unread"></i>
        {{ $notifNoLeidas > 0 ? 'sin leer' : 'todo al día' }}
      </p>
    </a>

  </div>

  {{-- ==================== GRID DOS COLUMNAS ==================== --}}
  <div class="dashboard-grid">

    {{-- ==================== ACTIVIDAD RECIENTE ==================== --}}
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-head-info">
          <i class="mdi mdi-bell-ring-outline"></i>
          <div>
            <h6>Actividad reciente</h6>
            <p>Tus últimas notificaciones</p>
          </div>
        </div>
        <a href="{{ route('admin.notificaciones.index') }}" class="dash-card-link">
          Ver todas <i class="mdi mdi-arrow-right"></i>
        </a>
      </div>

      <div class="dash-card-body">
        @if($ultimasNotif->count() > 0)
          <div class="activity-list">
            @foreach($ultimasNotif as $n)
              @php
                $esPrestado = $n->tipo === 'stock_prestado';
                $tipoClase = $esPrestado ? 'tipo-prestado' : 'tipo-devuelto';
                $tipoIcono = $esPrestado
                  ? 'mdi-arrow-down-bold-circle-outline'
                  : 'mdi-arrow-up-bold-circle-outline';
              @endphp
              <a href="{{ route('admin.notificaciones.index') }}" class="activity-item">
                <div class="activity-icon {{ $tipoClase }}">
                  <i class="mdi {{ $tipoIcono }}"></i>
                </div>
                <div class="activity-body">
                  <p class="activity-title">
                    @if(!$n->leida)
                      <span class="dot"></span>
                    @endif
                    {{ $n->titulo }}
                  </p>
                  <p class="activity-msg">{{ \Illuminate\Support\Str::limit($n->mensaje, 90) }}</p>
                  <div class="activity-meta">
                    @if($n->fromUser)
                      <span class="from">
                        <i class="mdi mdi-account-circle-outline"></i>
                        {{ $n->fromUser->name }}
                      </span>
                    @endif
                    <span>
                      <i class="mdi mdi-clock-outline"></i>
                      {{ $n->created_at->diffForHumans() }}
                    </span>
                  </div>
                </div>
              </a>
            @endforeach
          </div>
        @else
          <div class="activity-empty">
            <i class="mdi mdi-bell-sleep-outline"></i>
            <p>No hay actividad reciente</p>
          </div>
        @endif
      </div>
    </div>

    {{-- ==================== ESTADO DE INVENTARIO ==================== --}}
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-head-info">
          <i class="mdi mdi-warehouse"></i>
          <div>
            <h6>Estado del inventario</h6>
            <p>Resumen de stock</p>
          </div>
        </div>
        <a href="{{ route('admin.stocks.index') }}" class="dash-card-link">
          Ver inventario <i class="mdi mdi-arrow-right"></i>
        </a>
      </div>

      <div class="dash-card-body">
        <div class="inv-status-list">

          <div class="inv-status-item inv-normal">
            <div class="inv-status-icon"><i class="mdi mdi-check-circle-outline"></i></div>
            <div class="inv-status-info">
              <p class="inv-status-label">Normal</p>
              <p class="inv-status-value">
                {{ $normal }}
                <small>{{ $normal === 1 ? 'producto' : 'productos' }}</small>
              </p>
            </div>
          </div>

          <div class="inv-status-item inv-bajo">
            <div class="inv-status-icon"><i class="mdi mdi-alert-outline"></i></div>
            <div class="inv-status-info">
              <p class="inv-status-label">Stock bajo</p>
              <p class="inv-status-value">
                {{ $stockBajo }}
                <small>{{ $stockBajo === 1 ? 'producto' : 'productos' }}</small>
              </p>
            </div>
          </div>

          <div class="inv-status-item inv-sin-stock">
            <div class="inv-status-icon"><i class="mdi mdi-close-circle-outline"></i></div>
            <div class="inv-status-info">
              <p class="inv-status-label">Sin stock</p>
              <p class="inv-status-value">
                {{ $sinStock }}
                <small>{{ $sinStock === 1 ? 'producto' : 'productos' }}</small>
              </p>
            </div>
          </div>

          @if($sobreexistencia > 0)
            <div class="inv-status-item inv-sobre">
              <div class="inv-status-icon"><i class="mdi mdi-alert-circle-outline"></i></div>
              <div class="inv-status-info">
                <p class="inv-status-label">Sobreexistencia</p>
                <p class="inv-status-value">
                  {{ $sobreexistencia }}
                  <small>{{ $sobreexistencia === 1 ? 'producto' : 'productos' }}</small>
                </p>
              </div>
            </div>
          @endif

        </div>
      </div>
    </div>

  </div>

  {{-- ==================== ACCIONES RÁPIDAS ==================== --}}
  <h6 class="section-title">
    <i class="mdi mdi-lightning-bolt-outline"></i>
    Acciones rápidas
    <span class="line"></span>
  </h6>

  <div class="quick-actions">

    <a href="{{ route('admin.ventas.create') }}" class="quick-action">
      <div class="quick-action-icon"><i class="mdi mdi-cart-plus"></i></div>
      <span class="quick-action-label">Nueva venta</span>
    </a>

    <a href="{{ route('admin.compras.create') }}" class="quick-action">
      <div class="quick-action-icon"><i class="mdi mdi-truck-plus-outline"></i></div>
      <span class="quick-action-label">Nueva compra</span>
    </a>

    <a href="{{ route('admin.suministros.index') }}" class="quick-action">
      <div class="quick-action-icon"><i class="mdi mdi-package-variant-closed"></i></div>
      <span class="quick-action-label">Suministros</span>
    </a>

    <a href="{{ route('admin.stocks.index') }}" class="quick-action">
      <div class="quick-action-icon"><i class="mdi mdi-warehouse"></i></div>
      <span class="quick-action-label">Inventario</span>
    </a>

    <a href="{{ route('admin.users.index') }}" class="quick-action">
      <div class="quick-action-icon"><i class="mdi mdi-account-multiple-outline"></i></div>
      <span class="quick-action-label">Usuarios</span>
    </a>

    <a href="{{ route('admin.notificaciones.index') }}" class="quick-action">
      <div class="quick-action-icon"><i class="mdi mdi-bell-outline"></i></div>
      <span class="quick-action-label">Notificaciones</span>
    </a>

  </div>

</div>
@endsection