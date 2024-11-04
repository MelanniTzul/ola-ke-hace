<?php
require_once __DIR__ . '/../models/publicacionModel.php';
class ReservationCOntroller{
    private $publicacionModel;
    public function __construct(){
        $this->publicacionModel = new publicacionModel();
    }

    //*VISUALIZAR LA VISTA
    public function showPublicacion(){
        $reserva = $this->publicacionModel->getPublicaciones();
        extract(['reserva'=>$reserva]);
        include __DIR__.'/../views/publicacion/publicacion.php';
    }

    
    //*BORRRADO LOGICO
    public function deletePublicacion($id) {
    header('Content-Type: application/json');
    
    try {
        // Aquí tu lógica de borrado o actualización
        $result = $this->publicacionModel->deletePublicacion($id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => "Publicación eliminada correctamente"]);
        } else {
            echo json_encode(['success' => false, 'message' => "Error al eliminar la publicación"]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Error en el servidor: " . $e->getMessage()]);
    }
    exit;
}

    
    
}
?>