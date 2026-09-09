<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 👇 Compartir roles con todas las vistas de autenticación
        View::composer(['auth.register', 'livewire.auth.register'], function ($view) {
            $view->with('roles', Role::all());
        });
    }
}