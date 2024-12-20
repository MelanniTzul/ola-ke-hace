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
                        <a class="nav-link" href="reportes.php">Reportes</a>
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

                    $publicationController = new PublicationController();
                    $publicaciones = $publicationController->getPublicacionesReportadas();
                    ?>

                    <div class="container mt-4">
                        <h2 class="text-center mb-4">Publicaciones reportadas</h2>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Id Publicación</th>
                                        <th>Nombre publicación</th>
                                        <th>Descripción</th>
                                        <th>Fecha del evento</th>
                                        <th>Hora del evento</th>
                                        <th>Motivo</th>
                                        <th>Usuario que reportó</th>
                                        <th>Publicador</th>
                                        <th>Url de Imagen</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($publicaciones as $publicacion): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($publicacion['id_publicacion']); ?></td>
                                            <td><?php echo htmlspecialchars($publicacion['nombre_publicacion']); ?></td>
                                            <td><?php echo htmlspecialchars($publicacion['descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($publicacion['fecha']); ?></td>
                                            <td><?php echo htmlspecialchars($publicacion['hora']); ?></td>
                                            <td><?php echo htmlspecialchars($publicacion['motivo']); ?></td>
                                            <td><?php echo htmlspecialchars($publicacion['usuario_reporta']); ?></td>
                                            <td><?php echo htmlspecialchars($publicacion['usuario_publica']); ?></td>
                                            <td>
                                                <a href="<?php echo htmlspecialchars($publicacion['imagen']); ?>" target="_blank">
                                                    Ver Imagen
                                                </a>
                                            </td>
                                            <td>
                                                <button class="btn btn-success" onclick="aprobar(<?php echo $publicacion['id_publicacion']; ?>)">Aprobar</button>
                                                <button class="btn btn-danger" onclick="rechazar(<?php echo $publicacion['id_publicacion']; ?>)">Rechazar</button>
                                            </td>

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

<script>
    function aprobar(idPublicacion) {
        if (confirm("¿Estás seguro de aprobar esta publicación?")) {
            fetch('/app/controllers/publicationController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'autorizarReportePublicacion',
                        id_publicacion: idPublicacion
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Publicación reportada correctamente.');
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al aprobar la publicación.');
                });
        }
    }

    function rechazar(idPublicacion) {
        if (confirm("¿Estás seguro de rechazar esta publicación?")) {
            fetch('/app/controllers/publicationController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'rechazarReportePublicacion',
                        id_publicacion: idPublicacion
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Publicación no reportada.');
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al rechazar la publicación.');
                });
        }
    }
</script>