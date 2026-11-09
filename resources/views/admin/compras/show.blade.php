@extends('layouts.plantilla_maestra')

@section('title', 'Detalle de Compra')


@push('styles')
<style>
/* =========================================================
   DETALLE DE COMPRA
========================================================= */

.detalle-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.75rem;
}

.detalle-header h3 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--ink);
    margin: 0 0 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detalle-header h3 .mdi {
    color: var(--cyan);
}

.detalle-header p {
    color: var(--ink-muted);
    margin: 0;
    font-size: 0.88rem;
}

.btn-secondary-app {
    padding: 0.7rem 1.5rem;
    border-radius: var(--r-pill);
    border: 1px solid var(--line);
    background: var(--bg-2);
    color: var(--ink);
    font-weight: 700;
    font-size: 0.88rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    transition: all 0.3s var(--ease);
}

.btn-secondary-app:hover {
    border-color: var(--line-strong);
    color: var(--cyan);
    transform: translateY(-2px);
}

/* =========================================================
   INFORMACIÓN GENERAL
========================================================= */

.card-info {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    padding: 1.35rem;
    box-shadow: 0 20px 40px -28px var(--shadow);
    margin-bottom: 1.25rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
}

.info-item .info-label {
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--ink-faint);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-family: var(--font-display);
    margin-bottom: 0.35rem;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.info-item .info-label .mdi {
    color: var(--cyan);
    font-size: 0.9rem;
}

.info-item .info-value {
    font-size: 0.95rem;
    color: var(--ink);
    font-weight: 500;
}

.info-item .info-value.code {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--cyan);
    letter-spacing: 0.02em;
}

/* =========================================================
   TABLA DE PRODUCTOS
========================================================= */

.table-detalle {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.table-detalle thead th {
    background: color-mix(in srgb, var(--bg-2) 60%, transparent);
    color: var(--ink-muted);
    font-family: var(--font-display);
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 0.75rem;
    border-bottom: 1px solid var(--line);
    text-align: left;
    white-space: nowrap;
}

.table-detalle tbody td {
    padding: 0.75rem;
    border-bottom: 1px solid var(--line);
    font-size: 0.88rem;
    color: var(--ink);
    vertical-align: top;
}

.table-detalle tbody tr:last-child td {
    border-bottom: none;
}

.table-detalle .text-end {
    text-align: right;
}

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

/* =========================================================
   TOTALES
========================================================= */

.totales-box {
    max-width: 340px;
    margin-left: auto;
    margin-top: 1rem;
    background: var(--bg-2);
    border: 1px solid var(--line);
    border-radius: var(--r-md);
    padding: 1rem 1.25rem;
}

.totales-box .total-row {
    display: flex;
    justify-content: space-between;
    padding: 0.4rem 0;
    font-size: 0.88rem;
    color: var(--ink-muted);
}

.totales-box .total-row span:last-child {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--ink);
}

.totales-box .total-row.grand {
    border-top: 1px solid var(--line);
    padding-top: 0.75rem;
    margin-top: 0.4rem;
    font-size: 1rem;
    color: var(--ink);
}

.totales-box .total-row.grand span:last-child {
    font-size: 1.35rem;
    color: var(--cyan);
    text-shadow: 0 0 20px var(--glow-soft);
}

/* =========================================================
   ESTADOS DE COMPRA
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    border-radius: var(--r-pill);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.status-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.status-pendiente {
    background: color-mix(in srgb, var(--warning) 14%, transparent);
    color: var(--warning);
    border: 1px solid color-mix(in srgb, var(--warning) 40%, transparent);
}

.status-recibida {
    background: color-mix(in srgb, var(--cyan) 14%, transparent);
    color: var(--cyan);
    border: 1px solid var(--line-strong);
}

.status-cancelada {
    background: color-mix(in srgb, var(--error) 12%, transparent);
    color: var(--error);
    border: 1px solid color-mix(in srgb, var(--error) 40%, transparent);
}

/* =========================================================
   COMPROBANTES - ENCABEZADO
========================================================= */

.comprobantes-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 22px;
}

.comprobantes-header h5 {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--ink);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 9px;
}

.comprobantes-header h5 i {
    color: var(--cyan);
    font-size: 22px;
}

.comprobantes-descripcion {
    color: var(--ink-muted);
    font-size: 0.84rem;
    margin: 6px 0 0;
}

.comprobantes-contador {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    color: var(--cyan);
    border: 1px solid color-mix(in srgb, var(--cyan) 22%, transparent);
    border-radius: 10px;
    padding: 9px 13px;
    font-size: 0.82rem;
    font-weight: 700;
}

/* =========================================================
   ÁREA DE SUBIDA
========================================================= */

.comprobante-upload {
    background: var(--bg-2);
    border: 1px solid var(--line);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
}

.upload-content {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 18px;
}

.upload-icon {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    background: color-mix(in srgb, var(--cyan) 12%, transparent);
    color: var(--cyan);
    font-size: 26px;
}

.upload-text h6 {
    margin: 0;
    color: var(--ink);
    font-weight: 700;
}

.upload-text p {
    color: var(--ink-muted);
    margin: 4px 0 0;
    font-size: 0.82rem;
}

/* Etiqueta general del formulario */
.comprobante-upload label {
    color: var(--ink);
    font-weight: 600;
    font-size: 0.88rem;
    margin-bottom: 0.65rem;
}

/* Input de archivos */
.comprobante-upload input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--bg-1);
    color: var(--ink);
    font-size: 0.85rem;
}

.comprobante-upload small {
    display: block;
    color: var(--ink-muted);
    margin-top: 0.5rem;
}

/* Botón elegir archivos */
.upload-label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    background: #e8f6fa;
    color: #087f9c !important;
    border: 1px solid #a9dce8;
    border-radius: 10px;
    padding: 11px 18px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}

.upload-label:hover {
    background: #d5eff5;
    border-color: #087f9c;
    color: #06677f !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(8, 127, 156, 0.12);
}

.upload-label i {
    font-size: 19px;
    color: #087f9c !important;
}

/* Tema oscuro */
[data-theme="dark"] .upload-label {
    background: rgba(8, 127, 156, 0.18);
    color: #65d5ed !important;
    border-color: rgba(101, 213, 237, 0.35);
}

[data-theme="dark"] .upload-label i {
    color: #65d5ed !important;
}

[data-theme="dark"] .upload-label:hover {
    background: rgba(8, 127, 156, 0.30);
}

.upload-help {
    display: block;
    color: var(--ink-muted);
    font-size: 0.77rem;
    margin-top: 8px;
}

.archivos-seleccionados {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
}

.archivo-seleccionado-item {
    display: flex;
    align-items: center;
    gap: 7px;
    max-width: 100%;
    padding: 7px 11px;
    border: 1px solid var(--line);
    border-radius: 8px;
    background: var(--bg-1);
    color: var(--ink);
    font-size: 0.78rem;
    overflow-wrap: anywhere;
}

.archivo-seleccionado-item i {
    color: var(--cyan);
    font-size: 17px;
}

/* Botón subir comprobantes */
.btn-upload-comprobante {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #087f9c !important;
    color: #ffffff !important;
    border: 1px solid #087f9c;
    border-radius: 10px;
    padding: 11px 20px;
    margin-top: 17px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 3px 8px rgba(8, 127, 156, 0.18);
}

.btn-upload-comprobante:hover {
    background: #06677f !important;
    border-color: #06677f;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 12px rgba(8, 127, 156, 0.25);
}

.btn-upload-comprobante i {
    color: #ffffff !important;
    font-size: 18px;
}

/* =========================================================
   GALERÍA DE COMPROBANTES
========================================================= */

.comprobantes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(245px, 1fr));
    gap: 18px;
}

.comprobante-card {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.25s ease;
}

.comprobante-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    border-color: var(--line-strong);
}

.comprobante-preview {
    height: 190px;
    position: relative;
    overflow: hidden;
    background: var(--bg-2);
}

.comprobante-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
}

.comprobante-card:hover .comprobante-preview img {
    transform: scale(1.04);
}

.comprobante-zoom {
    position: absolute;
    right: 12px;
    bottom: 12px;
    width: 39px;
    height: 39px;
    border: none;
    border-radius: 10px;
    background: rgba(20, 30, 40, 0.88);
    color: #fff;
    font-size: 21px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.comprobante-card-body {
    padding: 14px;
}

.comprobante-file-icon {
    width: 39px;
    height: 39px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: color-mix(in srgb, var(--cyan) 11%, transparent);
    color: var(--cyan);
    border-radius: 10px;
    font-size: 22px;
}

.comprobante-file-info {
    margin-top: 10px;
    min-width: 0;
}

.comprobante-nombre {
    color: var(--ink);
    font-size: 0.84rem;
    font-weight: 700;
    overflow-wrap: anywhere;
    margin-bottom: 4px;
}

.comprobante-file-info small {
    color: var(--ink-muted);
    font-size: 0.75rem;
}

.comprobante-card-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 9px;
    margin-top: 13px;
    padding-top: 12px;
    border-top: 1px solid var(--line);
}

.btn-comprobante-ver {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 9px;
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--cyan) 25%, transparent);
    color: var(--cyan);
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-comprobante-ver:hover {
    background: var(--cyan);
    color: white;
}

.btn-comprobante-eliminar {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: transparent;
    color: var(--error);
    border: 1px solid color-mix(in srgb, var(--error) 35%, transparent);
    font-size: 18px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-comprobante-eliminar:hover {
    background: var(--error);
    color: white;
}

.form-eliminar-comprobante {
    margin: 0;
}

/* =========================================================
   ESTADO VACÍO
========================================================= */

.comprobantes-vacio {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px 20px;
    background: var(--bg-2);
    border: 1px dashed var(--line-strong);
    border-radius: 14px;
}

.comprobantes-vacio > i {
    font-size: 44px;
    color: var(--ink-muted);
}

.comprobantes-vacio h6 {
    color: var(--ink);
    font-weight: 700;
    margin: 10px 0 5px;
}

.comprobantes-vacio p {
    color: var(--ink-muted);
    font-size: 0.82rem;
    margin: 0;
}

/* =========================================================
   MODAL DE VISUALIZACIÓN
========================================================= */

.modal-comprobante-content {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: 16px;
    overflow: hidden;
}

.modal-comprobante-content .modal-header {
    border-bottom: 1px solid var(--line);
    padding: 18px 22px;
}

.modal-comprobante-content .modal-title {
    color: var(--ink);
    font-weight: 700;
}

.modal-comprobante-content .modal-title i {
    color: var(--cyan);
    margin-right: 7px;
}

.modal-comprobante-content .modal-header small {
    display: block;
    color: var(--ink-muted);
    margin-top: 5px;
    overflow-wrap: anywhere;
}

.modal-comprobante-content .modal-body {
    padding: 20px;
    background: var(--bg-2);
}

#imagenComprobanteModal {
    max-width: 100%;
    max-height: 75vh;
    object-fit: contain;
    border-radius: 8px;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 576px) {
    .comprobantes-grid {
        grid-template-columns: 1fr;
    }

    .comprobantes-header {
        align-items: flex-start;
    }

    .comprobante-upload {
        padding: 15px;
    }

    .comprobante-preview {
        height: 210px;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">

  {{-- ENCABEZADO --}}
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

  {{-- ============ DETALLE DE PRODUCTOS ============ --}}
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
                  <br>
                  <small style="color:var(--ink-muted);font-size:0.78rem;">
                    {{ $d->producto->marca }}
                    @if($d->producto->medida)
                      · {{ $d->producto->medida }}
                    @endif
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

              <td class="text-end">
                Bs. {{ number_format($d->precio_unitario, 2) }}
              </td>

              <td class="text-end">
                <strong>Bs. {{ number_format($d->subtotal, 2) }}</strong>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- TOTALES --}}
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

  {{-- ============ COMPROBANTES DE COMPRA ============ --}}
  <div class="card-info">

    {{-- CABECERA --}}
    <div class="comprobantes-header">
      <div>
        <h5>
          <i class="mdi mdi-file-image-outline"></i>
          Comprobantes de compra
        </h5>

        <p class="comprobantes-descripcion">
          Recibos, facturas y fotografías que respaldan esta compra.
        </p>
      </div>

      <span class="comprobantes-contador">
        <i class="mdi mdi-image-multiple-outline"></i>
        {{ $compra->archivos->count() }} archivo(s)
      </span>
    </div>

    {{-- MENSAJES --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-circle-outline"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="mdi mdi-alert-circle-outline"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger" role="alert">
        <strong>No se pudieron procesar los archivos:</strong>
        <ul class="mb-0 mt-2">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- FORMULARIO PARA SUBIR FOTOGRAFÍAS --}}
    @if($compra->estado === 'recibida')

      <div class="comprobante-upload">

    <form action="{{ route('admin.compras.archivos.store', $compra) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="upload-content">
            <div class="upload-icon">
                <i class="mdi mdi-cloud-upload-outline"></i>
            </div>

            <div class="upload-text">
                <h6>Adjuntar comprobantes</h6>
                <p>Selecciona una o varias fotografías de tus recibos o facturas.</p>
            </div>
        </div>

        <input
            type="file"
            id="archivos"
            name="archivos[]"
            accept=".jpg,.jpeg,.png,.webp"
            multiple
            required
            hidden
        >

        <div class="archivos-seleccionados" id="archivosSeleccionados"></div>

        <button type="button"
                class="btn-upload-comprobante"
                id="btnSeleccionarArchivos">
            <i class="mdi mdi-cloud-upload-outline"></i>
            Subir comprobante
        </button>

        <button type="submit"
                class="btn-upload-comprobante"
                id="btnEnviarComprobantes"
                style="display:none;">
            <i class="mdi mdi-check-circle-outline"></i>
            Guardar comprobantes
        </button>

        <small class="upload-help">
            Formatos permitidos: JPG, JPEG, PNG y WEBP. Máximo 5 MB por fotografía.
        </small>
    </form>

      </div>

    @else

      <div class="alert alert-warning" role="alert">
        <i class="mdi mdi-information-outline"></i>
        Para adjuntar comprobantes, primero debes marcar esta compra como recibida.
      </div>

    @endif

    {{-- GALERÍA --}}
    <div class="comprobantes-grid">

      @forelse($compra->archivos as $archivo)

        <div class="comprobante-card">

          <div class="comprobante-preview">

            <img
              src="{{ asset('storage/' . $archivo->ruta) }}"
              alt="Comprobante de compra"
              loading="lazy"
            >

            <button
              type="button"
              class="comprobante-zoom"
              data-bs-toggle="modal"
              data-bs-target="#modalComprobante"
              data-imagen="{{ asset('storage/' . $archivo->ruta) }}"
              data-nombre="{{ $archivo->nombre_original }}"
              title="Ampliar fotografía"
            >
              <i class="mdi mdi-magnify-plus-outline"></i>
            </button>

          </div>

          <div class="comprobante-card-body">

            <div class="comprobante-file-icon">
              <i class="mdi mdi-file-image-outline"></i>
            </div>

            <div class="comprobante-file-info">
              <div class="comprobante-nombre"
                   title="{{ $archivo->nombre_original }}">
                {{ $archivo->nombre_original }}
              </div>

              <small>Documento adjunto</small>
            </div>

            <div class="comprobante-card-actions">

              <button
                type="button"
                class="btn-comprobante-ver"
                data-bs-toggle="modal"
                data-bs-target="#modalComprobante"
                data-imagen="{{ asset('storage/' . $archivo->ruta) }}"
                data-nombre="{{ $archivo->nombre_original }}"
              >
                <i class="mdi mdi-eye-outline"></i>
                Ver comprobante
              </button>

              @if($compra->estado === 'recibida')

                <form
                  action="{{ route('admin.compras.archivos.destroy', [$compra, $archivo]) }}"
                  method="POST"
                  class="form-eliminar-comprobante"
                >
                  @csrf
                  @method('DELETE')

                  <button type="submit" class="btn-comprobante-eliminar"
                          title="Eliminar comprobante">
                    <i class="mdi mdi-trash-can-outline"></i>
                  </button>
                </form>

              @endif

            </div>

          </div>
        </div>

      @empty

        <div class="comprobantes-vacio">
          <i class="mdi mdi-file-image-outline"></i>
          <h6>No hay comprobantes registrados</h6>
          <p>Las fotografías de recibos y facturas aparecerán aquí.</p>
        </div>

      @endforelse

    </div>

  </div>

</div>

{{-- ============ MODAL PARA VISUALIZAR COMPROBANTES ============ --}}
<div class="modal fade" id="modalComprobante" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content modal-comprobante-content">

      <div class="modal-header">
        <div>
          <h5 class="modal-title">
            <i class="mdi mdi-file-image-outline"></i>
            Vista previa del comprobante
          </h5>

          <small id="nombreComprobanteModal"></small>
        </div>

        <button type="button" class="btn-close"
                data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body text-center">
        <img
          id="imagenComprobanteModal"
          src=""
          alt="Vista previa del comprobante"
        >
      </div>

    </div>
  </div>
</div>

@endsection


{{-- ============ FUNCIONES JAVASCRIPT ============ --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // VISTA PREVIA DE COMPROBANTES
    // ==========================================

    const modal = document.getElementById('modalComprobante');

    if (modal) {

        modal.addEventListener('show.bs.modal', function (event) {

            const boton = event.relatedTarget;

            const imagen = boton.getAttribute('data-imagen');
            const nombre = boton.getAttribute('data-nombre');

            document.getElementById('imagenComprobanteModal').src = imagen;

            document.getElementById('nombreComprobanteModal').textContent = nombre;

        });

        modal.addEventListener('hidden.bs.modal', function () {

            document.getElementById('imagenComprobanteModal').src = '';

        });
    }


    // ==========================================
    // CONFIRMAR ELIMINACIÓN CON SWEETALERT2
    // ==========================================

    document.querySelectorAll('.form-eliminar-comprobante').forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();

            Swal.fire({

                title: '¿Eliminar comprobante?',

                html: `
                    <p style="margin:0;color:#64748b;font-size:14px;line-height:1.6;">
                        ¿Estás seguro de que deseas eliminar este recibo o factura?
                        <br>
                        <strong>Esta acción no se puede deshacer.</strong>
                    </p>
                `,

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: '<i class="mdi mdi-trash-can-outline"></i> Sí, eliminar',

                cancelButtonText: 'Cancelar',

                confirmButtonColor: '#dc3545',

                cancelButtonColor: '#64748b',

                reverseButtons: true,

                focusCancel: true,

                allowOutsideClick: false,

                customClass: {
                    popup: 'swal-comprobante-popup'
                }

            }).then((result) => {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        });

    });


    // ==========================================
    // MOSTRAR ARCHIVOS SELECCIONADOS
    // ==========================================

    const inputArchivos = document.getElementById('archivos');
    const archivosSeleccionados = document.getElementById('archivosSeleccionados');

    if (inputArchivos && archivosSeleccionados) {

        inputArchivos.addEventListener('change', function () {

            archivosSeleccionados.innerHTML = '';

            const archivos = Array.from(this.files);

            if (archivos.length === 0) {
                return;
            }

            archivos.forEach(function (archivo) {

                const elemento = document.createElement('div');

                elemento.className = 'archivo-seleccionado-item';

                const icono = document.createElement('i');
                icono.className = 'mdi mdi-file-image-outline';

                const nombre = document.createElement('span');
                nombre.textContent = archivo.name;

                elemento.appendChild(icono);
                elemento.appendChild(nombre);

                archivosSeleccionados.appendChild(elemento);

            });

        });

    }

});


document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('archivos');
    const btnSeleccionar = document.getElementById('btnSeleccionarArchivos');
    const btnEnviar = document.getElementById('btnEnviarComprobantes');
    const contenedor = document.getElementById('archivosSeleccionados');

    if (!input || !btnSeleccionar || !btnEnviar) return;

    btnSeleccionar.addEventListener('click', function () {
        input.click();
    });

    input.addEventListener('change', function () {
        contenedor.innerHTML = '';

        if (input.files.length > 0) {
            Array.from(input.files).forEach(function (archivo) {
                const elemento = document.createElement('div');
                elemento.className = 'archivo-seleccionado-item';

                const icono = document.createElement('i');
                icono.className = 'mdi mdi-image-outline';

                const nombre = document.createElement('span');
                nombre.textContent = archivo.name;

                elemento.appendChild(icono);
                elemento.appendChild(nombre);
                contenedor.appendChild(elemento);
            });

            btnSeleccionar.style.display = 'none';
            btnEnviar.style.display = 'inline-flex';
        } else {
            btnSeleccionar.style.display = 'inline-flex';
            btnEnviar.style.display = 'none';
        }
    });
});
</script>
@endpush