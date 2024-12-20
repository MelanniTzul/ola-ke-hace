<?php
session_start();
require_once __DIR__ . '/../../controllers/userController.php';
require_once __DIR__ . '/../../controllers/publicacionControllers.php';
require_once __DIR__ . '/../../controllers/categoriaPublicacionController.php';

$userController = new UserController();
$reserController = new ReservationCOntroller();
$categoriaController = new CategoriaPublicacionController();
$categorias = $categoriaController->mostrarCategoria();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Segunda Pagina</title>
    <link rel="stylesheet" type="text/css" href="../../../public/css/style_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <!-- Botón de home -->
    <header>
        <a href="/app/views/home/home.php">
            <button class="icon-btn home-btn">
                <i class="fas fa-home"></i>
            </button>
        </a>
        <?php if (!isset($_SESSION['rol'])): ?>
            <button onclick="redirect()" class="btn btn-warning">Iniciar sesión</button>
        <?php elseif ($_SESSION['rol'] == 1): ?>
            <button onclick="redirectlogo()" class="">Panel de Administración</button>
        <?php elseif ($_SESSION['rol'] == 3): ?>
            <button data-bs-toggle="modal" data-bs-target="#eventosModal">Ver eventos que asistiré</button>
        <?php endif; ?>

        <?php if (isset($_SESSION['rol'])): ?>
            <button class="" onclick="mostrarNotificaciones()">
                <i class="fas fa-bell"></i>
                <span id="notificacion-badge" class="badge bg-danger" style="display: none;">0</span>
            </button>

            <div id="notificaciones-dropdown" class="dropdown-menu" style="display: none; position: absolute; right: 20px; top: 60px; z-index: 1000;">
                <ul id="notificaciones-list" class="list-group">
                    <!-- Aquí se cargarán las notificaciones -->
                </ul>
            </div>

            <button onclick="showUserProfile()" class="">Ver perfil</button>
            <button onclick="logout()" class="">Cerrar sesión</button>
        <?php endif; ?>



    </header>
    <script>
        function showUserProfile() {
            fetch('/app/controllers/getUserProfile.php') // Cambia a la ruta del nuevo endpoint
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Carga los datos en el modal
                    document.getElementById('userName').innerText = data.nombre_usuario || 'No disponible';
                    document.getElementById('userUsername').innerText = data.username || 'No disponible';
                    document.getElementById('userEmail').innerText = data.correo || 'No disponible';
                    document.getElementById('userCountry').innerText = data.nombre_pais || 'No disponible';
                    document.getElementById('userRole').innerText = data.nombre_rol || 'No disponible';
                    const userStateElement =document.getElementById('userEstado');
                    if(data.nombre_rol=='Publicador_de_anuncios'){
                        userStateElement.innerText = data.estado_usuario || 'No disponible';
                        userStateElement.parentElement.style.display = 'block';   
                    }else{
                        userStateElement.parentElement.style.display = 'none';   
                    }
                    // Muestra el modal
                    const modal = new bootstrap.Modal(document.getElementById('userProfileModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error al obtener los datos del usuario:', error);
                    alert('No se pudo cargar la información del perfil.');
                });
        }

        function mostrarNotificaciones() {
            const dropdown = document.getElementById('notificaciones-dropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';

            // Cargar notificaciones desde el backend
            fetch('/app/controllers/notificationController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'getNotificaciones',
                        userId: <?php echo $_SESSION['id']; ?>
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    const notificacionesList = document.getElementById('notificaciones-list');
                    notificacionesList.innerHTML = '';

                    if (data.success && data.notificaciones.length > 0) {
                        data.notificaciones.forEach(notificacion => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.textContent = notificacion.mensaje;
                            notificacionesList.appendChild(li);
                        });
                        document.getElementById('notificacion-badge').style.display = 'none';
                    } else {
                        const li = document.createElement('li');
                        li.className = 'list-group-item text-muted';
                        li.textContent = 'No tienes notificaciones.';
                        notificacionesList.appendChild(li);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar las notificaciones:', error);
                });
        }

        function marcarNotificacionesLeidas() {
            fetch('/app/controllers/notificationController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'marcarComoLeidas',
                    userId: <?php echo $_SESSION['id']; ?>
                })
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const eventosModal = document.getElementById('eventosModal');

            eventosModal.addEventListener('show.bs.modal', () => {
                fetch('/app/controllers/reservacionEventoController.php')
                    .then(response => response.json())
                    .then(data => {
                        const eventosContainer = document.getElementById('eventosContainer');
                        eventosContainer.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(evento => {
                                const eventoElement = document.createElement('div');
                                eventoElement.classList.add('card', 'mb-3');
                                eventoElement.innerHTML = `
                            <div class="card-body">
                                <h5 class="card-title">${evento.nombre_publicacion}</h5>
                                <p class="card-text">${evento.descripcion}</p>
                                <p class="card-text"><strong>Fecha:</strong> ${evento.fecha} <strong>Hora:</strong> ${evento.hora}</p>
                                <p class="card-text"><strong>Categoría:</strong> ${evento.nombre_categoria}</p>
                                <p class="card-text"><strong>Publicado por:</strong> ${evento.username}</p>
                                <p class="card-text text-success" id="countdown-${evento.id_publicacion}"></p>
                            </div>
                        `;
                                eventosContainer.appendChild(eventoElement);

                                // Inicializa el contador
                                startCountdown(evento.id_publicacion, evento.fecha, evento.hora);
                            });
                        } else {
                            eventosContainer.innerHTML = '<p>No tienes eventos reservados.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener los eventos:', error);
                    });
            });
        });

        function startCountdown(eventoId, fecha, hora) {
            const countdownElement = document.getElementById(`countdown-${eventoId}`);
            const eventoFechaHora = new Date(`${fecha}T${hora}`);

            const interval = setInterval(() => {
                const now = new Date();
                const diff = eventoFechaHora - now;

                if (diff <= 0) {
                    countdownElement.textContent = 'El evento ya ha comenzado';
                    clearInterval(interval);
                } else {
                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    countdownElement.textContent = `Tiempo restante: ${hours}h ${minutes}m ${seconds}s`;
                }
            }, 1000);
        }
    </script>
    <!-- SECCIONES -->
    <nav class="navbar">
        <div class="logo"></div>
        <ul class="nav-links">
            <li><a href="#reserva"><i class="fas fa-newspaper"></i><span class="nav-text">Publicaciones</span></a></li>
        </ul>
    </nav>

    <!-- Modal -->
    <div class="modal fade" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userProfileModalLabel">Información del Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <span id="userName"></span></p>
                    <p><strong>Usuario:</strong> <span id="userUsername"></span></p>
                    <p><strong>Correo:</strong> <span id="userEmail"></span></p>
                    <p><strong>País:</strong> <span id="userCountry"></span></p>
                    <p><strong>Rol:</strong> <span id="userRole"></span></p>
                    <p style="display: none;"><strong>Estado:</strong> <span id="userEstado"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="eventosModal" tabindex="-1" aria-labelledby="eventosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventosModalLabel">Eventos que asistiré</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="eventosContainer">
                        <!-- Aquí se cargarán los eventos -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>



    <div class="main-content">
        <div id="home" class="page-content" style="display:none;">
            <!-- AGREGAR PUBLICACION -->
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 2): ?>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
                <?php require __DIR__ . '/../../views/publicacion/addpublicacion.php'; ?>
            <?php endif; ?>

            <!-- Formulario publi-->
            <?php require __DIR__ . '/../../views/publicacion/addpublicacion.php' ?>

            <?php if (isset($_SESSION['rol'])): ?>
                <h1 class="title">Bienvenido <?php echo $_SESSION['username'] ?></h1>
                <h2 class="title">Rol: <?php echo $_SESSION['rol'] == 1 ? 'Administrador' : ($_SESSION['rol'] == 2 ? 'Publicador' : 'Usuario') ?></h2>
            <?php endif; ?>

            <h1 class="title">Publicaciones o Eventos</h1>

            <div class="container mt-4">
                <form id="filtroForm" method="GET" action="">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="categoria" class="form-label">Filtrar por categoría:</label>
                            <select name="categoria" id="categoria" class="form-select" onchange="document.getElementById('filtroForm').submit();">
                                <option value="">Todas las categorías</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo $categoria['id']; ?>" <?php echo isset($_GET['categoria']) && $_GET['categoria'] == $categoria['id'] ? 'selected' : ''; ?>>
                                        <?php echo $categoria['nombre_categoria']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>


            <?php
            $categoriaSeleccionada = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;
            $reserController->showPublicacion($categoriaSeleccionada);
            ?>
        </div>
    </div>

    <script>
        function redirect() {
            window.location.href = "/app/views/user/login.php";
        }

        function redirectlogo() {
            window.location.href = "/app/views/user/newUser.php";
        }

        function logout() {
            window.location.href = "/app/views/user/logout.php";
        }

        function toggleSidebar() {
            const navbar = document.querySelector('.navbar');
            navbar.classList.toggle('collapsed');
            const mainContent = document.querySelector('.main-content');
            mainContent.style.marginLeft = navbar.classList.contains('collapsed') ? '60px' : '200px';
        }

        function showContent(sectionId) {
            // Ocultar todas las secciones
            const sections = document.querySelectorAll('.page-content');
            sections.forEach(section => {
                section.style.display = 'none';
            });

            // Mostrar la sección seleccionada
            const selectedSection = document.getElementById(sectionId);
            selectedSection.style.display = 'block';

        }

        // Añadir event listeners a los enlaces de navegación
        document.querySelector('.nav-links').addEventListener('click', function(e) {
            if (e.target.tagName === 'A' || e.target.tagName === 'I' || e.target.tagName === 'SPAN') {
                const link = e.target.closest('a');
                const sectionId = link.getAttribute('href').substring(1); // Obtener el id de la sección
                showContent(sectionId);
                e.preventDefault(); // Prevenir la navegación real
            }
        });

        // Mostrar la sección de inicio por defecto
        showContent('home');
    </script>

</body>

</html>