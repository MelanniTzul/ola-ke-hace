<?php
class RolesModel {
    private $conn;

    public function __construct(){
        require __DIR__.'/../../config/conexion.php';
        $this->conn = $conn;
    }

    // public function getRoles(){
    //     $roles = [];
    //     $sql = "SELECT * FROM ola_ke_hace.rol";  // Asegúrate de que el nombre de la tabla sea correcto
    //     $resultado = $this->conn->query($sql);
    //     if ($resultado->num_rows > 0) {
    //         while ($fila = $resultado->fetch_assoc()) {
    //             $roles[] = $fila;
    //         }
    //     }
    //     return $roles;
    // }
    public function getRoles(){
        $roles = [];
        // Modificación en la consulta para excluir el rol con id 1 y mostrar solo los roles 2 y 3
        $sql = "SELECT * FROM ola_ke_hace.rol WHERE id_rol IN (2, 3)";
        $resultado = $this->conn->query($sql);

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $roles[] = $fila;
            }
        }

        return $roles;
    }

    public function getRolAdmin(){
        $roles = [];
        
        $sql = "SELECT * FROM ola_ke_hace.rol WHERE id_rol = 1";
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
