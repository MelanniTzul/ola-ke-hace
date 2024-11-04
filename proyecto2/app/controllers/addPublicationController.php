<?php
require __DIR__ .'/../models/addPublicationModel.php';

class AddPublicacionController {
    private $model;

    public function __construct() {
        // Instanciar el modelo
        $this->model = new AddPublicationModel();
    }

    // public function addPublication() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         echo "Valor original de fecha desde el formulario: " . $_POST['fecha'] . "<br>";
    //         // Mostrar los datos enviados en el formulario para depuración
    //         var_dump($_POST);

    //         // Procesar la fecha para asegurarse de que esté en el formato correcto (Y-m-d)
    //         $fecha = DateTime::createFromFormat('d/m/Y', $_POST['fecha']);
    //         if (!$fecha) {
    //             // Intentar con formato Y-m-d si el formato d/m/Y falla
    //             $fecha = DateTime::createFromFormat('Y-m-d', $_POST['fecha']);
    //         }
            
    //         // Verificar si la conversión fue exitosa
    //         if ($fecha) {
    //             $fecha_formateada = $fecha->format('Y-m-d');
    //         } else {
    //             echo "Error: Fecha no válida.";
    //             return;
    //         }

    //         // Construir el array de datos
    //         $data = [
    //             'nombre_publicacion' => $_POST['nombre_publicacion'],
    //             'estado' => filter_var($_POST['estado'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
    //             'descripcion' => $_POST['descripcion'],
    //             'fecha' => $fecha_formateada, // Usar la fecha ya procesada y formateada
    //             'id_categoria' => (int)$_POST['id_categoria'],
    //             'ubicacion' => $_POST['ubicacion'],
    //             'hora' => $_POST['hora'],
    //             'id_tipo_publico' => (int)$_POST['id_tipo_publico'],
    //             'limite_personas' => (int)$_POST['limite_personas'],
    //             'imagen' => $_POST['imagen']
    //         ];

    //         // Verificación de depuración
    //         echo "Fecha procesada para MySQL: " . $data['fecha'] . "<br>";
    //         var_dump($data);

    //         // Intentar agregar la publicación
    //         if ($this->model->addPublication($data)) {
    //             echo "¡Publicación agregada exitosamente!";
    //             header("Location: /app/views/home/home.php");
    //             exit();
    //         } else {
    //             echo "Error al agregar la publicación.";
    //         }
    //     }
    // }
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
