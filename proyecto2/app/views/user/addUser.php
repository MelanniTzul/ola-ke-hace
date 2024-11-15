<?php
require_once __DIR__ . '/../../controllers/paisController.php'; 
require_once __DIR__ . '/../../controllers/rolesController.php';



$paisController = new PaisController();
$paises = $paisController->mostrarPaises(); //* Obtener lista de países

$rolesController = new RolesController();
$roles = $rolesController->mostrarRoles(); //* Obtener lista de roles
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../../../public/css/styleAddUser.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" type="text/css" href="../../../public/css/styleAddUser.css">

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-8 col-lg-5">
        <main role="main" class="p-4">
            <div class="bg-white p-4 rounded-lg shadow-sm text-center">
                <!-- Título principal -->
                <h2 class="font-weight-bold mb-1">Crear una cuenta</h2>
                <p class="text-muted mb-4">Es rápido y fácil.</p>

                <!-- Formulario -->
                <form action="procesar_creacion_usuario.php" method="POST">
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
                        <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Contraseña" required>
                        <span onclick="togglePasswordVisibility('password')" class="position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            <i class="fa fa-eye" id="togglePasswordIcon1"></i>
                        </span>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="form-group position-relative">
                        <input type="password" id="confirmPassword" name="password" class="form-control form-control-lg" placeholder="Confirmar contraseña" required>
                        <span onclick="togglePasswordVisibility('confirmPassword')" class="position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            <i class="fa fa-eye" id="togglePasswordIcon2"></i>
                        </span>
                    </div>

                    <!-- País -->
                    <div class="form-group">
                        <select id="pais" name="pais" class="form-control form-control-lg" required>
                            <option disabled selected>Selecciona tu país</option>
                            <?php foreach ($paises as $pais): ?>
                                <option value="<?= $pais['id_pais'] ?>"><?= htmlspecialchars($pais['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Rol -->
                    <div class="form-group">
                        <select id="rol" name="rol" class="form-control form-control-lg" required>
                            <option disabled selected>Selecciona tu rol</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= $rol['id_rol'] ?>"><?= htmlspecialchars($rol['tipo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit">Crear Usuario</button>
                </form>

                <!-- Mensaje de pie -->
                <p class="mt-4 text-muted">¿Ya tienes una cuenta? <a href="login.php" class="text-primary font-weight-bold">Iniciar sesión</a></p>
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
