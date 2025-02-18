<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function __construct()
    {
        // Middleware para verificar la sesión
        $this->middleware(function ($request, $next) {
            if (!session('authenticated')) {
                return redirect()->route('login')->withErrors(['auth' => 'Debe iniciar sesión para acceder a esta página.']);
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('usuario');
    }
}