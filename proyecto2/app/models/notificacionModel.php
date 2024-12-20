<?php

class notificacionModel
{
    private $conn;

    public function __construct()
    {
        require __DIR__ . '/../../config/conexion.php';
        $this->conn = $conn;
    }

    public function createNotificacion($idUsuario, $tipo, $mensaje)
    {
        $stmt = $this->conn->prepare("INSERT INTO ola_ke_hace.notificaciones (id_usuario, tipo, mensaje, estado, fecha) VALUES (?, ?, ?, 0, CURDATE())");
        $stmt->bind_param("iss", $idUsuario, $tipo, $mensaje);
        return $stmt->execute();
    }


    public function readNotificacion($id_notificacion)
    {
        $sql = "
            UPDATE 
                ola_ke_hace.notificacion
            SET 
                estado = 1
            WHERE 
                id_notificacion = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_notificacion]);
    }

    public function getNotificaciones($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM ola_ke_hace.notificaciones WHERE id_usuario = ? AND estado = 0 ORDER BY fecha DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function marcarComoLeidas($userId) {
        $stmt = $this->conn->prepare("UPDATE ola_ke_hace.notificaciones SET estado = 1 WHERE id_usuario = ?");
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
