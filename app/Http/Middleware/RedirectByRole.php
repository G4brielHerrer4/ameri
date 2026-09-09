<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está autenticado y está en la página de login o root
        if (Auth::check() && ($request->is('login') || $request->is('/') || $request->is('register'))) {
            $user = Auth::user();
            
            if ($user->role && $user->role->slug === 'vendedor') {
                return redirect()->route('vendedor.dashboard');
            }
            
            if ($user->role && $user->role->slug === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }
        
        return $next($request);
    }
}