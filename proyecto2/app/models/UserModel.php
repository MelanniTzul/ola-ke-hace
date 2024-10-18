<?php
class UserModel {
    private $conn;
    
    public function __construct() {
        require __DIR__.'/../../config/conexion.php';
        $this->conn = $conn;
    }

    public function getUsers() {
        $users = [];
        $sql = "SELECT rol, user_name, id_empleado, id_cliente FROM DB_Edificio_Luna.user";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            // Recorrer las filas de resultados y almacenarlas en el array
            while($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        // Cerrar la conexión
        $this->conn->close();
        // Devolver el array de resultados
        return $users;
        
        
    }
}


