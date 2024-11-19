<?php
session_start();

$_SESSION = [];

session_destroy();

header("Location: /app/views/user/login.php");
exit();
