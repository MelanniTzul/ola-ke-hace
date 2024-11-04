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


    public function login(){
            if($_SERVER["REQUEST_METHOD"]== "POST"){
                $username = $_POST['username'];
                $password = $_POST['password'];
                $user = $this->userModel->validateUser($username,$password);
                if($user){
                    session_start();
                    $_SESSION['username']=$user['username'];
                    $_SESSION['rol']=$user['id_rol'];

                    header("Location: /app/views/home/home.php");
                    exit();
                }else{
                    header("Location: /login.php?error=1");
                    exit();
                }

            }
    }

    public function logout(){
        session_start();
        session_destroy();
        header("Location: /login.php");
        exit();
    }
}
