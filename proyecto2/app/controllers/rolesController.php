<?php
require_once __DIR__ . '/../models/rolesModel.php';

class RolesController {
    private $rolesModel;

    public function __construct(){
        $this->rolesModel = new RolesModel();
    }

    public function mostrarRoles(){
        return $this->rolesModel->getRoles();
    }


    public function mostrarRolAdmin(){
        return $this->rolesModel->getRolAdmin();
    }

    public function mostrarRolesJSON(){
        $roles = $this->rolesModel->getRoles();
        header('Content-Type: application/json');
        echo json_encode($roles);
    }
}
?>
