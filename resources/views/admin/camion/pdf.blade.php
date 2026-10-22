<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Camión - Cerámica Ameri</title>

    <style>
        @page {
            margin: 25px 25px 35px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #263238;
        }

        h1, h2, h3, p {
            margin-top: 0;
        }

        .encabezado {
            border-bottom: 2px solid #263238;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .empresa {
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitulo {
            color: #546e7a;
            font-size: 10px;
        }

        .periodo {
            margin-top: 8px;
            font-size: 9px;
        }

        .resumen {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .resumen td {
            width: 25%;
            border: 1px solid #d6dce0;
            padding: 9px;
            vertical-align: top;
        }

        .resumen .etiqueta {
            color: #546e7a;
            font-size: 8px;
            margin-bottom: 5px;
        }

        .resumen .valor {
            font-size: 13px;
            font-weight: bold;
        }

        h2 {
            font-size: 12px;
            margin-bottom: 8px;
            margin-top: 17px;
            padding-bottom: 5px;
            border-bottom: 1px solid #cfd8dc;
        }

        table.detalle {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8px;
        }

        table.detalle th {
            background-color: #263238;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 6px 4px;
            border: 1px solid #263238;
            word-wrap: break-word;
        }

        table.detalle td {
            padding: 5px 4px;
            border: 1px solid #d6dce0;
            vertical-align: top;
            word-wrap: break-word;
        }

        table.detalle tr:nth-child(even) td {
            background-color: #f5f7f8;
        }

        .numero {
            text-align: right;
        }

        .centrado {
            text-align: center;
        }

        .total td {
            background-color: #e8edf0 !important;
            font-weight: bold;
        }

        .sin-registros {
            padding: 12px;
            text-align: center;
            color: #78909c;
            border: 1px solid #d6dce0;
        }

        .nota {
            margin-top: 12px;
            color: #546e7a;
            font-size: 8px;
        }

        .pie {
            position: fixed;
            bottom: -22px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #78909c;
        }

        .estado-pagado {
            color: #1b5e20;
            font-weight: bold;
        }

        .estado-pendiente {
            color: #b45309;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- ENCABEZADO -->

    <div class="encabezado">
        <div class="empresa">CERÁMICA AMERI</div>
        <div class="subtitulo">Reporte de gestión del camión</div>

        <div class="periodo">
            <strong>Período:</strong>

            {{ $desde ? \Carbon\Carbon::parse($desde)->format('d/m/Y') : 'Inicio del historial' }}

            al

            {{ $hasta ? \Carbon\Carbon::parse($hasta)->format('d/m/Y') : 'Fecha actual' }}

            <br>

            <strong>Generado:</strong>
            {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>


    <!-- RESUMEN FINANCIERO -->

    <table class="resumen">
        <tr>
            <td>
                <div class="etiqueta">Ingresos cobrados</div>

                <div class="valor">
                    Bs {{ number_format($ingresosCobrados, 2, ',', '.') }}
                </div>
            </td>

            <td>
                <div class="etiqueta">Ingresos por cobrar</div>

                <div class="valor">
                    Bs {{ number_format($ingresosPendientes, 2, ',', '.') }}
                </div>
            </td>

            <td>
                <div class="etiqueta">Total de gastos</div>

                <div class="valor">
                    Bs {{ number_format($totalGastos, 2, ',', '.') }}
                </div>
            </td>

            <td>
                <div class="etiqueta">Balance de caja</div>

                <div class="valor">
                    Bs {{ number_format($balance, 2, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>


    <!-- HISTORIAL DE VIAJES -->

    <h2>1. Historial de viajes</h2>

    @if($viajes->isNotEmpty())

        <table class="detalle">

            <thead>
                <tr>
                    <th style="width: 8%;">Fecha</th>
                    <th style="width: 10%;">Chofer</th>
                    <th style="width: 12%;">Registrado por</th>
                    <th style="width: 10%;">Origen</th>
                    <th style="width: 10%;">Destino</th>
                    <th style="width: 12%;">Mercadería</th>
                    <th style="width: 10%;">Monto</th>
                    <th style="width: 10%;">Estado de cobro</th>
                    <th style="width: 18%;">Observaciones</th>
                </tr>
            </thead>

            <tbody>

                @foreach($viajes as $viaje)

                    <tr>
                        <td>
                            {{ $viaje->fecha
                                ? \Carbon\Carbon::parse($viaje->fecha)->format('d/m/Y')
                                : '-' }}
                        </td>

                        <td>
                            {{ $viaje->chofer->nombre ?? '-' }}
                        </td>

                        <td>
                            {{ $viaje->usuario?->name ?? 'No registrado' }}
                        </td>

                        <td>
                            {{ $viaje->origen ?: '-' }}
                        </td>

                        <td>
                            {{ $viaje->destino ?: '-' }}
                        </td>

                        <td>
                            {{ $viaje->mercaderia ?: '-' }}
                        </td>

                        <td class="numero">
                            Bs {{ number_format((float) $viaje->monto_cobrado, 2, ',', '.') }}
                        </td>

                        <td class="centrado">
                            @if($viaje->estado_pago === 'pagado')
                                <span class="estado-pagado">
                                    Pagado
                                </span>
                            @else
                                <span class="estado-pendiente">
                                    Pendiente
                                </span>
                            @endif
                        </td>

                        <td>
                            {{ $viaje->observaciones ?: '-' }}
                        </td>
                    </tr>

                @endforeach

                <tr class="total">
                    <td colspan="6">
                        TOTAL DE VIAJES: {{ $viajes->count() }}
                    </td>

                    <td class="numero">
                        Bs {{ number_format($viajes->sum('monto_cobrado'), 2, ',', '.') }}
                    </td>

                    <td colspan="2"></td>
                </tr>

            </tbody>
        </table>

    @else

        <div class="sin-registros">
            No hay viajes registrados en este período.
        </div>

    @endif


    <!-- HISTORIAL DE GASTOS -->

    <h2>2. Historial de gastos</h2>

    @if($gastos->isNotEmpty())

        <table class="detalle">

            <thead>
                <tr>
                    <th style="width: 9%;">Fecha</th>
                    <th style="width: 12%;">Tipo de gasto</th>
                    <th style="width: 11%;">Combustible</th>
                    <th style="width: 20%;">Descripción</th>
                    <th style="width: 10%;">Monto</th>
                    <th style="width: 10%;">Kilometraje</th>
                    <th style="width: 16%;">Observaciones</th>
                    <th style="width: 12%;">Registrado por</th>
                </tr>
            </thead>

            <tbody>

                @foreach($gastos as $gasto)

                    <tr>
                        <td>
                            {{ $gasto->fecha
                                ? \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y')
                                : '-' }}
                        </td>

                        <td>
                            {{ $gasto->tipo_gasto ?: '-' }}
                        </td>

                        <td>
                            {{ $gasto->tipo_combustible ?: '-' }}
                        </td>

                        <td>
                            {{ $gasto->descripcion ?: '-' }}
                        </td>

                        <td class="numero">
                            Bs {{ number_format((float) $gasto->monto, 2, ',', '.') }}
                        </td>

                        <td class="numero">
                            {{ $gasto->kilometraje !== null
                                ? number_format((float) $gasto->kilometraje, 0, ',', '.')
                                : '-' }}
                        </td>

                        <td>
                            {{ $gasto->observaciones ?: '-' }}
                        </td>

                        <td>
                            {{ $gasto->usuario?->name ?? 'No registrado' }}
                        </td>
                    </tr>

                @endforeach

                <tr class="total">
                    <td colspan="4">
                        TOTAL DE GASTOS: {{ $gastos->count() }}
                    </td>

                    <td class="numero">
                        Bs {{ number_format($gastos->sum('monto'), 2, ',', '.') }}
                    </td>

                    <td colspan="3"></td>
                </tr>

            </tbody>
        </table>

    @else

        <div class="sin-registros">
            No hay gastos registrados en este período.
        </div>

    @endif

    <!-- NOTA -->

    <div class="nota">
        El balance de caja se calcula restando los gastos de los ingresos cobrados.
        Los ingresos pendientes no se incluyen en el balance de caja.
    </div>


    <!-- PIE DE PÁGINA -->

    <div class="pie">
        Cerámica AMERI — Reporte de gestión del camión
    </div>

</body>
</html>