<?php

require_once __DIR__ .'/../models/tipoPublicoModel.php';
class TipoPublicoController{
    private $tipoPublicoModel;

    public function __construct(){
        $this->tipoPublicoModel = new TipoPublicoModel();
    }

    public function mostrarTipoPublico(){
        $publico = $this->tipoPublicoModel->getTipoPublico();
        return $publico;
    }

    public function mostrarPaisesJSON(){
        $publico = $this->tipoPublicoModel->getTipoPublico();
        header('Content-Type:application/json');
        echo json_encode($publico);
    }
}
?>