

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
        <form action="login_process.php" method="POST"> 
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

<?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
    <p style="color:red;">Credenciales incorrectas, por favor intenta de nuevo.</p>
<?php endif; ?>
<form action="login_process.php" method="POST">
    <input type="text" name="username" placeholder="Nombre de usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Iniciar sesión</button>
</form>


</html>

<?php
session_start();
require_once __DIR__ .'../../models/UserModel.php';
if($_SERVER["REQUEST_METHO"]== "POST"){
    //*Obtener los datos del formulario
        $username = $_POST['username'];
        $password = $_POST['password'];

        $userModel = new UserModel();

        $user = $userModel -> validateUser($username, $password);

        if($user){
            $_SESSION['username'] =$user['username'];
            $_SESSION['rol']=$password['id_role'];

            header("Location: /app/view/home/home.php");
            exit();
        }else{
                header("Location: /login.php?error=1");
                exit();
        }

}

?>