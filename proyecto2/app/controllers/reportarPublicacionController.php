<?php
require_once __DIR__ . '/../models/reportarPublicacionModel.php';

class dataServicioEdificioController {
    private $solServicioEdificioModel;

    public function __construct() {
        $this->solServicioEdificioModel = new ReportarPublicacionModel();
    }

    public function handleRequestS() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $id = $data['id'];
            $motivo = $data['motivo']; // Captura el motivo del reporte

            // Aquí debes procesar la reserva con el ID y el motivo
            $resultS = $this->solServicioEdificioModel->insertarReporte($id, $motivo);

            // Redirigir o responder según el resultado
            $parametros = http_build_query(['id' => $id, 'data' => $resultS]);

            $redirectUrl = $resultS ? '/app/views/reservation/reportarPublicacion.php?' . $parametros : '/errorPage.php?' . $parametros;

            echo json_encode(['success' => $resultS, 'redirectUrl' => $redirectUrl]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }
}

$controller = new dataServicioEdificioController();
$controller->handleRequestS();

?>
