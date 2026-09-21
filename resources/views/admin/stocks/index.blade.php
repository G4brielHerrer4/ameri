```blade
@extends('layouts.plantilla_maestra')

@section('title', 'Inventario')

@push('styles')

<style>

  /* ==================== HEADER ==================== */

  .stocks-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.75rem;
  }

  .stocks-header h3 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--ink);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.65rem;
  }

  .stocks-header h3 .icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: var(--r-md);
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
    color: var(--cyan-ink);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    box-shadow: 0 6px 20px -6px var(--glow);
  }

  .stocks-header p {
    color: var(--ink-muted);
    margin: 0.25rem 0 0;
    font-size: 0.88rem;
  }


  /* ==================== PANEL ==================== */

  .panel {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    overflow-x: auto;
    overflow-y: hidden;
    box-shadow: 0 20px 40px -28px var(--shadow);
  }

  .panel-body {
    padding: 1.35rem;
  }


  /* ==================== DATATABLE ==================== */

  .dataTables_wrapper {
    color: var(--ink);
    font-size: 0.85rem;
  }

  .dataTables_wrapper .dt-top {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    width: 100%;
  }

  .dataTables_wrapper .dt-top > .dataTables_length,
  .dataTables_wrapper .dt-top > .dataTables_filter {
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
    width: auto !important;
    text-align: left !important;
    display: block !important;
  }

  .dataTables_wrapper .dt-top > .dataTables_filter {
    margin-left: auto !important;
  }

  .dataTables_wrapper .dt-top .dataTables_length label,
  .dataTables_wrapper .dt-top .dataTables_filter label {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    margin: 0 !important;
    padding: 0 !important;
    font-size: 0.82rem;
    color: var(--ink-muted);
    white-space: nowrap;
    font-weight: 500;
  }

  .dataTables_wrapper .dt-top .dataTables_length select {
    background: var(--bg-2) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-md) !important;
    padding: 0 1.75rem 0 0.65rem !important;
    font-size: 0.82rem;
    cursor: pointer;
    height: 36px;
    margin: 0 !important;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238fa8ae' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.65rem center !important;
  }

  .dataTables_wrapper .dt-top .dataTables_filter input {
    background: var(--bg-2) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink) !important;
    border-radius: var(--r-md) !important;
    padding: 0.4rem 0.85rem !important;
    font-size: 0.85rem;
    margin: 0 0 0 0.5rem !important;
    height: 36px;
    min-width: 220px;
    transition: all 0.25s var(--ease);
  }

  .dataTables_wrapper .dt-top .dataTables_filter input:focus {
    border-color: var(--cyan) !important;
    box-shadow: 0 0 0 3px var(--glow-soft) !important;
    outline: none;
  }

  .dataTables_wrapper .dt-bottom {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 1rem;
    margin-top: 1rem;
    flex-wrap: wrap;
    width: 100%;
  }

  .dataTables_wrapper .dt-bottom > .dataTables_info,
  .dataTables_wrapper .dt-bottom > .dataTables_paginate {
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
    width: auto !important;
    text-align: left !important;
  }

  .dataTables_wrapper .dt-bottom > .dataTables_paginate {
    margin-left: auto !important;
  }

  .dataTables_wrapper .dt-bottom .dataTables_info {
    font-size: 0.78rem;
    color: var(--ink-faint);
    line-height: 36px;
    padding-top: 0 !important;
  }

  .dataTables_wrapper .dt-bottom .dataTables_paginate .pagination {
    margin: 0 !important;
    justify-content: flex-end !important;
    display: flex;
    align-items: center;
    gap: 3px;
  }

  .dataTables_wrapper .dataTables_paginate .page-item .page-link {
    background: var(--bg-1) !important;
    border: 1px solid var(--line) !important;
    color: var(--ink-muted) !important;
    border-radius: var(--r-sm) !important;
    padding: 0.35rem 0.7rem;
    font-size: 0.82rem;
    transition: all 0.25s var(--ease);
    margin: 0;
  }

  .dataTables_wrapper .dataTables_paginate .page-item .page-link:hover {
    border-color: var(--line-strong) !important;
    color: var(--cyan) !important;
  }

  .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
    background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
    color: var(--cyan-ink) !important;
    border-color: transparent !important;
    box-shadow: 0 6px 16px -6px var(--glow);
    font-weight: 700;
  }

  .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
    opacity: 0.4;
    cursor: not-allowed;
  }


  /* ==================== TABLA ==================== */

  .panel table.dataTable {
    width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0;
    margin: 0 !important;
  }

  .panel table.dataTable thead th {
    background: color-mix(in srgb, var(--bg-2) 60%, transparent) !important;
    color: var(--ink-muted) !important;
    font-family: var(--font-display);
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 0.75rem 0.75rem !important;
    border-bottom: 1px solid var(--line) !important;
    border-top: none !important;
    white-space: nowrap;
  }

  .panel table.dataTable tbody td {
    padding: 0.75rem 0.75rem !important;
    border-top: 1px solid var(--line) !important;
    color: var(--ink);
    font-size: 0.85rem;
    vertical-align: middle;
  }

  .panel table.dataTable tbody tr:hover {
    background: var(--surface-alpha) !important;
  }

  .panel table.dataTable tbody tr.odd {
    background: color-mix(in srgb, var(--bg-2) 35%, transparent);
  }


  /* ==================== PRODUCTO ==================== */

  .cell-product-name {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.85rem;
    display: block;
    max-width: 230px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .cell-product-meta {
    font-size: 0.72rem;
    color: var(--ink-faint);
    display: flex;
    gap: 0.35rem;
    align-items: center;
    margin-top: 0.15rem;
  }


  /* ==================== EXISTENCIA ==================== */

  .existencia {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.18rem;
    min-width: 125px;
  }

  .existencia-linea {
    display: flex;
    align-items: baseline;
    justify-content: flex-end;
    gap: 0.3rem;
    white-space: nowrap;
  }

  .existencia-cantidad {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.94rem;
    color: var(--cyan);
  }

  .existencia-unidad {
    font-size: 0.68rem;
    font-weight: 600;
    color: var(--ink-muted);
  }

  .existencia-secundaria .existencia-cantidad {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--ink);
  }

  .existencia-secundaria .existencia-unidad {
    font-size: 0.64rem;
    color: var(--ink-faint);
  }

  .existencia-vacia {
    color: var(--ink-faint);
    font-size: 0.78rem;
  }


  /* ==================== STOCK ==================== */

  .stock-qty {
    display: inline-flex;
    align-items: baseline;
    gap: 0.3rem;
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1rem;
    padding: 0.3rem 0.65rem;
    border-radius: var(--r-pill);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    border: 1px solid var(--line-strong);
    color: var(--cyan);
    white-space: nowrap;
  }

  .stock-qty.zero {
    background: color-mix(in srgb, var(--error) 10%, transparent);
    border-color: color-mix(in srgb, var(--error) 40%, transparent);
    color: var(--error);
  }

  .stock-qty.low {
    background: color-mix(in srgb, var(--warning) 12%, transparent);
    border-color: color-mix(in srgb, var(--warning) 40%, transparent);
    color: var(--warning);
  }

  .stock-qty.over {
    background: color-mix(in srgb, var(--error) 10%, transparent);
    border-color: color-mix(in srgb, var(--error) 40%, transparent);
    color: var(--error);
  }


  /* ==================== ESTADO ==================== */

  .estado-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    border-radius: var(--r-pill);
    padding: 0.3rem 0.65rem;
    font-size: 0.72rem;
    font-weight: 700;
    white-space: nowrap;
  }

  .estado-normal {
    color: var(--cyan);
    background: color-mix(in srgb, var(--cyan) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--cyan) 30%, transparent);
  }

  .estado-bajo {
    color: var(--warning);
    background: color-mix(in srgb, var(--warning) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--warning) 30%, transparent);
  }

  .estado-sin-stock {
    color: var(--error);
    background: color-mix(in srgb, var(--error) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--error) 30%, transparent);
  }

  .estado-sobre {
    color: var(--error);
    background: color-mix(in srgb, var(--error) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--error) 30%, transparent);
  }


  /* ==================== BOTONES ==================== */

  .btn-limites {
    border: 1px solid var(--line);
    background: var(--bg-2);
    color: var(--ink-muted);
    border-radius: var(--r-sm);
    padding: 0.35rem 0.55rem;
    transition: all 0.25s var(--ease);
  }

  .btn-limites:hover {
    border-color: var(--cyan);
    color: var(--cyan);
    background: color-mix(in srgb, var(--cyan) 8%, transparent);
  }


  /* ==================== MODALES ==================== */

  .modal-content {
    background: var(--bg-1);
    border: 1px solid var(--line);
    color: var(--ink);
    border-radius: var(--r-lg);
  }

  .modal-header,
  .modal-footer {
    border-color: var(--line);
  }

  .modal-title {
    font-family: var(--font-display);
    font-weight: 600;
  }

  .modal-body .form-label {
    color: var(--ink-muted);
    font-size: 0.82rem;
    font-weight: 600;
  }

  .modal-body .form-control {
    background: var(--bg-2);
    border-color: var(--line);
    color: var(--ink);
  }

  .modal-body .form-control:focus {
    background: var(--bg-2);
    color: var(--ink);
    border-color: var(--cyan);
    box-shadow: 0 0 0 3px var(--glow-soft);
  }


  /* ==================== DETALLE PRODUCTO ==================== */

  .detalle-seccion {
    border: 1px solid var(--line);
    border-radius: var(--r-md);
    padding: 1rem;
    background: var(--bg-2);
    margin-bottom: 1rem;
  }

  .detalle-seccion:last-child {
    margin-bottom: 0;
  }

  .detalle-seccion-titulo {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    color: var(--cyan);
    font-family: var(--font-display);
    font-size: 0.82rem;
    font-weight: 700;
    margin-bottom: 0.85rem;
  }

  .detalle-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem 1.25rem;
  }

  .detalle-item {
    min-width: 0;
  }

  .detalle-label {
    display: block;
    color: var(--ink-faint);
    font-size: 0.7rem;
    margin-bottom: 0.15rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .detalle-valor {
    display: block;
    color: var(--ink);
    font-size: 0.84rem;
    font-weight: 600;
    word-break: break-word;
  }

  .detalle-stock-principal {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 0.75rem;
  }

  .detalle-stock-card {
    border: 1px solid var(--line);
    border-radius: var(--r-md);
    padding: 0.85rem;
    text-align: center;
    background: var(--bg-1);
  }

  .detalle-stock-card .numero {
    display: block;
    color: var(--cyan);
    font-family: var(--font-display);
    font-size: 1.15rem;
    font-weight: 700;
  }

  .detalle-stock-card .unidad {
    display: block;
    color: var(--ink-muted);
    font-size: 0.7rem;
    margin-top: 0.15rem;
  }

  .detalle-admin {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.7rem 0;
    border-bottom: 1px solid var(--line);
  }

  .detalle-admin:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }

  .detalle-admin:first-child {
    padding-top: 0;
  }

  .detalle-admin-nombre {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    color: var(--ink);
    font-size: 0.82rem;
    font-weight: 600;
  }

  .detalle-admin-nombre i {
    color: var(--cyan);
  }

  .detalle-admin-cantidad {
    color: var(--cyan);
    font-family: var(--font-display);
    font-size: 0.82rem;
    font-weight: 700;
    text-align: right;
  }

  .detalle-admin-cantidad small {
    color: var(--ink-muted);
    font-family: inherit;
    font-size: 0.68rem;
    font-weight: 500;
  }

  .detalle-estado {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    border-radius: var(--r-pill);
    padding: 0.35rem 0.7rem;
    font-size: 0.75rem;
    font-weight: 700;
  }


  /* ==================== RESPONSIVE ==================== */

  @media (max-width: 767.98px) {

    .dataTables_wrapper .dt-top,
    .dataTables_wrapper .dt-bottom {
      flex-direction: column;
      align-items: stretch !important;
    }

    .dataTables_wrapper .dt-top > .dataTables_filter,
    .dataTables_wrapper .dt-bottom > .dataTables_paginate {
      margin-left: 0 !important;
      width: 100%;
    }

    .dataTables_wrapper .dt-top .dataTables_filter input {
      width: 100%;
      min-width: 0;
    }

    .existencia {
      align-items: flex-start;
    }

    .existencia-linea {
      justify-content: flex-start;
    }

    .detalle-grid {
      grid-template-columns: 1fr;
    }

    .detalle-stock-principal {
      grid-template-columns: 1fr;
    }

  }

</style>

@endpush


@section('content')

<div class="container-fluid">


  {{-- ==================== HEADER ==================== --}}

  <div class="stocks-header">

    <div>

      <h3>

        <span class="icon-wrap">
          <i class="mdi mdi-warehouse"></i>
        </span>

        Inventario

      </h3>

      <p>
        Existencias actuales de todos los productos y distribución por administrador
      </p>

    </div>

  </div>


  {{-- ==================== MENSAJES ==================== --}}

  @if(session('success'))

    <div
      class="alert alert-success alert-dismissible fade show"
      role="alert"
    >

      <i class="mdi mdi-check-circle-outline me-1"></i>

      {{ session('success') }}

      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
      ></button>

    </div>

  @endif


  @if(session('error'))

    <div
      class="alert alert-danger alert-dismissible fade show"
      role="alert"
    >

      <i class="mdi mdi-alert-circle-outline me-1"></i>

      {{ session('error') }}

      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
      ></button>

    </div>

  @endif


  {{-- ==================== TABLA ==================== --}}

  <div class="panel">

    <div class="panel-body">

      <table
        id="tablaStocks"
        class="table nowrap"
        style="width:100%"
      >

        <thead>

          <tr>

            <th>#</th>

            <th>Producto</th>

            <th>Categoría / Tipo</th>

            <th class="text-end">
              Existencia total
            </th>

            @foreach($admins as $admin)

              <th class="text-end">
                {{ $admin->name }}
              </th>

            @endforeach

            <th class="text-end">
              Mínimo
            </th>

            <th class="text-end">
              Máximo
            </th>

            <th>
              Estado
            </th>

            <th class="text-center">
              Acciones
            </th>

          </tr>

        </thead>


        <tbody>

        @forelse($inventario as $item)

          @php

            $producto = $item['producto'];

            $stockTotal = (float) $item['stock_total'];
            $stockMinimo = (float) $item['stock_minimo'];
            $stockMaximo = (float) $item['stock_maximo'];

            /*
             * ==========================================================
             * INFORMACIÓN DEL TIPO
             * ==========================================================
             */

            $tipoNombre = mb_strtolower(
                trim((string) ($producto->tipoProducto?->nombre ?? '')),
                'UTF-8'
            );


            /*
             * ==========================================================
             * UNIDAD DE LOS LÍMITES
             * ==========================================================
             */

            if (
                $tipoNombre === 'cerámica'
                || $tipoNombre === 'ceramica'
                || $tipoNombre === 'porcelanato'
            ) {

                $unidadLimite = 'cajas';

            } elseif (
                str_contains($tipoNombre, 'cemento cola')
            ) {

                $unidadLimite = 'bolsas';

            } else {

                $unidadLimite = 'piezas';

            }


            /*
             * ==========================================================
             * CONVERSIÓN DE EXISTENCIA
             * ==========================================================
             */

            $piezasPorCaja =
                (float) ($producto->piezas_por_caja ?? 0);

            $m2PorCaja =
                (float) ($producto->m2_por_caja ?? 0);

            $pesoProducto =
                (float) ($producto->peso ?? 0);

            $tieneCajas =
                $piezasPorCaja > 0;


            $cajasTotal = 0;
            $piezasTotal = 0;
            $m2Total = 0;


            if ($tieneCajas) {

                $cajasTotal = floor(
                    $stockTotal / $piezasPorCaja
                );

                $piezasTotal = $stockTotal;


                if ($m2PorCaja > 0) {

                    $m2Total =
                        ($stockTotal / $piezasPorCaja)
                        * $m2PorCaja;

                }

            }


            /*
             * ==========================================================
             * STOCK PARA COMPARAR LÍMITES
             * ==========================================================
             */

            if (
                $unidadLimite === 'cajas'
                && $piezasPorCaja > 0
            ) {

                $stockParaEstado =
                    $stockTotal / $piezasPorCaja;

            } else {

                $stockParaEstado =
                    $stockTotal;

            }


            /*
             * ==========================================================
             * ESTADO
             * ==========================================================
             */

            if ($stockTotal <= 0) {

                $estado = 'Sin stock';
                $estadoClase = 'estado-sin-stock';

            } elseif (
                $stockMinimo > 0
                && $stockParaEstado < $stockMinimo
            ) {

                $estado = 'Stock bajo';
                $estadoClase = 'estado-bajo';

            } elseif (
                $stockMaximo > 0
                && $stockParaEstado > $stockMaximo
            ) {

                $estado = 'Sobreexistencia';
                $estadoClase = 'estado-sobre';

            } else {

                $estado = 'Normal';
                $estadoClase = 'estado-normal';

            }


            /*
             * ==========================================================
             * CLASE VISUAL
             * ==========================================================
             */

            $stockClase = '';


            if ($stockTotal <= 0) {

                $stockClase = 'zero';

            } elseif (
                $stockMinimo > 0
                && $stockParaEstado < $stockMinimo
            ) {

                $stockClase = 'low';

            } elseif (
                $stockMaximo > 0
                && $stockParaEstado > $stockMaximo
            ) {

                $stockClase = 'over';

            }


            /*
             * ==========================================================
             * FORMATO
             * ==========================================================
             */

            $cantidadFormateada = function ($cantidad) {

                return rtrim(
                    rtrim(
                        number_format(
                            (float) $cantidad,
                            2,
                            '.',
                            ''
                        ),
                        '0'
                    ),
                    '.'
                );

            };


            /*
             * ==========================================================
             * DATOS DE STOCK POR DUEÑO
             * ==========================================================
             */

            $detalleAdmins = [];


            foreach ($admins as $admin) {

                $stockAdmin =
                    $item['stock_por_admin']->get($admin->id);

                $cantidadAdmin =
                    $stockAdmin
                        ? (float) $stockAdmin->cantidad
                        : 0;


                $cajasAdmin = 0;
                $piezasAdmin = 0;
                $m2Admin = 0;


                if ($tieneCajas) {

                    $cajasAdmin = floor(
                        $cantidadAdmin / $piezasPorCaja
                    );

                    $piezasAdmin = $cantidadAdmin;


                    if ($m2PorCaja > 0) {

                        $m2Admin =
                            ($cantidadAdmin / $piezasPorCaja)
                            * $m2PorCaja;

                    }

                }


                $detalleAdmins[] = [

                    'id' =>
                        $admin->id,

                    'nombre' =>
                        $admin->name,

                    'cantidad' =>
                        $cantidadAdmin,

                    'cajas' =>
                        $cajasAdmin,

                    'piezas' =>
                        $piezasAdmin,

                    'm2' =>
                        $m2Admin,

                ];

            }


            /*
             * ==========================================================
             * DATOS COMPLETOS PARA EL MODAL
             * ==========================================================
             */

            $detalleProducto = [

                'id' =>
                    $producto->id,

                'nombre' =>
                    $producto->nombre,

                'marca' =>
                    $producto->marca,

                'modelo' =>
                    $producto->modelo,

                'medida' =>
                    $producto->medida,

                'color' =>
                    $producto->color,

                'acabado' =>
                    $producto->acabado,

                'presentacion' =>
                    $producto->presentacion,

                'categoria' =>
                    $producto->tipoProducto?->categoria?->nombre,

                'tipo' =>
                    $producto->tipoProducto?->nombre,

                'piezas_caja' =>
                    $piezasPorCaja,

                'm2_caja' =>
                    $m2PorCaja,

                'peso' =>
                    $pesoProducto,

                'tiene_cajas' =>
                    $tieneCajas,

                'stock_total' =>
                    $stockTotal,

                'stock_minimo' =>
                    $stockMinimo,

                'stock_maximo' =>
                    $stockMaximo,

                'unidad' =>
                    $unidadLimite,

                'cajas_total' =>
                    $cajasTotal,

                'piezas_total' =>
                    $piezasTotal,

                'm2_total' =>
                    $m2Total,

                'estado' =>
                    $estado,

                'estado_clase' =>
                    $estadoClase,

                'admins' =>
                    $detalleAdmins,

            ];

          @endphp


          <tr>


            {{-- ==================== ID ==================== --}}

            <td>
              {{ $producto->id }}
            </td>


            {{-- ==================== PRODUCTO ==================== --}}

            <td>

              <span
                class="cell-product-name"
                title="{{ $producto->nombre }}"
              >

                {{ $producto->nombre }}

              </span>


              @if(
                $producto->marca
                || $producto->modelo
                || $producto->medida
              )

                <span class="cell-product-meta">

                  @if($producto->marca)

                    <i class="mdi mdi-trademark"></i>

                    {{ $producto->marca }}

                  @endif


                  @if($producto->modelo)

                    · {{ $producto->modelo }}

                  @endif


                  @if($producto->medida)

                    · {{ $producto->medida }}

                  @endif

                </span>

              @endif

            </td>


            {{-- ==================== CATEGORÍA / TIPO ==================== --}}

            <td>

              <span
                style="
                  font-size:0.82rem;
                  color:var(--ink-muted);
                "
              >

                {{ $producto->tipoProducto?->categoria?->nombre ?? '—' }}

              </span>


              <br>


              <span
                style="
                  font-size:0.72rem;
                  color:var(--ink-faint);
                "
              >

                {{ $producto->tipoProducto?->nombre ?? '' }}

              </span>

            </td>


            {{-- ==================== EXISTENCIA TOTAL ==================== --}}

            <td class="text-end">

              <div class="existencia">

                @if($tieneCajas)


                  {{-- CAJAS --}}

                  <div class="existencia-linea">

                    <span class="existencia-cantidad">

                      {{ $cantidadFormateada($cajasTotal) }}

                    </span>

                    <span class="existencia-unidad">

                      {{ $cajasTotal == 1 ? 'caja' : 'cajas' }}

                    </span>

                  </div>


                  {{-- PIEZAS --}}

                  <div class="existencia-linea existencia-secundaria">

                    <span class="existencia-cantidad">

                      {{ $cantidadFormateada($piezasTotal) }}

                    </span>

                    <span class="existencia-unidad">

                      {{ $piezasTotal == 1 ? 'pieza' : 'piezas' }}

                    </span>

                  </div>


                  {{-- M² --}}

                  @if($m2PorCaja > 0)

                    <div class="existencia-linea existencia-secundaria">

                      <span class="existencia-cantidad">

                        {{ $cantidadFormateada($m2Total) }}

                      </span>

                      <span class="existencia-unidad">
                        m²
                      </span>

                    </div>

                  @endif


                @else


                  {{-- PRODUCTOS SIN CAJA --}}

                  <div class="existencia-linea">

                    <span class="existencia-cantidad">

                      {{ $cantidadFormateada($stockTotal) }}

                    </span>

                    <span class="existencia-unidad">

                      {{ $unidadLimite }}

                    </span>

                  </div>


                @endif

              </div>

            </td>


            {{-- ==================== STOCK POR ADMIN ==================== --}}

            @foreach($admins as $admin)

              @php

                $stockAdmin =
                    $item['stock_por_admin']->get($admin->id);

                $cantidadAdmin =
                    $stockAdmin
                        ? (float) $stockAdmin->cantidad
                        : 0;

                $cajasAdmin = 0;
                $piezasAdmin = 0;
                $m2Admin = 0;


                if ($tieneCajas) {

                    $cajasAdmin = floor(
                        $cantidadAdmin / $piezasPorCaja
                    );

                    $piezasAdmin = $cantidadAdmin;


                    if ($m2PorCaja > 0) {

                        $m2Admin =
                            ($cantidadAdmin / $piezasPorCaja)
                            * $m2PorCaja;

                    }

                }

              @endphp


              <td class="text-end">

                <div class="existencia">

                  @if($tieneCajas)


                    <div class="existencia-linea">

                      <span class="existencia-cantidad">

                        {{ $cantidadFormateada($cajasAdmin) }}

                      </span>

                      <span class="existencia-unidad">

                        {{ $cajasAdmin == 1 ? 'caja' : 'cajas' }}

                      </span>

                    </div>


                    <div class="existencia-linea existencia-secundaria">

                      <span class="existencia-cantidad">

                        {{ $cantidadFormateada($piezasAdmin) }}

                      </span>

                      <span class="existencia-unidad">

                        {{ $piezasAdmin == 1 ? 'pieza' : 'piezas' }}

                      </span>

                    </div>


                    @if($m2PorCaja > 0)

                      <div class="existencia-linea existencia-secundaria">

                        <span class="existencia-cantidad">

                          {{ $cantidadFormateada($m2Admin) }}

                        </span>

                        <span class="existencia-unidad">
                          m²
                        </span>

                      </div>

                    @endif


                  @else


                    <div class="existencia-linea">

                      <span class="existencia-cantidad">

                        {{ $cantidadFormateada($cantidadAdmin) }}

                      </span>

                      <span class="existencia-unidad">

                        {{ $unidadLimite }}

                      </span>

                    </div>


                  @endif

                </div>

              </td>

            @endforeach


            {{-- ==================== MÍNIMO ==================== --}}

            <td class="text-end">

              <div class="existencia">

                <div class="existencia-linea">

                  <span
                    class="existencia-cantidad"
                    style="
                      color:var(--ink-muted);
                      font-size:0.85rem;
                    "
                  >

                    {{ $cantidadFormateada($stockMinimo) }}

                  </span>

                  <span class="existencia-unidad">

                    {{ $unidadLimite }}

                  </span>

                </div>

              </div>

            </td>


            {{-- ==================== MÁXIMO ==================== --}}

            <td class="text-end">

              <div class="existencia">

                <div class="existencia-linea">

                  <span
                    class="existencia-cantidad"
                    style="
                      color:var(--ink-muted);
                      font-size:0.85rem;
                    "
                  >

                    {{ $cantidadFormateada($stockMaximo) }}

                  </span>

                  <span class="existencia-unidad">

                    {{ $unidadLimite }}

                  </span>

                </div>

              </div>

            </td>


            {{-- ==================== ESTADO ==================== --}}

            <td>

              <span class="estado-badge {{ $estadoClase }}">

                @if($estado === 'Normal')

                  <i class="mdi mdi-check-circle-outline"></i>

                @elseif($estado === 'Stock bajo')

                  <i class="mdi mdi-alert-outline"></i>

                @elseif($estado === 'Sin stock')

                  <i class="mdi mdi-close-circle-outline"></i>

                @else

                  <i class="mdi mdi-alert-circle-outline"></i>

                @endif

                {{ $estado }}

              </span>

            </td>


            {{-- ==================== ACCIONES ==================== --}}

            <td class="text-center">

              <div class="d-flex justify-content-center gap-2">


                {{-- VER DETALLE --}}

                <button
                  type="button"
                  class="btn-limites"
                  title="Ver detalle del producto"
                  data-bs-toggle="modal"
                  data-bs-target="#modalDetalleProducto"
                  data-detalle='@json($detalleProducto)'
                >

                  <i class="mdi mdi-eye-outline"></i>

                </button>


                {{-- CONFIGURAR LÍMITES --}}

                <button
                  type="button"
                  class="btn-limites"
                  title="Configurar límites"
                  data-bs-toggle="modal"
                  data-bs-target="#modalLimites"
                  data-id="{{ $producto->id }}"
                  data-nombre="{{ $producto->nombre }}"
                  data-minimo="{{ $stockMinimo }}"
                  data-maximo="{{ $stockMaximo }}"
                  data-unidad="{{ $unidadLimite }}"
                >

                  <i class="mdi mdi-tune-variant"></i>

                </button>


              </div>

            </td>


          </tr>


        @empty


          <tr>

            <td
              colspan="{{ 8 + $admins->count() }}"
              class="text-center py-5"
            >

              <i
                class="mdi mdi-warehouse"
                style="
                  font-size:44px;
                  opacity:0.3;
                  color:var(--ink-faint);
                "
              ></i>


              <p
                class="mt-2 mb-0"
                style="color:var(--ink-muted);"
              >

                No hay productos registrados.

              </p>

            </td>

          </tr>


        @endforelse


        </tbody>

      </table>

    </div>

  </div>

</div>



{{-- ================================================================ --}}
{{-- MODAL LÍMITES                                                    --}}
{{-- ================================================================ --}}

<div
  class="modal fade"
  id="modalLimites"
  tabindex="-1"
  aria-hidden="true"
>

  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content">

      <form
        id="formLimites"
        method="POST"
      >

        @csrf

        @method('PATCH')


        <div class="modal-header">

          <h5 class="modal-title">
            Configurar límites de inventario
          </h5>


          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
          ></button>

        </div>


        <div class="modal-body">

          <p
            class="mb-4"
            style="color:var(--ink-muted);"
          >

            Producto:

            <strong
              id="limitesNombreProducto"
              style="color:var(--ink);"
            >
            </strong>

          </p>


          <div class="mb-3">

            <label class="form-label">

              Stock mínimo

              <span
                id="unidadMinimo"
                style="
                  color:var(--cyan);
                  font-weight:700;
                "
              >
                piezas
              </span>

            </label>


            <input
              type="number"
              name="stock_minimo"
              id="limitesMinimo"
              class="form-control"
              min="0"
              step="0.01"
              required
            >


            <small style="color:var(--ink-faint);">

              Si el stock total queda por debajo de este valor,
              aparecerá como stock bajo.

            </small>

          </div>


          <div class="mb-3">

            <label class="form-label">

              Stock máximo

              <span
                id="unidadMaximo"
                style="
                  color:var(--cyan);
                  font-weight:700;
                "
              >
                piezas
              </span>

            </label>


            <input
              type="number"
              name="stock_maximo"
              id="limitesMaximo"
              class="form-control"
              min="0"
              step="0.01"
              required
            >


            <small style="color:var(--ink-faint);">

              Si el stock total supera este valor,
              aparecerá como sobreexistencia.

            </small>

          </div>

        </div>


        <div class="modal-footer">

          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >

            Cancelar

          </button>


          <button
            type="submit"
            class="btn btn-primary"
          >

            <i class="mdi mdi-content-save-outline me-1"></i>

            Guardar límites

          </button>

        </div>


      </form>

    </div>

  </div>

</div>



{{-- ================================================================ --}}
{{-- MODAL DETALLE DEL PRODUCTO                                      --}}
{{-- ================================================================ --}}

<div
  class="modal fade"
  id="modalDetalleProducto"
  tabindex="-1"
  aria-hidden="true"
>

  <div
    class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"
  >

    <div class="modal-content">


      {{-- ==================== CABECERA ==================== --}}

      <div class="modal-header">

        <div>

          <h5 class="modal-title">

            <i class="mdi mdi-package-variant-closed me-1"></i>

            Detalle del producto

          </h5>


          <small
            id="detalleIdProducto"
            style="color:var(--ink-faint);"
          >
          </small>

        </div>


        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
        ></button>

      </div>


      {{-- ==================== CUERPO ==================== --}}

      <div class="modal-body">


        {{-- ==================== INFORMACIÓN GENERAL ==================== --}}

        <div class="detalle-seccion">

          <div class="detalle-seccion-titulo">

            <i class="mdi mdi-information-outline"></i>

            Información del producto

          </div>


          <div class="detalle-grid">


            <div class="detalle-item">

              <span class="detalle-label">
                Nombre
              </span>

              <span
                class="detalle-valor"
                id="detalleNombre"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Categoría
              </span>

              <span
                class="detalle-valor"
                id="detalleCategoria"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Tipo
              </span>

              <span
                class="detalle-valor"
                id="detalleTipo"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Marca
              </span>

              <span
                class="detalle-valor"
                id="detalleMarca"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Modelo
              </span>

              <span
                class="detalle-valor"
                id="detalleModelo"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Medida
              </span>

              <span
                class="detalle-valor"
                id="detalleMedida"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Color
              </span>

              <span
                class="detalle-valor"
                id="detalleColor"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Acabado
              </span>

              <span
                class="detalle-valor"
                id="detalleAcabado"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Presentación
              </span>

              <span
                class="detalle-valor"
                id="detallePresentacion"
              >
              </span>

            </div>


            <div
              class="detalle-item"
              id="detallePesoContainer"
            >

              <span class="detalle-label">
                Peso
              </span>

              <span
                class="detalle-valor"
                id="detallePeso"
              >
              </span>

            </div>


          </div>

        </div>


        {{-- ==================== CARACTERÍSTICAS ==================== --}}

        <div
          class="detalle-seccion"
          id="detalleCaracteristicas"
        >

          <div class="detalle-seccion-titulo">

            <i class="mdi mdi-package-variant"></i>

            Características de presentación

          </div>


          <div class="detalle-grid">


            <div
              class="detalle-item"
              id="detallePiezasCajaContainer"
            >

              <span class="detalle-label">
                Piezas por caja
              </span>

              <span
                class="detalle-valor"
                id="detallePiezasCaja"
              >
              </span>

            </div>


            <div
              class="detalle-item"
              id="detalleM2CajaContainer"
            >

              <span class="detalle-label">
                m² por caja
              </span>

              <span
                class="detalle-valor"
                id="detalleM2Caja"
              >
              </span>

            </div>


          </div>

        </div>


        {{-- ==================== EXISTENCIA ==================== --}}

        <div class="detalle-seccion">

          <div class="detalle-seccion-titulo">

            <i class="mdi mdi-warehouse"></i>

            Existencia actual

          </div>


          {{-- PRODUCTOS CON CAJAS --}}

          <div
            class="detalle-stock-principal"
            id="detalleStockCajas"
          >

            <div class="detalle-stock-card">

              <span
                class="numero"
                id="detalleCajasTotal"
              >
                0
              </span>

              <span class="unidad">
                cajas
              </span>

            </div>


            <div class="detalle-stock-card">

              <span
                class="numero"
                id="detallePiezasTotal"
              >
                0
              </span>

              <span class="unidad">
                piezas
              </span>

            </div>


            <div class="detalle-stock-card">

              <span
                class="numero"
                id="detalleM2Total"
              >
                0
              </span>

              <span class="unidad">
                m²
              </span>

            </div>

          </div>


          {{-- PRODUCTOS SIN CAJAS --}}

          <div
            id="detalleStockSimple"
            class="detalle-stock-card"
            style="display:none;"
          >

            <span
              class="numero"
              id="detalleStockSimpleCantidad"
            >
              0
            </span>

            <span
              class="unidad"
              id="detalleStockSimpleUnidad"
            >
              piezas
            </span>

          </div>


        </div>


        {{-- ==================== CONTROL ==================== --}}

        <div class="detalle-seccion">

          <div class="detalle-seccion-titulo">

            <i class="mdi mdi-chart-line"></i>

            Control de inventario

          </div>


          <div class="detalle-grid">


            <div class="detalle-item">

              <span class="detalle-label">
                Stock mínimo
              </span>

              <span
                class="detalle-valor"
                id="detalleMinimo"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Stock máximo
              </span>

              <span
                class="detalle-valor"
                id="detalleMaximo"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Unidad de control
              </span>

              <span
                class="detalle-valor"
                id="detalleUnidad"
              >
              </span>

            </div>


            <div class="detalle-item">

              <span class="detalle-label">
                Estado
              </span>

              <span
                id="detalleEstado"
                class="detalle-estado"
              >
              </span>

            </div>


          </div>

        </div>


        {{-- ==================== STOCK POR DUEÑO ==================== --}}

        <div class="detalle-seccion">

          <div class="detalle-seccion-titulo">

            <i class="mdi mdi-account-group-outline"></i>

            Stock por dueño

          </div>


          <div id="detalleStockAdmins"></div>

        </div>


      </div>


      {{-- ==================== FOOTER ==================== --}}

      <div class="modal-footer">

        <button
          type="button"
          class="btn btn-secondary"
          data-bs-dismiss="modal"
        >

          Cerrar

        </button>

      </div>


    </div>

  </div>

</div>



@endsection



@push('scripts')

<script>

  /* ================================================================
   * DATATABLE
   * ================================================================ */

  const dtEspanol = {

    decimal: ',',

    emptyTable: 'No hay datos disponibles',

    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',

    infoEmpty: 'Mostrando 0 a 0 de 0 registros',

    infoFiltered: '(filtrado de _MAX_ registros totales)',

    lengthMenu: 'Mostrar _MENU_ registros',

    loadingRecords: 'Cargando...',

    processing: 'Procesando...',

    search: 'Buscar:',

    zeroRecords: 'No se encontraron resultados',

    paginate: {

      first: 'Primero',

      last: 'Último',

      next: 'Siguiente',

      previous: 'Anterior'

    },

    aria: {

      sortAscending: ': ordenar ascendentemente',

      sortDescending: ': ordenar descendentemente'

    },

  };


  $(document).ready(function () {


    /* ================================================================
     * DATATABLE
     * ================================================================ */

    $('#tablaStocks').DataTable({

      language: dtEspanol,

      pageLength: 10,

      lengthMenu: [

        [5, 10, 25, 50, -1],

        [5, 10, 25, 50, 'Todos']

      ],

      order: [

        [1, 'asc']

      ],

      dom: '<"dt-top"lf>t<"dt-bottom"ip>',

    });



    /* ================================================================
     * MODAL LÍMITES
     * ================================================================ */

    const modalLimites =
      document.getElementById('modalLimites');


    modalLimites.addEventListener(
      'show.bs.modal',
      function (event) {


        const button =
          event.relatedTarget;


        const id =
          button.getAttribute('data-id');


        const nombre =
          button.getAttribute('data-nombre');


        const minimo =
          button.getAttribute('data-minimo');


        const maximo =
          button.getAttribute('data-maximo');


        const unidad =
          button.getAttribute('data-unidad')
          || 'piezas';


        document.getElementById(
          'limitesNombreProducto'
        ).textContent =
          nombre;


        document.getElementById(
          'limitesMinimo'
        ).value =
          minimo;


        document.getElementById(
          'limitesMaximo'
        ).value =
          maximo;


        document.getElementById(
          'unidadMinimo'
        ).textContent =
          unidad;


        document.getElementById(
          'unidadMaximo'
        ).textContent =
          unidad;


        document.getElementById(
          'formLimites'
        ).action =
          "{{ url('/admin/stocks/productos') }}/"
          + id
          + "/limites";

      });



    /* ================================================================
     * MODAL DETALLE PRODUCTO
     * ================================================================ */

    const modalDetalleProducto =
      document.getElementById(
        'modalDetalleProducto'
      );


    modalDetalleProducto.addEventListener(
      'show.bs.modal',
      function (event) {


        const button =
          event.relatedTarget;


        const detalleJson =
          button.getAttribute(
            'data-detalle'
          );


        if (!detalleJson) {

          return;

        }


        let producto;


        try {

          producto =
            JSON.parse(detalleJson);

        } catch (error) {

          console.error(
            'No se pudo cargar el detalle del producto:',
            error
          );

          return;

        }


        /* ============================================================
         * FUNCIÓN PARA FORMATEAR NÚMEROS
         * ============================================================ */

        const formatear =
          function (valor) {

            const numero =
              Number(valor || 0);

            return numero
              .toLocaleString(
                'es-BO',
                {
                  minimumFractionDigits: 0,
                  maximumFractionDigits: 2
                }
              );

          };


        /* ============================================================
         * FUNCIÓN PARA TEXTO VACÍO
         * ============================================================ */

        const texto =
          function (valor) {

            if (
              valor === null
              || valor === undefined
              || valor === ''
            ) {

              return '—';

            }

            return valor;

          };


        /* ============================================================
         * INFORMACIÓN GENERAL
         * ============================================================ */

        document.getElementById(
          'detalleIdProducto'
        ).textContent =
          'Código de producto #' +
          producto.id;


        document.getElementById(
          'detalleNombre'
        ).textContent =
          texto(producto.nombre);


        document.getElementById(
          'detalleCategoria'
        ).textContent =
          texto(producto.categoria);


        document.getElementById(
          'detalleTipo'
        ).textContent =
          texto(producto.tipo);


        document.getElementById(
          'detalleMarca'
        ).textContent =
          texto(producto.marca);


        document.getElementById(
          'detalleModelo'
        ).textContent =
          texto(producto.modelo);


        document.getElementById(
          'detalleMedida'
        ).textContent =
          texto(producto.medida);


        document.getElementById(
          'detalleColor'
        ).textContent =
          texto(producto.color);


        document.getElementById(
          'detalleAcabado'
        ).textContent =
          texto(producto.acabado);


        document.getElementById(
          'detallePresentacion'
        ).textContent =
          texto(producto.presentacion);



        /* ============================================================
         * PESO
         * ============================================================ */

        const pesoContainer =
          document.getElementById(
            'detallePesoContainer'
          );


        if (
          Number(producto.peso || 0) > 0
        ) {

          pesoContainer.style.display =
            'block';


          document.getElementById(
            'detallePeso'
          ).textContent =
            formatear(producto.peso)
            + ' kg';

        } else {

          pesoContainer.style.display =
            'none';

        }



        /* ============================================================
         * CARACTERÍSTICAS
         * ============================================================ */

        const piezasCajaContainer =
          document.getElementById(
            'detallePiezasCajaContainer'
          );


        const m2CajaContainer =
          document.getElementById(
            'detalleM2CajaContainer'
          );


        const caracteristicas =
          document.getElementById(
            'detalleCaracteristicas'
          );


        let tieneCaracteristicas =
          false;


        if (
          Number(producto.piezas_caja || 0) > 0
        ) {

          piezasCajaContainer.style.display =
            'block';


          document.getElementById(
            'detallePiezasCaja'
          ).textContent =
            formatear(producto.piezas_caja)
            + ' piezas';


          tieneCaracteristicas =
            true;

        } else {

          piezasCajaContainer.style.display =
            'none';

        }


        if (
          Number(producto.m2_caja || 0) > 0
        ) {

          m2CajaContainer.style.display =
            'block';


          document.getElementById(
            'detalleM2Caja'
          ).textContent =
            formatear(producto.m2_caja)
            + ' m²';


          tieneCaracteristicas =
            true;

        } else {

          m2CajaContainer.style.display =
            'none';

        }


        if (tieneCaracteristicas) {

          caracteristicas.style.display =
            'block';

        } else {

          caracteristicas.style.display =
            'none';

        }



        /* ============================================================
         * EXISTENCIA
         * ============================================================ */

        const stockCajas =
          document.getElementById(
            'detalleStockCajas'
          );


        const stockSimple =
          document.getElementById(
            'detalleStockSimple'
          );


        if (producto.tiene_cajas) {

          stockCajas.style.display =
            'grid';


          stockSimple.style.display =
            'none';


          document.getElementById(
            'detalleCajasTotal'
          ).textContent =
            formatear(producto.cajas_total);


          document.getElementById(
            'detallePiezasTotal'
          ).textContent =
            formatear(producto.piezas_total);


          document.getElementById(
            'detalleM2Total'
          ).textContent =
            formatear(producto.m2_total);


        } else {

          stockCajas.style.display =
            'none';


          stockSimple.style.display =
            'block';


          document.getElementById(
            'detalleStockSimpleCantidad'
          ).textContent =
            formatear(producto.stock_total);


          document.getElementById(
            'detalleStockSimpleUnidad'
          ).textContent =
            producto.unidad || 'piezas';

        }



        /* ============================================================
         * CONTROL DE INVENTARIO
         * ============================================================ */

        document.getElementById(
          'detalleMinimo'
        ).textContent =
          formatear(producto.stock_minimo)
          + ' '
          + (producto.unidad || 'piezas');


        document.getElementById(
          'detalleMaximo'
        ).textContent =
          formatear(producto.stock_maximo)
          + ' '
          + (producto.unidad || 'piezas');


        document.getElementById(
          'detalleUnidad'
        ).textContent =
          producto.unidad || 'piezas';



        /* ============================================================
         * ESTADO
         * ============================================================ */

        const estadoElemento =
          document.getElementById(
            'detalleEstado'
          );


        estadoElemento.textContent =
          '';


        const iconoEstado =
          document.createElement('i');


        if (
          producto.estado === 'Normal'
        ) {

          estadoElemento.className =
            'detalle-estado estado-normal';


          iconoEstado.className =
            'mdi mdi-check-circle-outline';


        } else if (
          producto.estado === 'Stock bajo'
        ) {

          estadoElemento.className =
            'detalle-estado estado-bajo';


          iconoEstado.className =
            'mdi mdi-alert-outline';


        } else if (
          producto.estado === 'Sin stock'
        ) {

          estadoElemento.className =
            'detalle-estado estado-sin-stock';


          iconoEstado.className =
            'mdi mdi-close-circle-outline';


        } else {

          estadoElemento.className =
            'detalle-estado estado-sobre';


          iconoEstado.className =
            'mdi mdi-alert-circle-outline';

        }


        estadoElemento.appendChild(
          iconoEstado
        );


        estadoElemento.appendChild(
          document.createTextNode(
            producto.estado
          )
        );



        /* ============================================================
         * STOCK POR DUEÑO
         * ============================================================ */

        const contenedorAdmins =
          document.getElementById(
            'detalleStockAdmins'
          );


        contenedorAdmins.innerHTML =
          '';


        if (
          producto.admins
          && producto.admins.length > 0
        ) {


          producto.admins.forEach(
            function (admin) {


              const fila =
                document.createElement(
                  'div'
                );


              fila.className =
                'detalle-admin';


              const nombre =
                document.createElement(
                  'div'
                );


              nombre.className =
                'detalle-admin-nombre';


              nombre.innerHTML =
                '<i class="mdi mdi-account-circle-outline"></i>'
                +
                ' '
                +
                admin.nombre;


              const cantidad =
                document.createElement(
                  'div'
                );


              cantidad.className =
                'detalle-admin-cantidad';


              if (producto.tiene_cajas) {


                let textoCantidad =
                  formatear(admin.cajas)
                  +
                  (
                    Number(admin.cajas) === 1
                      ? ' caja'
                      : ' cajas'
                  );


                textoCantidad +=
                  '<br><small>'
                  +
                  formatear(admin.piezas)
                  +
                  (
                    Number(admin.piezas) === 1
                      ? ' pieza'
                      : ' piezas'
                  );


                if (
                  Number(producto.m2_caja || 0) > 0
                ) {

                  textoCantidad +=
                    ' · '
                    +
                    formatear(admin.m2)
                    +
                    ' m²';

                }


                cantidad.innerHTML =
                  textoCantidad;


              } else {


                cantidad.textContent =
                  formatear(
                    admin.cantidad
                  )
                  +
                  ' '
                  +
                  (
                    producto.unidad
                    || 'piezas'
                  );

              }


              fila.appendChild(
                nombre
              );


              fila.appendChild(
                cantidad
              );


              contenedorAdmins.appendChild(
                fila
              );

            }
          );


        } else {


          contenedorAdmins.innerHTML =
            '<div style="color:var(--ink-faint);font-size:0.82rem;">'
            +
            'No hay información de stock por dueño.'
            +
            '</div>';

        }

      });

  });

</script>

@endpush
```
