<?php
require_once __DIR__ . '/notificacionModel.php';
require_once __DIR__ . '/addPublicationModel.php';
class ReservacionEventoModel
{

    private $conn;

    public function __construct()
    {
        require __DIR__ . '/../../config/conexion.php';
        $this->conn = $conn;
    }

    public function addReservacionEvento($id_usuario,$id_publicacion) {
        try{
            $sql = "INSERT INTO ola_ke_hace.reservacion_evento 
                (activo, id_usuario, id_publicacion) 
                VALUES (1,?,?)";
        
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }
    
        // Configura los tipos de datos
        $stmt->bind_param(
            "ii", 
            $id_usuario,              
            $id_publicacion
        );
    
        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }

        $publicationModel = new AddPublicationModel(conn: $this->conn);

        $editLimitPublication = $publicationModel->editLimitPublication($id_publicacion, 'asistir');

        if ($editLimitPublication) {
            $notificacionModel = new NotificacionModel();
            //obtener nombre de la publicacion
            $sql = "SELECT nombre_publicacion FROM ola_ke_hace.publicacion WHERE id_publicacion = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_publicacion);
            $stmt->execute();
            $result = $stmt->get_result();
            $nombre_publicacion = $result->fetch_assoc()['nombre_publicacion'];
            $notificacionModel->createNotificacion($id_usuario, "Asistencia a evento", "Has reservado ir al evento '{$nombre_publicacion}'");
            //obtener id del creador de la publicacion
            $sql = "SELECT id_usuario FROM ola_ke_hace.publicacion WHERE id_publicacion = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_publicacion);
            $stmt->execute();
            $result = $stmt->get_result();
            $id_creador = $result->fetch_assoc()['id_usuario'];
            $notificacionModel->createNotificacion($id_creador, "Asistencia a evento", "Un usuario ha reservado ir a tu evento $nombre_publicacion");
            $this->conn->commit();
            return true;
        } else {
            $this->conn->rollback();
            return false;
        }
        

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    
        return true;
    }

    public function deleteReservacionEvento($id_usuario, $id_publicacion) {
        try{

            $sql = "UPDATE ola_ke_hace.reservacion_evento SET activo = 0 WHERE id_usuario = ? AND id_publicacion = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_publicacion);
        $stmt->execute();
    
        if(!$stmt->affected_rows){
            return false;
        }

        $publicationModel = new AddPublicationModel(conn: $this->conn);
        $editLimitPublication = $publicationModel->editLimitPublication($id_publicacion, 'desastir');

        if ($editLimitPublication) {
            $notificacionModel = new NotificacionModel();
            //obtener nombre de la publicacion
            $sql = "SELECT nombre_publicacion FROM ola_ke_hace.publicacion WHERE id_publicacion = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_publicacion);
            $stmt->execute();
            $result = $stmt->get_result();
            $nombre_publicacion = $result->fetch_assoc()['nombre_publicacion'];
            $notificacionModel->createNotificacion($id_usuario, "Desasistencia a evento", "Has cancelado tu asistencia al evento '{$nombre_publicacion}'");
            //obtener id del creador de la publicacion
            $sql = "SELECT id_usuario FROM ola_ke_hace.publicacion WHERE id_publicacion = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_publicacion);
            $stmt->execute();
            $result = $stmt->get_result();
            $id_creador = $result->fetch_assoc()['id_usuario'];
            $notificacionModel->createNotificacion($id_creador, "Desasistencia a evento", "Un usuario ha cancelado su asistencia a tu evento $nombre_publicacion");
            $this->conn->commit();
            return true;
        } else {
            $this->conn->rollback();
            return false;
        }
    
        
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function userHasReservation($id_usuario, $id_publicacion) {
        $sql = "SELECT * FROM ola_ke_hace.reservacion_evento WHERE id_usuario = ? AND id_publicacion = ? AND activo = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_publicacion);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    public function countUserReservations($id_publicacion) {
        $sql = "SELECT limite_personas_actual FROM ola_ke_hace.publicacion WHERE id_publicacion = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_publicacion);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['limite_personas_actual'] ?? 0;
    }

    public function getReservation($id_user): mixed {
        $sql = "
            SELECT 
                p.id_publicacion,
                p.nombre_publicacion,
                p.fecha,
                p.hora,
                p.descripcion,
                p.id_categoria,
                p.id_usuario,
                u.username,
                cp.nombre_categoria
            FROM 
                ola_ke_hace.reservacion_evento re
            INNER JOIN
                ola_ke_hace.publicacion p ON re.id_publicacion = p.id_publicacion
            INNER JOIN
                ola_ke_hace.usuario u ON p.id_usuario = u.id_usuario
            INNER JOIN
                ola_ke_hace.categoria_publicacion cp ON p.id_categoria = cp.id
            WHERE 
                re.id_usuario = ? AND re.activo = 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();

        $reservaciones = [];
        while ($row = $result->fetch_assoc()) {
            $reservaciones[] = $row;
        }

        return $reservaciones;
    }

    public function isEventExpired($id_publicacion) {
        $sql = "SELECT CONCAT(fecha, ' ', hora) AS fecha_hora_evento FROM ola_ke_hace.publicacion WHERE id_publicacion = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_publicacion);
        $stmt->execute();
        $result = $stmt->get_result();
        $fecha_hora_evento = $result->fetch_assoc()['fecha_hora_evento'] ?? null;
    
        if ($fecha_hora_evento) {
            return strtotime($fecha_hora_evento) < time();
        }
        return false;
    }
    
    
    
}
