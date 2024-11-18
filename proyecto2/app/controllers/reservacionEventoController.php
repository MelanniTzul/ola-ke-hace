
<?php
require_once __DIR__ . '/../models/reservacionEventoModel.php';
require_once __DIR__ . '/../../config/conexion.php';

class ReservacionEventoController
{
    private $reservacionEventoModel;

    public function __construct()
    {
        global $conn; 
        $this->reservacionEventoModel = new ReservacionEventoModel($conn);
    }

    public function addReservacion()
    {
        header('Content-Type: application/json'); // Asegura el encabezado JSON

        // Verifica si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decodifica el JSON enviado
            $data = json_decode(file_get_contents('php://input'), true);

            $id_usuario = isset($data['id_usuario']) ? (int)$data['id_usuario'] : null;
            $id_publicacion = isset($data['id_publicacion']) ? (int)$data['id_publicacion'] : null;

            // Valida los datos
            if ($id_usuario === null || $id_publicacion === null) {
                echo json_encode(["success" => false, "message" => "ID de usuario o publicación no proporcionados."]);
                return;
            }

            $activo = 1; // Por defecto activo

            // Intenta agregar la reservación
            $result = $this->reservacionEventoModel->addReservacionEvento($activo, $id_usuario, $id_publicacion);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Reservación agregada exitosamente."]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al agregar la reservación."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Método no permitido."]);
        }
    }
}

$controller = new ReservacionEventoController();
$controller->addReservacion();
