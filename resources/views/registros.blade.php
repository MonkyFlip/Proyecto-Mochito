<!DOCTYPE html>
<html>

<head>
    <title>Registros IoT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container mt-1">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Registros IoT</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <form class="d-flex ms-auto" action="{{ route('registros.index') }}" method="GET">
                        <input class="form-control me-2" type="search" placeholder="Buscar" name="query"
                            value="{{ $query ?? '' }}">
                        <button class="btn btn-outline-success me-2" type="submit"><i
                                class="fas fa-search"></i></button>
                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()"><i
                                class="fas fa-times"></i></button>
                    </form>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        @if (session('user_email'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('usuario.index') }}"><i class="fas fa-user"></i>
                                Usuario</a>
                        </li>
                        @endif
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="reportesDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-file-alt"></i> Reportes
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="reportesDropdown">
                                <li>
                                    <form id="previsualizarPdfForm" action="{{ route('reporte.previsualizar-pdf') }}"
                                        target="_blank" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="datos" id="previsualizarPdfDatos">
                                        <input type="hidden" name="columnas" id="previsualizarPdfColumnas">
                                        <button type="submit" class="dropdown-item"
                                            onclick="preparePrevisualizarPdfData()"><i class="fas fa-eye"></i>
                                            Previsualizar PDF</button>
                                    </form>
                                </li>
                                <li>
                                    <form id="reporteExcelForm" action="{{ route('reporte.excel') }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input type="hidden" name="datos" id="excelDatos">
                                        <input type="hidden" name="columnas" id="excelColumnas">
                                        <button type="submit" class="dropdown-item" onclick="prepareExcelData()"><i
                                                class="fas fa-file-excel"></i> Generar Excel</button>
                                    </form>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><button type="button" class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#estadisticasUsuariosModal">
                                        <i class="fas fa-chart-bar"></i> Estadísticas de Usuarios</button></li>
                                <li><button type="button" class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#estadisticasIotModal">
                                        <i class="fas fa-chart-line"></i> Estadísticas IoT</button></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            @if (session('user_email'))
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </button>
                            </form>
                            @else
                            <a class="btn btn-primary" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div id="registrosTable">
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus-circle"></i> Crear Registro
            </button>

            <style>
            .table-hover tbody tr:hover {
                background-color: rgb(216, 216, 216);
            }
            </style>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Flujo de Agua</th>
                        <th>Nivel de Agua</th>
                        <th>Temperatura</th>
                        <th>Energía</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($registros as $registro)
                    <tr>
                        <td>{{ $registro['id_registro'] }}</td>
                        <td>{{ $registro['flujo_agua'] }} L</td>
                        <td>{{ $registro['nivel_agua'] }} L</td>
                        <td>{{ $registro['temp'] }} °C</td>
                        <td>{{ $registro['energia'] }}</td>
                        <td>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#viewModal{{ $registro['id_registro'] }}">
                                <i class="fas fa-eye"></i> Ver
                            </button>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $registro['id_registro'] }}">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $registro['id_registro'] }}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                    <!-- Modal Ver -->
                    <div class="modal fade" id="viewModal{{ $registro['id_registro'] }}" tabindex="-1"
                        aria-labelledby="viewModalLabel{{ $registro['id_registro'] }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewModalLabel{{ $registro['id_registro'] }}">Ver
                                        Registro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>ID:</strong> {{ $registro['id_registro'] }}</p>
                                    <p><strong>Flujo de Agua:</strong> {{ $registro['flujo_agua'] }}</p>
                                    <p><strong>Nivel de Agua:</strong> {{ $registro['nivel_agua'] }}</p>
                                    <p><strong>Temperatura:</strong> {{ $registro['temp'] }}</p>
                                    <p><strong>Energía:</strong> {{ $registro['energia'] }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Editar -->
                    <div class="modal fade" id="editModal{{ $registro['id_registro'] }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $registro['id_registro'] }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $registro['id_registro'] }}">Editar
                                        Registro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('registros.update', $registro['id_registro']) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="flujo_agua" class="form-label">Flujo de Agua</label>
                                            <input type="text" class="form-control" name="flujo_agua"
                                                value="{{ $registro['flujo_agua'] }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nivel_agua" class="form-label">Nivel de Agua</label>
                                            <input type="text" class="form-control" name="nivel_agua"
                                                value="{{ $registro['nivel_agua'] }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="temp" class="form-label">Temperatura</label>
                                            <input type="text" class="form-control" name="temp"
                                                value="{{ $registro['temp'] }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="energia" class="form-label">Energía</label>
                                            <select class="form-control" name="energia" required>
                                                <option value="solar"
                                                    {{ $registro['energia'] == 'solar' ? 'selected' : '' }}>Solar
                                                </option>
                                                <option value="electricidad"
                                                    {{ $registro['energia'] == 'electricidad' ? 'selected' : '' }}>
                                                    Electricidad</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Eliminar -->
                    <div class="modal fade" id="deleteModal{{ $registro['id_registro'] }}" tabindex="-1"
                        aria-labelledby="deleteModalLabel{{ $registro['id_registro'] }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $registro['id_registro'] }}">Eliminar
                                        Registro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>ID:</strong> {{ $registro['id_registro'] }}</p>
                                    <p><strong>Flujo de Agua:</strong> {{ $registro['flujo_agua'] }}</p>
                                    <p><strong>Nivel de Agua:</strong> {{ $registro['nivel_agua'] }}</p>
                                    <p><strong>Temperatura:</strong> {{ $registro['temp'] }}</p>
                                    <p><strong>Energía:</strong> {{ $registro['energia'] }}</p>
                                    <p>¿Estás seguro de que deseas eliminar este registro?</p>
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('registros.destroy', $registro['id_registro']) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
            <!-- Código de paginación de registros IoT -->
            <div class="d-flex justify-content-center">
                {{ $registros->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5', ['pageName' => 'page_registros']) }}
            </div>
        </div>
        <!-- Modal para Estadísticas de Usuarios -->
        <div class="modal fade" id="estadisticasUsuariosModal" tabindex="-1" aria-labelledby="estadisticasUsuariosLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="estadisticasUsuariosLabel">Estadísticas de Usuarios</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <canvas id="usuariosChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Estadísticas IoT -->
        <div class="modal fade" id="estadisticasIotModal" tabindex="-1" aria-labelledby="estadisticasIotLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="estadisticasIotLabel">Estadísticas IoT</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-4">
                                <canvas id="flujoAguaChart"></canvas>
                            </div>
                            <div class="col-md-6 mb-4">
                                <canvas id="nivelAguaChart"></canvas>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-4">
                                <canvas id="tempChart"></canvas>
                            </div>
                            <div class="col-md-6 mb-4">
                                <canvas id="energiaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Scripts de Bootstrap y jQuery -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
            integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
        </script>
        <script>
            function clearSearch() {
                document.querySelector('input[name="query"]').value = '';
                document.querySelector('form').submit();
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
        <script>
        function collectTableData() {
            const datos = [];
            const columnas = ['id_registro', 'flujo_agua', 'nivel_agua', 'temp', 'energia'];

            document.querySelectorAll('table tbody tr').forEach(row => {
                const fila = {};
                columnas.forEach((columna, index) => {
                    fila[columna] = row.cells[index].innerText.replace(/ L| °C/g,
                        ''); // Remover unidades si es necesario
                });
                datos.push(fila);
            });

            return {
                datos,
                columnas
            };
        }

        function preparePrevisualizarPdfData() {
            const {
                datos,
                columnas
            } = collectTableData();
            document.getElementById('previsualizarPdfDatos').value = JSON.stringify(datos);
            document.getElementById('previsualizarPdfColumnas').value = JSON.stringify(columnas);
        }

        function prepareExcelData() {
            const {
                datos,
                columnas
            } = collectTableData();
            document.getElementById('excelDatos').value = JSON.stringify(datos);
            document.getElementById('excelColumnas').value = JSON.stringify(columnas);
        }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var usuariosModal = new bootstrap.Modal(document.getElementById('estadisticasUsuariosModal'));
            var iotModal = new bootstrap.Modal(document.getElementById('estadisticasIotModal'));

            usuariosModal._element.addEventListener('shown.bs.modal', function() {
                fetch("{{ route('graficos.datos-usuarios') }}")
                    .then(response => response.json())
                    .then(data => {
                        var ctxUsuarios = document.getElementById('usuariosChart').getContext('2d');
                        new Chart(ctxUsuarios, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Usuarios',
                                    data: data.data,
                                    backgroundColor: ['rgba(255, 99, 132, 0.6)',
                                        'rgba(54, 162, 235, 0.6)'
                                    ], // Colores para admin y usuario normal
                                    borderColor: ['rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
            });

            iotModal._element.addEventListener('shown.bs.modal', function() {
                fetch("{{ route('graficos.datos-registros') }}")
                    .then(response => response.json())
                    .then(data => {
                        // Gráfico de flujo de agua
                        var ctxFlujoAgua = document.getElementById('flujoAguaChart').getContext(
                            '2d');
                        new Chart(ctxFlujoAgua, {
                            type: 'bar',
                            data: {
                                labels: ['1-5 L', '6-10 L', '11-15 L', '>16 L'],
                                datasets: [{
                                    label: 'Flujo de Agua',
                                    data: data.flujoAguaRangos,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        // Gráfico de nivel de agua
                        var ctxNivelAgua = document.getElementById('nivelAguaChart').getContext(
                            '2d');
                        new Chart(ctxNivelAgua, {
                            type: 'bar',
                            data: {
                                labels: ['1-5 L', '6-10 L', '11-15 L', '>16 L'],
                                datasets: [{
                                    label: 'Nivel de Agua',
                                    data: data.nivelAguaRangos,
                                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        // Gráfico de temperatura
                        var ctxTemp = document.getElementById('tempChart').getContext('2d');
                        new Chart(ctxTemp, {
                            type: 'bar',
                            data: {
                                labels: ['1-10 °C', '11-20 °C', '21-30 °C', '>31 °C'],
                                datasets: [{
                                    label: 'Temperatura',
                                    data: data.tempRangos,
                                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        // Gráfico de tipos de energía
                        var ctxEnergia = document.getElementById('energiaChart').getContext('2d');
                        new Chart(ctxEnergia, {
                            type: 'pie',
                            data: {
                                labels: ['Solar', 'Electricidad'],
                                datasets: [{
                                    label: 'Energía',
                                    data: [data.energiaTipos.solar, data
                                        .energiaTipos.electricidad
                                    ],
                                    backgroundColor: ['rgba(255, 159, 64, 0.6)',
                                        'rgba(255, 205, 86, 0.6)'
                                    ],
                                    borderColor: ['rgba(255, 159, 64, 1)',
                                        'rgba(255, 205, 86, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    title: {
                                        display: true,
                                        text: 'Tipos de Energía'
                                    }
                                }
                            }
                        });
                    });
            });
        });

        function toggleTable() {
            const registrosTable = document.getElementById('registrosTable');
            const usuariosTable = document.getElementById('usuariosTable');
            const toggleButton = document.getElementById('toggleTableButton');

            if (registrosTable.style.display === 'none') {
                registrosTable.style.display = 'block';
                usuariosTable.style.display = 'none';
                toggleButton.textContent = 'Mostrar Usuarios';
            } else {
                registrosTable.style.display = 'none';
                usuariosTable.style.display = 'block';
                toggleButton.textContent = 'Mostrar Registros IoT';
            }
        }
        </script>
</body>

</html>