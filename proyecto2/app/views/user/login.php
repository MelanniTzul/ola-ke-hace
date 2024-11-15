<?php
session_start();
require_once __DIR__ . '/../../controllers/userController.php'; // Incluir el controlador de usuario

$userController = new UserController();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userController->login(); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Segunda Pagina</title>
    <link rel="stylesheet" type="text/css" href="../../../public/css/style_login.css">
</head>
<body>

    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <h3><strong>ola que hace</strong></h3>
        <h3>Ingrese sus credenciales para acceder a su cuenta</h3>
        
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Nombre de usuario" required >
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar sesión</button>
        </form>

        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <p style="color:red;">Credenciales incorrectas, por favor intenta de nuevo.</p>
        <?php endif; ?>

        <p>¿No tienes una cuenta? <a href="addUser.php">Regístrate aquí</a></p>
    </div>
</body>
</html>
