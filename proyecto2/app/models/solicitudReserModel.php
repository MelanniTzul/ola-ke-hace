<?php
class SolReservationModel {
    private $conn;

    public function __construct() {
        require __DIR__.'/../../config/conexion.php';
        $this->conn = $conn;
    }

    public function getReservationData() {
        $stmt = $this->conn->prepare("SELECT * FROM cliente WHERE id_cliente = 1");

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            return $data;
        } else {
            echo "Error: " . $stmt->error;
            return null;
        }
    }
}
