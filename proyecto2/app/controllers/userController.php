<?php
require_once __DIR__ . '/../models/UserModel.php';


class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function showUsers() {
        $users = $this->userModel->getUsers();
        extract(['users'=>$users]); 
        include __DIR__ . '/../views/user/userView.php';
    }
}
