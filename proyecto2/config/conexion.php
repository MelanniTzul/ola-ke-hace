
<?php
$servidor = "localhost";
$usuario = "root";
$password = "admin";
$base_datos = "ola_ke_hace";

//*CONEXIÓN
try {
    $conn = new mysqli($servidor, $usuario, $password, $base_datos);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    error_log("Connected successfully") ;
} catch (\Throwable $th) {
    error_log('Error: '.$th->getMessage());
    
}
?>