//*IMPORTACIONES
<?php
require_once __DIR__ . '/../../controllers/userController.php';
require_once __DIR__ . '/../../controllers/publicacionControllers.php';

$userController = new UserController();
$reserController = new ReservationCOntroller();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Segunda Pagina</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <!-- Botón de home -->
    <div class="top-bar">
        <button class="icon-btn home-btn">
            <i class="fas fa-home"></i>
        </button>

        <!-- Botón de Notificaciones -->
        <button class="icon-btn notification-btn">
            <i class="fas fa-bell"></i>
        </button>
        <button onclick="redirect()" class="login-btn">Iniciar sesión</button>


    </div>
    <script>
        function redirect() {
            window.location.href = "/app/views/user/login.php";
        }
    </script>
    <!-- SECCIONES -->
    <nav class="navbar">
        <div class="logo"></div>
        <ul class="nav-links">
            <li><a href="#reserva"><i class="fas fa-newspaper"></i><span class="nav-text">Publicaciones</span></a></li>
        </ul>
    </nav>

    <div class="main-content">
        <div id="home" class="page-content" style="display:none;">
            <!-- AGREGAR PUBLICACION -->
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><strong>Agregar Publicación</strong></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form [formGroup]="publicationForm" (ngSubmit)="onSubmit()">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="form-label">Nombre de la Publicación</label>
                                        <input type="text" formControlName="nombre_publicacion" class="form-control" id="nombre" placeholder="Cumpleaños">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="estado" class="form-label">Estado</label>
                                        <select formControlName="estado" class="form-select" id="estado">
                                            <option [ngValue]="true">Activo</option>
                                            <option [ngValue]="false">Inactivo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea formControlName="descripcion" class="form-control" id="descripcion" rows="3" placeholder="Descripción del evento"></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha" class="form-label">Fecha</label>
                                        <input type="date" formControlName="fecha" class="form-control" id="fecha" placeholder="2024-10-15">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="id_categoria" class="form-label">ID Categoría</label>
                                        <input type="number" formControlName="id_categoria" class="form-control" id="id_categoria" placeholder="2">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="ubicacion" class="form-label">Ubicación</label>
                                        <input type="text" formControlName="ubicacion" class="form-control" id="ubicacion" placeholder="Centro de Convenciones">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="hora" class="form-label">Hora</label>
                                        <input type="time" formControlName="hora" class="form-control" id="hora" placeholder="10:00 AM">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="id_tipo_publico" class="form-label">ID Tipo Público</label>
                                        <input type="number" formControlName="id_tipo_publico" class="form-control" id="id_tipo_publico" placeholder="1">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="limite_personas" class="form-label">Límite de Personas</label>
                                        <input type="number" formControlName="limite_personas" class="form-control" id="limite_personas" placeholder="150">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="imagen" class="form-label">URL de la Imagen</label>
                                        <input type="text" formControlName="imagen" class="form-control" id="imagen" placeholder="https://entercommla.com/hipegot/2024/07/PORTADA-1-1.png">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" (click)="onSubmit()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

            <h1 class="title">Publicaciones o Eventos</h1>

            <?php
            $reserController->showPublicacion();
            ?>
        </div>
    </div>

    <script>
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