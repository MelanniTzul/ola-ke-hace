<?php
class ReportarPublicacionModel
{
    private $conn;

    public function __construct()
    {
        require __DIR__ . '/../../config/conexion.php';
        $this->conn = $conn;
    }

    // Método para insertar el reporte
    public function insertarReporte($idPublicacion, $motivo)
    {
        $stmt = $this->conn->prepare("INSERT INTO reporte_publicacion (id_publicacion, motivo) VALUES (?, ?)");
        $stmt->bind_param("is", $idPublicacion, $motivo);
        
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error: " . $stmt->error;
            return false;
        }
    }
}

