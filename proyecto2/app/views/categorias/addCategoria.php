<?php
session_start();
require_once __DIR__ . '/../../controllers/categoriaPublicacionController.php';

$categoriaController = new CategoriaPublicacionController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoriaController->createCategoria();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Agregar Categoría</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar py-4">
                <a href="/app/views/home/home.php">
                    <button class="icon-btn home-btn">
                        <i class="fas fa-home"></i>
                    </button>
                </a>
                <div class="sidebar-sticky">
                    <h5 class="text-center">Panel de Administración</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="../user/newUser.php">Crear Usuario Administrador</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../user/aprobacionPublicaciones.php">Aprobar Publicaciones Pendientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../user/reportes.php">Reporte</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="addCategoria.php">Agregar Categoría</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../user/topUserReport.php">Top 3 Usuarios Con Más Reportes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../user/topPublicationsReport.php">Top 3 Publicaciones Con Más Reportes</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="container vh-100 d-flex align-items-center justify-content-center">
                    <div class="col-md-8 col-lg-5">
                        <main role="main" class="p-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm text-center">
                                <!-- Título principal -->
                                <h2 class="font-weight-bold mb-1">Crear nueva categoría</h2>

                                <!-- Formulario -->
                                <form action="addCategoria.php" method="POST">
                                    <!-- Nombre -->
                                    <div class="form-group">
                                        <input type="text" id="nombre_categoria" name="nombre_categoria" class="form-control form-control-lg" placeholder="Nombre Categoría" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Crear Categoría</button>
                                </form>
                            </div>
                        </main>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php if (isset($_SESSION['categoria_creada'])): ?>
        <script>
            alert('Categoría creada con éxito');
            <?php unset($_SESSION['categoria_creada']); ?>
        </script>
    <?php endif; ?>
</body>
</html>