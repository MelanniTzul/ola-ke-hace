<?php

require_once __DIR__ . '/../models/categoriaPublicacionModel.php';
class CategoriaPublicacionController
{
    private $categoriaPublicacionModel;

    public function __construct()
    {
        $this->categoriaPublicacionModel = new categoriaPublicacionModel();
    }

    public function mostrarCategoria()
    {
        $publico = $this->categoriaPublicacionModel->getCategoriaP();
        return $publico;
    }

    public function mostrarCategoriaJSON()
    {
        $publico = $this->categoriaPublicacionModel->getCategoriaP();
        header('Content-Type:application/json');
        echo json_encode($publico);
    }

    public function createCategoria()
    {
        $nombre = $_POST['nombre_categoria'];
        $resultado = $this->categoriaPublicacionModel->createCategoria($nombre);
        if ($resultado) {
            $_SESSION['categoria_creada'] = true;
            header('Location: addCategoria.php');
            exit();
        }
    }
}
