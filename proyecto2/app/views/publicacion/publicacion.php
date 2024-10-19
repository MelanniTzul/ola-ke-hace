<head>
    <link rel="stylesheet" type="text/css" href="/public/css/style_reservation.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<div id="reservations">
    <?php foreach ($reserva as $res): ?>
        <div class="card">
            <div></div>
            <img src="<?php echo htmlspecialchars($res['imagen']); ?>" alt="Imagen del apartamento" class="card-img">
            <div class="card-body">
                <h3>● <?php echo htmlspecialchars($res['nombre_publicacion']); ?></h3>
                <p>Fecha: <?php echo htmlspecialchars($res['fecha']); ?></p>
                <p>Ubicación: <?php echo htmlspecialchars($res['ubicacion']); ?></p>
                <p>Descripción:<?php echo htmlspecialchars($res['descripcion']); ?></p>

            </div>

            <!-- Botones de acción -->
            <div style="padding: 10px;">
                <button class="btn btn-primary" onclick="editar(<?php echo htmlspecialchars($res['id']); ?>)">Editar</button>
                <button class="btn btn-primary" onclick="eliminar(<?php echo htmlspecialchars($res['id']); ?>)">Eliminar</button>
                <button class="btn btn-primary" onclick="reservar()">Participar</button>
                <!-- <button class="btn btn-primary" onclick="reservar(<?php echo htmlspecialchars($res['id']); ?>)">Reportar</button> -->
                <button class="btn btn-primary" onclick="reportar(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Reportar</button>

            </div>

            <script>
                function reportar(reservaId) {
                    let motivo = prompt('Ingresa el motivo del reporte:');

                    if (!motivo) {
                        alert('Debes ingresar un motivo para el reporte.');
                        return;
                    }

                    fetch('../../../app/controllers/reportarPublicacionController.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                id: reservaId,
                                motivo: motivo
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Reporte enviado exitosamente.');
                                window.location.href = '/app/views/home/home.php';
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error en la solicitud:', error);
                            alert('Error al enviar el reporte. Por favor intenta nuevamente.');
                        });
                }
            </script>
        </div>
    <?php endforeach; ?>
</div>