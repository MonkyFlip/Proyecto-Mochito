<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\GraficosController;

// Ruta predeterminada para la vista de visitante
Route::get('/', [VisitanteController::class, 'index'])->name('visitante.index');

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/register', [LoginController::class, 'register'])->name('register');

// Rutas protegidas por verificación de sesión en controladores
Route::get('/registros', [RegistrosController::class, 'index'])->name('registros.index');
Route::post('/registros', [RegistrosController::class, 'store'])->name('registros.store');
Route::put('/registros/{id}', [RegistrosController::class, 'update'])->name('registros.update');
Route::delete('/registros/{id}', [RegistrosController::class, 'destroy'])->name('registros.destroy');

// Rutas para los usuarios dentro del mismo controlador de registros
Route::post('/usuarios', [RegistrosController::class, 'storeUsuario'])->name('usuarios.store');
Route::put('/usuarios/{id}', [RegistrosController::class, 'updateUsuario'])->name('usuarios.update');
Route::delete('/usuarios/{id}', [RegistrosController::class, 'destroyUsuario'])->name('usuarios.destroy');

// Rutas para la vista de registros de usuarios
Route::get('/registros_usuarios', [RegistrosController::class, 'indexUsuarios'])->name('registros_usuarios.index');

// Rutas para la vista de usuario individual
Route::get('/usuario/{id}', [UsuarioController::class, 'show'])->name('usuario.show');

// Rutas para la vista de usuario principal
Route::get('/usuario', [UsuarioController::class, 'index'])->name('usuario.index');

// Rutas para la vista de visitante
Route::get('/visitante', [VisitanteController::class, 'index'])->name('visitante.index');

// Reportes en PDF o Excel
Route::post('/reporte/previsualizar-pdf', [ReportesController::class, 'previsualizarReportePDF'])->name('reporte.previsualizar-pdf');
Route::post('/reporte/excel', [ReportesController::class, 'generarReporteExcel'])->name('reporte.excel');

// Graficas
Route::get('/graficos/datos-usuarios', [GraficosController::class, 'obtenerDatosUsuarios'])->name('graficos.datos-usuarios');
Route::get('/graficos/datos-registros', [GraficosController::class, 'obtenerDatosRegistros'])->name('graficos.datos-registros');
