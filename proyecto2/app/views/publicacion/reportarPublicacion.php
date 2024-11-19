<?php

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportar publicacón</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style_sol.css">
</head>

<body>
    <div class="form-container">
        <h2>Reportar publicacón</h2>
        <form action="/app/views/publicacion/reportarPublicacion.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Motivo:</label>
                    <input type="text" id="nombre" name="nombre" value="" required>
                </div>
            </div>
            <div class="button-group">
                <button type="button" class="btn-regresar" onclick="history.back()">Regresar</button>
                <button type="submit" class="btn">Enviar Solicitud</button>
            </div>

        </form>
    </div>
</body>

</html>

<script>
    function exito(){
        alert('Reservación exitosa');
    }
</script>