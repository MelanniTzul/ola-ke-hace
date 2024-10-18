<?php
    // Incluye la configuración de la conexión si es necesario
    require_once("config/conexion.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mi Proyecto HTML y PHP</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
    <?php include('app/views/home/home.php'); ?>
</body>
</html>
