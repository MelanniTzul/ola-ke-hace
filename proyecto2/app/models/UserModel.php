<?php
class UserModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getUsers()
    {
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

    public function getUserByUsername($username)
    {
        $sql = "
            SELECT 
                usuario.nombre AS nombre_usuario, 
                usuario.username, 
                usuario.correo, 
                pais.nombre AS nombre_pais, 
                rol.tipo AS nombre_rol,
                case 
                when usuario.estado = 1 then 'Activo'
                when usuario.estado = 0 then 'Baneado'
                end as estado_usuario
            FROM 
                usuario
            JOIN 
                pais ON usuario.id_pais = pais.id_pais
            JOIN 
                rol ON usuario.id_rol = rol.id_rol
            WHERE 
                usuario.username = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }



    public function validateUser($username, $password)
    {
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

    public function insertUser($nombre, $username, $email, $password, $id_pais, $id_rol, $estado)
    {
        $sql = "INSERT INTO usuario (nombre, username, pass, correo, id_pais, id_rol, estado) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }
        $stmt->bind_param("ssssiii", $nombre, $username, $password, $email, $id_pais, $id_rol, $estado);
        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }
        return true;
    }

    public function getUsersReport()
    {
        $users = [];

        // SQL Query
        $sql = "
        SELECT 
            usuario.id_usuario, 
            usuario.nombre, 
            usuario.username, 
            usuario.correo, 
            pais.nombre AS pais, 
            rol.tipo AS rol, 
            CASE 
                WHEN usuario.estado = 1 THEN 'Activo' 
                WHEN usuario.estado = 0 THEN 'Inactivo'
                ELSE 'Desconocido'
            END AS estado,
            COUNT(reporte_publicacion.id_reporte_publicacion) AS cantidad_reportes
        FROM 
            ola_ke_hace.usuario
        JOIN 
            ola_ke_hace.pais ON usuario.id_pais = pais.id_pais
        JOIN 
            ola_ke_hace.rol ON usuario.id_rol = rol.id_rol
        JOIN 
            ola_ke_hace.publicacion ON publicacion.id_usuario = usuario.id_usuario
        JOIN 
            ola_ke_hace.reporte_publicacion ON reporte_publicacion.id_publicacion = publicacion.id_publicacion
        WHERE 
            usuario.estado IN (0, 1) 
            AND usuario.id_rol = 2
        GROUP BY 
            usuario.id_usuario, 
            usuario.nombre, 
            usuario.username, 
            usuario.correo, 
            pais.nombre, 
            rol.tipo, 
            usuario.estado
        ORDER BY 
            cantidad_reportes DESC
        LIMIT 3;
    ";

        try {
            // Preparar la consulta
            if (!$stmt = $this->conn->prepare($sql)) {
                throw new Exception("Error preparando la consulta: " . $this->conn->error);
            }

            // Ejecutar
            $stmt->execute();

            // Obtener resultados
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            // Cerrar la declaración
            $stmt->close();
        } catch (Exception $e) {
            error_log($e->getMessage()); // Registrar error en logs
            return ["error" => "Error al ejecutar la consulta"]; // Respuesta controlada
        }

        return $users;
    }


    public function obtener3UsuariosMasBaneados()
    {
        $users = [];

        // SQL Query
        $sql = "
        SELECT 
        u.id_usuario,
        u.nombre, 
        u.username, 
        u.correo, 
        cbu.id_usuario, 
        COUNT(cbu.id_usuario) AS conteo
    FROM 
        conteo_baneo_usuarios cbu
    JOIN 
        ola_ke_hace.usuario u 
    ON 
        cbu.id_usuario = u.id_usuario
    GROUP BY 
        cbu.id_usuario
    ORDER BY 
        conteo DESC
    LIMIT 3;

        ";

        try {
            // Preparar la consulta
            if (!$stmt = $this->conn->prepare($sql)) {
                throw new Exception("Error preparando la consulta: " . $this->conn->error);
            }

            // Ejecutar
            $stmt->execute();

            // Obtener resultados
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            // Cerrar la declaración
            $stmt->close();
        } catch (Exception $e) {
            error_log($e->getMessage()); // Registrar error en logs
            return ["error" => "Error al ejecutar la consulta"]; // Respuesta controlada
        }

        return $users;
    }




    public function __destruct()
    {
        $this->conn->close();
    }
}
