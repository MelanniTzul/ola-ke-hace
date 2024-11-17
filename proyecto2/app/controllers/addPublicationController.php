<?php
require_once __DIR__ . '/../models/addPublicationModel.php';
require_once __DIR__ . '/../../config/conexion.php';

class AddPublicacionController
{
    private $addPublicationModel;

    public function __construct()
    {
        // Instanciar el modelo
        global $conn;
        $this->addPublicationModel = new AddPublicationModel(conn: $conn);
    }

    public function addPublication()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "Paso 1: Inicio de addPublication<br>";
            $nombre_publicacion = $_POST['nombre_publicacion'] ?? null;
            $estado = 1;
            $descripcion = $_POST['descripcion'] ?? null;
            $fecha = date('Y-m-d');
            $id_categoria = (int)$_POST['id_categoria'];
            $ubicacion = $_POST['ubicacion'];
            $hora =  date('H:i:s');
            $id_tipo_publico = (int)$_POST['id_tipo_publico'];
            $limite_personas = (int)$_POST['limite_personas'];
            $imagen = $_POST['imagen'];


            echo "Paso 3: Datos preparados<br>";
            $isCreatedPublication = $this->addPublicationModel->addPublication($nombre_publicacion, $estado, $descripcion, $fecha, $id_categoria, $ubicacion, $hora, $id_tipo_publico, $limite_personas, $imagen);

            if ($isCreatedPublication) {
                echo "¡Publicación agregada exitosamente!";
                header("Location: /app/views/home/home.php");
                exit();
            } else {
                die("Error al agregar la publicación en el modelo.");
            }
        }
    }


    public function editPublication()
    {
        // Método para editar publicación
    }
}

$controller = new AddPublicacionController();
$controller->addPublication();
