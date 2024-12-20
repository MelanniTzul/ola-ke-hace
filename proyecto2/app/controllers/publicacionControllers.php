<?php
require_once __DIR__ . '/../models/publicacionModel.php';
class ReservationCOntroller
{
    private $publicacionModel;
    public function __construct()
    {
        $this->publicacionModel = new publicacionModel();
    }

    //*VISUALIZAR LA VISTA
    public function showPublicacion($categoriaId = null)
    {
        $reserva = $this->publicacionModel->getPublicaciones($categoriaId);
        extract(['reserva' => $reserva]);
        include __DIR__ . '/../views/publicacion/publicacion.php';
    }



    //*BORRRADO LOGICO
    public function deletePublicacion($id)
    {
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

    //Obtener usuarios que asistiran a la publicacion
    public function obtenerPersonasQueAsistiran($idPublicacion)
    {
        header('Content-Type: application/json');

        try {
            $usuarios = $this->publicacionModel->obtenerPersonasQueAsistiran($idPublicacion);
            echo json_encode(['success' => true, 'usuarios' => $usuarios]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Error en el servidor: " . $e->getMessage()]);
        }
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    $controller = new ReservationController();



    $idPublicacion = $data['id_publicacion'] ?? null;
    if ($idPublicacion) {
        $controller->obtenerPersonasQueAsistiran((int)$idPublicacion);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de publicación no proporcionado.']);
    }
}
