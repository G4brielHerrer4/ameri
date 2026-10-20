@extends('layouts.plantilla_maestra')

@section('title', 'Detalle de Venta')

@push('styles')
<style>
  .detalle-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    gap: 1rem; flex-wrap: wrap; margin-bottom: 1.75rem;
  }
  .detalle-header h3 {
    font-family: var(--font-display); font-weight: 600;
    color: var(--ink); margin: 0 0 0.25rem;
    display: flex; align-items: center; gap: 0.5rem;
  }
  .detalle-header h3 .mdi { color: var(--cyan); }
  .detalle-header p { color: var(--ink-muted); margin: 0; font-size: 0.88rem; }

  .btn-secondary-app {
    padding: 0.7rem 1.5rem; border-radius: var(--r-pill);
    border: 1px solid var(--line); background: var(--bg-2);
    color: var(--ink); font-weight: 700; font-size: 0.88rem;
    cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 0.4rem;
    transition: all 0.3s var(--ease);
  }
  .btn-secondary-app:hover {
    border-color: var(--line-strong); color: var(--cyan);
    transform: translateY(-2px);
  }

  .card-info {
    background: var(--bg-1); border: 1px solid var(--line);
    border-radius: var(--r-lg); padding: 1.35rem;
    box-shadow: 0 20px 40px -28px var(--shadow);
    margin-bottom: 1.25rem;
  }
  .info-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
  }
  .info-label {
    font-size: 0.7rem; font-weight: 700; color: var(--ink-faint);
    text-transform: uppercase; letter-spacing: 0.06em;
    font-family: var(--font-display); margin-bottom: 0.35rem;
    display: flex; align-items: center; gap: 0.35rem;
  }
  .info-label .mdi { color: var(--cyan); font-size: 0.9rem; }
  .info-value { font-size: 0.95rem; color: var(--ink); font-weight: 500; }
  .info-value.code {
    font-family: var(--font-display); font-weight: 700;
    color: var(--cyan); letter-spacing: 0.02em;
  }

  .table-detalle {
    width: 100%; border-collapse: separate; border-spacing: 0;
  }
  .table-detalle thead th {
    background: color-mix(in srgb, var(--bg-2) 60%, transparent);
    color: var(--ink-muted); font-family: var(--font-display);
    font-size: 0.68rem; font-weight: 700; letter-spacing: 0.06em;
    text-transform: uppercase; padding: 0.75rem;
    border-bottom: 1px solid var(--line); text-align: left;
  }
  .table-detalle tbody td {
    padding: 0.75rem; border-bottom: 1px solid var(--line);
    font-size: 0.88rem; color: var(--ink); vertical-align: top;
  }
  .table-detalle tbody tr:last-child td { border-bottom: none; }
  .table-detalle .text-end { text-align: right; }

  /* Estilos para los movimientos */
  .movimiento-item {
    display: inline-flex; align-items: center; gap: 0.4rem;
    font-size: 0.75rem; padding: 0.3rem 0.6rem;
    background: var(--surface-alpha);
    border: 1px solid var(--line);
    border-radius: var(--r-pill);
    margin-bottom: 0.3rem;
  }
  .movimiento-item.es-propio {
    background: color-mix(in srgb, var(--cyan) 8%, transparent);
    border-color: var(--line-strong);
  }
  .movimiento-item .mdi { color: var(--cyan); font-size: 0.85rem; }
  .movimiento-item strong { color: var(--ink); font-family: var(--font-display); }
  .movimiento-item .tienda-tag {
    display: inline-flex; align-items: center; gap: 0.2rem;
    background: var(--bg-1); padding: 0.1rem 0.4rem;
    border-radius: var(--r-sm); border: 1px solid var(--line);
    color: var(--ink-muted); font-size: 0.68rem; font-weight: 600;
  }

  .totales-box {
    max-width: 340px; margin-left: auto; margin-top: 1rem;
    background: var(--bg-2); border: 1px solid var(--line);
    border-radius: var(--r-md); padding: 1rem 1.25rem;
  }
  .total-row {
    display: flex; justify-content: space-between;
    padding: 0.4rem 0; font-size: 0.88rem; color: var(--ink-muted);
  }
  .total-row span:last-child {
    font-family: var(--font-display); font-weight: 600; color: var(--ink);
  }
  .total-row.grand {
    border-top: 1px solid var(--line);
    padding-top: 0.75rem; margin-top: 0.4rem;
    font-size: 1rem; color: var(--ink);
  }
  .total-row.grand span:last-child {
    font-size: 1.35rem; color: var(--cyan);
    text-shadow: 0 0 20px var(--glow-soft);
  }

  .status-badge {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.35rem 0.75rem; border-radius: var(--r-pill);
    font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.04em;
  }
  .status-badge::before {
    content: ''; width: 6px; height: 6px;
    border-radius: 50%; background: currentColor;
  }
  .status-pendiente {
    background: color-mix(in srgb, var(--warning) 14%, transparent);
    color: var(--warning);
    border: 1px solid color-mix(in srgb, var(--warning) 40%, transparent);
  }
  .status-vendida {
    background: color-mix(in srgb, var(--cyan) 14%, transparent);
    color: var(--cyan); border: 1px solid var(--line-strong);
  }
  .status-cancelada {
    background: color-mix(in srgb, var(--error) 12%, transparent);
    color: var(--error);
    border: 1px solid color-mix(in srgb, var(--error) 40%, transparent);
  }
</style>
@endpush

@section('content')
<div class="container-fluid">

  <div class="detalle-header">
    <div>
      <h3><i class="mdi mdi-file-document-outline"></i> Detalle de Venta</h3>
      <p>Información completa de la operación</p>
    </div>
    <a href="{{ route('admin.ventas.index') }}" class="btn-secondary-app">
      <i class="mdi mdi-arrow-left"></i> Volver al listado
    </a>
  </div>

  {{-- Info general --}}
  <div class="card-info">
    <div class="info-grid">
      <div>
        <div class="info-label"><i class="mdi mdi-pound"></i> N° Venta</div>
        <div class="info-value code">{{ $venta->numero_venta }}</div>
      </div>
      <div>
        <div class="info-label">
          <i class="mdi mdi-calendar"></i> Fecha de venta
        </div>
        <div class="info-value">
          {{ $venta->fecha_venta->format('d/m/Y') }}
        </div>
      </div>

      <div>
        <div class="info-label">
          <i class="mdi mdi-clock-outline"></i> Hora de registro
        </div>
        <div class="info-value">
          {{ $venta->created_at ? $venta->created_at->format('H:i:s') : '—' }}
        </div>
      </div>
      <div>
        <div class="info-label"><i class="mdi mdi-account"></i> Vendedor</div>
        <div class="info-value">{{ $venta->user->name ?? '—' }}</div>
      </div>
      <div>
        <div class="info-label"><i class="mdi mdi-account-box"></i> Cliente</div>
        <div class="info-value">{{ $venta->cliente_nombre }}</div>
      </div>
      <div>
        <div class="info-label"><i class="mdi mdi-card-account-details"></i> CI / NIT</div>
        <div class="info-value">{{ $venta->cliente_documento }}</div>
      </div>
      @if($venta->cliente_telefono)
        <div>
          <div class="info-label"><i class="mdi mdi-phone"></i> Teléfono</div>
          <div class="info-value">{{ $venta->cliente_telefono }}</div>
        </div>
      @endif
      <div>
        <div class="info-label"><i class="mdi mdi-information-outline"></i> Estado</div>
        <div class="info-value">
          @if($venta->estado === 'pendiente')
            <span class="status-badge status-pendiente">Pendiente</span>
          @elseif($venta->estado === 'vendida')
            <span class="status-badge status-vendida">Vendida</span>
          @else
            <span class="status-badge status-cancelada">Cancelada</span>
          @endif
        </div>
      </div>
      @if($venta->observaciones)
        <div style="grid-column: 1 / -1;">
          <div class="info-label"><i class="mdi mdi-comment-text-outline"></i> Observaciones</div>
          <div class="info-value">{{ $venta->observaciones }}</div>
        </div>
      @endif
    </div>
  </div>

  {{-- Productos --}}
  <div class="card-info">
    <h5 style="font-family:var(--font-display);font-weight:600;color:var(--ink);margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
      <i class="mdi mdi-format-list-bulleted" style="color:var(--cyan);"></i>
      Productos y origen del stock
    </h5>

    <div class="table-responsive">
      <table class="table-detalle">
        <thead>
          <tr>
            <th>#</th>
            <th>Producto</th>
            <th class="text-end">Cantidad</th>
            <th class="text-end">Precio Unit.</th>
            <th class="text-end">Subtotal</th>
            <th>Origen del stock</th>
          </tr>
        </thead>
        <tbody>
          @foreach($venta->detalles as $i => $d)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>
                <strong>{{ $d->producto->nombre ?? '—' }}</strong>
                @if($d->producto?->marca)
                  <br><small style="color:var(--ink-muted);font-size:0.78rem;">{{ $d->producto->marca }}</small>
                @endif
              </td>
              <td class="text-end">
                {{ rtrim(rtrim(number_format($d->cantidad, 2, '.', ''), '0'), '.') }}
                <small style="color:var(--ink-muted);">{{ $d->unidad ?? 'unidad' }}</small>
              </td>
              <td class="text-end">Bs. {{ number_format($d->precio_unitario, 2) }}</td>
              <td class="text-end"><strong>Bs. {{ number_format($d->subtotal, 2) }}</strong></td>
              <td>
                @foreach($d->movimientos as $mov)
                  <div class="movimiento-item {{ $mov->stock_user_id === $venta->user_id ? 'es-propio' : '' }}">
                    <i class="mdi mdi-account"></i>
                    <span>{{ $mov->stockUser->name ?? '—' }}</span>
                    <strong>× {{ rtrim(rtrim(number_format($mov->cantidad, 2, '.', ''), '0'), '.') }}</strong>
                    
                    {{-- 🔥 MOSTRAMOS LA TIENDA DE ORIGEN 🔥 --}}
                    @if($mov->deposito)
                      <span class="tienda-tag">
                        <i class="mdi mdi-storefront"></i> {{ $mov->deposito->nombre }}
                      </span>
                    @endif
                  </div>
                @endforeach
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="totales-box">
      <div class="total-row"><span>Subtotal</span><span>Bs. {{ number_format($venta->subtotal, 2) }}</span></div>
      <div class="total-row"><span>Descuento</span><span>Bs. {{ number_format($venta->descuento, 2) }}</span></div>
      <div class="total-row grand"><span>Total</span><span>Bs. {{ number_format($venta->total, 2) }}</span></div>
    </div>
  </div>
</div>
@endsection