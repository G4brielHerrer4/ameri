<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CatalogoController;
use App\Http\Controllers\Admin\PedidoAdminController;
use App\Http\Controllers\Admin\DespachoController;
use App\Http\Controllers\Vendedor\VendedorController;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Frontend\CatalogoFrontend;
use App\Http\Controllers\Frontend\PedidoController;

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});



// Grupo de rutas protegidas por Jetstream
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Dashboard principal (redirige según rol)
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

    // ========== RUTAS DE ADMINISTRACIÓN (SOLO ADMIN) ==========
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
        
        // Usuarios - CRUD completo
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');


    });
    
    // ========== RUTAS DE VENDEDOR (SOLO VENDEDOR) ==========
    Route::middleware(['role:vendedor'])->prefix('vendedor')->name('vendedor.')->group(function () {
        
        // Dashboard Vendedor
        Route::get('/dashboard', [VendedorController::class, 'index'])->name('dashboard');

    });

    // ========== RUTAS DE CLIENTE (SOLO CLIENTE) ==========
    Route::middleware(['role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
        
        // Dashboard Cliente
        Route::get('/dashboard', [ClienteController::class, 'index'])->name('dashboard');
    });
});