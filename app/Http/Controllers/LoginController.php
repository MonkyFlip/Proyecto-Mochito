<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        //Verificar que el usuario existe en la base de datos
        $credentials = $request->only('email', 'password');
        $usuario = DB::table('tb_usuarios')->where('email', $credentials['email'])->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'El correo electrónico no está registrado.']);
        }

        if (!Hash::check($credentials['password'], $usuario->password)) {
            return back()->withErrors(['password' => 'La contraseña es incorrecta.']);
        }

        // Autenticar al usuario manualmente
        Session::put('authenticated', true);
        Session::put('user_email', $usuario->email);

        // Redirigir al usuario según el dominio del correo
        if (str_ends_with($usuario->email, '@mony-tek.com')) {
            return redirect()->route('registros.index')->with('success', 'Login exitoso. Bienvenido.'); //Administrador
        } else {
            return redirect()->route('usuario.index')->with('success', 'Login exitoso. Bienvenido.'); //Usuario
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente.');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|max:255',
            'email' => 'required|email|unique:tb_usuarios',
            'password' => 'required|min:8',
        ]);

        // Asignar rol según el dominio del correo
        $rol = str_ends_with($validatedData['email'], '@mony-tek.com') ? 1 : 2; //1=Admin, 2=Usuario

        // Encriptar la contraseña antes de guardarla
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['id_rol'] = $rol;

        DB::table('tb_usuarios')->insert($validatedData);

        return redirect('/login')->with('success', 'Usuario registrado exitosamente. Por favor, inicie sesión.');
    }
}
