<?php
class publicacionModel {
    private $conn;
    
    public function __construct() {
        require __DIR__.'/../../config/conexion.php';
        $this->conn = $conn;
    }

    public function getReservation() {
        //*Guardar en un arreglo los departamentoss
        $reservation = [];
        $sql = "SELECT id_publicacion, nombre_publicacion, estado, descripcion,fecha, id_categoria, ubicacion, hora, id_tipo_publico,limite_personas,imagen FROM ola_ke_hace.publicacion";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            // Recorrer las filas de resultados y almacenarlas en el array
            while($row = $result->fetch_assoc()) {
                $reservation[] = $row;
            }
        }
        // Cerrar la conexión
        $this->conn->close();
        // Devolver el array de resultados
        return $reservation;
        
        
    }

    // public function updatePublicacion($id_publicacion, $nombre_publicacion, $estado, $descripcion, $fecha, $id_categoria, $ubicacion, $hora, $id_tipo_publico, $limite_personas, $imagen) {
    //     // Preparar la consulta SQL para actualizar la publicación
    //     $sql = "UPDATE ola_ke_hace.publicacion 
    //             SET nombre_publicacion = ?, estado = ?, descripcion = ?, fecha = ?, id_categoria = ?, ubicacion = ?, hora = ?, id_tipo_publico = ?, limite_personas = ?, imagen = ?
    //             WHERE id_publicacion = ?";
    
    //     // Preparar la consulta
    //     if ($stmt = $this->conn->prepare($sql)) {
    //         // Enlazar los parámetros a la consulta
    //         $stmt->bind_param('ssssisssisi', $nombre_publicacion, $estado, $descripcion, $fecha, $id_categoria, $ubicacion, $hora, $id_tipo_publico, $limite_personas, $imagen, $id_publicacion);
    
    //         // Ejecutar la consulta
    //         if ($stmt->execute()) {
    //             // Si la ejecución es exitosa, devolver verdadero
    //             return true;
    //         } else {
    //             // Si falla, devolver falso
    //             return false;
    //         }
            
    //         // Cerrar el statement
    //         $stmt->close();
    //     } else {
    //         // Si no se pudo preparar la consulta, devolver falso
    //         return false;
    //     }
    // }
    

    // public function getTipoPublico() {
    //     //*Guardar en un arreglo los departamentoss
    //     $tipo = [];
    //     $sql = "SELECT idpublico, tipo_publico FROM ola_ke_hace.tipo_publico";
    //     $result = $this->conn->query($sql);
    //     if ($result->num_rows > 0) {
    //         // Recorrer las filas de resultados y almacenarlas en el array
    //         while($row = $result->fetch_assoc()) {
    //             $tip[] = $row;
    //         }
    //     }
    //     // Cerrar la conexión
    //     $this->conn->close();
    //     // Devolver el array de resultados
    //     return $tipo;
        
        
    // }
}
