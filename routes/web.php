<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\ProveedorController;
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

    // ========== DASHBOARD (redirige según rol) ==========
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

        // Dashboard
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // ---------- Usuarios ----------
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // ============================================================
        // SUMINISTROS (Proveedores + Productos)
        // ============================================================
        Route::prefix('suministros')->name('suministros.')->group(function () {

            // Vista principal con las 2 columnas
            Route::get('/', function () {
                $proveedores = \App\Models\Proveedor::orderBy('id', 'desc')->get();
                $productos   = \App\Models\Producto::latest()->get();

                return view('admin.suministros.index', compact('proveedores', 'productos'));
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
    });

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