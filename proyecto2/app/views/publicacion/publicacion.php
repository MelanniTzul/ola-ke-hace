<?php
require_once __DIR__ . '/../../controllers/addPublicationController.php';

//* Inicialización de controladores
$controller = new AddPublicacionController();

if ($_POST['action'] === 'deletePublicacion') {
    $controller->deletePublication();
}




?>

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
                <button class="btn btn-primary" onclick="eliminar(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Eliminar</button>
                <button class="btn btn-primary" onclick="reportar(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Reportar</button>
                <button class="btn btn-primary" onclick="asistir(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Asistir</button>

            </div>

            <script>
                //*RESERVACION EVENTO
                function asistir(id_publicacion) {
                    const id_usuario = prompt("Ingrese el ID del usuario que asistirá:");

                    if (!id_usuario || isNaN(id_usuario)) {
                        alert("ID de usuario inválido.");
                        return;
                    }

                    fetch('/app/controllers/reservacionEventoController.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                id_usuario: parseInt(id_usuario), // Asegura que sea un entero
                                id_publicacion: id_publicacion // Envía correctamente el ID de la publicación
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                window.location.reload(); // Refrescar la página
                            } else {
                                alert("Error: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error en la solicitud:", error);
                            alert("Error al intentar registrar la asistencia.");
                        });
                }


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


                function eliminar(id_publicacion) {
                    console.log("ID a eliminar:", id_publicacion); 
                    if (confirm("¿Estás seguro de que deseas eliminar esta publicación?")) {
                        fetch("/app/controllers/addPublicationController.php?action=deletePublication", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    action: 'deletePublicacion',
                                    id_publicacion: id_publicacion
                                })
                            })
                            .then(response => {
                                console.log("Respuesta del servidor:", response);
                                if (!response.ok) { 
                                    throw new Error("Error en la respuesta del servidor");
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log("Datos recibidos del servidor:", data);
                                if (data.success) {
                                    alert(data.message);
                                    window.location.reload();
                                } else {
                                    alert("Error: " + data.message);
                                }
                            })
                            .catch(error => {
                                console.error(`Error en la solicitud: ${error}`); 
                                alert('Error al intentar eliminar la publicación.');
                            });

                    }
                }
            </script>


        </div>
    <?php endforeach; ?>
</div>