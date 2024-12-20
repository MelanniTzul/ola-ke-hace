<?php
require_once __DIR__ . '/userController.php';

header('Content-Type: application/json'); // Asegura que el cliente reciba JSON

$controller = new UserController();
$controller->getUserProfile();
