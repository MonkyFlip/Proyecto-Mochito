<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Usuarios</title>
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
        <h1>Reporte de Usuarios</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datos as $dato)
                <tr>
                    <td>{{ $dato['id_usuario'] }}</td>
                    <td>{{ $dato['nombre'] }}</td>
                    <td>{{ $dato['email'] }}</td>
                    <td>{{ $dato['id_rol'] == 1 ? 'Administrador' : 'Usuario Normal' }}</td>
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
