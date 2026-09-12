<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();
        
        // Verificar si el usuario tiene rol
        if ($user && $user->role) {
            switch ($user->role->slug) {
                case 'vendedor':
                    return redirect()->route('vendedor.dashboard');
                case 'admin':
                    return redirect()->route('dashboard');
                case 'cliente':
                    return redirect()->route('cliente.dashboard');
                // default:
                //     return redirect()->route('dashboard');
            }
        }
        
        return redirect()->route('dashboard');
    }
}