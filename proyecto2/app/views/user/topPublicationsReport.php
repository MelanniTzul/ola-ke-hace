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
                    require_once __DIR__ . '/../../controllers/publicationController.php';

                    require_once __DIR__ . '/../../controllers/categoriaPublicacionController.php';

                    $publicationController = new PublicationController();

                    $filters = [
                        'nombre' => $_GET['nombre'] ?? '',
                        'username' => $_GET['username'] ?? '',
                        'ubicacion' => $_GET['ubicacion'] ?? '',
                        'categoria' => $_GET['categoria'] ?? '',
                        'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
                        'fecha_fin' => $_GET['fecha_fin'] ?? '',
                        'estado_reporte' => $_GET['estado_reporte'] ?? '',
                    ];

                    $publicaciones = $publicationController->contar3PublicacionesReportadas($filters);
                    $categoriaController = new CategoriaPublicacionController();
                    $categorias = $categoriaController->mostrarCategoria();
                    ?>

                    <div class="container mt-4">
                        <h2 class="text-center mb-4">Top 3 Publicaciones Más Reportadas</h2>

                        <form method="GET" action="" class="mb-3">
                            <div class="form-row">

                                <div class="col-md-4">
                                    <input type="text" name="nombre" class="form-control" placeholder="Nombre Publicación" value="<?php echo htmlspecialchars($_GET['nombre'] ?? ''); ?>">
                                </div>

                                <div class="col-md-4">
                                    <input type="text" name="username" class="form-control" placeholder="Username Publicador" value="<?php echo htmlspecialchars($_GET['username'] ?? ''); ?>">
                                </div>

                                <div class="col-md-4">
                                    <input type="text" name="ubicacion" class="form-control" placeholder="Ubicación" value="<?php echo htmlspecialchars($_GET['ubicacion'] ?? ''); ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="categoria">Categoría</label>
                                    <select name="categoria" class="form-control">
                                        <option value="">Categoría</option>
                                        <?php foreach ($categorias as $categoria) : ?>
                                            <option value="<?php echo $categoria['id']; ?>" <?php echo ($_GET['categoria'] ?? '') == $categoria['id'] ? 'selected' : ''; ?>>
                                                <?php echo $categoria['nombre_categoria']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="fecha_inicio">Fecha Inicial de Publicación</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="<?php echo htmlspecialchars($_GET['fecha_inicio'] ?? ''); ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="fecha_fin">Fecha Final de Publicación</label>
                                    <input type="date" name="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($_GET['fecha_fin'] ?? ''); ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="estado_reporte">Estado de Reporte</label>
                                    <select name="estado_reporte" class="form-control">
                                        <option value="">Estado</option>
                                        <option value="3" <?php echo ($_GET['estado_reporte'] ?? '') == '3' ? 'selected' : ''; ?>>Reporte Sin Aprobar Por Administrador</option>
                                        <option value="1" <?php echo ($_GET['estado_reporte'] ?? '') == '1' ? 'selected' : ''; ?>>Reporte Aprobado</option>
                                        <option value="2" <?php echo ($_GET['estado_reporte'] ?? '') == '2' ? 'selected' : ''; ?>>Reporte Rechazado</option>
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
                                        <th>Id Publicación</th>
                                        <th>Nombre Publicación</th>
                                        <th>Estado Reporte</th>
                                        <th>Descripcion</th>
                                        <th>Fecha</th>
                                        <th>Categoría</th>
                                        <th>Ubicación</th>
                                        <th>Hora</th>
                                        <th>Límite Personas Máximo</th>
                                        <th>Url Imagen</th>
                                        <th>Username Publicador</th>
                                        <th>Límite Personas Actual</th>
                                        <th>Cantidad reportes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($publicaciones as $publicacion): ?>
                                        <tr>

                                            <td><?php echo $publicacion['id_publicacion']; ?></td>
                                            <td><?php echo $publicacion['nombre_publicacion']; ?></td>
                                            <td><?php echo $publicacion['estado']; ?></td>
                                            <td><?php echo $publicacion['descripcion']; ?></td>
                                            <td><?php echo $publicacion['fecha']; ?></td>
                                            <td><?php echo $publicacion['categoria']; ?></td>
                                            <td><?php echo $publicacion['ubicacion']; ?></td>
                                            <td><?php echo $publicacion['hora']; ?></td>
                                            <td><?php echo $publicacion['limite_personas']; ?></td>
                                            <td>
                                                <a href="<?php echo htmlspecialchars($publicacion['imagen']); ?>" target="_blank">
                                                    Ver Imagen
                                                </a>
                                            </td>
                                            <td><?php echo $publicacion['username']; ?></td>
                                            <td><?php echo $publicacion['limite_personas_actual']; ?></td>
                                            <td><?php echo $publicacion['cantidad_reportes']; ?></td>
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