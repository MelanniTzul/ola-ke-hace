
<?php
$servidor = "localhost";
$usuario = "root";
$password = "admin";
$base_datos = "ola_ke_hace";

//*CONEXIÓN
    $conn = new mysqli($servidor, $usuario, $password, $base_datos);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{
        error_log("Connected successfully") ;
    }
?>


