
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <title>Recibo de venta</title>

    <style>
        @page {
            margin: 3mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            width: 74mm;
            margin: 0 auto;
            padding: 0;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            line-height: 1.35;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .company {
            font-size: 17px;
            font-weight: bold;
            letter-spacing: .5px;
            margin-bottom: 3px;
        }

        .subtitle {
            font-size: 10px;
            margin-bottom: 8px;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 7px 0;
        }

        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin: 7px 0 4px;
            font-size: 10px;
        }

        .line {
            margin: 2px 0;
            overflow-wrap: break-word;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        .products {
            width: 100%;
            border-collapse: collapse;
        }

        .products th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 4px 1px;
            font-size: 9px;
        }

        .products td {
            padding: 5px 1px;
            vertical-align: top;
            border-bottom: 1px dotted #777;
            font-size: 9px;
        }

        .products .amount {
            text-align: right;
            white-space: nowrap;
            width: 27%;
        }

        .product-name {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 3px;
        }

        .stock-detail {
            font-size: 8px;
            margin-top: 3px;
            padding-left: 4px;
        }

        .totals td {
            padding: 2px 0;
        }

        .total-final {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px !important;
        }

        .observations {
            overflow-wrap: break-word;
            white-space: pre-wrap;
        }

        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 9px;
        }

        .no-print {
            display: none;
        }
        .product-info {
            font-size: 8px;
            margin: 2px 0;
            overflow-wrap: break-word;
        }
        .price-breakdown {
            font-size: 8px;
            margin-top: 4px;
            line-height: 1.5;
        }

        .quantity-info {
            font-size: 9px;
            margin-top: 5px;
            padding: 3px;
            border-top: 1px dotted #777;
            border-bottom: 1px dotted #777;
        }

        .stock-detail {
            font-size: 8px;
            margin-top: 4px;
        }
    </style>
</head>

<body>

    {{-- ENCABEZADO --}}
    <div class="center">
        <div class="company">DISTRIBUIDORA AMERI</div>
        <div class="subtitle">RECIBO DE VENTA</div>
    </div>

    <div class="separator"></div>

    {{-- DATOS DE LA VENTA --}}
    <div class="line">
        <span class="bold">N.º de venta:</span>
        {{ $venta->numero_venta ?? $venta->id }}
    </div>

        <div class="line">
        <span class="bold">Fecha:</span>
        {{ $venta->fecha_venta
            ? \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y')
            : 'No registrada' }}
    </div>

    <div class="line">
        <span class="bold">Hora:</span>
        {{ $venta->created_at
            ? $venta->created_at->format('H:i')
            : 'No registrada' }}
    </div>

    <div class="line">
        <span class="bold">Vendedor:</span>
        {{ $venta->user->name ?? 'No registrado' }}
    </div>

    <div class="separator"></div>

    {{-- DATOS DEL CLIENTE --}}
    <div class="section-title">Datos del cliente</div>

    <div class="line">
        <span class="bold">Nombre:</span>
        {{ $venta->cliente_nombre ?: 'No registrado' }}
    </div>

    <div class="line">
        <span class="bold">CI / NIT:</span>
        {{ $venta->cliente_documento ?: 'No registrado' }}
    </div>

    <div class="line">
        <span class="bold">Teléfono:</span>
        {{ $venta->cliente_telefono ?: 'No registrado' }}
    </div>

    <div class="separator"></div>

    {{-- PRODUCTOS --}}
    <div class="section-title">Detalle de productos</div>

    <table class="products">
        <thead>
            <tr>
                <th>Producto</th>
                <th class="amount">Importe</th>
            </tr>
        </thead>

        <tbody>
            @php
                $productosAgrupados = $venta->detalles->groupBy('producto_id');
            @endphp

            @forelse($productosAgrupados as $grupo)

                @php
                    $producto = $grupo->first()->producto;

                    $tipo = mb_strtolower(
                        trim((string) ($producto?->tipoProducto?->nombre ?? '')),
                        'UTF-8'
                    );

                    $esCeramica = in_array($tipo, [
                        'cerámica',
                        'ceramica',
                        'porcelanato'
                    ], true);

                    $esPastina = $tipo === 'pastinas';

                    // Depósitos utilizados.
                    $depositos = $grupo
                        ->flatMap(fn($detalle) => $detalle->movimientos)
                        ->map(fn($movimiento) => $movimiento->deposito->nombre ?? null)
                        ->filter()
                        ->unique()
                        ->values();

                    // Características necesarias según el producto.
                    if ($esPastina) {
                        $atributos = [
                            'Modelo' => $producto->modelo ?? null,
                            'Medida' => $producto->medida ?? null,
                        ];
                    } else {
                        $atributos = [
                            'Marca' => $producto->marca ?? null,
                            'Medida' => $producto->medida ?? null,
                            'Color' => $producto->color ?? null,
                            'Acabado' => $producto->acabado ?? null,
                        ];
                    }

                    $subtotalProducto = $grupo->sum('subtotal');

                    // Cantidades agrupadas por unidad.
                    $cantidadesPorUnidad = $grupo->groupBy(function ($detalle) {
                        return strtolower(
                            $detalle->unidad_venta
                            ?? $detalle->unidad
                            ?? 'pieza'
                        );
                    });

                    // Cantidad física total descontada.
                    $cantidadStockTotal = (int) round(
                        (float) $grupo->sum('cantidad_stock')
                    );

                    $piezasPorCaja = (int) ($producto->piezas_por_caja ?? 0);

                    $cajasEquivalentes = $piezasPorCaja > 0
                        ? intdiv($cantidadStockTotal, $piezasPorCaja)
                        : 0;

                    $piezasRestantes = $piezasPorCaja > 0
                        ? $cantidadStockTotal % $piezasPorCaja
                        : 0;
                @endphp

                <tr>
                    <td>
                        {{-- NOMBRE --}}
                        <div class="product-name">
                            {{ $producto->nombre ?? 'Producto no disponible' }}
                        </div>

                        {{-- CARACTERÍSTICAS --}}
                        @foreach($atributos as $etiqueta => $valor)
                            @if($valor !== null && trim((string) $valor) !== '')
                                <div class="product-info">
                                    <strong>{{ $etiqueta }}:</strong>
                                    {{ $valor }}
                                </div>
                            @endif
                        @endforeach

                        {{-- CANTIDAD VENDIDA --}}
                        <div class="quantity-info">
                            <strong>Cantidad vendida:</strong>

                            @foreach($cantidadesPorUnidad as $unidad => $detallesUnidad)
                                @if(!$loop->first)
                                    +
                                @endif

                                @php
                                    $unidadVisible = match ($unidad) {
                                        'm2' => 'm²',
                                        'pieza' => 'piezas',
                                        'caja' => 'cajas',
                                        'bolsa' => 'bolsas',
                                        default => $unidad,
                                    };
                                @endphp

                                {{ number_format(
                                    (float) $detallesUnidad->sum(fn($d) =>
                                        $d->cantidad_venta ?? $d->cantidad
                                    ),
                                    0,
                                    ',',
                                    '.'
                                ) }}
                                {{ $unidadVisible }}
                            @endforeach
                        </div>

                        {{-- DESGLOSE DE PRECIOS --}}
                        <div class="price-breakdown">
                            @foreach($grupo->groupBy(function ($detalle) {
                                return strtolower(
                                    $detalle->unidad_venta
                                    ?? $detalle->unidad
                                    ?? 'pieza'
                                ) . '|' . (string) $detalle->precio_unitario;
                            }) as $lineasPrecio)

                                @php
                                    $primerDetalle = $lineasPrecio->first();

                                    $unidad = $primerDetalle->unidad_venta
                                        ?? $primerDetalle->unidad
                                        ?? 'pieza';

                                    $unidadVisible = match (strtolower($unidad)) {
                                        'm2' => 'm²',
                                        'pieza' => 'piezas',
                                        'caja' => 'cajas',
                                        'bolsa' => 'bolsas',
                                        default => $unidad,
                                    };

                                    $cantidadLinea = $lineasPrecio->sum(fn($d) =>
                                        $d->cantidad_venta ?? $d->cantidad
                                    );

                                    $subtotalLinea = $lineasPrecio->sum('subtotal');
                                @endphp

                                <div>
                                    {{ number_format((float) $cantidadLinea, 0, ',', '.') }}
                                    {{ $unidadVisible }}
                                    × Bs {{ number_format((float) $primerDetalle->precio_unitario, 2, ',', '.') }}
                                    = Bs {{ number_format((float) $subtotalLinea, 2, ',', '.') }}
                                </div>
                            @endforeach
                        </div>

                        {{-- EQUIVALENCIA EN CAJAS Y PIEZAS --}}
                        @if($esCeramica && $piezasPorCaja > 0)
                            <div class="product-info">
                                <strong>Equivalencia:</strong>

                                @if($cajasEquivalentes > 0)
                                    {{ $cajasEquivalentes }}
                                    {{ $cajasEquivalentes === 1 ? 'caja' : 'cajas' }}
                                @endif

                                @if($piezasRestantes > 0)
                                    @if($cajasEquivalentes > 0)
                                        +
                                    @endif

                                    {{ $piezasRestantes }}
                                    {{ $piezasRestantes === 1 ? 'pieza' : 'piezas' }}
                                @endif

                                @if($cantidadStockTotal === 0)
                                    No registrada
                                @endif
                            </div>
                        @endif

                        {{-- DEPÓSITO --}}
                        <div class="stock-detail">
                            <strong>Depósito:</strong>
                            {{ $depositos->isNotEmpty()
                                ? $depositos->implode(', ')
                                : 'No registrado' }}
                        </div>
                    </td>

                    <td class="amount">
                        <strong>
                            Bs {{ number_format((float) $subtotalProducto, 2, ',', '.') }}
                        </strong>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="2" class="center">
                        No hay productos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="separator"></div>

    {{-- TOTALES --}}
    <div class="section-title">Resumen de la venta</div>

    <table class="totals">
        <tr>
            <td>Subtotal:</td>
            <td class="right">
                Bs {{ number_format((float) $venta->subtotal, 2, ',', '.') }}
            </td>
        </tr>

        <tr>
            <td>Descuento:</td>
            <td class="right">
                Bs {{ number_format((float) $venta->descuento, 2, ',', '.') }}
            </td>
        </tr>

        <tr>
            <td class="total-final">TOTAL:</td>
            <td class="right total-final">
                Bs {{ number_format((float) $venta->total, 2, ',', '.') }}
            </td>
        </tr>
    </table>

    {{-- OBSERVACIONES --}}
    @if(!empty($venta->observaciones))
        <div class="separator"></div>

        <div class="section-title">Observaciones</div>

        <div class="observations">
            {{ $venta->observaciones }}
        </div>
    @endif

    <div class="separator"></div>

    {{-- PIE DEL RECIBO --}}
    <div class="footer">
        <strong>¡Gracias por su compra!</strong>
        <div>DISTRIBUIDORA AMERI</div>
        <div>Conserve este recibo.</div>
    </div>

</body>
</html>
