<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../config/conexion.php';
class UserController
{
    private $userModel;
    private $jwtSecret = "tu_secreto_super_seguro";
    private $jwtAlgo = "HS256";


    public function __construct()
    {
        global $conn;
        $this->userModel = new UserModel(conn: $conn);
    }

    public function showUsers()
    {
        $users = $this->userModel->getUsers();
        extract(['users' => $users]);
        include __DIR__ . '/../views/user/userView.php';
    }


    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user = $this->userModel->validateUser($username, $password);
            if ($user) {
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['id'] = $user['id_usuario'];
                $_SESSION['rol'] = $user['id_rol'];

                header("Location: /app/views/home/home.php");
                exit();
            } else {
                header("Location: /app/views/user/login.php?error=1");
                exit();
            }
        }
    }

    public function createUser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? null;
            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = $_POST['pass'] ?? null;
            $id_pais = isset($_POST['id_pais']) ? (int) $_POST['id_pais'] : null;
            $id_rol = isset($_POST['id_rol']) ? (int) $_POST['id_rol'] : null;
            $estado = 1;


            // Debugging
            var_dump($nombre, $username, $email, $password, $id_pais, $id_rol);

            if (empty($nombre) || empty($username) || empty($email) || empty($password) || empty($id_pais) || empty($id_rol)) {
                die("Faltan datos en el formulario.");
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $isCreatedUser = $this->userModel->insertUser($nombre, $username, $email, $hashedPassword, $id_pais, $id_rol, $estado);
            if ($isCreatedUser) {
                header("Location: /app/views/home/home.php");
            } else {
                header("Location: /app/views/user/addUser.php?error=1");
            }
            exit();
        }
    }


    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /login.php");
        exit();
    }
}
