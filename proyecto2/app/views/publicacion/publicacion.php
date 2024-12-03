<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservaciones</title>
    <link rel="stylesheet" type="text/css" href="../../../public/css/style_reservation.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
    <div id="reservations" class="container mt-4">
        <?php foreach ($reserva as $res): ?>
            <div class="card mb-3">
                <input type="hidden" value="<?php echo htmlspecialchars($res['id_usuario']); ?>">
                <input type="hidden" value="<?php echo htmlspecialchars($res['imagen']); ?>">
                <img src="<?php echo htmlspecialchars($res['imagen']); ?>" alt="Imagen del apartamento" class="card-img-top">
                <div class="card-body">
                    <h3>● <?php echo htmlspecialchars($res['nombre_publicacion']); ?></h3>
                    <p>Fecha: <?php echo htmlspecialchars($res['fecha']); ?></p>
                    <p>Hora: <?php echo htmlspecialchars($res['hora']); ?></p>
                    <p>Ubicación: <?php echo htmlspecialchars($res['ubicacion']); ?></p>
                    <p>Descripción: <?php echo htmlspecialchars($res['descripcion']); ?></p>
                    <strong class="bold-text">Publicador:</strong> <?php echo htmlspecialchars($res['nombre']); ?>
                </div>

                <div class="card-footer text-end">
                    <?php
                    if (isset($_SESSION['rol'], $_SESSION['id']) && $_SESSION['rol'] == 3) {
                        // Mostrar opciones para rol 3
                    ?>
                        <button class="btn btn-warning" onclick="reportar(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Reportar</button>
                        <button class="btn btn-success" onclick="asistir(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Asistir</button>
                    <?php
                    } elseif (isset($_SESSION['rol'], $_SESSION['id']) && ($_SESSION['rol'] == 1 || $_SESSION['id'] == $res['id_usuario'])) {
                        // Mostrar opciones para dueño de la publicación o rol 1
                    ?>
                        <button class="btn btn-primary" onclick="editar(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Editar</button>
                        <button class="btn btn-danger" onclick="eliminar(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Eliminar</button>
                    <?php
                    }
                    ?>
                </div>


            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal para Editar Publicación -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Publicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId" name="id_publicacion">
                        <div class="mb-3">
                            <label for="editNombrePublicacion" class="form-label">Título</label>
                            <input type="text" class="form-control" id="editNombrePublicacion" name="nombre_publicacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUbicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="editUbicacion" name="ubicacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="editDescripcion" name="descripcion" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editImagen" class="form-label">Imagen</label>
                            <input type="text" class="form-control" id="editImagen" name="imagen" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editar(id_publicacion) {
            // Buscar el elemento de la tarjeta correspondiente
            const card = document.querySelector(`button[onclick="editar(${id_publicacion})"]`).closest('.card');

            // Extraer los valores de ubicación y descripción del contenido de la tarjeta
            const nombre_publicacion = card.querySelector('h3').textContent.replace('● ', '');
            const ubicacion = card.querySelector('p:nth-of-type(3)').textContent.replace('Ubicación: ', '');
            const descripcion = card.querySelector('p:nth-of-type(4)').textContent.replace('Descripción:', '').trim();
            const imagen = card.querySelector('input[type="hidden"]').value;

            // Asignar los valores a los campos del modal
            document.getElementById('editId').value = id_publicacion;
            document.getElementById('editNombrePublicacion').value = nombre_publicacion;
            document.getElementById('editUbicacion').value = ubicacion;
            document.getElementById('editDescripcion').value = descripcion;
            document.getElementById('editImagen').value = imagen;

            // Mostrar el modal usando Bootstrap
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }


        document.getElementById('editForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('/app/controllers/publicationController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'editPublication',
                        id_publicacion: formData.get('id_publicacion'),
                        nombre_publicacion: formData.get('nombre_publicacion'),
                        ubicacion: formData.get('ubicacion'),
                        descripcion: formData.get('descripcion'),
                        imagen: formData.get('imagen')
                    })

                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Publicación actualizada correctamente.');
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al actualizar la publicación.');
                });
        });

        function eliminar(id_publicacion) {
            if (confirm("¿Estás seguro de que deseas eliminar esta publicación?")) {
                fetch('/app/controllers/publicationController.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'deletePublication',
                            id_publicacion: id_publicacion
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Publicación eliminada correctamente.');
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al eliminar la publicación.');
                    });
            }
        }

        function reportar(id_publicacion) {
            let motivo = prompt('Ingresa el motivo del reporte:');
            if (!motivo) {
                alert('Debes ingresar un motivo para el reporte.');
                return;
            }

            fetch('/app/controllers/publicationController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'reportPublication',
                        id_publicacion: id_publicacion,
                        motivo: motivo
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Reporte enviado exitosamente.');
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al enviar el reporte.');
                });
        }

        function asistir(id_publicacion) {
            // Encuentra la tarjeta que contiene los datos del evento
            const card = document.querySelector(`button[onclick="asistir(${id_publicacion})"]`).closest('.card');

            // Extrae la fecha y hora del evento
            const nombre_publicacion = card.querySelector('h3').textContent.replace('● ', '');
            const fecha = card.querySelector('p:nth-of-type(1)').textContent.replace('Fecha: ', '').trim();
            console.log(fecha);
            const hora = card.querySelector('p:nth-of-type(2)').textContent.replace('Hora: ', '').trim();
            console.log(hora);
            // Combina fecha y hora en un objeto Date
            const eventoDate = new Date(`${fecha}T${hora}`);

            // Verifica si la fecha es válida
            if (isNaN(eventoDate)) {
                alert('La fecha u hora del evento no son válidas.');
                return;
            }

            // Crea la notificación flotante para el contador
            let contadorDiv = document.createElement('div');
            contadorDiv.id = `contador-${id_publicacion}`;
            contadorDiv.classList.add('alert', 'alert-info', 'fixed-bottom', 'text-center');
            contadorDiv.style.zIndex = 1050; // Asegura que quede visible sobre otros elementos
            contadorDiv.style.marginBottom = '20px';
            document.body.appendChild(contadorDiv);

            // Actualiza el contador cada segundo
            const intervalo = setInterval(() => {
                const now = new Date();
                const tiempoRestante = eventoDate - now;

                // Si el evento ya ha comenzado, detén el contador
                if (tiempoRestante <= 0) {
                    clearInterval(intervalo);
                    contadorDiv.textContent = "¡El evento ya comenzó!";
                    setTimeout(() => {
                        contadorDiv.remove();
                    }, 5000); // Elimina la notificación tras 5 segundos
                    return;
                }

                // Calcula días, horas, minutos y segundos restantes
                const dias = Math.floor(tiempoRestante / (1000 * 60 * 60 * 24));
                const horas = Math.floor((tiempoRestante % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutos = Math.floor((tiempoRestante % (1000 * 60 * 60)) / (1000 * 60));
                const segundos = Math.floor((tiempoRestante % (1000 * 60)) / 1000);

                // Actualiza el contenido de la notificación
                contadorDiv.textContent = `${nombre_publicacion} Tiempo restante para el evento: ${dias}d ${horas}h ${minutos}m ${segundos}s`;
            }, 1000);
        }
    </script>
</body>

</html>