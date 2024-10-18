<?php
require_once __DIR__ . '/../models/solicitudReserModel.php';

class SolReservationController {
    private $solReservationModel;

    public function __construct() {
        $this->solReservationModel = new SolReservationModel();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
                $id = $data['id'];
                // Procesa la reserva usando el ID
                $result = $this->solReservationModel->getReservationData();

                // Prepara los parámetros para la URL
                $parametros = http_build_query(['id' => $id, 'data' => $result ]);

                // Define la URL de redirección con parámetros
                $redirectUrl = $result ? '/app/views/reservation/solicitudReserva.php?' . $parametros : '/errorPage.php?' . $parametros;

                echo json_encode(['success' => $result, 'redirectUrl' => $redirectUrl]);
            } else {
                echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            }
    }
}

$controller = new SolReservationController();
$controller->handleRequest();
?>
