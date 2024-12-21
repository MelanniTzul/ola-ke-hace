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
                        <a class="nav-link" href="reportes.php">Aprobación de reportes de publicaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../categorias/addCategoria.php">Agregar Categoría</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="topUserReport.php">Top 3 Usuarios Con Más Reportes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="topPublicationsReport.php">Top 3 Publicaciones Con Más Reportes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="top3UsersBan.php">Usuarios Baneados</a>
                    </li>
                </ul>
            </div>
        </nav>

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
                    <?php
                    require_once __DIR__ . '/../../controllers/userController.php';
                    require_once __DIR__ . '/../../controllers/paisController.php';
                    $userController = new UserController();
                    $filters = [
                        'nombre' => $_GET['nombre'] ?? '',
                        'username' => $_GET['username'] ?? '',
                        'correo' => $_GET['correo'] ?? '',
                        'pais' => $_GET['pais'] ?? '',
                        'estado' => $_GET['estado'] ?? ''
                    ];
                    $users = $userController->getUsersReport($filters);
                    $paisController = new PaisController();
                    $paises = $paisController->mostrarPaises();
                    ?>

                    <div class="container mt-4">
                        <h2 class="text-center mb-4">Top 3 Usuarios Más Reportados</h2>

                        <form method="GET" action="" class="mb-3">

                            <div class="row">
                                <!-- Búsqueda por nombre -->
                                <div class="col-md-4">
                                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" value="<?php echo htmlspecialchars($_GET['nombre'] ?? ''); ?>">
                                </div>

                                <!-- Búsqueda por username -->
                                <div class="col-md-4">
                                    <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo htmlspecialchars($_GET['username'] ?? ''); ?>">
                                </div>

                                <!-- Búsqueda por correo -->
                                <div class="col-md-4">
                                    <input type="text" name="correo" class="form-control" placeholder="Correo" value="<?php echo htmlspecialchars($_GET['correo'] ?? ''); ?>">
                                </div>


                                <div class="col-md-4">
                                    <br>
                                    <select name="pais" class="form-control">
                                        <option value="">País</option>
                                        <?php foreach ($paises as $pais) : ?>
                                            <option value="<?php echo $pais['id_pais']; ?>" <?php echo ($_GET['pais'] ?? '') == $pais['id_pais'] ? 'selected' : ''; ?>>
                                                <?php echo $pais['nombre']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>



                                <div class="col-md-4">
                                    <br>
                                    <select name="estado" class="form-control">
                                        <option value="">Estado</option>
                                        <option value="Activo" <?php echo ($_GET['estado'] ?? '') === 'Activo' ? 'selected' : ''; ?>>Activo</option>
                                        <option value="Baneado" <?php echo ($_GET['estado'] ?? '') === 'Baneado' ? 'selected' : ''; ?>>Baneado</option>
                                    </select>

                                </div>

                                <div class="col-md-4">
                                    <br>
                                    <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                                </div>



                            </div>

                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Id Usuario</th>
                                        <th>Nombre</th>
                                        <th>Username</th>
                                        <th>Correo</th>
                                        <th>País</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Cantidad de reportes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['id_usuario']); ?></td>
                                            <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['correo']); ?></td>
                                            <td><?php echo htmlspecialchars($user['pais']); ?></td>
                                            <td><?php echo htmlspecialchars($user['rol']); ?></td>
                                            <td><?php echo htmlspecialchars($user['estado']); ?></td>
                                            <td><?php echo htmlspecialchars($user['cantidad_reportes']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
