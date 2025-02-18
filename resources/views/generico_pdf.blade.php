<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container {
        padding: 20px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #004085;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f8f9fa;
        color: #333;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    .footer {
        text-align: center;
        padding: 10px;
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #f8f9fa;
        color: #333;
        border-top: 1px solid #ddd;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Reporte de Hydromochito</h1>
        <table>
            <thead>
                @if (isset($datos[0]['id_registro']))
                <!-- Encabezados para registros IoT -->
                <tr>
                    <th>ID</th>
                    <th>Flujo de Agua</th>
                    <th>Nivel de Agua</th>
                    <th>Temperatura</th>
                    <th>Energía</th>
                </tr>
                @elseif (isset($datos[0]['id_usuario']))
                <!-- Encabezados para usuarios -->
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                </tr>
                @endif
            </thead>
            <tbody>
                @foreach ($datos as $dato)
                <tr>
                    @if (isset($dato['id_registro']))
                    <!-- Filas para registros IoT -->
                    <td>{{ $dato['id_registro'] }}</td>
                    <td>{{ $dato['flujo_agua'] }} L</td>
                    <td>{{ $dato['nivel_agua'] }} L</td>
                    <td>{{ $dato['temp'] }} °C</td>
                    <td>{{ $dato['energia'] }}</td>
                    @elseif (isset($dato['id_usuario']))
                    <!-- Filas para usuarios -->
                    <td>{{ $dato['id_usuario'] }}</td>
                    <td>{{ $dato['nombre'] }}</td>
                    <td>{{ $dato['email'] }}</td>
                    <td>{{ $dato['id_rol'] == 1 ? 'Administrador' : 'Usuario Normal' }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            © {{ date('Y') }} Mony-Tek. Todos los derechos reservados.
        </div>
    </div>
</body>

</html>