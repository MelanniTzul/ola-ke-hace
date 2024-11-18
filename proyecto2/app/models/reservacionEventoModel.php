<?php
class ReservacionEventoModel
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addReservacionEvento($activo, $id_usuario,$id_publicacion) {
        $sql = "INSERT INTO ola_ke_hace.reservacion_evento 
                (activo, id_usuario, id_publicacion) 
                VALUES (?,?,?)";
        
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }
    
        // Configura los tipos de datos
        $stmt->bind_param(
            "iii", 
            $activo,  
            $id_usuario,              
            $id_publicacion
        );
    
        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }
    
        return true;
    }
    
    
}
