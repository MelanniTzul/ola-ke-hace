<?php
class UserModel {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUsers() {
        $users = [];
        $sql = "SELECT nombre, username, pass, correo, id_pais, id_rol FROM ola_ke_hace.usuario";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        return $users;
    }

    public function validateUser($username, $password) {
        $stm = $this->conn->prepare("SELECT * FROM usuario WHERE username = ? LIMIT 1");
        $stm->bind_param("s", $username);
        $stm->execute();
        $result = $stm->get_result();
        
        // Verificar si se encontró el usuario
        if ($result) {
            var_dump($result->num_rows); // Verificar el número de filas devueltas
        } else {
            echo "Error en la consulta: " . $this->conn->error;
        }
    
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc(); // Obtener los datos del usuario
            var_dump($user); // Verificar el contenido de $user
            /*
            if (password_verify($password, $user['pass'])) {
                echo "Contraseña verificada correctamente.";
                return $user; 
            } else {
                echo "La contraseña no coincide.";
            }
                */
                return $user;
        } else {
            echo "Usuario no encontrado.";
        }
        
        return false;
    }
    
    
    public function __destruct() {
        $this->conn->close(); 
    }
}
