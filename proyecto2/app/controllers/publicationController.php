<?php

require_once __DIR__ . '/../models/addPublicationModel.php';
require_once __DIR__ . '/../../config/conexion.php';

class PublicationController
{
    private $addPublicationModel;

    public function __construct()
    {
        global $conn;
        $this->addPublicationModel = new AddPublicationModel(conn: $conn);
    }

    public function getPublicacionesSinAprobar()
    {
        return $this->addPublicationModel->getPublicacionesSinAprobar();
    }

    public function getPublicacionesReportadas()
    {
        return $this->addPublicationModel->getPublicacionesReportadas();
    }

    public function contar3PublicacionesReportadas()
    {
        return $this->addPublicationModel->contar3PublicacionesReportadas();
    }

    public function addPublication($data)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido.", 405);
            }
            print_r(json_encode($data)) ;
            session_start();

            if (!isset($_SESSION['id'])) {
                throw new Exception("Usuario no autenticado.", 401);
            }

            $nombre_publicacion = $data['nombre_publicacion'] ?? null;
            $estado = $data['estado'] ?? null;
            $descripcion = $data['descripcion'] ?? null;
            $fecha = $data['fecha'] ?? date('Y-m-d');
            $id_categoria = (int)($data['id_categoria'] ?? 0);
            $ubicacion = $data['ubicacion'] ?? null;
            $hora = $data['hora'] ?? date('H:i:s');
            $id_tipo_publico = (int)($data['id_tipo_publico'] ?? 0);
            $limite_personas = (int)($data['limite_personas'] ?? 0);
            $imagen = $data['imagen'] ?? null;
            $id_usuario = $_SESSION['id'];
            $aprobado = 0;

            $isCreatedPublication = $this->addPublicationModel->addPublication(
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

            if ($isCreatedPublication) {
                header("Location: /app/views/home/home.php");
                exit();
            } else {
                // header("Location: /app/views/home/home.php");
                die("Error al crear la publicación");
                // exit();
            }
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            header("Location: /app/views/home/home.php");
            exit();
        }
    }


    public function deletePublication($data)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }

        $id_publicacion = filter_var($data['id_publicacion'] ?? null, FILTER_VALIDATE_INT);

        if (!$id_publicacion) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de publicación no válido o no proporcionado.']);
            return;
        }

        $isDeleted = $this->addPublicationModel->deletePublication($id_publicacion);

        if ($isDeleted) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Publicación eliminada correctamente.']);
            return;
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la publicación.']);
            return;
        }
    }

    public function editPublication($data)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }

        $id_publicacion = filter_var($data['id_publicacion'] ?? null, FILTER_VALIDATE_INT);

        if (!$id_publicacion) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de publicación no válido o no proporcionado.']);
            return;
        }
        $nombre_publicacion = $data['nombre_publicacion'] ?? null;
        $ubicacion = $data['ubicacion'] ?? null;
        $descripcion = $data['descripcion'] ?? null;
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $imagen = $data['imagen'] ?? null;

        $isEdited = $this->addPublicationModel->editPublication($nombre_publicacion, $ubicacion, $descripcion, $fecha, $hora, $imagen, $id_publicacion);
        if ($isEdited) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Publicación editada correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al editar la publicación.']);
        }
    }

    public function reportPublication($data)
    {

        header('Content-Type: application/json');

        if (ob_get_contents()) {
            ob_clean();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }

        $id_publicacion = filter_var($data['id_publicacion'] ?? null, FILTER_VALIDATE_INT);
        if (!$id_publicacion) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de publicación no válido o no proporcionado.']);
            return;
        }

        $motivo = $data['motivo'] ?? null;
        if (!$motivo) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El motivo del reporte es obligatorio.']);
            return;
        }

        $estado = 0;
        session_start();
        $id_usuario = $_SESSION['id'];

        try {
            $isReported = $this->addPublicationModel->reportPublication($id_publicacion, $motivo, $estado, $id_usuario);

            if ($isReported) {
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Publicación reportada correctamente.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'No se pudo reportar la publicación.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function aprobarPublicacion($data)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }

        $id_publicacion = filter_var($data['id_publicacion'], FILTER_VALIDATE_INT);

        if (!$id_publicacion) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de publicación inválido.']);
            return;
        }

        $result = $this->addPublicationModel->aprobarPublicacion($id_publicacion);

        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Publicación aprobada correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al aprobar la publicación.']);
        }
    }

    public function autorizarReportePublicacion($data)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }

        $id_publicacion = filter_var($data['id_publicacion'], FILTER_VALIDATE_INT);

        if (!$id_publicacion) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de publicación inválido.']);
            return;
        }

        $result = $this->addPublicationModel->autorizarReportePublicacion($id_publicacion);

        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Publicación aprobada correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al aprobar la publicación.']);
        }
    }

    public function rechazarPublicacion($data)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }

        $id_publicacion = filter_var($data['id_publicacion'], FILTER_VALIDATE_INT);

        if (!$id_publicacion) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de publicación inválido.']);
            return;
        }

        $result = $this->addPublicationModel->rechazarPublicacion($id_publicacion);

        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Publicación aprobada correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al aprobar la publicación.']);
        }
    }

    public function rechazarReportePublicacion($data)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }

        $id_publicacion = filter_var($data['id_publicacion'], FILTER_VALIDATE_INT);

        if (!$id_publicacion) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de publicación inválido.']);
            return;
        }

        $result = $this->addPublicationModel->rechazarReportePublicacion($id_publicacion);

        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Publicación aprobada correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al aprobar la publicación.']);
        }
    }
}

// Punto de entrada principal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    $controller = new PublicationController();

    switch ($action) {
        case 'addPublication':
            $controller->addPublication($data);
            break;
        case 'deletePublication':
            $controller->deletePublication($data);
            break;
        case 'editPublication':
            $controller->editPublication($data);
            break;
        case 'reportPublication':
            $controller->reportPublication($data);
            break;
        case 'aprobarPublicacion':
            $controller->aprobarPublicacion($data);
            break;
        case 'rechazarPublicacion':
            $controller->rechazarPublicacion($data);
            break;
        case 'autorizarReportePublicacion':
            $controller->autorizarReportePublicacion($data);
            break;
        case 'rechazarReportePublicacion':
            $controller->rechazarReportePublicacion($data);
            break;


        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
    }
}
