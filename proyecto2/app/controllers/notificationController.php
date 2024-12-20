<?php
require_once __DIR__ . '/../models/notificacionModel.php';

class NotificationController {
    private $notificacionModel;

    public function __construct() {
        $this->notificacionModel = new NotificacionModel();
    }

    public function getNotificaciones($userId) {
        header('Content-Type: application/json');
        try {
            $notificaciones = $this->notificacionModel->getNotificaciones($userId);
            echo json_encode(['success' => true, 'notificaciones' => $notificaciones]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    
    $notificationController = new NotificationController();

    switch ($action) {
        case 'getNotificaciones':
            $userId = $data['userId'] ?? '';
            $notificationController->getNotificaciones($userId);
            break;
        default:
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
            break;
    }
}