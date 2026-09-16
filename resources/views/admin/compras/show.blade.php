@extends('layouts.plantilla_maestra')

@section('title', 'Detalle de Compra')

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
  .info-item .info-label {
    font-size: 0.7rem; font-weight: 700; color: var(--ink-faint);
    text-transform: uppercase; letter-spacing: 0.06em;
    font-family: var(--font-display); margin-bottom: 0.35rem;
    display: flex; align-items: center; gap: 0.35rem;
  }
  .info-item .info-label .mdi { color: var(--cyan); font-size: 0.9rem; }
  .info-item .info-value {
    font-size: 0.95rem; color: var(--ink); font-weight: 500;
  }
  .info-item .info-value.code {
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
    text-transform: uppercase; padding: 0.75rem 0.75rem;
    border-bottom: 1px solid var(--line); text-align: left;
    white-space: nowrap;
  }
  .table-detalle tbody td {
    padding: 0.75rem; border-bottom: 1px solid var(--line);
    font-size: 0.88rem; color: var(--ink);
    vertical-align: top;
  }
  .table-detalle tbody tr:last-child td { border-bottom: none; }
  .table-detalle .text-end { text-align: right; }

  .total-base-badge {
    display: inline-block;
    padding: 0.25rem 0.55rem;
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    color: var(--cyan);
    border: 1px solid var(--line-strong);
    border-radius: var(--r-pill);
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.78rem;
  }

  .totales-box {
    max-width: 340px; margin-left: auto; margin-top: 1rem;
    background: var(--bg-2); border: 1px solid var(--line);
    border-radius: var(--r-md); padding: 1rem 1.25rem;
  }
  .totales-box .total-row {
    display: flex; justify-content: space-between;
    padding: 0.4rem 0; font-size: 0.88rem;
    color: var(--ink-muted);
  }
  .totales-box .total-row span:last-child {
    font-family: var(--font-display); font-weight: 600; color: var(--ink);
  }
  .totales-box .total-row.grand {
    border-top: 1px solid var(--line);
    padding-top: 0.75rem; margin-top: 0.4rem;
    font-size: 1rem; color: var(--ink);
  }
  .totales-box .total-row.grand span:last-child {
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
  .status-recibida {
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
      <h3>
        <i class="mdi mdi-file-document-outline"></i>
        Orden de Compra
      </h3>
      <p>Detalle de la compra registrada</p>
    </div>

    <a href="{{ route('admin.compras.index') }}" class="btn-secondary-app">
      <i class="mdi mdi-arrow-left"></i> Volver al listado
    </a>
  </div>

  {{-- ============ INFO GENERAL ============ --}}
  <div class="card-info">
    <div class="info-grid">
      <div class="info-item">
        <div class="info-label"><i class="mdi mdi-pound"></i> N° Orden</div>
        <div class="info-value code">{{ $compra->numero_orden }}</div>
      </div>

      <div class="info-item">
        <div class="info-label"><i class="mdi mdi-calendar"></i> Fecha</div>
        <div class="info-value">{{ $compra->fecha_compra->format('d/m/Y') }}</div>
      </div>

      <div class="info-item">
        <div class="info-label"><i class="mdi mdi-truck"></i> Proveedor</div>
        <div class="info-value">{{ $compra->proveedor->nombre ?? '—' }}</div>
      </div>

      <div class="info-item">
        <div class="info-label"><i class="mdi mdi-account-circle"></i> Registrado por</div>
        <div class="info-value">{{ $compra->user->name ?? '—' }}</div>
      </div>

      <div class="info-item">
        <div class="info-label"><i class="mdi mdi-information-outline"></i> Estado</div>
        <div class="info-value">
          @if($compra->estado === 'pendiente')
            <span class="status-badge status-pendiente">Pendiente</span>
          @elseif($compra->estado === 'recibida')
            <span class="status-badge status-recibida">Recibida</span>
          @else
            <span class="status-badge status-cancelada">Cancelada</span>
          @endif
        </div>
      </div>

      @if($compra->observaciones)
        <div class="info-item" style="grid-column: 1 / -1;">
          <div class="info-label"><i class="mdi mdi-comment-text-outline"></i> Observaciones</div>
          <div class="info-value">{{ $compra->observaciones }}</div>
        </div>
      @endif
    </div>
  </div>

  {{-- ============ DETALLE ============ --}}
  <div class="card-info">
    <h5 style="font-family:var(--font-display);font-weight:600;color:var(--ink);margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
      <i class="mdi mdi-format-list-bulleted" style="color:var(--cyan);"></i>
      Productos
    </h5>

    <div class="table-responsive">
      <table class="table-detalle">
        <thead>
          <tr>
            <th>#</th>
            <th>Producto</th>
            <th class="text-end">Cantidad</th>
            <th class="text-end">Factor</th>
            <th class="text-end">Total base</th>
            <th class="text-end">Precio Unit.</th>
            <th class="text-end">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($compra->detalles as $i => $d)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>
                <strong>{{ $d->producto->nombre ?? '—' }}</strong>
                @if($d->producto?->marca)
                  <br><small style="color:var(--ink-muted);font-size:0.78rem;">
                    {{ $d->producto->marca }}
                    @if($d->producto->medida) · {{ $d->producto->medida }} @endif
                  </small>
                @endif
              </td>
              <td class="text-end">
                {{ rtrim(rtrim(number_format($d->cantidad, 2, '.', ''), '0'), '.') }}
                <small style="color:var(--ink-muted);">{{ $d->unidad_compra }}</small>
              </td>
              <td class="text-end">
                <small style="color:var(--ink-muted);">
                  × {{ rtrim(rtrim(number_format($d->unidades_por_paquete, 2, '.', ''), '0'), '.') }}
                </small>
              </td>
              <td class="text-end">
                <span class="total-base-badge">
                  {{ rtrim(rtrim(number_format($d->cantidad_total, 2, '.', ''), '0'), '.') }}
                  {{ $d->producto->unidad_base ?? 'unidades' }}
                </span>
              </td>
              <td class="text-end">Bs. {{ number_format($d->precio_unitario, 2) }}</td>
              <td class="text-end"><strong>Bs. {{ number_format($d->subtotal, 2) }}</strong></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="totales-box">
      <div class="total-row">
        <span>Subtotal</span>
        <span>Bs. {{ number_format($compra->subtotal, 2) }}</span>
      </div>
      <div class="total-row">
        <span>Descuento</span>
        <span>Bs. {{ number_format($compra->descuento, 2) }}</span>
      </div>
      <div class="total-row grand">
        <span>Total</span>
        <span>Bs. {{ number_format($compra->total, 2) }}</span>
      </div>
    </div>
  </div>
</div>
@endsection