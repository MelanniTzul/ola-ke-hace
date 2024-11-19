<?php
class AddPublicationModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addPublication($nombre_publicacion, $estado, $descripcion, $fecha, $id_categoria, $ubicacion, $hora, $id_tipo_publico, $limite_personas, $imagen, $id_usuario, $aprobado)
    {
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
                $this->banearUsuario($id_usuario);
                throw new Exception("El usuario ha sido baneado debido a publicaciones repetidamente reportadas.");
            }

            // Si tenía publicaciones automáticas, pierde el privilegio
            $aprobado = 0;
        } else {
            // Verificar si el usuario ya tiene 2 publicaciones aprobadas
            $totalAprobadas = $this->contarPublicacionesAprobadas($id_usuario);
            var_dump($totalAprobadas);
            if ($totalAprobadas >= 2) {
                $aprobado = 1; // Publicaciones automáticas
            }
        }

        $sql = "INSERT INTO ola_ke_hace.publicacion 
                (nombre_publicacion, estado, descripcion, fecha, id_categoria, ubicacion, hora, id_tipo_publico, limite_personas, imagen, id_usuario, aprobado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            file_put_contents('log.txt', "Error al preparar la consulta: " . $this->conn->error . PHP_EOL, FILE_APPEND);
            return false;
        }

        $stmt->bind_param(
            "sississiisii",
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
            $aprobado
        );

        if (!$stmt->execute()) {
            file_put_contents('log.txt', "Error al ejecutar la consulta: " . $stmt->error . PHP_EOL, FILE_APPEND);
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


    public function reportPublication($idPublicacion, $motivo, $estado)
    {

        try {
            // Inserta el nuevo reporte
            $stmt = $this->conn->prepare("INSERT INTO ola_ke_hace.reporte_publicacion (id_publicacion, motivo, estado) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $idPublicacion, $motivo, $estado);

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

    public function getPublicacionesSinAprobar(): array
    {
        $publicaciones = [];
        $sql = "SELECT id_publicacion, nombre_publicacion, estado, descripcion, fecha, id_categoria, ubicacion, hora, id_tipo_publico, limite_personas, imagen, id_usuario, aprobado 
            FROM ola_ke_hace.publicacion 
            WHERE aprobado = 0 AND estado = 1";
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
        $sql = "SELECT 
        p.id_publicacion AS id_publicacion, 
        p.nombre_publicacion AS nombre_publicacion, 
        p.estado AS estado, 
        p.descripcion AS descripcion, 
        p.fecha AS fecha, 
        p.hora AS hora, 
        p.imagen AS imagen,
        rp.motivo AS motivo
        FROM ola_ke_hace.publicacion p
        INNER JOIN ola_ke_hace.reporte_publicacion rp 
            ON p.id_publicacion = rp.id_publicacion
        WHERE p.estado = 1 AND rp.estado = 0;
        ";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $publicacionesReportadas[] = $row;
            }
        }
        $this->conn->close();
        return $publicacionesReportadas;
    }


    public function aprobarPublicacion($idPublicacion)
    {
        $stmt = $this->conn->prepare("UPDATE ola_ke_hace.publicacion SET aprobado = 1 WHERE id_publicacion = ?");
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
        $stmt = $this->conn->prepare("UPDATE ola_ke_hace.publicacion SET aprobado = 2, estado = 0 WHERE id_publicacion = ?");
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

    public function contarPublicacionesAprobadas($idUsuario)
    {
        $sql = "SELECT COUNT(*) as total_aprobadas 
                FROM ola_ke_hace.publicacion 
                WHERE id_usuario = ? AND aprobado = 1";
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
                GROUP BY p.id_publicacion";
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

        return true;
    }
}
