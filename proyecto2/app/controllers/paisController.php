<?php
require_once __DIR__ .'/../models/paisModel.php';

class PaisController{
    private $paisModel;

    public function __construct(){
        $this->paisModel = new paisModel();
    }

    public function mostrarPaises(){
        $paises = $this->paisModel->getPais();
        return $paises;
    }

    public function mostrarPaisesJSON(){
        $paises = $this->paisModel->getPais();
        header('Content-Type:application/json');
        echo json_encode($paises);
    }
}
?>