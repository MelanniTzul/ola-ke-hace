
<?php
require_once __DIR__ . '/../models/reservacionEventoModel.php';

class ReservacionEventoController
{
    private $reservacionEventoModel;

    public function __construct()
    {
        $this->reservacionEventoModel = new ReservacionEventoModel();
    }

    public function addReservacion()
    {
        header('Content-Type: application/json');

        // Verifica si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decodifica el JSON enviado
            $data = json_decode(file_get_contents('php://input'), true);

            session_start();
            $id_usuario = $_SESSION['id'];
            session_write_close();
            $id_publicacion = isset($data['id_publicacion']) ? (int)$data['id_publicacion'] : null;

            // Valida los datos
            if ($id_usuario === null || $id_publicacion === null) {
                echo json_encode(["success" => false, "message" => "ID de usuario o publicación no proporcionados."]);
                return;
            }


            // Intenta agregar la reservación
            $result = $this->reservacionEventoModel->addReservacionEvento($id_usuario, $id_publicacion);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Reservación agregada exitosamente."]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al agregar la reservación."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Método no permitido."]);
        }
    }

    public function deleteReservacion()
    {
        header('Content-Type: application/json');

        // Verifica si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decodifica el JSON enviado
            $data = json_decode(file_get_contents('php://input'), true);

            session_start();
            $id_usuario = $_SESSION['id'];
            session_write_close();
            $id_publicacion = isset($data['id_publicacion']) ? (int)$data['id_publicacion'] : null;

            // Valida los datos
            if ($id_usuario === null || $id_publicacion === null) {
                echo json_encode(["success" => false, "message" => "ID de usuario o publicación no proporcionados."]);
                return;
            }

            // Intenta eliminar la reservación
            $result = $this->reservacionEventoModel->deleteReservacionEvento($id_usuario, $id_publicacion);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Reservación eliminada exitosamente."]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al eliminar la reservación."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Método no permitido."]);
        }
    }

    public function userHasReservation()
    {
        header('Content-Type: application/json');

        // Verifica si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decodifica el JSON enviado
            $data = json_decode(file_get_contents('php://input'), true);

            session_start();
            $id_usuario = $_SESSION['id'];
            session_write_close();
            $id_publicacion = isset($data['id_publicacion']) ? (int)$data['id_publicacion'] : null;

            // Valida los datos
            if ($id_usuario === null || $id_publicacion === null) {
                echo json_encode(["success" => false, "message" => "ID de usuario o publicación no proporcionados."]);
                return;
            }

            // Verifica si el usuario tiene reservación
            $hasReservation = $this->reservacionEventoModel->userHasReservation($id_usuario, $id_publicacion);

            echo json_encode(["success" => true, "hasReservation" => $hasReservation]);
        } else {
            echo json_encode(["success" => false, "message" => "Método no permitido."]);
        }
    }

    public function countUserReservations()
    {
        header('Content-Type: application/json');

        // Verifica si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $id_publicacion = $data['id_publicacion'] ?? null;

            // Valida los datos
            if ($id_publicacion === null) {
                echo json_encode(["success" => false, "message" => "ID de publicación no proporcionado."]);
                return;
            }

            // Obtiene el límite actual de usuarios
            $count = $this->reservacionEventoModel->countUserReservations($id_publicacion);

            echo json_encode(["success" => true, "count" => $count]);
        } else {
            echo json_encode(["success" => false, "message" => "Método no permitido."]);
        }
    }

    public function getReservation(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            session_start();
            $id_usuario = $_SESSION['id'] ?? null;
            session_write_close();

            if ($id_usuario === null) {
                echo json_encode(["success" => false, "message" => "Usuario no autenticado."]);
                return;
            }

            $reservaciones = $this->reservacionEventoModel->getReservation($id_usuario);

            // Depura la salida antes de enviarla
            if (empty($reservaciones)) {
                echo json_encode([]); // Retorna un array vacío si no hay datos
            } else {
                echo json_encode($reservaciones); // Retorna las reservaciones
            }
        } else {
            echo json_encode(["success" => false, "message" => "Método no permitido."]);
        }
    }

    public function loadEventStatus()
{
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_publicacion = $data['id_publicacion'] ?? null;

        session_start();
        $id_usuario = $_SESSION['id'] ?? null;
        session_write_close();

        if ($id_publicacion === null || $id_usuario === null) {
            echo json_encode(["success" => false, "message" => "ID de publicación o usuario no proporcionado."]);
            return;
        }

        $count = $this->reservacionEventoModel->countUserReservations($id_publicacion);
        $isExpired = $this->reservacionEventoModel->isEventExpired($id_publicacion);
        $hasReservation = $this->reservacionEventoModel->userHasReservation($id_usuario, $id_publicacion);

        echo json_encode([
            "success" => true,
            "count" => $count,
            "isExpired" => $isExpired,
            "hasReservation" => $hasReservation
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Método no permitido."]);
    }
}

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    $reservacionEventoController = new ReservacionEventoController();

    switch ($action) {
        case 'addReservacion':
            $reservacionEventoController->addReservacion();
            break;
        case 'deleteReservacion':
            $reservacionEventoController->deleteReservacion();
            break;
        case 'userHasReservation':
            $reservacionEventoController->userHasReservation();
            break;
        case 'countUserReservations':
            $reservacionEventoController->countUserReservations();
            break;
        case 'loadEventStatus':
            $reservacionEventoController->loadEventStatus();
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $reservacionEventoController = new ReservacionEventoController();

    $reservacionEventoController->getReservation();
}
