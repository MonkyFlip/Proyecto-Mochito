<!DOCTYPE html>
<html>

<head>
    <title>Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Registros IoT</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('usuario.index') }}"><i class="fas fa-user"></i>
                                Usuario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('visitante.index') }}"><i
                                    class="fas fa-user-friends"></i> Visitante</a>
                        </li>
                        @if (session('user_email') && str_ends_with(session('user_email'), '@mony-tek.com'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('registros.index') }}"><i class="fas fa-database"></i>
                                Registros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('registros_usuarios.index') }}"><i
                                    class="fas fa-users"></i> Usuarios</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h1>Bienvenido, Usuario</h1>
        <p>Esta es la vista de usuario.</p>
    </div>
</body>

</html>