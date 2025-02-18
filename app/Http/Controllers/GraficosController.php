<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GraficosController extends Controller
{
    public function obtenerDatosUsuarios()
    {
        // Obtener datos de la API
        $response = Http::get('http://localhost:3000/api/usuarios'); // Reemplaza con la URL de tu API
        $usuarios = $response->json();

        $admins = 0;
        $usuariosNormales = 0;

        foreach ($usuarios as $usuario) {
            if ($usuario['id_rol'] == 1) {
                $admins++;
            } elseif ($usuario['id_rol'] == 2) {
                $usuariosNormales++;
            }
        }

        $labels = ['Administradores', 'Usuarios Normales'];
        $data = [$admins, $usuariosNormales];

        return response()->json(compact('labels', 'data'));
    }

    public function obtenerDatosRegistros()
    {
        // Obtener datos de la API
        $response = Http::get('http://localhost:3000/api/registros'); // Reemplaza con la URL de tu API
        $registros = $response->json();

        // Inicializar contadores para los rangos
        $flujoAguaRangos = [0, 0, 0, 0];
        $nivelAguaRangos = [0, 0, 0, 0];
        $tempRangos = [0, 0, 0, 0];
        $energiaTipos = ['solar' => 0, 'electricidad' => 0];

        foreach ($registros as $registro) {
            // Contar flujo de agua en rangos
            if ($registro['flujo_agua'] <= 5) {
                $flujoAguaRangos[0]++;
            } elseif ($registro['flujo_agua'] <= 10) {
                $flujoAguaRangos[1]++;
            } elseif ($registro['flujo_agua'] <= 15) {
                $flujoAguaRangos[2]++;
            } else {
                $flujoAguaRangos[3]++;
            }

            // Contar nivel de agua en rangos
            if ($registro['nivel_agua'] <= 5) {
                $nivelAguaRangos[0]++;
            } elseif ($registro['nivel_agua'] <= 10) {
                $nivelAguaRangos[1]++;
            } elseif ($registro['nivel_agua'] <= 15) {
                $nivelAguaRangos[2]++;
            } else {
                $nivelAguaRangos[3]++;
            }

            // Contar temperatura en rangos
            if ($registro['temp'] <= 10) {
                $tempRangos[0]++;
            } elseif ($registro['temp'] <= 20) {
                $tempRangos[1]++;
            } elseif ($registro['temp'] <= 30) {
                $tempRangos[2]++;
            } else {
                $tempRangos[3]++;
            }

            // Contar tipos de energía
            if ($registro['energia'] == 'solar') {
                $energiaTipos['solar']++;
            } elseif ($registro['energia'] == 'electricidad') {
                $energiaTipos['electricidad']++;
            }
        }

        return response()->json(compact('flujoAguaRangos', 'nivelAguaRangos', 'tempRangos', 'energiaTipos'));
    }
}