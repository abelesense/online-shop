<?php

// Получение данных из POST - запроса
$errors = [];
if(isset($_POST["username"])){
    $email = $_POST["username"];
} else {
    $errors["username"] = "Username is required";
}
if(isset($_POST["password"])){
    $password = $_POST["password"];
} else {
    $errors["password"] = "Password is required";
}
$pdo = new PDO("pgsql:host=db;port=5432;dbname=dbname", "dbuser", "pwd");
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();
 //Если нет ошибок, выполняем подключение к БД и проверку пользователя
if (!empty($user)) {
    if (isset($user["password"])) {
        $passwordHash = $user["password"];
    } else {
        die('internal server error');
    }
    if (password_verify($password, $passwordHash)) {
        session_start();
        $_SESSION['userId']= $user['id'];
        $_SESSION['userName'] = $user['name'];

    } else {
        $errors["username"] = "Username or password is incorrect";
    }
} else {
    $errors["username"] = "Username or password is incorrect";

}
require_once __DIR__ . '/../form/get_login.php';
?>