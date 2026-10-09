<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\ProveedorController;
use App\Http\Controllers\Admin\CompraController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Admin\NotificacionController;
use App\Http\Controllers\Vendedor\VendedorController;
use App\Http\Controllers\Cliente\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // ============================================================
    // DASHBOARD (redirige según rol)
    // ============================================================
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

    // ============================================================
    // ADMIN
    // ============================================================
    Route::middleware(['role:administrador'])->prefix('admin')->name('admin.')->group(function () {

        // ---------- Dashboard ----------
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // ============================================================
        // USUARIOS
        // ============================================================
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/',        [UserController::class, 'index'])->name('index');
            Route::get('/create',  [UserController::class, 'create'])->name('create');
            Route::post('/',       [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}',  [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        // ============================================================
        // SUMINISTROS (Proveedores + Productos)
        // ============================================================
        Route::prefix('suministros')->name('suministros.')->group(function () {

            // Vista principal con 2 columnas
            Route::get('/', function () {
                $proveedores = \App\Models\Proveedor::orderBy('id', 'desc')->get();

                $productos = \App\Models\Producto::with([
                    'tipoProducto.categoria'
                ])->latest()->get();

                $tiposProductos = \App\Models\TipoProducto::with('categoria')
                    ->orderBy('nombre')
                    ->get();

                $categoriasProductos = \App\Models\CategoriaProducto::with('tipos')
                    ->orderBy('nombre')
                    ->get();

                return view('admin.suministros.index', compact(
                    'proveedores',
                    'productos',
                    'tiposProductos',
                    'categoriasProductos'
                ));
            })->name('index');

            // ---------- Proveedores ----------
            Route::prefix('proveedores')->name('proveedores.')->group(function () {
                Route::post('/', [ProveedorController::class, 'store'])->name('store');
                Route::put('/{id}', [ProveedorController::class, 'update'])->name('update');
                Route::patch('/{id}/estado', [ProveedorController::class, 'cambiarEstado'])->name('estado');
                Route::delete('/{id}', [ProveedorController::class, 'destroy'])->name('destroy');
            });

            // ---------- Productos ----------
            Route::prefix('productos')->name('productos.')->group(function () {
                Route::post('/', [ProductoController::class, 'store'])->name('store');
                Route::put('/{producto}', [ProductoController::class, 'update'])->name('update');
                Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name('destroy');
            });
        });

        // ============================================================
        // COMPRAS
        // ============================================================
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

        // ============================================================
        // STOCK
        // ============================================================
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [StockController::class, 'index'])->name('index');

            Route::patch(
                '/productos/{producto}/limites',[StockController::class, 'actualizarLimites'])->name('limites');

            // Actualizar precios de venta del producto
            Route::patch(
                '/productos/{producto}/precios',[StockController::class, 'actualizarPrecios'])->name('precios');
        });


        // ============================================================
        // VENTAS
        // ============================================================
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

            // AJAX: consultar stock de un producto en todos los admins
            Route::get('/stock-producto/{producto}', [VentaController::class, 'stockProducto'])->name('stock-producto');
        });

        // ============================================================
        // NOTIFICACIONES
        // ============================================================
        Route::prefix('notificaciones')->name('notificaciones.')->group(function () {
            Route::get('/', [NotificacionController::class, 'index'])->name('index');
            Route::patch('/{notificacion}/leida', [NotificacionController::class, 'marcarLeida'])->name('leida');
            Route::patch('/todas/leidas', [NotificacionController::class, 'marcarTodasLeidas'])->name('todas-leidas');
            Route::delete('/{notificacion}', [NotificacionController::class, 'destroy'])->name('destroy');
        });

    }); // ← cierre del grupo ADMIN

    // ============================================================
    // VENDEDOR
    // ============================================================
    Route::middleware(['role:vendedor'])->prefix('vendedor')->name('vendedor.')->group(function () {
        Route::get('/dashboard', [VendedorController::class, 'index'])->name('dashboard');
    });

    // ============================================================
    // CLIENTE
    // ============================================================
    Route::middleware(['role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
        Route::get('/dashboard', [ClienteController::class, 'index'])->name('dashboard');
    });
});