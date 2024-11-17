<?php
class AddPublicationModel
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addPublication($nombre_publicacion, $estado, $descripcion, $fecha, $id_categoria, $ubicacion, $hora, $id_tipo_publico, $limite_personas, $imagen) {
        $sql = "INSERT INTO ola_ke_hace.publicacion 
                (nombre_publicacion, estado, descripcion, fecha, id_categoria, ubicacion, hora, id_tipo_publico, limite_personas, imagen) 
                VALUES (?,?,?,?,?,?,?,?,?,?)";
        
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }
    
        // Configura los tipos de datos
        $stmt->bind_param(
            "sisssisiss", 
            $nombre_publicacion,  
            $estado,              
            $descripcion,         
            $fecha,               
            $id_categoria,        
            $ubicacion,           
            $hora,               
            $id_tipo_publico,     
            $limite_personas,     
            $imagen             
        );
    
        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }
    
        return true;
    }
    
    
}
