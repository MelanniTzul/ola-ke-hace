<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        .main-content {
            margin-left: 250px;
            padding-top: 20px;
        }

        .sidebar-sticky {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            padding-top: 20px;
        }
    </style>
    <script>
        function redirect() {
            window.location.href = "app/views/user/aprobacionUser.php";
        }
    </script>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <h5 class="text-center py-3">Panel de Administración</h5>
                    <ul class="nav flex-column">
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="crear_usuario.php">Crear Usuario</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link active" href="#" onclick="redirect()">Aprobar Usuarios Pendientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="gestion_publicaciones.php">Gestión de Publicaciones</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h2>Aprobar Usuarios Pendientes</h2>
                </div>

                <!-- Table of Pending Users -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Correo Electrónico</th>
                                <th>Rol Solicitado</th>
                                <th>Fecha de Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Suponiendo que los datos se generan dinámicamente -->
                            <tr>
                                <td>Juan Pérez</td>
                                <td>juan.perez@example.com</td>
                                <td>Publicador de Anuncios</td>
                                <td>2023-11-10</td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="aprobarUsuario(1)">Aprobar</button>
                                    <button class="btn btn-danger btn-sm" onclick="rechazarUsuario(1)">Rechazar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>María López</td>
                                <td>maria.lopez@example.com</td>
                                <td>Usuario Registrado</td>
                                <td>2023-11-11</td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="aprobarUsuario(2)">Aprobar</button>
                                    <button class="btn btn-danger btn-sm" onclick="rechazarUsuario(2)">Rechazar</button>
                                </td>
                            </tr>
                            <!-- Más filas aquí -->
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

</body>

</html>
