<?php
require_once __DIR__ . '/notificacionModel.php';
class AddPublicationModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addPublication($nombre_publicacion, $estado, $descripcion, $fecha, $id_categoria, $ubicacion, $hora, $id_tipo_publico, $limite_personas, $imagen, $id_usuario, $aprobado)
    {
        try {
            // Verificar si el usuario esta baneado
            $userBaneado = $this->obtenerEstadoUsuario($id_usuario);
            if ($userBaneado == 0) {
                throw new Exception("El usuario se encuentra baneado.");
            }
            $totalReportes = $this->contarReportesUsuario($id_usuario);

            if ($totalReportes >= 1) {
                // Si nunca tuvo publicaciones automáticas, banear
                $totalAprobadas = $this->contarPublicacionesAprobadas($id_usuario);
                if ($totalAprobadas <= 2) {
                    $this->banearUsuario($id_usuario); //baneado y sin privilegios
                    throw new Exception("El usuario ha sido baneado debido a publicaciones repetidamente reportadas.");
                }

                // Si tenía publicaciones automáticas, pierde el privilegio
                $aprobado = 0; //perdio privilegios
            } else {
                // Verificar si el usuario ya tiene 2 publicaciones aprobadas
                $totalAprobadas = $this->contarPublicacionesAprobadas($id_usuario);
                var_dump($totalAprobadas);
                if ($totalAprobadas >= 2) {
                    $aprobado = 1; // Publicaciones automáticas
                }
            }

            $sql = "INSERT INTO ola_ke_hace.publicacion 
                (nombre_publicacion, estado, descripcion, fecha, id_categoria, ubicacion, hora, id_tipo_publico, limite_personas, imagen, id_usuario, aprobado, limite_personas_actual) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                file_put_contents('log.txt', "Error al preparar la consulta: " . $this->conn->error . PHP_EOL, FILE_APPEND);
                return false;
            }

            $stmt->bind_param(
                "sississiisiii",
                $nombre_publicacion,
                $estado,
                $descripcion,
                $fecha,
                $id_categoria,
                $ubicacion,
                $hora,
                $id_tipo_publico,
                $limite_personas,
                $imagen,
                $id_usuario,
                $aprobado,
                $limite_personas
            );

            if (!$stmt->execute()) {
                file_put_contents('log.txt', "Error al ejecutar la consulta: " . $stmt->error . PHP_EOL, FILE_APPEND);
                return false;
            }

            //Obtener id de los administradores
            $stmt = $this->conn->prepare("SELECT id_usuario FROM ola_ke_hace.usuario WHERE id_rol = 1");

            if (!$stmt->execute()) {
                file_put_contents('log.txt', "Error al obtener los administradores: " . $stmt->error . PHP_EOL, FILE_APPEND);
                return false;
            }

            $result = $stmt->get_result();
            $admins = $result->fetch_all(MYSQLI_ASSOC);

            //obtener nombre del usuario que creó la publicación
            $stmt = $this->conn->prepare("SELECT username FROM ola_ke_hace.usuario WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $nombre_usuario = $result->fetch_assoc()['username'];

            // Crear notificaciones para los administradores
            $notificacionModel = new NotificacionModel();
            foreach ($admins as $admin) {
                $mensaje = "El username '{$nombre_usuario}' ha creado una nueva publicación con nombre '{$nombre_publicacion}' .";
                $tipo = "Creación";
                $notificacionModel->createNotificacion($admin['id_usuario'], $tipo, $mensaje);
            }

            $this->conn->commit();

            return true;
        } catch (Exception $e) {
            // $this->conn->rollback();
            return false;
        }
    }

    public function editLimitPublication($id_publicacion, $type): bool
    {

        if ($type === 'asistir') {
            $sql = "UPDATE ola_ke_hace.publicacion SET limite_personas_actual = limite_personas_actual - 1 WHERE id_publicacion = ?";
        } else {
            $sql = "UPDATE ola_ke_hace.publicacion SET limite_personas_actual = limite_personas_actual + 1 WHERE id_publicacion = ?";
        }
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            file_put_contents('log.txt', "Error al preparar la consulta: " . $this->conn->error . PHP_EOL, FILE_APPEND);
            return false;
        }

        $stmt->bind_param("i", $id_publicacion);

        if (!$stmt->execute()) {
            file_put_contents('log.txt', "Error al ejecutar la consulta: " . $stmt->error . PHP_EOL, FILE_APPEND);
            return false;
        }

        if ($stmt->affected_rows <= 0) {
            file_put_contents('log.txt', "No se encontró un registro con ID: $id_publicacion" . PHP_EOL, FILE_APPEND);
            return false;
        }

        return true;
    }


    public function deletePublication($id_publicacion)
    {
        $sql = "UPDATE ola_ke_hace.publicacion SET estado = 0 WHERE id_publicacion = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            file_put_contents('log.txt', "Error al preparar la consulta: " . $this->conn->error . PHP_EOL, FILE_APPEND);
            return false;
        }

        $stmt->bind_param("i", $id_publicacion);

        if (!$stmt->execute()) {
            file_put_contents('log.txt', "Error al ejecutar la consulta: " . $stmt->error . PHP_EOL, FILE_APPEND);
            return false;
        }

        if ($stmt->affected_rows <= 0) {
            file_put_contents('log.txt', "No se encontró un registro con ID: $id_publicacion" . PHP_EOL, FILE_APPEND);
            return false;
        }

        //Informar al usuario que su publicacion ha sido eliminada
        $stmt = $this->conn->prepare("SELECT id_usuario FROM ola_ke_hace.publicacion WHERE id_publicacion = ?");
        $stmt->bind_param("i", $id_publicacion);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $idUsuario = $row['id_usuario'];

        //obtener nombre de la publicacion
        $stmt = $this->conn->prepare("SELECT nombre_publicacion FROM ola_ke_hace.publicacion WHERE id_publicacion = ?");
        $stmt->bind_param("i", $id_publicacion);
        $stmt->execute();
        $result = $stmt->get_result();
        $nombre_publicacion = $result->fetch_assoc()['nombre_publicacion'];

        $mensaje = "Tu publicación '{$nombre_publicacion}' ha sido eliminada.";
        $tipo = "Eliminación";

        $notificacionModel = new NotificacionModel();
        $notificacionModel->createNotificacion($idUsuario, $tipo, $mensaje);

        $this->conn->commit();

        return true;
    }

    public function obtenerEstadoUsuario($id_usuario)
    {
        $sql = "SELECT estado FROM ola_ke_hace.usuario WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            file_put_contents("Error al preparar la consulta: " . $this->conn->error . PHP_EOL, FILE_APPEND);
            return false;
        }
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $fila = $resultado->fetch_assoc()) {
            return $fila['estado']; // Devuelve directamente el campo 'estado'
        }

        return null; // Devuelve null si no se encuentra el registro
    }


    public function reportPublication($idPublicacion, $motivo, $estado, $id_usuario)
    {

        try {
            // Inserta el nuevo reporte
            $stmt = $this->conn->prepare("INSERT INTO ola_ke_hace.reporte_publicacion (id_publicacion, motivo, estado, id_usuario) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isii", $idPublicacion, $motivo, $estado, $id_usuario);


            if (!$stmt->execute()) {
                throw new Exception("Error al insertar el reporte: " . $stmt->error);
            }

            //Obtener id de todos los administradores
            $stmt = $this->conn->prepare("SELECT id_usuario FROM ola_ke_hace.usuario WHERE id_rol = 1");

            if (!$stmt->execute()) {
                throw new Exception("Error al obtener los administradores: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $admins = $result->fetch_all(MYSQLI_ASSOC);

            //obtener nombre de la publicacion
            $stmt = $this->conn->prepare("SELECT nombre_publicacion FROM ola_ke_hace.publicacion WHERE id_publicacion = ?");
            $stmt->bind_param("i", $idPublicacion);
            $stmt->execute();
            $result = $stmt->get_result();
            $nombre_publicacion = $result->fetch_assoc()['nombre_publicacion'];

            // Crear notificaciones para los administradores

            $notificacionModel = new NotificacionModel();
            foreach ($admins as $admin) {
                $mensaje = "La publicación '{$nombre_publicacion}' ha sido reportada por un usuario.";
                $tipo = "Reporte";
                $notificacionModel->createNotificacion($admin['id_usuario'], $tipo, $mensaje);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // En caso de error, revierte la transacción
            $this->conn->rollback();
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
            return false;
        }
    }

    public function editPublication($nombre_publicacion, $ubicacion, $descripcion, $fecha, $hora, $imagen, $idPublicacion)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE ola_ke_hace.publicacion SET nombre_publicacion = ?, ubicacion = ?, descripcion = ?, fecha = ?, hora = ?, imagen = ? WHERE id_publicacion = ?");
            $stmt->bind_param("ssssssi", $nombre_publicacion, $ubicacion, $descripcion, $fecha, $hora, $imagen, $idPublicacion);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    return true;
                } else {
                    throw new Exception("No se actualizó ninguna fila. Verifica el ID de la publicación.");
                }
            } else {
                throw new Exception("Error al ejecutar el UPDATE: " . $stmt->error);
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
            return false;
        }
    }

    // public function getPublicacionesSinAprobar(): array
    // {
    //     $publicaciones = [];
    //     $sql = "SELECT 
    //     id_publicacion, 
    //     nombre_publicacion, 
    //     estado, descripcion, 
    //     fecha, 
    //     id_categoria,
    //     ubicacion,
    //     hora, 
    //     id_tipo_publico,
    //     limite_personas, 
    //     imagen, id_usuario, 
    //     aprobado 
    //     FROM 
    //     ola_ke_hace.publicacion 
    //     WHERE aprobado = 0 AND estado = 1";
    //     $result = $this->conn->query($sql);
    //     if ($result->num_rows > 0) {
    //         while ($row = $result->fetch_assoc()) {
    //             $publicaciones[] = $row;
    //         }
    //     }
    //     $this->conn->close();
    //     return $publicaciones;
    // }
    public function getPublicacionesSinAprobar(): array
    {
        $publicaciones = [];
        $sql = "SELECT 
                p.id_publicacion, 
                p.nombre_publicacion, 
                p.estado, 
                p.descripcion, 
                p.fecha, 
                p.id_categoria, 
                p.ubicacion, 
                p.hora, 
                p.id_tipo_publico, 
                p.limite_personas, 
                p.imagen, 
                p.id_usuario, 
                p.aprobado,
                u.username AS nombre_usuario
            FROM 
                ola_ke_hace.publicacion p
            JOIN 
                ola_ke_hace.usuario u 
            ON 
                p.id_usuario = u.id_usuario
            WHERE 
                p.aprobado = 0 AND p.estado = 1";

        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $publicaciones[] = $row;
            }
        }
        $this->conn->close();
        return $publicaciones;
    }


    public function getPublicacionesReportadas(): array
    {
        $publicacionesReportadas = [];
        $sql = "
        SELECT 
            p.id_publicacion AS id_publicacion, 
            p.nombre_publicacion AS nombre_publicacion, 
            p.estado AS estado, 
            p.descripcion AS descripcion, 
            p.fecha AS fecha, 
            p.hora AS hora, 
            p.imagen AS imagen,
            rp.motivo AS motivo,
            ur.username AS usuario_reporta, 
            up.username AS usuario_publica
        FROM 
            ola_ke_hace.publicacion p
        INNER JOIN 
            ola_ke_hace.reporte_publicacion rp 
            ON p.id_publicacion = rp.id_publicacion
        INNER JOIN 
            ola_ke_hace.usuario ur 
            ON rp.id_usuario = ur.id_usuario
        INNER JOIN 
            ola_ke_hace.usuario up 
            ON p.id_usuario = up.id_usuario
        WHERE 
            p.estado = 1 
            AND rp.estado = 0;
    ";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $publicacionesReportadas[] = $row;
            }
        }
        $this->conn->close();
        return $publicacionesReportadas;
    }



    public function aprobarPublicacion($idPublicacion)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE ola_ke_hace.publicacion SET aprobado = 1 WHERE id_publicacion = ?");
            $stmt->bind_param("i", $idPublicacion);

            if (!$stmt->execute() || $stmt->affected_rows === 0) {
                throw new Exception("No se pudo aprobar la publicación.");
            }

            $stmt = $this->conn->prepare("SELECT id_usuario, nombre_publicacion FROM ola_ke_hace.publicacion WHERE id_publicacion = ?");
            $stmt->bind_param("i", $idPublicacion);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                throw new Exception("No se encontró la publicación.");
            }

            $publicacion = $result->fetch_assoc();
            $idUsuario = $publicacion['id_usuario'];
            $nombrePublicacion = $publicacion['nombre_publicacion'];

            // Crear la notificación para el usuario publicador
            $mensaje = "Tu publicación '{$nombrePublicacion}' ha sido aprobada.";
            $tipo = "Aprobación";

            $notificacionModel = new NotificacionModel();
            $notificacionModel->createNotificacion($idUsuario, $tipo, $mensaje);
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function autorizarReportePublicacion($idPublicacion)
    {
        $stmt = $this->conn->prepare("UPDATE ola_ke_hace.reporte_publicacion SET estado = 1 WHERE id_publicacion = ?");
        $stmt->bind_param("i", $idPublicacion);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function rechazarReportePublicacion($idPublicacion)
    {
        $stmt = $this->conn->prepare("UPDATE ola_ke_hace.reporte_publicacion SET estado = 2 WHERE id_publicacion = ?");

        $stmt->bind_param("i", $idPublicacion);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function rechazarPublicacion($idPublicacion)
    {
        try {

            $stmt = $this->conn->prepare("UPDATE ola_ke_hace.publicacion SET aprobado = 2, estado = 0 WHERE id_publicacion = ?");
            $stmt->bind_param("i", $idPublicacion);


            if (!$stmt->execute() || $stmt->affected_rows === 0) {
                throw new Exception("No se pudo aprobar la publicación.");
            }

            // Obtener el ID del usuario que creó la publicación
            $stmt = $this->conn->prepare("SELECT id_usuario, nombre_publicacion FROM ola_ke_hace.publicacion WHERE id_publicacion = ?");
            $stmt->bind_param("i", $idPublicacion);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                throw new Exception("No se encontró la publicación.");
            }

            $publicacion = $result->fetch_assoc();
            $idUsuario = $publicacion['id_usuario'];
            $nombrePublicacion = $publicacion['nombre_publicacion'];

            // Crear la notificación para el usuario publicador
            $mensaje = "Tu publicación '{$nombrePublicacion}' ha sido rechazada.";

            $tipo = "Rechazo";

            $notificacionModel = new NotificacionModel();
            $notificacionModel->createNotificacion($idUsuario, $tipo, $mensaje);
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function contarPublicacionesAprobadas($idUsuario)
    {
        $sql = "SELECT COUNT(*) as total_aprobadas 
                FROM ola_ke_hace.publicacion 
                WHERE id_usuario = ? AND aprobado = 1"; //1 = publicacion aprobada por el admin
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            file_put_contents('log.txt', "Error al preparar la consulta: " . $this->conn->error . PHP_EOL, FILE_APPEND);
            return false;
        }

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total_aprobadas'] ?? 0;
    }

    public function contarReportesUsuario($idUsuario)
    {
        $sql = "SELECT COUNT(*) as total_reportes 
                FROM ola_ke_hace.reporte_publicacion rp
                INNER JOIN ola_ke_hace.publicacion p ON rp.id_publicacion = p.id_publicacion
                WHERE p.id_usuario = ? and rp.estado = 1 
                GROUP BY p.id_publicacion"; //rp.estado = 1 = publicacion reportada aprobada por admin
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            file_put_contents('log.txt', "Error al preparar la consulta: " . $this->conn->error . PHP_EOL, FILE_APPEND);
            return false;
        }

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total_reportes'] ?? 0;
    }

    public function banearUsuario($idUsuario)
    {
        try {
            $sql = "UPDATE ola_ke_hace.usuario SET estado = 0 WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                file_put_contents('log.txt', "Error al preparar la consulta: " . $this->conn->error . PHP_EOL, FILE_APPEND);
                return false;
            }

            $stmt->bind_param("i", $idUsuario);
            if (!$stmt->execute()) {
                file_put_contents('log.txt', "Error al ejecutar la consulta: " . $stmt->error . PHP_EOL, FILE_APPEND);
                return false;
            }

            //insertar id_usuario a tabla conteo_baneo_usuarios
            $stmt = $this->conn->prepare("INSERT INTO ola_ke_hace.conteo_baneo_usuarios (id_usuario, fecha) VALUES (?, NOW())");
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();

            $mensaje = "Has sido baneado por publicaciones repetidamente reportadas.";
            $tipo = "Baneo";

            $notificacionModel = new NotificacionModel();
            $notificacionModel->createNotificacion($idUsuario, $tipo, $mensaje);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function contar3PublicacionesReportadas($filters = [])
    {
        $posts = [];
        $sql = "
    SELECT 
        publicacion.id_publicacion, 
        publicacion.nombre_publicacion, 
        CASE 
            WHEN reporte_publicacion.estado = 0 THEN 'Reporte Sin Aprobar Por Administrador'
            WHEN reporte_publicacion.estado = 1 THEN 'Reporte Aprobado'
            WHEN reporte_publicacion.estado = 2 THEN 'Reporte Rechazado'
            ELSE 'Desconocido'
        END AS estado, 
        publicacion.descripcion, 
        DATE_FORMAT(publicacion.fecha, '%d/%m/%Y') AS fecha,
        categoria_publicacion.nombre_categoria AS categoria, 
        publicacion.ubicacion, 
        publicacion.hora, 
        publicacion.limite_personas, 
        publicacion.imagen, 
        usuario.username,
        publicacion.limite_personas_actual,
        COUNT(reporte_publicacion.id_reporte_publicacion) AS cantidad_reportes
    FROM 
        ola_ke_hace.publicacion
     JOIN 
        ola_ke_hace.usuario 
        ON publicacion.id_usuario = usuario.id_usuario
     JOIN 
        ola_ke_hace.reporte_publicacion 
        ON reporte_publicacion.id_publicacion = publicacion.id_publicacion
     JOIN
        ola_ke_hace.categoria_publicacion
        ON publicacion.id_categoria = categoria_publicacion.id
    WHERE 1=1
    ";

        $params = [];
        $types = "";

        // Filtros
        if (!empty($filters['nombre'])) {
            $sql .= " AND publicacion.nombre_publicacion LIKE ?";
            $params[] = "%" . $filters['nombre'] . "%";
            $types .= "s";
        }

        if (!empty($filters['username'])) {
            $sql .= " AND usuario.username LIKE ?";
            $params[] = "%" . $filters['username'] . "%";
            $types .= "s";
        }

        if (!empty($filters['ubicacion'])) {
            $sql .= " AND publicacion.ubicacion LIKE ?";
            $params[] = "%" . $filters['ubicacion'] . "%";
            $types .= "s";
        }

        if (!empty($filters['categoria'])) {
            $sql .= " AND categoria_publicacion.id = ?";
            $params[] = $filters['categoria'];
            $types .= "i";
        }

        if (!empty($filters['fecha_inicio'])) {
            $sql .= " AND publicacion.fecha >= ?";
            $params[] = $filters['fecha_inicio'];
            $types .= "s";
        }

        if (!empty($filters['fecha_fin'])) {
            $sql .= " AND publicacion.fecha <= ?";
            $params[] = $filters['fecha_fin'];
            $types .= "s";
        }

        if (!empty($filters['estado_reporte'])) {
            $sql .= " AND reporte_publicacion.estado = ?";
            if ($filters['estado_reporte'] == 3) {
                $filters['estado_reporte'] = 0;
            }
            $params[] = $filters['estado_reporte'];
            $types .= "s";
        }

        $sql .= "
    GROUP BY 
        publicacion.id_publicacion, 
        publicacion.nombre_publicacion, 
        reporte_publicacion.estado,
        publicacion.estado,
        publicacion.descripcion, 
        publicacion.fecha, 
        publicacion.id_categoria, 
        publicacion.ubicacion, 
        publicacion.hora, 
        publicacion.limite_personas, 
        publicacion.imagen, 
        usuario.username, 
        publicacion.limite_personas_actual
    ORDER BY 
        cantidad_reportes DESC
    LIMIT 3;";

        try {
            if (!$stmt = $this->conn->prepare($sql)) {
                throw new Exception("Error preparando la consulta: " . $this->conn->error);
            }

            if (count($params) > 0) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();

            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }

            $result->free();
            $stmt->close();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ["error" => $e->getMessage()];
        }

        return $posts;
    }
}
