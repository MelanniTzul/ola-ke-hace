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
                <input type="hidden" value="<?php echo htmlspecialchars($res['imagen']); ?>">
                <img src="<?php echo htmlspecialchars($res['imagen']); ?>" alt="Imagen del apartamento" class="card-img-top">
                <div class="card-body">
                    <h3>● <?php echo htmlspecialchars($res['nombre_publicacion']); ?></h3>
                    <p>Fecha: <?php echo htmlspecialchars($res['fecha']); ?></p>
                    <p>Hora: <?php echo htmlspecialchars($res['hora']); ?></p>
                    <p>Ubicación: <?php echo htmlspecialchars($res['ubicacion']); ?></p>
                    <p>Descripción: <?php echo htmlspecialchars($res['descripcion']); ?></p>
                    <p>Límite de personas Máximas: <?php echo htmlspecialchars($res['limite_personas']); ?> </p>
                    <p>Límite de personas Actual: <?php echo htmlspecialchars($res['limite_personas_actual']); ?> </p>
                    <p>Categoría: <?php echo htmlspecialchars($res['nombre_categoria']); ?></p>
                    <strong class="bold-text">Publicador:</strong> <?php echo htmlspecialchars($res['nombre']); ?>
                </div>

                <div class="card-footer text-end">
                    <?php
                    if (isset($_SESSION['rol'], $_SESSION['id']) && $_SESSION['rol'] == 3) {
                        // Mostrar opciones para rol 3
                    ?>
                        <button class="btn btn-warning" onclick="reportar(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Reportar</button>
                        <div class="card-footer text-end">
                            <div id="actions-<?php echo htmlspecialchars($res['id_publicacion']); ?>">

                            </div>
                        </div>

                    <?php
                    } elseif (isset($_SESSION['rol'], $_SESSION['id']) && ($_SESSION['rol'] == 1 || $_SESSION['id'] == $res['id_usuario'])) {
                        // Mostrar opciones para dueño de la publicación o rol 1
                    ?>
                        <button class="btn btn-primary" onclick="editar(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Editar</button>
                        <button class="btn btn-danger" onclick="eliminar(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Eliminar</button>
                        <button class="btn btn-warning" onclick="verUsuarios(<?php echo htmlspecialchars($res['id_publicacion']); ?>)">Ver usuarios que van a asistir</button>
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

    <div class="modal fade" id="usuariosModal" tabindex="-1" aria-labelledby="usuariosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usuariosModalLabel">Usuarios que asistirán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Username</th>
                                <th>Correo</th>
                            </tr>
                        </thead>
                        <tbody id="usuariosTableBody">
                            <!-- Aquí se cargarán los datos dinámicamente -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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

        function verUsuarios(id_publicacion) {
            fetch('/app/controllers/publicacionControllers.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'obtenerPersonasQueAsistiran',
                        id_publicacion: id_publicacion,
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        const usuarios = data.usuarios;
                        const tableBody = document.getElementById('usuariosTableBody');
                        tableBody.innerHTML = '';

                        usuarios.forEach((usuario) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td>${usuario.nombre}</td>
                        <td>${usuario.username}</td>
                        <td>${usuario.correo}</td>
                    `;
                            tableBody.appendChild(row);
                        });

                        const modal = new bootstrap.Modal(document.getElementById('usuariosModal'));
                        modal.show();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al obtener los usuarios.');
                });
        }


        document.addEventListener('DOMContentLoaded', () => {
            const publicaciones = document.querySelectorAll('[id^="actions-"]');
            publicaciones.forEach(pub => {
                const id_publicacion = pub.id.split('-')[1];
                loadButtons(id_publicacion);
            });
        });


        function loadButtons(id_publicacion) {
            fetch('/app/controllers/reservacionEventoController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'loadEventStatus',
                        id_publicacion: id_publicacion
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) throw new Error(data.message);

                    const actionsContainer = document.getElementById(`actions-${id_publicacion}`);
                    const {
                        count,
                        isExpired,
                        hasReservation
                    } = data;

                    if (isExpired) {
                        actionsContainer.innerHTML = `<button class="btn btn-secondary" disabled>Evento pasado</button>`;
                    } else if (count <= 0) {
                        actionsContainer.innerHTML = `<button class="btn btn-secondary" disabled>No disponible</button>`;
                    } else if (hasReservation) {
                        actionsContainer.innerHTML = `<button class="btn btn-danger" onclick="desasistir(${id_publicacion})">Ya no asistir</button>`;
                    } else {
                        actionsContainer.innerHTML = `<button class="btn btn-success" onclick="asistir(${id_publicacion})">Asistir</button>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const actionsContainer = document.getElementById(`actions-${id_publicacion}`);
                    actionsContainer.innerHTML = '<p class="text-danger">Error al cargar las acciones.</p>';
                });
        }



        function asistir(id_publicacion) {
            // Realizar una solicitud al backend para agregar la reservación
            fetch('/app/controllers/reservacionEventoController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'addReservacion', // Acción definida en el controlador
                        id_publicacion: id_publicacion
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Has reservado tu asistencia al evento exitosamente.');
                        window.location.reload(); // Opcional: Recargar la página para reflejar cambios
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al reservar tu asistencia al evento.');
                });
        }

        function desasistir(id_publicacion) {
            fetch('/app/controllers/reservacionEventoController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'deleteReservacion', // Acción definida en el controlador
                        id_publicacion: id_publicacion
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Has cancelado tu asistencia al evento exitosamente.');
                        loadButtons(id_publicacion); // Recargar los botones dinámicamente
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al cancelar tu asistencia al evento.');
                });
        }
    </script>
</body>

</html>