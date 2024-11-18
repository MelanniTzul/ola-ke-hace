<?php
class AddPublicationModel
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addPublication($nombre_publicacion, $estado, $descripcion, $fecha, $id_categoria, $ubicacion, $hora, $id_tipo_publico, $limite_personas, $imagen)
    {
        $sql = "INSERT INTO ola_ke_hace.publicacion 
                (nombre_publicacion, estado, descripcion, fecha, id_categoria, ubicacion, hora, id_tipo_publico, limite_personas, imagen) 
                VALUES (?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }

        // Configura los tipos de datos
        $stmt->bind_param(
            "sisssisiss",
            $nombre_publicacion,
            $estado,
            $descripcion,
            $fecha,
            $id_categoria,
            $ubicacion,
            $hora,
            $id_tipo_publico,
            $limite_personas,
            $imagen
        );

        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }

        return true;
    }


    // public function deletePublication($id_publicacion)
    // {
    //     file_put_contents('log.txt', "ID recibido en el modelo: $id_publicacion" . PHP_EOL, FILE_APPEND);

    //     $sql = "UPDATE ola_ke_hace.publicacion SET estado = 0 WHERE id_publicacion = ?";
    //     $stmt = $this->conn->prepare($sql);
    
    //     if (!$stmt) {
    //         die(json_encode(['success' => false, 'message' => "Error al preparar la consulta: " . $this->conn->error]));
    //     }
    
    //     $stmt->bind_param("i", $id_publicacion);
    
    //     if (!$stmt->execute()) {
    //         die(json_encode(['success' => false, 'message' => "Error al ejecutar la consulta: " . $stmt->error]));
    //     }
    
    //     return true;
    // }
    public function deletePublication($id_publicacion)
{
    file_put_contents('log.txt', "ID recibido en el modelo: $id_publicacion" . PHP_EOL, FILE_APPEND);

    $sql = "UPDATE ola_ke_hace.publicacion SET estado = 0 WHERE id_publicacion = 29";
    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        file_put_contents('log.txt', "Error al preparar la consulta: " . $this->conn->error . PHP_EOL, FILE_APPEND);
        die(json_encode(['success' => false, 'message' => "Error al preparar la consulta."]));
    }

    $stmt->bind_param("i", $id_publicacion);

    if (!$stmt->execute()) {
        file_put_contents('log.txt', "Error al ejecutar la consulta: " . $stmt->error . PHP_EOL, FILE_APPEND);
        die(json_encode(['success' => false, 'message' => "Error al ejecutar la consulta."]));
    }

    if ($stmt->affected_rows <= 0) {
        file_put_contents('log.txt', "Consulta ejecutada pero no afectó filas. ID: $id_publicacion" . PHP_EOL, FILE_APPEND);
        echo json_encode(['success' => false, 'message' => "No se encontró un registro para actualizar."]);
        return false;
    }

    file_put_contents('log.txt', "Registro actualizado correctamente. ID: $id_publicacion" . PHP_EOL, FILE_APPEND);
    return true;
}

    
}
