<?php
class RolesModel {
    private $conn;

    public function __construct(){
        require __DIR__.'/../../config/conexion.php';
        $this->conn = $conn;
    }

    public function getRoles(){
        $roles = [];
        $sql = "SELECT * FROM ola_ke_hace.rol";  // Asegúrate de que el nombre de la tabla sea correcto
        $resultado = $this->conn->query($sql);
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $roles[] = $fila;
            }
        }
        return $roles;
    }
}
?>
