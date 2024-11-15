<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar py-4">
            <div class="sidebar-sticky">
                <h5 class="text-center">Panel de Administración</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="newUser.php">Crear Usuario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aprobacionUser.php">Aprobar Usuarios Pendientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gestion_publicaciones.php">Gestión de Publicaciones</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h2>Crear Usuario</h2>
            </div>

            <form action="procesar_creacion_usuario.php" method="POST" class="bg-white p-4 rounded shadow-sm">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol" class="form-control">
                        <option value="Administrador">Administrador</option>
                        <option value="Publicador">Publicador de Anuncios</option>
                        <option value="Registrado">Usuario Registrado</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </form>
        </main>
    </div>
</div>
