<?php
// Получаем URI из запроса и метод HTTPS
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
require_once '../Controller/UserController.php';
//Обработка регистрации
if ($requestUri === '/registration'){
    if ($requestMethod === 'GET'){

        $obj = new UserController();
        $obj->getRegistration();
    } elseif ($requestMethod === 'POST'){
        $obj = new UserController();
        $obj->registrate();
    } else {
        // Если метод не поддерживается
        echo "HTTP метод $requestMethod не поддерживается";
    }
} elseif ($requestUri === '/login'){
    if ($requestMethod === 'GET'){
        $obj = new UserController();
        $obj->getLogin();
    } elseif ($requestMethod === 'POST'){
        $obj = new UserController();
        $obj->login();
    } else {
        // Если метод не поддерживается
        echo "HTTP метод $requestMethod не поддерживается";
    }
} else {
    require_once './404.php';
}