<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\User;

class StockController extends Controller
{
    public function index()
    {
        $admins = User::whereHas('role', function ($q) {
            $q->where('slug', 'administrador');
        })->orderBy('name')->get();

        $stocks = Stock::with(['user', 'producto.tipoProducto.categoria'])
            ->whereHas('user.role', fn($q) => $q->where('slug', 'administrador'))
            ->orderBy('user_id')
            ->get();

        $resumenPorAdmin = $stocks->groupBy('user_id')->map(function ($grupo) {
            return [
                'user'      => $grupo->first()->user,
                'productos' => $grupo->count(),
                'unidades'  => $grupo->sum('cantidad'),
            ];
        });

        return view('admin.stocks.index', compact('admins', 'stocks', 'resumenPorAdmin'));
    }
}