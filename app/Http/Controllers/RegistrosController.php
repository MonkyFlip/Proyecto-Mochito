<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class RegistrosController extends Controller
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

    public function index(Request $request)
    {
        $query = $request->input('query');

        // Obtener y filtrar los registros
        $responseRegistros = Http::get('http://localhost:3000/api/registros');
        $dataRegistros = collect($responseRegistros->json());

        if ($query) {
            $dataRegistros = $dataRegistros->filter(function ($item) use ($query) {
                return stripos($item['flujo_agua'], $query) !== false ||
                    stripos($item['nivel_agua'], $query) !== false ||
                    stripos($item['temp'], $query) !== false ||
                    stripos($item['energia'], $query) !== false;
            });
        }

        // Paginación para los registros
        $currentPageRegistros = $request->input('page_registros', 1);
        $perPageRegistros = 10;
        $paginatedRegistros = new LengthAwarePaginator(
            $dataRegistros->forPage($currentPageRegistros, $perPageRegistros),
            $dataRegistros->count(),
            $perPageRegistros,
            $currentPageRegistros,
            ['path' => $request->url(), 'query' => $request->query(), 'pageName' => 'page_registros']
        );

        return view('registros', [
            'registros' => $paginatedRegistros,
            'query' => $query
        ]);
    }


    public function indexUsuarios(Request $request)
    {
        $query = $request->input('query');

        // Obtener y filtrar los usuarios
        $responseUsuarios = Http::get('http://localhost:3000/api/usuarios');
        $dataUsuarios = collect($responseUsuarios->json());

        if ($query) {
            $dataUsuarios = $dataUsuarios->filter(function ($item) use ($query) {
                return stripos($item['nombre'], $query) !== false ||
                    stripos($item['email'], $query) !== false ||
                    stripos($item['id_rol'] == 1 ? 'Administrador' : 'Usuario Normal', $query) !== false;
            });
        }

        // Paginación para los usuarios
        $currentPageUsuarios = $request->input('page_usuarios', 1);
        $perPageUsuarios = 10;
        $paginatedUsuarios = new LengthAwarePaginator(
            $dataUsuarios->forPage($currentPageUsuarios, $perPageUsuarios),
            $dataUsuarios->count(),
            $perPageUsuarios,
            $currentPageUsuarios,
            ['path' => $request->url(), 'query' => $request->query(), 'pageName' => 'page_usuarios']
        );

        return view('registros_usuarios', [
            'usuarios' => $paginatedUsuarios,
            'query' => $query
        ]);
    }
        

    public function store(Request $request)
    {
        $data = $request->except('_token');

        $response = Http::post('http://localhost:3000/api/registros', $data);

        if ($response->successful()) {
            return redirect()->route('registros.index')->with('success', 'Registro creado exitosamente.');
        } else {
            return redirect()->route('registros.index')->with('error', 'Error al crear el registro.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        $response = Http::put('http://localhost:3000/api/registros/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('registros.index')->with('success', 'Registro actualizado exitosamente.');
        } else {
            return redirect()->route('registros.index')->with('error', 'Error al actualizar el registro.');
        }
    }

    public function destroy($id)
    {
        $response = Http::delete('http://localhost:3000/api/registros/' . $id);

        if ($response->successful()) {
            return redirect()->route('registros.index')->with('success', 'Registro eliminado exitosamente.');
        } else {
            return redirect()->route('registros.index')->with('error', 'Error al eliminar el registro.');
        }
    }

    // Acciones para usuarios
    public function storeUsuario(Request $request)
    {
        $data = $request->except('_token');

        $response = Http::post('http://localhost:3000/api/usuarios', $data);

        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
        } else {
            return redirect()->route('usuarios.index')->with('error', 'Error al crear el usuario.');
        }
    }

    public function updateUsuario(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        $response = Http::put('http://localhost:3000/api/usuarios/' . $id, $data);

        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
        } else {
            return redirect()->route('usuarios.index')->with('error', 'Error al actualizar el usuario.');
        }
    }

    public function destroyUsuario($id)
    {
        $response = Http::delete('http://localhost:3000/api/usuarios/' . $id);

        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
        } else {
            return redirect()->route('usuarios.index')->with('error', 'Error al eliminar el usuario.');
        }
    }
}
