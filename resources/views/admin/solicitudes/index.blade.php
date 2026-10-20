@extends('layouts.plantilla_maestra')
@section('title', 'Ventas Internas')

@push('styles')
<style>
  /* ==================== HEADER ==================== */
  .solicitud-header-main {
    display: flex; align-items: center; gap: 0.85rem;
    margin-bottom: 2rem; flex-wrap: wrap;
  }
  .solicitud-header-main .icon-wrap {
    width: 46px; height: 46px; border-radius: var(--r-md);
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); display: inline-flex; align-items: center;
    justify-content: center; font-size: 1.4rem;
    box-shadow: 0 6px 20px -6px var(--glow);
  }
  .solicitud-header-main h3 {
    font-family: var(--font-display); font-weight: 600;
    color: var(--ink); margin: 0; font-size: 1.35rem;
  }
  .solicitud-header-main p {
    color: var(--ink-muted); margin: 0.15rem 0 0; font-size: 0.88rem;
  }

  /* ==================== PANELES ==================== */
  .panel-col {
    background: var(--bg-1); border: 1px solid var(--line);
    border-radius: var(--r-lg); overflow: hidden;
    box-shadow: 0 20px 40px -28px var(--shadow);
    height: 100%; display: flex; flex-direction: column;
  }
  .panel-col-head {
    padding: 1.1rem 1.35rem; border-bottom: 1px solid var(--line);
    background: linear-gradient(180deg, color-mix(in srgb, var(--cyan) 4%, transparent), transparent);
    font-family: var(--font-display); font-weight: 600;
    color: var(--ink); font-size: 0.98rem;
    display: flex; align-items: center; gap: 0.6rem;
  }
  .panel-col-head .mdi { color: var(--cyan); font-size: 1.15rem; }
  .panel-col-body { padding: 1.35rem; flex-grow: 1; overflow-y: auto; max-height: 800px; }

  /* ==================== TARJETAS DE SOLICITUD ==================== */
  .sol-card {
    background: var(--bg-2); border: 1px solid var(--line);
    border-radius: var(--r-md); padding: 1.15rem;
    margin-bottom: 1rem; transition: all 0.3s var(--ease);
  }
  .sol-card:hover { border-color: var(--line-strong); box-shadow: 0 10px 25px -15px var(--shadow); }
  .sol-card-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    gap: 1rem; margin-bottom: 0.85rem;
  }
  .sol-card-title {
    font-family: var(--font-display); font-weight: 600;
    color: var(--ink); font-size: 0.95rem; margin: 0;
  }
  .sol-card-meta { font-size: 0.75rem; color: var(--ink-muted); display: flex; flex-direction: column; gap: 0.15rem; }
  .sol-card-body {
    background: var(--bg-1); border: 1px solid var(--line);
    border-radius: var(--r-sm); padding: 0.85rem; margin-bottom: 1rem;
  }
  
  /* Productos */
  .sol-producto {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 0.85rem; padding: 0.4rem 0; border-bottom: 1px dashed var(--line);
  }
  .sol-producto:last-child { border-bottom: none; padding-bottom: 0; }
  .sol-producto:first-child { padding-top: 0; }
  .sol-producto-nombre { font-weight: 600; color: var(--ink); }
  .sol-producto-cantidad { 
    font-family: var(--font-display); font-weight: 700; color: var(--cyan);
    background: var(--glow-soft); padding: 0.15rem 0.6rem; border-radius: var(--r-pill);
    font-size: 0.78rem; white-space: nowrap;
  }

  /* Badges de Estado */
  .badge-estado {
    padding: 0.25rem 0.65rem; border-radius: var(--r-pill);
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.04em; display: inline-flex; align-items: center; gap: 0.3rem;
  }
  .badge-pendiente { background: color-mix(in srgb, var(--warning) 15%, transparent); color: var(--warning); border: 1px solid color-mix(in srgb, var(--warning) 40%, transparent); }
  .badge-aprobada { background: color-mix(in srgb, var(--cyan) 15%, transparent); color: var(--cyan); border: 1px solid var(--line-strong); }
  .badge-rechazada { background: color-mix(in srgb, var(--error) 15%, transparent); color: var(--error); border: 1px solid color-mix(in srgb, var(--error) 40%, transparent); }
  .badge-completada { background: color-mix(in srgb, var(--success) 15%, transparent); color: var(--success); border: 1px solid color-mix(in srgb, var(--success) 40%, transparent); }

  /* Botones de Acción Iguales */
  .btn-actions-group { display: flex; gap: 0.5rem; width: 100%; }
  .btn-accion-sol {
    flex: 1; padding: 0.65rem 1rem; border-radius: var(--r-md);
    font-weight: 700; font-size: 0.85rem; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;
    transition: all 0.3s var(--ease); border: none; text-align: center;
  }
  .btn-accion-sol.aprobar {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink); box-shadow: 0 4px 14px -4px var(--glow);
  }
  .btn-accion-sol.aprobar:hover { transform: translateY(-2px); box-shadow: 0 8px 20px -6px var(--glow); }
  .btn-accion-sol.rechazar {
    background: color-mix(in srgb, var(--error) 10%, transparent);
    color: var(--error); border: 1px solid color-mix(in srgb, var(--error) 30%, transparent);
  }
  .btn-accion-sol.rechazar:hover { background: color-mix(in srgb, var(--error) 20%, transparent); transform: translateY(-2px); }
  .btn-accion-sol.recibir {
    width: 100%; background: linear-gradient(135deg, var(--cyan), var(--cyan-2)); color: var(--cyan-ink);
  }

  /* Modales (Estilo Profesional) */
  .modal-content {
    background: var(--bg-1) !important; border: 1px solid var(--line-strong) !important;
    border-radius: var(--r-lg) !important; box-shadow: 0 40px 80px -40px var(--shadow) !important;
  }
  .ameri-modal-header {
    background: linear-gradient(135deg, color-mix(in srgb, var(--cyan) 18%, var(--bg-1)) 0%, var(--bg-2) 100%) !important;
    border-bottom: 1px solid var(--line) !important; padding: 1.25rem 1.5rem !important;
    display: flex; align-items: center; justify-content: space-between;
  }
  .ameri-modal-icon {
    width: 48px; height: 48px; border-radius: var(--r-md);
    background: color-mix(in srgb, var(--cyan) 15%, transparent);
    border: 1px solid var(--line-strong); color: var(--cyan);
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.5rem; box-shadow: 0 0 16px -4px var(--glow); flex-shrink: 0;
  }
  .ameri-modal-header .modal-title { color: var(--ink) !important; font-family: var(--font-display); font-weight: 600; font-size: 1.1rem; margin: 0; }
  .ameri-modal-sub { color: var(--ink-muted) !important; font-size: 0.8rem; margin: 0.2rem 0 0; }
  
  .modal-body { padding: 1.5rem !important; background: var(--bg-1) !important; }
  .modal-body .form-label { color: var(--ink-muted); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem; display: block; text-transform: uppercase; letter-spacing: 0.04em; }
  .modal-body .form-control, .modal-body .form-select {
    background: var(--bg-2); border: 1px solid var(--line);
    color: var(--ink); border-radius: var(--r-md); padding: 0.7rem 0.9rem; font-size: 0.9rem;
    transition: all 0.25s var(--ease);
  }
  .modal-body .form-control:focus, .modal-body .form-select:focus {
    background: var(--bg-3); color: var(--ink); border-color: var(--cyan);
    box-shadow: 0 0 0 3px var(--glow-soft); outline: none;
  }
  .ameri-modal-footer {
    background: var(--bg-2) !important; border-top: 1px solid var(--line) !important;
    padding: 1rem 1.5rem !important; display: flex; justify-content: flex-end; gap: 0.65rem;
  }
  .ameri-modal-footer .btn-secondary-app {
    background: var(--bg-1); border: 1px solid var(--line); color: var(--ink);
    padding: 0.6rem 1.25rem; border-radius: var(--r-pill); font-weight: 700; font-size: 0.85rem;
  }
  .ameri-modal-footer .btn-primary-app {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2)); color: var(--cyan-ink);
    border: none; padding: 0.6rem 1.25rem; border-radius: var(--r-pill); font-weight: 700; font-size: 0.85rem;
    box-shadow: 0 4px 14px -4px var(--glow);
  }

  /* Stock Badge Dinámico */
  .stock-badge {
    display: inline-flex; align-items: center; gap: 0.35rem; margin-top: 0.5rem;
    font-size: 0.78rem; font-weight: 600; color: var(--cyan);
    background: color-mix(in srgb, var(--cyan) 8%, transparent);
    padding: 0.35rem 0.65rem; border-radius: var(--r-sm); border: 1px solid var(--line-strong);
  }
  .stock-badge.cero { 
    color: var(--error); background: color-mix(in srgb, var(--error) 8%, transparent);
    border-color: color-mix(in srgb, var(--error) 30%, transparent);
  }

  /* Alertas de error/éxito de Laravel */
  .alert-app {
    border-radius: var(--r-md);
    border: 1px solid var(--line);
    background: var(--bg-1);
    color: var(--ink);
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    font-size: 0.88rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
  }
  .alert-app.error {
    background: color-mix(in srgb, var(--error) 8%, var(--bg-1));
    border-color: color-mix(in srgb, var(--error) 40%, transparent);
    color: var(--error);
  }
  .alert-app.success {
    background: color-mix(in srgb, var(--success) 8%, var(--bg-1));
    border-color: color-mix(in srgb, var(--success) 40%, transparent);
    color: var(--success);
  }
  .alert-app .mdi { font-size: 1.25rem; }

  /* ==================== SWEETALERT2 PREMIUM ==================== */
  .swal2-popup {
    background: var(--bg-1) !important;
    border: 1px solid var(--line-strong) !important;
    border-radius: var(--r-lg) !important;
    box-shadow: 0 40px 80px -40px var(--shadow) !important;
    padding: 1.75rem !important;
  }
  .swal2-title {
    font-family: var(--font-display) !important;
    color: var(--ink) !important;
    font-size: 1.25rem !important;
    font-weight: 600 !important;
  }
  .swal2-html-container {
    color: var(--ink-muted) !important;
    font-size: 0.9rem !important;
    margin-top: 0.75rem !important;
    text-align: left !important;
  }
  .swal2-confirm, .swal2-cancel {
    font-weight: 700 !important;
    font-size: 0.88rem !important;
    padding: 0.7rem 1.5rem !important;
    border-radius: var(--r-pill) !important;
    transition: all 0.3s ease !important;
    border: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.4rem !important;
  }
  .swal2-confirm {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
    color: var(--cyan-ink) !important;
    box-shadow: 0 6px 20px -6px var(--glow) !important;
  }
  .swal2-confirm:hover { transform: translateY(-2px) !important; box-shadow: 0 12px 28px -6px var(--glow) !important; }
  .swal2-cancel {
    background: var(--bg-2) !important;
    color: var(--ink) !important;
    border: 1px solid var(--line) !important;
  }
  .swal2-cancel:hover { border-color: var(--line-strong) !important; color: var(--cyan) !important; }
  .swal2-icon.swal2-warning { border-color: var(--warning) !important; color: var(--warning) !important; }
  .swal2-icon.swal2-question { border-color: var(--cyan) !important; color: var(--cyan) !important; }
  .swal2-icon.swal2-success { border-color: var(--success) !important; color: var(--success) !important; }
  .swal2-container.swal2-backdrop-show { background: rgba(5, 8, 10, 0.75) !important; backdrop-filter: blur(4px); }
</style>
@endpush

@section('content')
<div class="container-fluid">

  {{-- Alertas de Laravel --}}
  @if(session('error'))
    <div class="alert-app error">
      <i class="mdi mdi-alert-circle"></i>
      <div>
        <strong style="display:block; margin-bottom:0.25rem;">Ocurrió un error</strong>
        {{ session('error') }}
      </div>
    </div>
  @endif

  @if(session('success'))
    <div class="alert-app success">
      <i class="mdi mdi-check-circle"></i>
      <div>
        <strong style="display:block; margin-bottom:0.25rem;">¡Todo listo!</strong>
        {{ session('success') }}
      </div>
    </div>
  @endif

  @if($errors->any())
    <div class="alert-app error">
      <i class="mdi mdi-alert-circle"></i>
      <div>
        <strong style="display:block; margin-bottom:0.25rem;">Revisa los siguientes detalles:</strong>
        <ul class="mb-0 mt-1" style="padding-left: 1.2rem;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  <div class="solicitud-header-main">
    <span class="icon-wrap"><i class="mdi mdi-swap-horizontal"></i></span>
    <div>
      <h3>Ventas Internas</h3>
      <p>Gestión de solicitudes de stock entre administradores</p>
    </div>
  </div>

  <div class="row">
    {{-- SOLICITUDES RECIBIDAS (YO SOY EL PROVEEDOR - ADMIN B) --}}
    <div class="col-lg-6 mb-4">
      <div class="panel-col">
        <div class="panel-col-head">
          <i class="mdi mdi-inbox-arrow-down"></i> Solicitudes que me hicieron
        </div>
        <div class="panel-col-body">
          @forelse($solicitudesRecibidas as $sol)
            <div class="sol-card">
              <div class="sol-card-header">
                <div>
                  <h5 class="sol-card-title">{{ $sol->solicitante->name }} te solicita:</h5>
                  <div class="sol-card-meta">
                    <span><i class="mdi mdi-clock-outline"></i> {{ $sol->created_at->format('d/m/Y H:i') }}</span>
                  </div>
                </div>
                <span class="badge-estado badge-{{ $sol->estado }}">{{ ucfirst($sol->estado) }}</span>
              </div>
              
              <div class="sol-card-body">
                @foreach($sol->detalles as $det)
                  @php
                    $tipo = mb_strtolower(trim((string)($det->producto->tipoProducto?->nombre ?? '')), 'UTF-8');
                    $esCeramica = in_array($tipo, ['cerámica', 'ceramica', 'porcelanato']);
                    $esBolsa = in_array($tipo, ['cemento cola', 'pastinas']);
                    $unidadMostrar = 'piezas';
                    if ($esCeramica) $unidadMostrar = 'cajas';
                    elseif ($esBolsa) $unidadMostrar = 'bolsas';
                    elseif ($det->producto->unidad_base) $unidadMostrar = $det->producto->unidad_base;
                  @endphp
                  <div class="sol-producto">
                    <span class="sol-producto-nombre">{{ $det->producto->nombre }}</span>
                    <span class="sol-producto-cantidad">{{ $det->cantidad }} {{ $unidadMostrar }}</span>
                  </div>
                @endforeach
              </div>

              @if($sol->estado === 'pendiente')
                <div class="btn-actions-group">
                  <button class="btn-accion-sol aprobar" data-bs-toggle="modal" data-bs-target="#modalAprobar{{ $sol->id }}">
                    <i class="mdi mdi-currency-usd"></i> Precio y Aprobar
                  </button>
                  <form action="{{ route('admin.solicitudes.rechazar', $sol) }}" method="POST" style="flex: 1; display: flex;">
                    @csrf 
                    <button class="btn-accion-sol rechazar w-100" onclick="return confirm('¿Rechazar solicitud?')">
                      Rechazar
                    </button>
                  </form>
                </div>
              @endif
            </div>

            {{-- MODAL APROBAR CON PRECIO --}}
            <div class="modal fade" id="modalAprobar{{ $sol->id }}" tabindex="-1">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <form action="{{ route('admin.solicitudes.aprobar', $sol) }}" method="POST" class="modal-content form-aprobar" id="formAprobar{{ $sol->id }}" novalidate>
                  @csrf
                  <div class="modal-header ameri-modal-header">
                    <div class="d-flex align-items-center gap-3">
                      <div class="ameri-modal-icon"><i class="mdi mdi-cash-multiple"></i></div>
                      <div>
                        <h5 class="modal-title">Aprobar y Poner Precio</h5>
                        <p class="ameri-modal-sub">Define el precio de venta y la tienda de origen</p>
                      </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <p style="color: var(--ink-muted); font-size: 0.9rem;" class="mb-4">Vas a venderle a <strong>{{ $sol->solicitante->name }}</strong> el siguiente stock:</p>
                    
                    @foreach($sol->detalles as $det)
                      @php
                        $tipo = mb_strtolower(trim((string)($det->producto->tipoProducto?->nombre ?? '')), 'UTF-8');
                        $esCeramica = in_array($tipo, ['cerámica', 'ceramica', 'porcelanato']);
                        $esBolsa = in_array($tipo, ['cemento cola', 'pastinas']);
                        $unidadMostrar = 'piezas';
                        if ($esCeramica) $unidadMostrar = 'cajas';
                        elseif ($esBolsa) $unidadMostrar = 'bolsas';
                        elseif ($det->producto->unidad_base) $unidadMostrar = $det->producto->unidad_base;
                      @endphp
                      <div class="p-3 mb-3" style="background: var(--bg-2); border-radius: var(--r-md); border: 1px solid var(--line);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <strong style="color: var(--ink); font-size: 1rem;">{{ $det->producto->nombre }}</strong>
                          <span class="sol-producto-cantidad">{{ $det->cantidad }} {{ $unidadMostrar }}</span>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label">¿De qué tienda sacarás el stock? <span style="color:var(--error);">*</span></label>
                            <select name="detalles[{{ $det->id }}][deposito_origen_id]" class="form-select select-tienda" required data-detalle="{{ $det->id }}">
                              <option value="">Selecciona tu tienda...</option>
                              @foreach($depositos as $dep)
                                @php
                                  $stockEnTienda = $misStocks->where('producto_id', $det->producto_id)->where('deposito_id', $dep->id)->first();
                                  $cantStock = $stockEnTienda ? $stockEnTienda->cantidad : 0;
                                @endphp
                                <option value="{{ $dep->id }}" data-stock="{{ $cantStock }}">
                                  {{ $dep->nombre }} (Stock: {{ $cantStock }})
                                </option>
                              @endforeach
                            </select>
                            <span class="stock-badge" id="badge-stock-{{ $det->id }}">
                              <i class="mdi mdi-information-outline"></i> Selecciona una tienda.
                            </span>
                          </div>
                          
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Precio Total de Venta Interna (Bs) <span style="color:var(--error);">*</span></label>
                            <input type="number" name="detalles[{{ $det->id }}][precio]" class="form-control input-precio" required placeholder="Ej. 500.00" step="0.01" min="0.01">
                            <small style="color: var(--ink-muted); display: block; margin-top: 0.35rem; font-size: 0.75rem;">
                              Monto total que le cobrarás por las {{ $det->cantidad }} {{ $unidadMostrar }}.
                            </small>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                  <div class="modal-footer ameri-modal-footer">
                    <button type="button" class="btn-secondary-app" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-app btn-submit-aprobar" data-form="formAprobar{{ $sol->id }}">
                      <i class="mdi mdi-check"></i> Descontar y Aprobar
                    </button>
                  </div>
                </form>
              </div>
            </div>
          @empty
            <div class="text-center py-5">
              <i class="mdi mdi-inbox-outline" style="font-size: 3rem; color: var(--ink-faint); opacity: 0.5;"></i>
              <h5 style="color: var(--ink-muted); margin-top: 1rem;">No hay solicitudes recibidas</h5>
            </div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- MIS SOLICITUDES (YO SOY EL SOLICITANTE - ADMIN A) --}}
    <div class="col-lg-6 mb-4">
      <div class="panel-col">
        <div class="panel-col-head">
          <i class="mdi mdi-inbox-arrow-up"></i> Mis solicitudes enviadas
        </div>
        <div class="panel-col-body">
          @forelse($misSolicitudes as $sol)
            <div class="sol-card">
              <div class="sol-card-header">
                <div>
                  <h5 class="sol-card-title">Para: {{ $sol->proveedor->name }}</h5>
                  <div class="sol-card-meta">
                    <span><i class="mdi mdi-clock-outline"></i> {{ $sol->created_at->format('d/m/Y H:i') }}</span>
                  </div>
                </div>
                <span class="badge-estado badge-{{ $sol->estado }}">{{ ucfirst($sol->estado) }}</span>
              </div>
              
              <div class="sol-card-body">
                @foreach($sol->detalles as $det)
                  @php
                    $tipo = mb_strtolower(trim((string)($det->producto->tipoProducto?->nombre ?? '')), 'UTF-8');
                    $esCeramica = in_array($tipo, ['cerámica', 'ceramica', 'porcelanato']);
                    $esBolsa = in_array($tipo, ['cemento cola', 'pastinas']);
                    $unidadMostrar = 'piezas';
                    if ($esCeramica) $unidadMostrar = 'cajas';
                    elseif ($esBolsa) $unidadMostrar = 'bolsas';
                    elseif ($det->producto->unidad_base) $unidadMostrar = $det->producto->unidad_base;
                  @endphp
                  <div class="sol-producto">
                    <div>
                      <span class="sol-producto-nombre">{{ $det->producto->nombre }}</span>
                      @if($det->precio_acordado) 
                        <br><small style="color: var(--success); font-size: 0.75rem;"><i class="mdi mdi-tag"></i> Precio acordado: Bs. {{ number_format($det->precio_acordado, 2) }}</small>
                      @endif
                    </div>
                    <span class="sol-producto-cantidad">{{ $det->cantidad }} {{ $unidadMostrar }}</span>
                  </div>
                @endforeach
              </div>

              @if($sol->estado === 'aprobada')
                <div class="alert py-2 mb-3" style="font-size: 0.8rem; background: color-mix(in srgb, var(--cyan) 8%, transparent); border: 1px solid var(--line-strong); color: var(--cyan);">
                  <i class="mdi mdi-check-circle"></i> El proveedor ya descontó el stock. Elige tu tienda destino para recibirlo.
                </div>
                <button class="btn-accion-sol recibir" data-bs-toggle="modal" data-bs-target="#modalRecibir{{ $sol->id }}">
                  <i class="mdi mdi-package-down"></i> Elegir Tienda Destino
                </button>
              @endif
            </div>

            {{-- MODAL RECIBIR --}}
            @if($sol->estado === 'aprobada')
              <div class="modal fade" id="modalRecibir{{ $sol->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <form action="{{ route('admin.solicitudes.recibir', $sol) }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header ameri-modal-header">
                      <div class="d-flex align-items-center gap-3">
                        <div class="ameri-modal-icon"><i class="mdi mdi-storefront-outline"></i></div>
                        <div>
                          <h5 class="modal-title">Recibir Stock</h5>
                          <p class="ameri-modal-sub">Selecciona el destino final</p>
                        </div>
                      </div>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <p style="color: var(--ink-muted); font-size: 0.9rem;" class="mb-3">Elige a qué tienda de tu inventario quieres que ingrese la mercadería.</p>
                      
                      @foreach($sol->detalles as $det)
                        <div class="mb-3">
                          <label class="form-label">{{ $det->producto->nombre }}</label>
                          <select name="detalles[{{ $det->id }}][deposito_destino_id]" class="form-select" required>
                            <option value="">Selecciona tu tienda...</option>
                            @foreach($depositos as $dep)
                              <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                            @endforeach
                          </select>
                        </div>
                      @endforeach
                    </div>
                    <div class="modal-footer ameri-modal-footer">
                      <button type="button" class="btn-secondary-app" data-bs-dismiss="modal">Cancelar</button>
                      <button type="submit" class="btn-primary-app">Ingresar a mi Inventario</button>
                    </div>
                  </form>
                </div>
              </div>
            @endif
          @empty
            <div class="text-center py-5">
              <i class="mdi mdi-send-outline" style="font-size: 3rem; color: var(--ink-faint); opacity: 0.5;"></i>
              <h5 style="color: var(--ink-muted); margin-top: 1rem;">No has enviado solicitudes</h5>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  
  // 1. Lógica para mostrar el stock dinámico al seleccionar la tienda
  const selectsTienda = document.querySelectorAll('.select-tienda');
  selectsTienda.forEach(select => {
    select.addEventListener('change', function() {
      const detalleId = this.dataset.detalle;
      const badge = document.getElementById('badge-stock-' + detalleId);
      const selectedOption = this.options[this.selectedIndex];
      
      if (!selectedOption.value) {
        badge.innerHTML = '<i class="mdi mdi-information-outline"></i> Selecciona una tienda.';
        badge.classList.remove('cero');
        return;
      }

      const stock = selectedOption.dataset.stock;
      badge.innerHTML = '<i class="mdi mdi-package-variant"></i> Stock en esta tienda: <strong>' + stock + '</strong>';
      
      if (parseFloat(stock) <= 0) {
        badge.classList.add('cero');
        badge.innerHTML = '<i class="mdi mdi-alert-circle"></i> Sin stock en esta tienda';
      } else {
        badge.classList.remove('cero');
      }
    });
  });

  // 2. Lógica de SweetAlert2 para Aprobar
  const btnsAprobar = document.querySelectorAll('.btn-submit-aprobar');
  
  btnsAprobar.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault(); 
      
      const formId = this.dataset.form;
      const form = document.getElementById(formId);
      
      const selects = form.querySelectorAll('.select-tienda');
      const inputsPrecio = form.querySelectorAll('.input-precio');
      
      let valido = true;
      let mensajeError = '';

      selects.forEach(select => {
        if (!select.value) {
          valido = false;
          mensajeError = 'Debes seleccionar de qué tienda sacarás el stock.';
          select.style.borderColor = 'var(--error)';
        } else {
          select.style.borderColor = 'var(--line)';
        }
      });

      inputsPrecio.forEach(input => {
        const valor = parseFloat(input.value);
        if (!input.value || isNaN(valor) || valor <= 0) {
          valido = false;
          mensajeError = 'Debes ingresar un precio total válido mayor a 0.';
          input.style.borderColor = 'var(--error)';
        } else {
          input.style.borderColor = 'var(--line)';
        }
      });

      if (!valido) {
        Swal.fire({
          title: 'Faltan datos',
          text: mensajeError,
          icon: 'warning',
          confirmButtonText: 'Entendido',
          buttonsStyling: false,
          customClass: {
            confirmButton: 'swal2-confirm'
          }
        });
        return;
      }

      Swal.fire({
        title: '¿Aprobar y Descontar?',
        html: `
          <p style="margin-bottom: 0.5rem;">Estás a punto de:</p>
          <ul style="text-align: left; display: inline-block; font-size: 0.9rem; margin-bottom: 0;">
            <li><i class="mdi mdi-check text-success"></i> Descontar el stock de tu tienda.</li>
            <li><i class="mdi mdi-check text-success"></i> Notificar al solicitante.</li>
            <li><i class="mdi mdi-currency-usd text-success"></i> Aceptar el precio de venta interna.</li>
          </ul>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="mdi mdi-check"></i> Sí, Aprobar',
        cancelButtonText: 'Cancelar',
        buttonsStyling: false,
        reverseButtons: true,
        focusCancel: true,
        customClass: {
          confirmButton: 'swal2-confirm',
          cancelButton: 'swal2-cancel'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const modalElement = form.closest('.modal');
          if (modalElement) {
             const modalInstance = bootstrap.Modal.getInstance(modalElement);
             if (modalInstance) {
                 modalInstance.hide();
             }
          }
          
          setTimeout(() => {
              form.submit();
          }, 300);
        }
      });
    });
  });

});
</script>
@endpush