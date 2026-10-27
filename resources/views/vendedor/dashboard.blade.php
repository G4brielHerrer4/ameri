{{-- resources/views/vendedor/dashboard.blade.php --}}
@extends('layouts.plantilla_maestra_ven')

@section('title', 'Dashboard - Vendedor')

@push('styles')
<style>
  /* =====================================================
     HEADER DE BIENVENIDA
     ===================================================== */
  .welcome-section {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1.5rem; flex-wrap: wrap; margin-bottom: 2rem;
    padding: 1.75rem 2rem; border-radius: var(--r-lg);
    background: linear-gradient(135deg, color-mix(in srgb, var(--cyan) 12%, var(--bg-1)) 0%, var(--bg-1) 70%);
    border: 1px solid var(--line); position: relative; overflow: hidden;
  }
  .welcome-section::before {
    content: ''; position: absolute; top: -50%; right: -10%; width: 300px; height: 300px;
    border-radius: 50%; background: radial-gradient(circle, color-mix(in srgb, var(--cyan) 20%, transparent), transparent 70%);
    pointer-events: none;
  }
  .welcome-info { position: relative; z-index: 1; }
  .welcome-info .greeting {
    font-family: var(--font-display); font-size: 0.78rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.1em; color: var(--cyan);
    margin: 0 0 0.35rem; display: flex; align-items: center; gap: 0.4rem;
  }
  .welcome-info h1 {
    font-family: var(--font-display); font-weight: 700;
    font-size: clamp(1.5rem, 3vw, 2rem); color: var(--ink); margin: 0 0 0.35rem; line-height: 1.15;
  }
  .welcome-info h1 .highlight { color: var(--cyan); }
  .welcome-info p { color: var(--ink-muted); margin: 0; font-size: 0.9rem; }
  .welcome-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; position: relative; z-index: 1; }
  .btn-welcome {
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.7rem 1.35rem; border-radius: var(--r-pill); border: none;
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); font-size: 0.88rem; font-weight: 700; cursor: pointer;
    transition: all 0.3s var(--ease); text-decoration: none;
    box-shadow: 0 4px 14px -4px var(--glow); white-space: nowrap;
  }
  .btn-welcome:hover { transform: translateY(-2px); color: var(--cyan-ink); box-shadow: 0 10px 24px -6px var(--glow); }
  .btn-welcome-secondary { background: var(--bg-2); color: var(--ink); border: 1px solid var(--line); box-shadow: none; }
  .btn-welcome-secondary:hover { color: var(--cyan); border-color: var(--line-strong); }

  /* =====================================================
     TITULOS DE SECCIÓN
     ===================================================== */
  .section-title {
    display: flex; align-items: center; gap: 0.55rem;
    font-family: var(--font-display); font-weight: 600; font-size: 1rem;
    color: var(--ink); margin: 0 0 1.25rem;
  }
  .section-title .mdi { color: var(--cyan); font-size: 1.25rem; }
  .section-title .line { flex: 1; height: 1px; background: linear-gradient(to right, var(--line), transparent); }

  /* =====================================================
     STATS CARDS
     ===================================================== */
  .stats-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem; margin-bottom: 2rem;
  }
  .stat-card {
    background: var(--bg-1); border: 1px solid var(--line); border-radius: var(--r-lg);
    padding: 1.25rem; position: relative; overflow: hidden;
    transition: all .25s var(--ease); text-decoration: none; display: flex; flex-direction: column;
  }
  .stat-card:hover { transform: translateY(-3px); border-color: color-mix(in srgb, var(--cyan) 50%, var(--line)); box-shadow: 0 15px 35px -20px var(--shadow); }
  .stat-card-icon {
    width: 44px; height: 44px; border-radius: var(--r-md);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--cyan) 30%, transparent);
    color: var(--cyan); display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.35rem; margin-bottom: 1rem; transition: transform .3s var(--ease);
  }
  .stat-card:hover .stat-card-icon { transform: scale(1.08) rotate(-5deg); }
  .stat-card-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-muted); margin-bottom: 0.25rem; }
  .stat-card-value { font-family: var(--font-display); font-weight: 700; font-size: 1.8rem; line-height: 1; color: var(--ink); margin: 0 0 0.35rem; }
  .stat-card-sub { font-size: 0.78rem; color: var(--ink-faint); display: flex; align-items: center; gap: 0.3rem; }

  /* Colores por tipo de stat */
  .stat-pendiente .stat-card-icon { background: color-mix(in srgb, var(--warning) 10%, transparent); border-color: color-mix(in srgb, var(--warning) 30%, transparent); color: var(--warning); }
  .stat-pendiente:hover { border-color: color-mix(in srgb, var(--warning) 50%, var(--line)); }
  .stat-pendiente:hover .stat-card-icon { color: var(--warning); }

  .stat-vendida .stat-card-icon { background: color-mix(in srgb, var(--cyan) 10%, transparent); border-color: color-mix(in srgb, var(--cyan) 30%, transparent); color: var(--cyan); }

  .stat-ingresos .stat-card-icon { background: color-mix(in srgb, var(--success) 10%, transparent); border-color: color-mix(in srgb, var(--success) 30%, transparent); color: var(--success); }
  .stat-ingresos:hover { border-color: color-mix(in srgb, var(--success) 50%, var(--line)); }

  /* =====================================================
     GRID PRINCIPAL
     ===================================================== */
  .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; margin-bottom: 2rem; }
  @media (max-width: 991.98px) { .dashboard-grid { grid-template-columns: 1fr; } }

  .dash-card {
    background: var(--bg-1); border: 1px solid var(--line); border-radius: var(--r-lg);
    overflow: hidden; box-shadow: 0 20px 40px -28px var(--shadow); display: flex; flex-direction: column;
  }
  .dash-card-head {
    display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
    padding: 1.15rem 1.35rem; border-bottom: 1px solid var(--line);
    background: linear-gradient(180deg, color-mix(in srgb, var(--cyan) 4%, transparent), transparent);
  }
  .dash-card-head-info { display: flex; align-items: center; gap: 0.6rem; }
  .dash-card-head-info .mdi { color: var(--cyan); font-size: 1.25rem; }
  .dash-card-head-info h6 { font-family: var(--font-display); font-weight: 600; font-size: 1rem; color: var(--ink); margin: 0; }
  .dash-card-head-info p { font-size: 0.75rem; color: var(--ink-muted); margin: 0; }
  .dash-card-link { font-size: 0.8rem; font-weight: 600; color: var(--cyan); text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; transition: gap .2s ease; }
  .dash-card-link:hover { gap: 0.45rem; color: var(--cyan); }
  .dash-card-body { padding: 1.25rem; flex: 1; }

  /* =====================================================
     ACTIVIDAD RECIENTE
     ===================================================== */
  .activity-list { display: flex; flex-direction: column; gap: 0.15rem; }
  .activity-item { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.85rem; border-radius: var(--r-md); text-decoration: none; color: inherit; transition: background .2s ease; }
  .activity-item:hover { background: var(--surface-alpha); }
  .activity-item + .activity-item { border-top: 1px solid var(--line); }
  .activity-icon { width: 36px; height: 36px; border-radius: var(--r-sm); flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; background: color-mix(in srgb, var(--cyan) 12%, transparent); color: var(--cyan); }
  .activity-icon.tipo-faltante { background: color-mix(in srgb, var(--warning) 12%, transparent); color: var(--warning); }
  .activity-icon.tipo-prestado { background: color-mix(in srgb, var(--error) 12%, transparent); color: var(--error); }
  .activity-body { flex: 1; min-width: 0; }
  .activity-title { font-weight: 600; color: var(--ink); font-size: 0.85rem; margin: 0 0 0.2rem; display: flex; align-items: center; gap: 0.4rem; }
  .activity-title .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--cyan); flex-shrink: 0; }
  .activity-msg { font-size: 0.78rem; color: var(--ink-muted); margin: 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  .activity-meta { display: flex; align-items: center; gap: 0.75rem; font-size: 0.7rem; color: var(--ink-faint); margin-top: 0.35rem; }
  .activity-meta .from { color: var(--cyan); font-weight: 600; }

  /* =====================================================
     CHART
     ===================================================== */
  .chart-container { position: relative; height: 300px; width: 100%; }

  /* =====================================================
     TOP PRODUCTOS
     ===================================================== */
  .top-productos { display: flex; flex-direction: column; gap: 0.65rem; }
  .top-producto-item {
    display: flex; align-items: center; gap: 0.85rem;
    padding: 0.75rem 0.85rem; border-radius: var(--r-md);
    background: var(--bg-2); border: 1px solid var(--line);
    transition: all .2s var(--ease);
  }
  .top-producto-item:hover { border-color: var(--line-strong); transform: translateX(2px); }
  .top-producto-rank {
    width: 30px; height: 30px; border-radius: var(--r-sm);
    background: color-mix(in srgb, var(--cyan) 12%, transparent);
    color: var(--cyan); font-family: var(--font-display); font-weight: 700;
    font-size: 0.85rem; display: inline-flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .top-producto-info { flex: 1; min-width: 0; }
  .top-producto-name { font-size: 0.85rem; font-weight: 600; color: var(--ink); margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .top-producto-meta { font-size: 0.72rem; color: var(--ink-muted); margin: 0.1rem 0 0; }
  .top-producto-total {
    font-family: var(--font-display); font-weight: 700; font-size: 0.85rem;
    color: var(--cyan); flex-shrink: 0; text-align: right;
  }

  /* =====================================================
     ESTADO DE VENTAS (cards inferiores)
     ===================================================== */
  .estado-ventas-list { display: flex; flex-direction: column; gap: 0.65rem; }
  .estado-ventas-item {
    display: flex; align-items: center; gap: 0.85rem;
    padding: 0.85rem 1rem; border-radius: var(--r-md);
    background: var(--bg-2); border: 1px solid var(--line);
    position: relative; overflow: hidden;
  }
  .estado-ventas-item::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; background: var(--ev-color);
  }
  .estado-ventas-icon {
    width: 38px; height: 38px; border-radius: var(--r-sm);
    background: color-mix(in srgb, var(--ev-color) 14%, transparent);
    color: var(--ev-color); display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
  }
  .estado-ventas-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); margin: 0 0 0.15rem; }
  .estado-ventas-value { font-family: var(--font-display); font-weight: 700; font-size: 1.25rem; color: var(--ink); line-height: 1; }
  .estado-ventas-value small { font-size: 0.72rem; color: var(--ink-faint); font-weight: 500; margin-left: 0.3rem; }

  .ev-pendiente { --ev-color: var(--warning); }
  .ev-vendida { --ev-color: var(--cyan); }
  .ev-cancelada { --ev-color: var(--error); }
  .ev-total { --ev-color: var(--ink-muted); }

  /* =====================================================
     ACCIONES RÁPIDAS
     ===================================================== */
  .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.85rem; }
  .quick-action {
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.6rem;
    padding: 1.25rem 0.75rem; border-radius: var(--r-md); background: var(--bg-2);
    border: 1px solid var(--line); color: var(--ink); text-decoration: none; text-align: center;
    transition: all .25s var(--ease);
  }
  .quick-action:hover { transform: translateY(-3px); border-color: var(--line-strong); color: var(--cyan); background: color-mix(in srgb, var(--cyan) 6%, var(--bg-2)); box-shadow: 0 12px 24px -12px var(--glow); }
  .quick-action-icon { width: 46px; height: 46px; border-radius: var(--r-md); background: color-mix(in srgb, var(--cyan) 10%, transparent); color: var(--cyan); display: inline-flex; align-items: center; justify-content: center; font-size: 1.4rem; transition: transform .3s ease; }
  .quick-action:hover .quick-action-icon { transform: scale(1.1) rotate(-5deg); }
  .quick-action-label { font-size: 0.82rem; font-weight: 600; }

  /* =====================================================
     RESPONSIVE
     ===================================================== */
  @media (max-width: 575.98px) {
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .quick-actions { grid-template-columns: 1fr 1fr; }
    .welcome-section { padding: 1.25rem; }
    .btn-welcome { width: 100%; justify-content: center; }
  }
</style>
@endpush

@section('content')
@php
  $userId = auth()->id();

  /* ====================================================
     STATS DEL VENDEDOR
     ==================================================== */
  $misVentas        = \App\Models\Venta::where('user_id', $userId)->get();
  $totalVentas      = $misVentas->count();
  $ventasPendientes = $misVentas->where('estado', 'pendiente')->count();
  $ventasVendidas   = $misVentas->where('estado', 'vendida')->count();
  $ventasCanceladas = $misVentas->where('estado', 'cancelada')->count();

  // Ingresos del mes (solo ventas vendidas)
  $ingresosMes = \App\Models\Venta::where('user_id', $userId)
      ->where('estado', 'vendida')
      ->whereMonth('fecha_venta', now()->month)
      ->whereYear('fecha_venta', now()->year)
      ->sum('total');

  $notifNoLeidas = \App\Models\Notificacion::where('user_id', $userId)->where('leida', false)->count();

  $ultimasNotif = \App\Models\Notificacion::with('fromUser')
      ->where('user_id', $userId)
      ->latest()
      ->take(5)
      ->get();

  /* ====================================================
     VENTAS DE LOS ÚLTIMOS 7 DÍAS (gráfico)
     ==================================================== */
  $dias = collect();
  for ($i = 6; $i >= 0; $i--) {
      $fecha = now()->subDays($i);
      $dias->push([
          'label' => $fecha->translatedFormat('D'),
          'fecha' => $fecha->toDateString(),
          'total' => 0,
      ]);
  }

  $ventas7dias = \App\Models\Venta::where('user_id', $userId)
      ->where('estado', 'vendida')
      ->whereDate('fecha_venta', '>=', now()->subDays(6)->toDateString())
      ->selectRaw('DATE(fecha_venta) as dia, SUM(total) as total')
      ->groupBy('dia')
      ->pluck('total', 'dia');

  $chartLabels = [];
  $chartData   = [];
  foreach ($dias as $d) {
      $chartLabels[] = ucfirst($d['label']);
      $chartData[]   = (float) ($ventas7dias[$d['fecha']] ?? 0);
  }

  $hayVentas7dias = array_sum($chartData) > 0;

    /* ====================================================
    TOP 5 PRODUCTOS VENDIDOS POR EL VENDEDOR
    (sin unidad_base — usamos venta_detalles.unidad)
    ==================================================== */
  $topProductos = \App\Models\VentaDetalle::query()
      ->join('ventas', 'ventas.id', '=', 'venta_detalles.venta_id')
      ->join('productos', 'productos.id', '=', 'venta_detalles.producto_id')
      ->where('ventas.user_id', $userId)
      ->where('ventas.estado', 'vendida')
      ->selectRaw('
          productos.id,
          productos.nombre,
          SUM(venta_detalles.cantidad_stock) as cantidad_total,
          SUM(venta_detalles.subtotal) as monto_total,
          COUNT(DISTINCT ventas.id) as num_ventas
      ')
      ->groupBy('productos.id', 'productos.nombre')
      ->orderByDesc('monto_total')
      ->take(5)
      ->get();
@endphp

<div class="container-fluid">

  {{-- ==================== BIENVENIDA ==================== --}}
  <div class="welcome-section">
    <div class="welcome-info">
      <p class="greeting"><i class="mdi mdi-account-tie"></i> Panel de vendedor</p>
      <h1>Hola, <span class="highlight">{{ Auth::user()->name }}</span></h1>
     <p>Resumen de tu actividad · {{ now()->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}</p>
    </div>
    <div class="welcome-actions">
      <a href="{{ route('vendedor.ventas.create') }}" class="btn-welcome"><i class="mdi mdi-cart-plus"></i> Nueva venta</a>
      <a href="{{ route('vendedor.ventas.index') }}" class="btn-welcome btn-welcome-secondary"><i class="mdi mdi-format-list-bulleted"></i> Mis ventas</a>
    </div>
  </div>

  {{-- ==================== STATS ==================== --}}
  <h6 class="section-title"><i class="mdi mdi-chart-box-outline"></i> Resumen general <span class="line"></span></h6>

  <div class="stats-grid">
    <a href="{{ route('vendedor.ventas.index') }}" class="stat-card">
      <div class="stat-card-icon"><i class="mdi mdi-cart-outline"></i></div>
      <div class="stat-card-label">Total ventas</div>
      <div class="stat-card-value">{{ number_format($totalVentas) }}</div>
      <div class="stat-card-sub"><i class="mdi mdi-format-list-bulleted"></i> registradas</div>
    </a>

    <a href="{{ route('vendedor.ventas.index') }}" class="stat-card stat-pendiente">
      <div class="stat-card-icon"><i class="mdi mdi-clock-outline"></i></div>
      <div class="stat-card-label">Pendientes</div>
      <div class="stat-card-value">{{ number_format($ventasPendientes) }}</div>
      <div class="stat-card-sub"><i class="mdi mdi-progress-clock"></i> por confirmar</div>
    </a>

    <a href="{{ route('vendedor.ventas.index') }}" class="stat-card stat-vendida">
      <div class="stat-card-icon"><i class="mdi mdi-check-circle-outline"></i></div>
      <div class="stat-card-label">Vendidas</div>
      <div class="stat-card-value">{{ number_format($ventasVendidas) }}</div>
      <div class="stat-card-sub"><i class="mdi mdi-check-all"></i> completadas</div>
    </a>

    <div class="stat-card stat-ingresos">
      <div class="stat-card-icon"><i class="mdi mdi-currency-usd"></i></div>
      <div class="stat-card-label">Ingresos del mes</div>
      <div class="stat-card-value">Bs. {{ number_format($ingresosMes, 2) }}</div>
      <div class="stat-card-sub"><i class="mdi mdi-calendar-month-outline"></i> {{ now()->translatedFormat('F Y') }}</div>
    </div>
  </div>

  {{-- ==================== GRÁFICO Y ACTIVIDAD ==================== --}}
  <div class="dashboard-grid">

    {{-- Gráfico de los últimos 7 días --}}
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-head-info">
          <i class="mdi mdi-chart-line"></i>
          <div>
            <h6>Ventas últimos 7 días</h6>
            <p>Tu rendimiento semanal</p>
          </div>
        </div>
        <a href="{{ route('vendedor.ventas.index') }}" class="dash-card-link">Ver historial <i class="mdi mdi-arrow-right"></i></a>
      </div>
      <div class="dash-card-body">
        @if($hayVentas7dias)
          <div class="chart-container">
            <canvas id="ventasChart"></canvas>
          </div>
        @else
          <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5 opacity-75">
            <i class="mdi mdi-chart-line text-muted" style="font-size: 4rem;"></i>
            <h6 class="mt-3 text-muted">Aún no hay ventas en los últimos 7 días</h6>
            <a href="{{ route('vendedor.ventas.create') }}" class="btn-welcome mt-3">
              <i class="mdi mdi-plus"></i> Registrar venta
            </a>
          </div>
        @endif
      </div>
    </div>

    {{-- Actividad reciente --}}
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-head-info">
          <i class="mdi mdi-bell-ring-outline"></i>
          <div>
            <h6>Actividad reciente</h6>
            <p>{{ $notifNoLeidas }} sin leer</p>
          </div>
        </div>
        <a href="#" class="dash-card-link" onclick="event.preventDefault();">Ver todas</a>
      </div>
      <div class="dash-card-body">
        @if($ultimasNotif->count() > 0)
          <div class="activity-list">
            @foreach($ultimasNotif as $n)
              @php
                $tipoClase = match($n->tipo) {
                    'stock_faltante' => 'tipo-faltante',
                    'stock_prestado' => 'tipo-prestado',
                    default => '',
                };
                $tipoIcono = match($n->tipo) {
                    'stock_faltante' => 'mdi-alert-circle-outline',
                    'stock_prestado' => 'mdi-arrow-down-bold-circle-outline',
                    'stock_devuelto' => 'mdi-arrow-up-bold-circle-outline',
                    default => 'mdi-information-outline',
                };
              @endphp
              <div class="activity-item">
                <div class="activity-icon {{ $tipoClase }}"><i class="mdi {{ $tipoIcono }}"></i></div>
                <div class="activity-body">
                  <p class="activity-title">
                    @if(!$n->leida) <span class="dot"></span> @endif {{ $n->titulo }}
                  </p>
                  <p class="activity-msg">{{ \Illuminate\Support\Str::limit($n->mensaje, 70) }}</p>
                  <div class="activity-meta">
                    @if($n->fromUser) <span class="from"><i class="mdi mdi-account-circle-outline"></i> {{ $n->fromUser->name }}</span> @endif
                    <span><i class="mdi mdi-clock-outline"></i> {{ $n->created_at->diffForHumans() }}</span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-4 text-muted">
            <i class="mdi mdi-bell-sleep-outline" style="font-size: 3rem; opacity: 0.4;"></i>
            <p class="mt-2 mb-0">No hay notificaciones recientes</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- ==================== ESTADO DE TUS VENTAS ==================== --}}
  <h6 class="section-title"><i class="mdi mdi-chart-donut"></i> Estado de tus ventas <span class="line"></span></h6>

  <div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="estado-ventas-item ev-total h-100">
        <div class="estado-ventas-icon"><i class="mdi mdi-format-list-bulleted"></i></div>
        <div>
          <p class="estado-ventas-label">Total</p>
          <p class="estado-ventas-value">{{ $totalVentas }} <small>ventas</small></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="estado-ventas-item ev-pendiente h-100">
        <div class="estado-ventas-icon"><i class="mdi mdi-clock-outline"></i></div>
        <div>
          <p class="estado-ventas-label">Pendientes</p>
          <p class="estado-ventas-value">{{ $ventasPendientes }} <small>ventas</small></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="estado-ventas-item ev-vendida h-100">
        <div class="estado-ventas-icon"><i class="mdi mdi-check-circle-outline"></i></div>
        <div>
          <p class="estado-ventas-label">Vendidas</p>
          <p class="estado-ventas-value">{{ $ventasVendidas }} <small>ventas</small></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="estado-ventas-item ev-cancelada h-100">
        <div class="estado-ventas-icon"><i class="mdi mdi-close-circle-outline"></i></div>
        <div>
          <p class="estado-ventas-label">Canceladas</p>
          <p class="estado-ventas-value">{{ $ventasCanceladas }} <small>ventas</small></p>
        </div>
      </div>
    </div>
  </div>

  {{-- ==================== TOP PRODUCTOS ==================== --}}
  <h6 class="section-title"><i class="mdi mdi-trophy-outline"></i> Tus productos más vendidos <span class="line"></span></h6>

  <div class="row mb-4">
    <div class="col-lg-12">
      <div class="dash-card">
        <div class="dash-card-head">
          <div class="dash-card-head-info">
            <i class="mdi mdi-star-outline"></i>
            <div>
              <h6>Top 5 productos</h6>
              <p>Basado en ventas confirmadas</p>
            </div>
          </div>
        </div>
        <div class="dash-card-body">
          @if($topProductos->count() > 0)
            <div class="top-productos">
              @foreach($topProductos as $i => $p)
                <div class="top-producto-item">
                  <div class="top-producto-rank">{{ $i + 1 }}</div>
                  <div class="top-producto-info">
                    <p class="top-producto-name">{{ $p->nombre }}</p>
                    <p class="top-producto-meta">
                      {{ number_format($p->cantidad_total, 0) }} {{ $p->unidad_base }} vendidas
                      · {{ $p->num_ventas }} {{ $p->num_ventas == 1 ? 'venta' : 'ventas' }}
                    </p>
                  </div>
                  <div class="top-producto-total">
                    Bs. {{ number_format($p->monto_total, 2) }}
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="text-center py-4 text-muted">
              <i class="mdi mdi-trophy-outline" style="font-size: 3rem; opacity: 0.4;"></i>
              <p class="mt-2 mb-0">Aún no tienes productos vendidos</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ==================== ACCIONES RÁPIDAS ==================== --}}
  <h6 class="section-title"><i class="mdi mdi-lightning-bolt-outline"></i> Acciones rápidas <span class="line"></span></h6>

  <div class="quick-actions">
    <a href="{{ route('vendedor.ventas.create') }}" class="quick-action">
      <div class="quick-action-icon"><i class="mdi mdi-cart-plus"></i></div>
      <span class="quick-action-label">Nueva venta</span>
    </a>
    <a href="{{ route('vendedor.ventas.index') }}" class="quick-action">
      <div class="quick-action-icon"><i class="mdi mdi-format-list-bulleted"></i></div>
      <span class="quick-action-label">Mis ventas</span>
    </a>
    <a href="{{ route('vendedor.ventas.index') }}" class="quick-action">
      <div class="quick-action-icon"><i class="mdi mdi-clock-outline"></i></div>
      <span class="quick-action-label">Pendientes</span>
    </a>
    <a href="#" class="quick-action" onclick="event.preventDefault();">
      <div class="quick-action-icon"><i class="mdi mdi-bell-outline"></i></div>
      <span class="quick-action-label">Notificaciones</span>
    </a>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($hayVentas7dias)
        const ctx = document.getElementById('ventasChart').getContext('2d');

        const isDark    = document.documentElement.getAttribute('data-theme') === 'dark';
        const textColor = isDark ? '#92a7ad' : '#52666d';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--cyan').trim() || '#00d4ff';

        // Degradado para las barras
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, primaryColor);
        gradient.addColorStop(1, 'rgba(0, 212, 255, 0.15)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Ventas (Bs)',
                    data: @json($chartData),
                    backgroundColor: gradient,
                    borderRadius: 6,
                    barThickness: 36,
                    hoverBackgroundColor: primaryColor,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#0f171b' : '#ffffff',
                        titleColor: isDark ? '#e6eff1' : '#0f1f24',
                        bodyColor: isDark ? '#92a7ad' : '#52666d',
                        borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return ' Bs. ' + context.parsed.y.toLocaleString('es-BO');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: {
                            color: textColor,
                            callback: function(value) { return 'Bs ' + value; }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: textColor, font: { weight: 'bold' } }
                    }
                }
            }
        });
    @endif
});
</script>
@endpush