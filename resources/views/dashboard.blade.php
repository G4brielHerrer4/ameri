{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.plantilla_maestra')

@section('title', 'Dashboard - AMERI')

@push('styles')
<style>
  /* =====================================================
     HEADER DE BIENVENIDA
     ===================================================== */
  .welcome-section {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1.5rem; flex-wrap: wrap; margin-bottom: 2rem;
    padding: 1.75rem 2rem; border-radius: var(--r-lg);
    background: linear-gradient(135deg, color-mix(in srgb, var(--primary) 12%, var(--bg-1)) 0%, var(--bg-1) 70%);
    border: 1px solid var(--line); position: relative; overflow: hidden;
  }
  .welcome-section::before {
    content: ''; position: absolute; top: -50%; right: -10%; width: 300px; height: 300px;
    border-radius: 50%; background: radial-gradient(circle, color-mix(in srgb, var(--primary) 20%, transparent), transparent 70%);
    pointer-events: none;
  }
  .welcome-info { position: relative; z-index: 1; }
  .welcome-info .greeting {
    font-family: var(--font-display); font-size: 0.78rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary);
    margin: 0 0 0.35rem; display: flex; align-items: center; gap: 0.4rem;
  }
  .welcome-info h1 {
    font-family: var(--font-display); font-weight: 700;
    font-size: clamp(1.5rem, 3vw, 2rem); color: var(--ink); margin: 0 0 0.35rem; line-height: 1.15;
  }
  .welcome-info h1 .highlight { color: var(--primary); }
  .welcome-info p { color: var(--ink-muted); margin: 0; font-size: 0.9rem; }
  .welcome-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; position: relative; z-index: 1; }
  .btn-welcome {
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.7rem 1.35rem; border-radius: var(--r-pill); border: none;
    background: linear-gradient(135deg, var(--primary), var(--primary-2));
    color: var(--primary-ink); font-size: 0.88rem; font-weight: 700; cursor: pointer;
    transition: all 0.3s var(--ease); text-decoration: none;
    box-shadow: 0 4px 14px -4px var(--glow); white-space: nowrap;
  }
  .btn-welcome:hover { transform: translateY(-2px); color: var(--primary-ink); box-shadow: 0 10px 24px -6px var(--glow); }
  .btn-welcome-secondary { background: var(--bg-2); color: var(--ink); border: 1px solid var(--line); box-shadow: none; }
  .btn-welcome-secondary:hover { color: var(--primary); border-color: var(--line-strong); }

  /* =====================================================
     TITULOS
     ===================================================== */
  .section-title {
    display: flex; align-items: center; gap: 0.55rem;
    font-family: var(--font-display); font-weight: 600; font-size: 1rem;
    color: var(--ink); margin: 0 0 1.25rem;
  }
  .section-title .mdi { color: var(--primary); font-size: 1.25rem; }
  .section-title .line { flex: 1; height: 1px; background: linear-gradient(to right, var(--line), transparent); }

  /* =====================================================
     STATS CARDS (REDISEÑADAS)
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
  .stat-card:hover { transform: translateY(-3px); border-color: color-mix(in srgb, var(--primary) 50%, var(--line)); box-shadow: 0 15px 35px -20px var(--shadow); }
  .stat-card-icon {
    width: 44px; height: 44px; border-radius: var(--r-md);
    background: color-mix(in srgb, var(--primary) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--primary) 30%, transparent);
    color: var(--primary); display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.35rem; margin-bottom: 1rem; transition: transform .3s var(--ease);
  }
  .stat-card:hover .stat-card-icon { transform: scale(1.08) rotate(-5deg); }
  .stat-card-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-muted); margin-bottom: 0.25rem; }
  .stat-card-value { font-family: var(--font-display); font-weight: 700; font-size: 1.8rem; line-height: 1; color: var(--ink); margin: 0 0 0.35rem; }
  .stat-card-sub { font-size: 0.78rem; color: var(--ink-faint); display: flex; align-items: center; gap: 0.3rem; }

  /* =====================================================
     GRÁFICO Y ACTIVIDAD
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
    background: linear-gradient(180deg, color-mix(in srgb, var(--primary) 4%, transparent), transparent);
  }
  .dash-card-head-info { display: flex; align-items: center; gap: 0.6rem; }
  .dash-card-head-info .mdi { color: var(--primary); font-size: 1.25rem; }
  .dash-card-head-info h6 { font-family: var(--font-display); font-weight: 600; font-size: 1rem; color: var(--ink); margin: 0; }
  .dash-card-head-info p { font-size: 0.75rem; color: var(--ink-muted); margin: 0; }
  .dash-card-link { font-size: 0.8rem; font-weight: 600; color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; transition: gap .2s ease; }
  .dash-card-link:hover { gap: 0.45rem; color: var(--primary); }
  .dash-card-body { padding: 1.25rem; flex: 1; }

  /* Lista de actividad */
  .activity-list { display: flex; flex-direction: column; gap: 0.15rem; }
  .activity-item { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.85rem; border-radius: var(--r-md); text-decoration: none; color: inherit; transition: background .2s ease; }
  .activity-item:hover { background: var(--surface-alpha); }
  .activity-item + .activity-item { border-top: 1px solid var(--line); }
  .activity-icon { width: 36px; height: 36px; border-radius: var(--r-sm); flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; background: color-mix(in srgb, var(--primary) 12%, transparent); color: var(--primary); }
  .activity-icon.tipo-prestado { background: color-mix(in srgb, var(--warning) 12%, transparent); color: var(--warning); }
  .activity-body { flex: 1; min-width: 0; }
  .activity-title { font-weight: 600; color: var(--ink); font-size: 0.85rem; margin: 0 0 0.2rem; display: flex; align-items: center; gap: 0.4rem; }
  .activity-title .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--primary); flex-shrink: 0; }
  .activity-msg { font-size: 0.78rem; color: var(--ink-muted); margin: 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  .activity-meta { display: flex; align-items: center; gap: 0.75rem; font-size: 0.7rem; color: var(--ink-faint); margin-top: 0.35rem; }
  .activity-meta .from { color: var(--primary); font-weight: 600; }

  /* Inventario */
  .inv-status-list { display: flex; flex-direction: column; gap: 0.65rem; }
  .inv-status-item { display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 1rem; border-radius: var(--r-md); background: var(--bg-2); border: 1px solid var(--line); position: relative; overflow: hidden; }
  .inv-status-item::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: var(--inv-color); }
  .inv-status-icon { width: 38px; height: 38px; border-radius: var(--r-sm); background: color-mix(in srgb, var(--inv-color) 14%, transparent); color: var(--inv-color); display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
  .inv-status-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); margin: 0 0 0.15rem; }
  .inv-status-value { font-family: var(--font-display); font-weight: 700; font-size: 1.25rem; color: var(--ink); line-height: 1; }
  .inv-status-value small { font-size: 0.72rem; color: var(--ink-faint); font-weight: 500; margin-left: 0.3rem; }
  .inv-normal { --inv-color: var(--success); }
  .inv-bajo { --inv-color: var(--warning); }
  .inv-sin-stock { --inv-color: var(--error); }
  .inv-sobre { --inv-color: #ff8c42; }

  /* Chart Container */
  .chart-container { position: relative; height: 320px; width: 100%; }

  /* =====================================================
     ACCIONES RÁPIDAS
     ===================================================== */
  .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 0.85rem; }
  .quick-action {
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.6rem;
    padding: 1.25rem 0.75rem; border-radius: var(--r-md); background: var(--bg-2);
    border: 1px solid var(--line); color: var(--ink); text-decoration: none; text-align: center;
    transition: all .25s var(--ease);
  }
  .quick-action:hover { transform: translateY(-3px); border-color: var(--line-strong); color: var(--primary); background: color-mix(in srgb, var(--primary) 6%, var(--bg-2)); box-shadow: 0 12px 24px -12px var(--glow); }
  .quick-action-icon { width: 46px; height: 46px; border-radius: var(--r-md); background: color-mix(in srgb, var(--primary) 10%, transparent); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; font-size: 1.4rem; transition: transform .3s ease; }
  .quick-action:hover .quick-action-icon { transform: scale(1.1) rotate(-5deg); }
  .quick-action-label { font-size: 0.82rem; font-weight: 600; }

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
  /* ====================================================
     DATOS GENERALES DEL SISTEMA
     ==================================================== */
  $adminActualId = auth()->id();
  
  // 🔥 LA CLAVE: Buscamos todos los productos donde el admin tenga un registro,
  // ya sea porque configuró precios o porque tiene stock (incluso si es 0).
  $misProductosIds = \App\Models\PrecioProductoAdmin::where('user_id', $adminActualId)
      ->pluck('producto_id')
      ->merge(
          \App\Models\Stock::where('user_id', $adminActualId)->pluck('producto_id')
      )
      ->unique();
      
  $totalProductos    = \App\Models\Producto::whereIn('id', $misProductosIds)->count();
  $totalProveedores  = \App\Models\Proveedor::count();
  $totalAdmins       = \App\Models\User::whereHas('role', function ($q) { $q->where('slug', 'administrador'); })->count();
  $notifNoLeidas     = \App\Models\Notificacion::where('user_id', $adminActualId)->where('leida', false)->count();
  
  $ultimasNotif = \App\Models\Notificacion::with('fromUser')
                      ->where('user_id', $adminActualId)->latest()->take(5)->get();

  /* ====================================================
     DATOS PARA EL GRÁFICO DE VENTAS (HOY POR ADMIN)
     ==================================================== */
  $ventasHoy = \App\Models\Venta::where('estado', 'vendida')
      ->whereDate('fecha_venta', now()->toDateString())
      ->selectRaw('user_id, sum(total) as total_vendido')
      ->groupBy('user_id')
      ->get();

  $chartLabels = [];
  $chartData = [];
  
  foreach($ventasHoy as $v) {
      $admin = \App\Models\User::find($v->user_id);
      $chartLabels[] = $admin ? $admin->name : 'Desconocido';
      $chartData[] = (float) $v->total_vendido;
  }

  $hayVentasHoy = count($chartData) > 0;

  /* ====================================================
     ESTADO DEL INVENTARIO (SOLO DEL ADMIN LOGUEADO)
     ==================================================== */
  $sinStock = 0; $stockBajo = 0; $normal = 0; $sobreexistencia = 0;
  
  // Traemos los productos del admin (incluso con cantidad 0)
  $productosInventario = \App\Models\Producto::whereIn('id', $misProductosIds)
      ->with(['tipoProducto.categoria', 'stocks' => function($q) use ($adminActualId) {
          $q->where('user_id', $adminActualId);
      }, 'preciosAdmin' => function($q) use ($adminActualId) {
          $q->where('user_id', $adminActualId);
      }])
      ->get();
  
  foreach ($productosInventario as $p) {
      // Sumamos el stock de las tiendas de este admin
      $stockTotal = (float) $p->stocks->sum('cantidad');
      
      //Límites individuales del admin (no del producto global)
      $config = $p->preciosAdmin->first();
      $stockMin = $config ? (float) $config->stock_minimo : 0;
      $stockMax = $config ? (float) $config->stock_maximo : 0;
      
      $piezasCaja = (float) ($p->piezas_por_caja ?? 0);
      $tipoNombre = mb_strtolower(trim((string) ($p->tipoProducto?->nombre ?? '')), 'UTF-8');
      $esCaja = in_array($tipoNombre, ['cerámica','ceramica','porcelanato']) && $piezasCaja > 0;
      
      // Si es cerámica, calculamos el equivalente en cajas para comparar con los límites
      $stockParaEstado = $esCaja ? ($stockTotal / $piezasCaja) : $stockTotal;

      // Evaluación de los estados
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
      <p class="greeting"><i class="mdi mdi-hand-wave-outline"></i> Bienvenido de nuevo</p>
      <h1>Hola, <span class="highlight">{{ Auth::user()->name }}</span></h1>
      <p>Esto es lo que está pasando en AMERI hoy · {{ now()->translatedFormat('l, d \\d\\e F \\d\\e Y') }}</p>
    </div>
    <div class="welcome-actions">
      <a href="{{ route('admin.ventas.create') }}" class="btn-welcome"><i class="mdi mdi-cart-plus"></i> Nueva venta</a>
      <a href="{{ route('admin.compras.create') }}" class="btn-welcome btn-welcome-secondary"><i class="mdi mdi-truck-plus-outline"></i> Nueva compra</a>
    </div>
  </div>

  {{-- ==================== STATS ==================== --}}
  <h6 class="section-title"><i class="mdi mdi-chart-box-outline"></i> Resumen general <span class="line"></span></h6>

  <div class="stats-grid">
    <a href="{{ route('admin.suministros.index') }}" class="stat-card">
      <div class="stat-card-icon"><i class="mdi mdi-cube-outline"></i></div>
      <div class="stat-card-label">Productos</div>
      <div class="stat-card-value">{{ number_format($totalProductos) }}</div>
      <div class="stat-card-sub"><i class="mdi mdi-package-variant"></i> en catálogo</div>
    </a>

    <a href="{{ route('admin.suministros.index') }}" class="stat-card">
      <div class="stat-card-icon"><i class="mdi mdi-truck-outline"></i></div>
      <div class="stat-card-label">Proveedores</div>
      <div class="stat-card-value">{{ number_format($totalProveedores) }}</div>
      <div class="stat-card-sub"><i class="mdi mdi-account-multiple-outline"></i> registrados</div>
    </a>

    <a href="{{ route('admin.users.index') }}" class="stat-card">
      <div class="stat-card-icon"><i class="mdi mdi-shield-account-outline"></i></div>
      <div class="stat-card-label">Administradores</div>
      <div class="stat-card-value">{{ number_format($totalAdmins) }}</div>
      <div class="stat-card-sub"><i class="mdi mdi-account-cog-outline"></i> activos</div>
    </a>

    <a href="{{ route('admin.notificaciones.index') }}" class="stat-card">
      <div class="stat-card-icon"><i class="mdi mdi-bell-outline"></i></div>
      <div class="stat-card-label">Notificaciones</div>
      <div class="stat-card-value">{{ number_format($notifNoLeidas) }}</div>
      <div class="stat-card-sub"><i class="mdi mdi-email-mark-as-unread"></i> sin leer</div>
    </a>
  </div>

  {{-- ==================== GRÁFICO Y ACTIVIDAD ==================== --}}
  <div class="dashboard-grid">
    
    {{-- Gráfico de Ventas del Día --}}
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-head-info">
          <i class="mdi mdi-chart-bar"></i>
          <div>
            <h6>Ventas de Hoy por Admin</h6>
            <p>Rendimiento diario</p>
          </div>
        </div>
        <a href="{{ route('admin.reportes.index') }}" class="dash-card-link">Ver reportes <i class="mdi mdi-arrow-right"></i></a>
      </div>
      <div class="dash-card-body">
        @if($hayVentasHoy)
          <div class="chart-container">
            <canvas id="ventasChart"></canvas>
          </div>
        @else
          <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5 opacity-75">
            <i class="mdi mdi-chart-bar text-muted" style="font-size: 4rem;"></i>
            <h6 class="mt-3 text-muted">Aún no hay ventas registradas hoy</h6>
          </div>
        @endif
      </div>
    </div>

    {{-- Actividad Reciente --}}
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-head-info">
          <i class="mdi mdi-bell-ring-outline"></i>
          <div>
            <h6>Actividad Reciente</h6>
            <p>Notificaciones</p>
          </div>
        </div>
      </div>
      <div class="dash-card-body">
        @if($ultimasNotif->count() > 0)
          <div class="activity-list">
            @foreach($ultimasNotif as $n)
              @php
                $esPrestado = $n->tipo === 'stock_prestado';
                $tipoClase = $esPrestado ? 'tipo-prestado' : 'tipo-devuelto';
                $tipoIcono = $esPrestado ? 'mdi-arrow-down-bold-circle-outline' : 'mdi-arrow-up-bold-circle-outline';
              @endphp
              <a href="{{ route('admin.notificaciones.index') }}" class="activity-item">
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
              </a>
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

  {{-- ==================== ESTADO DE INVENTARIO ==================== --}}
  <h6 class="section-title"><i class="mdi mdi-warehouse"></i> Estado del Inventario <span class="line"></span></h6>
  
  <div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="inv-status-item inv-normal h-100">
        <div class="inv-status-icon"><i class="mdi mdi-check-circle-outline"></i></div>
        <div class="inv-status-info">
          <p class="inv-status-label">Normal</p>
          <p class="inv-status-value">{{ $normal }} <small>productos</small></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="inv-status-item inv-bajo h-100">
        <div class="inv-status-icon"><i class="mdi mdi-alert-outline"></i></div>
        <div class="inv-status-info">
          <p class="inv-status-label">Stock Bajo</p>
          <p class="inv-status-value">{{ $stockBajo }} <small>productos</small></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="inv-status-item inv-sin-stock h-100">
        <div class="inv-status-icon"><i class="mdi mdi-close-circle-outline"></i></div>
        <div class="inv-status-info">
          <p class="inv-status-label">Sin Stock</p>
          <p class="inv-status-value">{{ $sinStock }} <small>productos</small></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="inv-status-item inv-sobre h-100">
        <div class="inv-status-icon"><i class="mdi mdi-alert-circle-outline"></i></div>
        <div class="inv-status-info">
          <p class="inv-status-label">Sobreexistencia</p>
          <p class="inv-status-value">{{ $sobreexistencia }} <small>productos</small></p>
        </div>
      </div>
    </div>
  </div>

  {{-- ==================== ACCIONES RÁPIDAS ==================== --}}
  <h6 class="section-title mt-2"><i class="mdi mdi-lightning-bolt-outline"></i> Acciones rápidas <span class="line"></span></h6>

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

@push('scripts')
{{-- Importamos Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($hayVentasHoy)
        const ctx = document.getElementById('ventasChart').getContext('2d');
        
        // Detectar si es modo oscuro o claro para colores del gráfico
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const textColor = isDark ? '#92a7ad' : '#52666d';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
        
        // Obtener el color primario (cyan, verde, naranja o violeta)
        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Total Vendido (Bs)',
                    data: @json($chartData),
                    backgroundColor: primaryColor,
                    borderRadius: 6,
                    barThickness: 40,
                    hoverBackgroundColor: '#ffffff'
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