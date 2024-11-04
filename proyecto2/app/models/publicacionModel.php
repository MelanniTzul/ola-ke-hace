<?php
class publicacionModel {
    private $conn;
    
    public function __construct() {
        require __DIR__.'/../../config/conexion.php';
        $this->conn = $conn;
    }

    // public function getPublicaciones() {
    //     //*Guardar en un arreglo los departamentoss   
    public function getPublicaciones() {
        $reservation = [];
        $sql = "SELECT id_publicacion, nombre_publicacion, estado, descripcion, fecha, id_categoria, ubicacion, hora, id_tipo_publico, limite_personas, imagen 
                FROM ola_ke_hace.publicacion 
                WHERE estado = 1"; 
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $reservation[] = $row;
            }
        }
        $this->conn->close();
        return $reservation;
    }
    

      public function deletePublicacion($id) {
        $sql = "UPDATE ola_ke_hace.publicacion SET estado = 0 WHERE id_publicacion = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    
}
