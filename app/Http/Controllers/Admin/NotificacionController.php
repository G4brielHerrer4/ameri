<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::with(['fromUser', 'venta'])
            ->where('user_id', auth()->id())
            ->orderBy('leida', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida(Notificacion $notificacion)
    {
        if ($notificacion->user_id !== auth()->id()) {
            abort(403);
        }

        $notificacion->update(['leida' => true]);

        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function marcarTodasLeidas()
    {
        Notificacion::where('user_id', auth()->id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return back()->with('success', 'Todas las notificaciones fueron marcadas como leídas.');
    }

    public function destroy(Notificacion $notificacion)
    {
        if ($notificacion->user_id !== auth()->id()) {
            abort(403);
        }

        $notificacion->delete();

        return back()->with('success', 'Notificación eliminada.');
    }
}