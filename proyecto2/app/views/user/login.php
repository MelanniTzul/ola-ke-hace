<!DOCTYPE html>
<html>

<head>
    <title>Segunda Pagina</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style_login.css">
</head>

<body>

    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <h3><strong>ola que hace </strong></h3>
        <h3>Ingrese sus credenciales para acceder a su cuenta</h3>
        <form action="login_process.php" method="POST"> <!-- Cambia a tu archivo de procesamiento de login -->
            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <!-- <input type="submit" value="Iniciar sesión"> -->
            <button onclick="redirect()">Continuar</button>
        </form>
        <script>
            function redirect() {
                window.location.href = "/app/views/home/home.php";
            }
        </script>
        <p>¿No tienes una cuenta? <a href="#">Regístrate aquí</a></p>
    </div>
</body>

</html>