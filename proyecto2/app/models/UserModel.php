<?php
class UserModel {
    private $conn;
    
    public function __construct() {
        require __DIR__.'/../../config/conexion.php';
        $this->conn = $conn;
    }

    public function getUsers() {
        $users = [];
        $sql = "SELECT nombre, username, pass, correo, id_pais, id_rol FROM ola_ke_hace.usuario";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            // Recorrer las filas de resultados y almacenarlas en el array
            while($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        // Cerrar la 0
        $this->conn->close();
        // Devolver el array de resultados
        return $users;
        
        
    }

    public function validateUser($username, $password) {
        // Preparar la consulta para buscar el usuario por nombre de usuario
        $stm = $this->conn->prepare("SELECT * FROM ola_ke_hace.usuario WHERE username = ? LIMIT 1");
        $stm->bind_param("s", $username);
        $stm->execute();
        $result = $stm->get_result();
    
        // Verificar si se encontró el usuario
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc(); // Obtener los datos del usuario
            
            // Verificar la contraseña utilizando password_verify
            if (password_verify($password, $user['pass'])) {
                return $user; // Retornar el usuario si la contraseña es correcta
            }
        }
        
        // Retornar false si no hay coincidencia en el nombre de usuario o la contraseña
        return false;
    }
    
}


