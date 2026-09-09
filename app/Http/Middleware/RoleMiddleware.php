<?php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect('login');
        }

        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Verificar si el usuario tiene rol
        if (!$user->role) {
            abort(403, 'No tienes un rol asignado.');
        }

        // Si no se especificaron roles, permitir acceso
        if (empty($roles)) {
            return $next($request);
        }

        // Verificar si el rol del usuario está en la lista de roles permitidos
        if (in_array($user->role->slug, $roles)) {
            return $next($request);
        }

        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}