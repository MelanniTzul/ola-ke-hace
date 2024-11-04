<?php
class AddPublicationModel
{

    private $conn;

    public function __construct()
    {
        require __DIR__ . '/../../config/conexion.php';
        $this->conn = $conn;
    }

    public function addPublication($data) {
        // Generar una consulta SQL estática usando los valores directamente para probar
        $query = "INSERT INTO ola_ke_hace.publicacion (nombre_publicacion, estado, descripcion, fecha, id_categoria, ubicacion, hora, id_tipo_publico, limite_personas, imagen) 
                  VALUES ('{$data['nombre_publicacion']}', {$data['estado']}, '{$data['descripcion']}', '{$data['fecha']}', {$data['id_categoria']}, '{$data['ubicacion']}', '{$data['hora']}', {$data['id_tipo_publico']}, {$data['limite_personas']}, '{$data['imagen']}')";
    
        // Ejecutar la consulta y mostrar el resultado
        if ($this->conn->query($query) === TRUE) {
            echo "Consulta ejecutada correctamente.<br>";
            return true;
        } else {
            echo "Error al ejecutar la consulta: " . $this->conn->error . "<br>";
            return false;
        }
    }
    
}
