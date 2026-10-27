<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MensajeContacto;

class MensajeContactoController extends Controller
{
    public function index()
    {
        $mensajes = MensajeContacto::latest()->paginate(15);

        return view('admin.mensajes.index', compact('mensajes'));
    }

    public function mostrar(MensajeContacto $mensaje)
    {
        $mensaje->update(['leido' => true]);

        return view('admin.mensajes.show', compact('mensaje'));
    }
}