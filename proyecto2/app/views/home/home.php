<?php
session_start();
require_once __DIR__ . '/../../controllers/userController.php';
require_once __DIR__ . '/../../controllers/publicacionControllers.php';

$userController = new UserController();
$reserController = new ReservationCOntroller();
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
        <?php endif; ?>

        <?php if (isset($_SESSION['rol'])): ?>
        <button class="">
            <i class="fas fa-bell"></i>
        </button>
        <button onclick="perfil()" class="">Ver perfil</button>
        <button onclick="logout()" class="">Cerrar sesión</button>
        <?php endif; ?>



    </header>
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

        function perfil(){
            window.location.href = "/app/views/user/infoPerfil.php"
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