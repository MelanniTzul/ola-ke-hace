<?php
class IngresoSoliModel {
    private $conn;

    public function __construct() {
        require __DIR__.'/../../config/conexion.php';

        $this->conn = $conn; // Asegúrate de que $conn es una instancia de mysqli
    }

    public function insertServicioEdificioData( $total_servicio, $fecha_inicio, $fecha_final, $id_servicio_edificio, $id_inventario) {
        // Preparar la sentencia SQL
        $sql = "INSERT INTO solicitud_cliente_servicio 
                (id_solicitud_cliente_servicio, total_servicio,fecha_inicio,id_cliente,id_servicio_edificio,id_inventario,id_empleado,fecha_final) 
                VALUES (?,?,?,?,?,?,?,?)";

        // Preparar la sentencia SQL
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param(1,$total_servicio, $fecha_inicio, 1, $id_servicio_edificio, 1, 1,$fecha_final);

            // Ejecutar la sentencia y manejar el resultado
            if ($stmt->execute()) {
                $stmt->close(); // Cerrar la declaración
                return true; // Éxito en la inserción
            } else {
                error_log("Error en la inserción: " . $stmt->error); // Registrar el error en el log
                $stmt->close(); // Cerrar la declaración
                return false; // Error en la inserción
            }
        } else {
            error_log('Error en la preparación de la consulta: ' . $this->conn->error); // Registrar el error en el log
            return false; // Error en la preparación de la consulta
        }
    }
}
?>
