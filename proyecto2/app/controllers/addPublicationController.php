<?php
require __DIR__ .'/../models/addPublicationModel.php';

class AddPublicacionController {
    private $model;

    public function __construct() {
        // Instanciar el modelo
        global $conn;
        $this->model = new AddPublicationModel(conn:$conn);
    }

    public function addPublication() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "Paso 1: Inicio de addPublication<br>";
            
            // Procesar la fecha
            $fecha = DateTime::createFromFormat('Y-m-d', $_POST['fecha']);
            if (!$fecha) {
                $fecha = DateTime::createFromFormat('d/m/Y', $_POST['fecha']);
            }
    
            if ($fecha) {
                $fecha_formateada = $fecha->format('Y-m-d');
                echo "Paso 2: Fecha formateada - " . $fecha_formateada . "<br>";
            } else {
                die("Error: Fecha no válida");
            }
    
            $data = [
                'nombre_publicacion' => $_POST['nombre_publicacion'],
                'estado' => filter_var($_POST['estado'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
                'descripcion' => $_POST['descripcion'],
                'fecha' => $fecha_formateada,
                'id_categoria' => (int)$_POST['id_categoria'],
                'ubicacion' => $_POST['ubicacion'],
                'hora' => $_POST['hora'],
                'id_tipo_publico' => (int)$_POST['id_tipo_publico'],
                'limite_personas' => (int)$_POST['limite_personas'],
                'imagen' => $_POST['imagen']
            ];
    
            echo "Paso 3: Datos preparados<br>";
    
            if ($this->model->addPublication($data)) {
                echo "¡Publicación agregada exitosamente!";
                header("Location: /app/views/home/home.php");
                exit();
            } else {
                die("Error al agregar la publicación en el modelo.");
            }
        }
    }
    

    public function editPublication(){
        // Método para editar publicación
    }
}

// Crear una instancia del controlador y llamar a la función para procesar el formulario
$controller = new AddPublicacionController();
$controller->addPublication();
?>
