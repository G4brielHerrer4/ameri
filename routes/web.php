    <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Http\Request;

    use App\Http\Controllers\Admin\UserController;
    use App\Http\Controllers\Admin\ProductoController;
    use App\Http\Controllers\Admin\CatalogoController;
    use App\Http\Controllers\CatalogoPublicoController;
    use App\Http\Controllers\Admin\ProveedorController;
    use App\Http\Controllers\Admin\CompraController;
    use App\Http\Controllers\Admin\StockController;
    use App\Http\Controllers\Admin\VentaController;
    use App\Http\Controllers\Admin\ReporteController;
    use App\Http\Controllers\Admin\NotificacionController;
    use App\Http\Controllers\Admin\SolicitudStockController;
    use App\Http\Controllers\Vendedor\VendedorController;
    use App\Http\Controllers\Vendedor\VentaVendedorController;   // NUEVO
    use App\Http\Controllers\Cliente\ClienteController;
    use App\Http\Controllers\ContactoController;
    use App\Http\Controllers\Admin\MensajeContactoController;

    use App\Http\Controllers\Auth\PasswordResetCodeController;

    /* ============================================================
    RECUPERACIÓN DE CONTRASEÑA
    ============================================================ */
    Route::get('/forgot-password', [PasswordResetCodeController::class, 'showRequestForm'])
        ->middleware('guest')->name('password.request');

    Route::post('/forgot-password', [PasswordResetCodeController::class, 'sendCode'])
        ->middleware(['guest', 'throttle:3,1'])->name('password.email');

    Route::get('/verify-reset-code', [PasswordResetCodeController::class, 'showVerifyForm'])
        ->middleware('guest')->name('password.code.form');

    Route::post('/verify-reset-code', [PasswordResetCodeController::class, 'verifyCode'])
        ->middleware(['guest', 'throttle:5,1'])->name('password.code.verify');

    Route::get('/reset-password', [PasswordResetCodeController::class, 'showResetForm'])
        ->middleware('guest')->name('password.code.reset');

    Route::post('/reset-password', [PasswordResetCodeController::class, 'resetPassword'])
        ->middleware(['guest', 'throttle:5,1'])->name('password.code.update');

    Route::get('/', fn () => view('welcome'));

    Route::post('/contacto', [ContactoController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('contacto.store');


    /* ---------- CATÁLOGO PÚBLICO ---------- */

    Route::get('/catalogo', [CatalogoPublicoController::class, 'index'])
        ->name('catalogo.index');

    Route::get('/catalogo/categoria/{categoria}', [CatalogoPublicoController::class, 'index'])
        ->whereNumber('categoria')
        ->name('catalogo.categoria');


    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
        \App\Http\Middleware\VerificarEstadoUsuario::class,
    ])->group(function () {

        /* ============================================================
        DASHBOARD (redirige según rol)
        ============================================================ */
        Route::get('/dashboard', function () {
            $user = auth()->user();

            if ($user->role && $user->role->slug === 'vendedor') {
                return redirect()->route('vendedor.dashboard');
            }
            if ($user->role && $user->role->slug === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role && $user->role->slug === 'cliente') {
                return redirect()->route('cliente.dashboard');
            }

            return view('dashboard');
        })->name('dashboard');

        /* ============================================================
        ADMIN
        ============================================================ */
        Route::middleware(['role:administrador'])->prefix('admin')->name('admin.')->group(function () {

            Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

            /* ---------- USUARIOS ---------- */
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/',        [UserController::class, 'index'])->name('index');
                Route::get('/create',  [UserController::class, 'create'])->name('create');
                Route::post('/',       [UserController::class, 'store'])->name('store');
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('/{user}',  [UserController::class, 'update'])->name('update');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
                Route::patch('/{user}/estado', [UserController::class, 'cambiarEstado'])->name('estado');
            });

            /* ---------- SUMINISTROS ---------- */
            Route::prefix('suministros')->name('suministros.')->group(function () {

                Route::get('/', function () {
                    $proveedores = \App\Models\Proveedor::orderBy('id', 'desc')->get();
                    $productos   = \App\Models\Producto::with(['tipoProducto.categoria'])->latest()->get();
                    $tiposProductos      = \App\Models\TipoProducto::with('categoria')->orderBy('nombre')->get();
                    $categoriasProductos = \App\Models\CategoriaProducto::with('tipos')->orderBy('nombre')->get();

                    return view('admin.suministros.index', compact(
                        'proveedores', 'productos', 'tiposProductos', 'categoriasProductos'
                    ));
                })->name('index');

                Route::prefix('proveedores')->name('proveedores.')->group(function () {
                    Route::post('/', [ProveedorController::class, 'store'])->name('store');
                    Route::put('/{id}', [ProveedorController::class, 'update'])->name('update');
                    Route::patch('/{id}/estado', [ProveedorController::class, 'cambiarEstado'])->name('estado');
                    Route::delete('/{id}', [ProveedorController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('productos')->name('productos.')->group(function () {
                    Route::post('/', [ProductoController::class, 'store'])->name('store');
                    Route::put('/{producto}', [ProductoController::class, 'update'])->name('update');
                    Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name('destroy');
                });
            });

            /* ---------- COMPRAS ---------- */
            Route::prefix('compras')->name('compras.')->group(function () {
                Route::get('/',        [CompraController::class, 'index'])->name('index');
                Route::get('/create',  [CompraController::class, 'create'])->name('create');
                Route::post('/',       [CompraController::class, 'store'])->name('store');
                Route::get('/{compra}',          [CompraController::class, 'show'])->name('show');
                Route::get('/{compra}/edit',     [CompraController::class, 'edit'])->name('edit');
                Route::put('/{compra}',          [CompraController::class, 'update'])->name('update');
                Route::patch('/{compra}/estado', [CompraController::class, 'cambiarEstado'])->name('estado');
                Route::delete('/{compra}',       [CompraController::class, 'destroy'])->name('destroy');
            });

            /* ---------- STOCK ---------- */
            Route::prefix('stocks')->name('stocks.')->group(function () {
                Route::get('/', [StockController::class, 'index'])->name('index');
                Route::patch('/productos/{producto}/limites', [StockController::class, 'actualizarLimites'])->name('limites');
                Route::patch('/productos/{producto}/precios', [StockController::class, 'actualizarPrecios'])->name('precios');
            });

            /* ---------- VENTAS (ADMIN) ---------- */
            Route::prefix('ventas')->name('ventas.')->group(function () {
                Route::get('/',           [VentaController::class, 'index'])->name('index');
                Route::get('/create',     [VentaController::class, 'create'])->name('create');
                Route::post('/',          [VentaController::class, 'store'])->name('store');
                Route::get('/{venta}',    [VentaController::class, 'show'])->name('show');
                Route::get('/{venta}/edit',[VentaController::class, 'edit'])->name('edit');
                Route::put('/{venta}',    [VentaController::class, 'update'])->name('update');
                Route::patch('/{venta}/confirmar', [VentaController::class, 'confirmar'])->name('confirmar');
                Route::patch('/{venta}/cancelar',  [VentaController::class, 'cancelar'])->name('cancelar');
                Route::delete('/{venta}', [VentaController::class, 'destroy'])->name('destroy');

                Route::get('/stock-producto/{producto}', [VentaController::class, 'stockProducto'])->name('stock-producto');
            });

            /* ---------- NOTIFICACIONES ---------- */
            Route::prefix('notificaciones')->name('notificaciones.')->group(function () {
                Route::get('/', [NotificacionController::class, 'index'])->name('index');
                Route::patch('/{notificacion}/leida', [NotificacionController::class, 'marcarLeida'])->name('leida');
                Route::patch('/todas/leidas', [NotificacionController::class, 'marcarTodasLeidas'])->name('todas-leidas');
                Route::delete('/{notificacion}', [NotificacionController::class, 'destroy'])->name('destroy');
            });

            /* ---------- REPORTES ---------- */
            Route::prefix('reportes')->name('reportes.')->group(function () {
                Route::get('/', [ReporteController::class, 'index'])->name('index');
                Route::get('/exportar-pdf', [ReporteController::class, 'exportarPdf'])->name('exportar-pdf');
            });

            /* ---------- SOLICITUDES DE STOCK (VENTA INTERNA) ---------- */
            Route::prefix('solicitudes-stock')->name('solicitudes.')->group(function () {
                Route::get('/', [SolicitudStockController::class, 'index'])->name('index');
                Route::post('/', [SolicitudStockController::class, 'store'])->name('store');
                Route::post('/{solicitud}/aprobar',  [SolicitudStockController::class, 'aprobar'])->name('aprobar');
                Route::post('/{solicitud}/rechazar', [SolicitudStockController::class, 'rechazar'])->name('rechazar');
                Route::post('/{solicitud}/recibir',  [SolicitudStockController::class, 'recibir'])->name('recibir');
            });

            /* ---------- CAMIÓN ---------- */
            Route::prefix('camion')->name('camion.')
                ->controller(\App\Http\Controllers\Admin\CamionController::class)
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/crear', 'create')->name('create');

                    Route::post('/choferes', 'guardarChofer')->name('choferes.store');
                    Route::put('/choferes/{chofer}', 'actualizarChofer')->name('choferes.update');
                    Route::patch('/choferes/{chofer}/estado', 'cambiarEstadoChofer')->name('choferes.estado');
                    Route::delete('/choferes/{chofer}', 'eliminarChofer')->name('choferes.destroy');

                    Route::post('/viajes', 'guardarViaje')->name('viajes.store');
                    Route::put('/viajes/{viaje}', 'actualizarViaje')->name('viajes.update');
                    Route::patch('/viajes/{viaje}/estado-pago', 'cambiarEstadoPago')->name('viajes.estado-pago');
                    Route::delete('/viajes/{viaje}', 'eliminarViaje')->name('viajes.destroy');

                    Route::post('/gastos', 'guardarGasto')->name('gastos.store');
                    Route::put('/gastos/{gasto}', 'actualizarGasto')->name('gastos.update');
                    Route::delete('/gastos/{gasto}', 'eliminarGasto')->name('gastos.destroy');
                    Route::get('/gastos/{gasto}/comprobante', 'comprobante')->name('gastos.comprobante');
                    Route::get('/choferes/{chofer}/editar', 'editarChofer')->name('choferes.edit');
                    Route::get('/viajes/{viaje}/editar', 'editarViaje')->name('viajes.edit');
                    Route::get('/gastos/{gasto}/editar', 'editarGasto')->name('gastos.edit');
                    Route::get('/exportar-pdf', 'exportarPdf')->name('exportar-pdf');
                });

                
                /* ---------- CATÁLOGO PÚBLICO (ADMINISTRACIÓN) ---------- */
                Route::prefix('catalogo')->name('catalogo.')->group(function () {
                    Route::get('/', [CatalogoController::class, 'index'])->name('index');
                    Route::put('/{producto}', [CatalogoController::class, 'update'])->name('update');
                    Route::patch('/{producto}/visibilidad', [CatalogoController::class, 'cambiarVisibilidad'])->name('visibilidad');
                });

                Route::get('/mensajes', [MensajeContactoController::class, 'index'])
                ->name('mensajes.index');

            Route::get('/mensajes/{mensaje}', [MensajeContactoController::class, 'mostrar'])
                ->name('mensajes.show');

            /* ---------- BUSCAR PRODUCTO ---------- */
            Route::get('/buscar-producto', [ProductoController::class, 'buscarGlobal'])->name('productos.buscar');
        });

        /* ============================================================
        VENDEDOR
        ============================================================ */
        Route::middleware(['role:vendedor'])
            ->prefix('vendedor')
            ->name('vendedor.')
            ->group(function () {

                Route::get('/dashboard', [VendedorController::class, 'index'])->name('dashboard');

                   /* ---------- BUSCAR PRODUCTO ---------- */
        Route::get('/buscar-producto', [ProductoController::class, 'buscarGlobal'])
            ->name('productos.buscar');


                Route::prefix('ventas')->name('ventas.')->group(function () {

                    /* ---- Vistas ---- */
                    Route::get('/',        [VentaVendedorController::class, 'index'])->name('index');
                    Route::get('/create',  [VentaVendedorController::class, 'create'])->name('create');
                    Route::post('/',       [VentaVendedorController::class, 'store'])->name('store');

                    /* ---- AJAX (deben ir ANTES de /{venta}) ---- */
                    Route::get('/productos-admin/{admin}',   [VentaVendedorController::class, 'productosAdmin'])->name('productos-admin');
                    Route::get('/stock-producto/{producto}', [VentaVendedorController::class, 'stockProducto'])->name('stock-producto');
                    Route::post('/solicitar-venta-interna',  [VentaVendedorController::class, 'solicitarVentaInterna'])->name('solicitar-venta-interna');

                    /* ---- CRUD con {venta} ---- */
                    Route::get('/{venta}/edit', [VentaVendedorController::class, 'edit'])->name('edit');
                    Route::put('/{venta}',      [VentaVendedorController::class, 'update'])->name('update');
                    Route::get('/{venta}',      [VentaVendedorController::class, 'show'])->name('show');
                    Route::patch('/{venta}/confirmar', [VentaVendedorController::class, 'confirmar'])->name('confirmar');
                    Route::patch('/{venta}/cancelar',  [VentaVendedorController::class, 'cancelar'])->name('cancelar');
                    Route::delete('/{venta}',   [VentaVendedorController::class, 'destroy'])->name('destroy');
                    Route::post('/notificar-faltante', [VentaVendedorController::class, 'notificarFaltante'])->name('notificar-faltante');
                });
            });

        /* ============================================================
        CLIENTE
        ============================================================ */
        Route::middleware(['role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
            Route::get('/dashboard', [ClienteController::class, 'index'])->name('dashboard');
        });
    });