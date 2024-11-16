<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">


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

            <div class="container vh-100 d-flex align-items-center justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <main role="main" class="p-4">
                        <div class="bg-white p-4 rounded-lg shadow-sm text-center">
                            <!-- Título principal -->
                            <h2 class="font-weight-bold mb-1">Crear una cuenta</h2>
                            <p class="text-muted mb-4">Es rápido y fácil.</p>

                            <!-- Formulario -->
                            <form action="addUser.php" method="POST">
                                <!-- Nombre -->
                                <div class="form-group">
                                    <input type="text" id="nombre" name="nombre" class="form-control form-control-lg" placeholder="Nombre" required>
                                </div>

                                <!-- Username -->
                                <div class="form-group">
                                    <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Username" required>
                                </div>

                                <!-- Correo Electrónico -->
                                <div class="form-group">
                                    <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Correo Electrónico" required>
                                </div>

                                <!-- Contraseña -->
                                <div class="form-group position-relative">
                                    <input type="password" id="password" name="pass" class="form-control form-control-lg" placeholder="Contraseña" required>
                                    <span onclick="togglePasswordVisibility('password')" class="position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                        <i class="fa fa-eye" id="togglePasswordIcon1"></i>
                                    </span>
                                </div>

                                <!-- Confirmar Contraseña -->
                                <div class="form-group position-relative">
                                    <input type="password" id="confirmPassword" class="form-control form-control-lg" placeholder="Confirmar contraseña" required>
                                    <span onclick="togglePasswordVisibility('confirmPassword')" class="position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                        <i class="fa fa-eye" id="togglePasswordIcon2"></i>
                                    </span>
                                </div>

                                <!-- País -->
                                <div class="form-group">
                                    <select id="pais" name="id_pais" class="form-control form-control-lg" required>
                                        <option disabled selected>Selecciona tu país</option>
                                        <?php foreach ($paises as $pais): ?>
                                            <option value="<?= $pais['id_pais'] ?>"><?= htmlspecialchars($pais['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Rol -->
                                <div class="form-group">
                                    <select id="rol" name="id_rol" class="form-control form-control-lg" required>
                                        <option disabled selected>Selecciona tu rol</option>
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?= $rol['id_rol'] ?>"><?= htmlspecialchars($rol['tipo']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <button type="submit">Crear Usuario</button>
                            </form>

                            <!-- Mensaje de pie -->
                        </div>
                    </main>
                </div>
            </div>

            <!-- JavaScript para alternar la visibilidad de la contraseña -->
            <script>
                function togglePasswordVisibility(inputId) {
                    const passwordInput = document.getElementById(inputId);
                    const icon = document.querySelector(`#togglePasswordIcon${inputId === 'password' ? '1' : '2'}`);
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                }
            </script>
        </main>
    </div>
</div>