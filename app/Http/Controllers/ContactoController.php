<?php

namespace App\Http\Controllers;

use App\Models\MensajeContacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'mensaje' => ['required', 'string', 'max:5000'],
        ]);

        MensajeContacto::create($datos);

        return redirect('/#contact')
            ->with('contacto_exito', '¡Mensaje enviado correctamente! Nos pondremos en contacto contigo.');
    }
}
