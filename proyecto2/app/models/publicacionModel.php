<?php
class publicacionModel
{
    private $conn;

    public function __construct()
    {
        require __DIR__ . '/../../config/conexion.php';
        $this->conn = $conn;
    }

    public function getPublicaciones($categoriaId = null, $misPublicaciones = false)
    {
        $reservation = [];
        $userRole = isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
        $userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;

        $sql = "
        SELECT 
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
            p.limite_personas_actual,
            u.nombre,
            cp.nombre_categoria,
            rp.total_reportes
        FROM 
            ola_ke_hace.publicacion p
        LEFT JOIN (
            SELECT 
                id_publicacion,
                COUNT(*) as total_reportes 
            FROM 
                ola_ke_hace.reporte_publicacion
            WHERE 
                estado = 1
            GROUP BY 
                id_publicacion
        ) rp ON p.id_publicacion = rp.id_publicacion
        LEFT JOIN
            ola_ke_hace.usuario u ON p.id_usuario = u.id_usuario
        LEFT JOIN
            ola_ke_hace.categoria_publicacion cp ON p.id_categoria = cp.id 
        WHERE 
            p.estado = 1 
            AND p.aprobado = 1 
            AND (rp.total_reportes < 3 OR rp.total_reportes IS NULL)
    ";

        if ($categoriaId) {
            $sql .= " AND p.id_categoria = ?";
        }

        if ($misPublicaciones && $userRole == 2 && $userId) {
            $sql .= " AND p.id_usuario = ?";
        }

        $stmt = $this->conn->prepare($sql);

        if ($categoriaId && $misPublicaciones && $userRole == 2 && $userId) {
            $stmt->bind_param("ii", $categoriaId, $userId);
        } elseif ($categoriaId) {
            $stmt->bind_param("i", $categoriaId);
        } elseif ($misPublicaciones && $userRole == 2 && $userId) {
            $stmt->bind_param("i", $userId);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reservation[] = $row;
            }
        }

        $stmt->close();
        $this->conn->close();
        return $reservation;
    }

    public function deletePublicacion($id)
    {
        $sql = "UPDATE ola_ke_hace.publicacion SET estado = 0 WHERE id_publicacion = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenerPersonasQueAsistiran($idPublicacion)
    {
        $personas = [];
        $sql = "
            SELECT 
                u.nombre,
                u.username,
                u.correo
            FROM 
                ola_ke_hace.usuario u
            JOIN
                ola_ke_hace.reservacion_evento re ON u.id_usuario = re.id_usuario
            WHERE 
                re.id_publicacion = ?
                AND re.activo = 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idPublicacion);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $personas[] = $row;
            }
        }

        $stmt->close();
        $this->conn->close();
        return $personas;
    }
}
