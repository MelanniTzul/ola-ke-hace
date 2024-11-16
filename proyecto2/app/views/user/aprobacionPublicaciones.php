<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<div class="container-fluid">
    <div class="row">
    <a href="/app/views/home/home.php">
            <button class="icon-btn home-btn">
                <i class="fas fa-home"></i>
            </button>
        </a>
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar py-4">
            <div class="sidebar-sticky">
                <h5 class="text-center">Panel de Administración</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="newUser.php">Crear Usuario Administrador</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aprobacionPublicaciones.php">Aprobar Publicaciones Pendientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reportes.php">Reporte</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <!-- <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4"> -->


            <?php
            require_once __DIR__ . '/../../controllers/paisController.php';
            require_once __DIR__ . '/../../controllers/rolesController.php';
            require_once __DIR__ . '/../../controllers/userController.php';


            $userController = new UserController();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $userController->createUser();
            }

            $paisController = new PaisController();
            $paises = $paisController->mostrarPaises(); //* Obtener lista de países

            $rolesController = new RolesController();
            $roles = $rolesController->mostrarRolAdmin(); //* Obtener lista de roles
            ?>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="../../../public/css/styleAddUser.css">
            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <!-- Font Awesome CSS -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

            <div class="container vh-100  justify-content-center">
                <!-- <div class="col-md-8 col-lg-5">
                        
                </div> -->
                <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->

                <div class="container-fluid">
                    
                    <div class="row">
                        
                        <!-- Main content -->
                        <!-- <main role="main" class=""> -->
                            <div class="container mt-4">
                                <h2 class="text-center mb-4">A Probación de Notificación de Publicaciones</h2>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Mensaje</th>
                                                <th>Fecha</th>
                                                <th>ID Usuario</th>
                                                <th>ID Publicación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Ejemplo de datos dinámicos
                                            $solicitudes = [
                                                [
                                                    'id_solicitud_notificacion' => 1,
                                                    'mensaje' => 'Aceptar esta publicación',
                                                    'fecha' => '2024-10-15',
                                                    'id_usuario' => 1,
                                                    'id_publicacion' => 1
                                                ],
                                                [
                                                    'id_solicitud_notificacion' => 2,
                                                    'mensaje' => 'Revisar esta publicación',
                                                    'fecha' => '2024-11-20',
                                                    'id_usuario' => 2,
                                                    'id_publicacion' => 3
                                                ]
                                            ];

                                            foreach ($solicitudes as $solicitud) {
                                                echo "<tr>";
                                                echo "<td>{$solicitud['id_solicitud_notificacion']}</td>";
                                                echo "<td>{$solicitud['mensaje']}</td>";
                                                // Formatear la fecha
                                                $fechaFormateada = date('d-m-Y', strtotime($solicitud['fecha']));
                                                echo "<td>{$fechaFormateada}</td>";
                                                echo "<td>{$solicitud['id_usuario']}</td>";
                                                echo "<td>{$solicitud['id_publicacion']}</td>";
                                                echo '<td class="text-center">
                                        <button class="btn btn-success btn-sm me-2">Aceptar</button>
                                        <button class="btn btn-danger btn-sm">Rechazar</button>
                                      </td>';
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <!-- </main> -->
                    </div>
                </div>

            </div>

            <!-- JavaScript para alternar la visibilidad de la contraseña -->
            <script>
                
            </script>
        <!-- </main> -->
    </div>
</div>