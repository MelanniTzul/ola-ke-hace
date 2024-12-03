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
        $sql = "
            SELECT 
                    p.id_publicacion,
                    p.nombre_publicacion, 
                    p.estado,
                    p.descripcion, 
                    p.fecha,
                    p.id_categoria, 
                    p.ubicacion, 
                    p.hora, 
                    p.id_tipo_publico,
                    p.limite_personas, 
                    p.imagen, 
                    p.id_usuario,
                    u.nombre,
                    rp.total_reportes
            FROM 
                ola_ke_hace.publicacion p
            LEFT JOIN (
                SELECT 
                    id_publicacion,
                    COUNT(*) as total_reportes 
                FROM 
                    ola_ke_hace.reporte_publicacion
                WHERE 
                    estado = 1
                GROUP BY 
                    id_publicacion
            ) rp ON p.id_publicacion = rp.id_publicacion
            LEFT JOIN
                ola_ke_hace.usuario u ON p.id_usuario = u.id_usuario 
            WHERE 
                p.estado = 1 
              AND p.aprobado = 1 
              AND (rp.total_reportes < 3 OR rp.total_reportes IS NULL); 
        ";
    
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
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
