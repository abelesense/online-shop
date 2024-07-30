<?php
use Controller\CartController;
use Controller\ProductController;
use Controller\UserController;
use Controller\UserProductController;
use Model\UserProduct;
// Получаем URI из запроса и метод HTTPS
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$func1 = function (string $className) {
    $className = str_replace('\\', '/', $className); // Замена обратных слэшей на прямые
    $path = "../{$className}.php";

    if (file_exists($path)) {
            require $path;
            return true;
        }

    return false;
};

spl_autoload_register($func1);


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
} elseif ($requestUri === '/myprofile'){
    if ($requestMethod === 'GET'){

        $obj = new UserController();
        $obj->getMyProfile();
    }
} elseif ($requestUri === '/catalog'){
    if ($requestMethod === 'GET'){
        $product = new ProductController();
        $product->showCatalog();
    } else {
        echo "HTTPS метод $requestMethod не поддерживается";
    }
} elseif ($requestUri === '/logout'){
    if ($requestMethod === 'GET'){
        $obj = new UserController();
        $obj->logout();
    } else {
        echo "HTTPS метод $requestMethod не поддерживается";
    }
} elseif ($requestUri === '/add-product') {
    if ($requestMethod === 'GET') {
        $cartController = new CartController();
        $cartController->getAddProductForm();
    } elseif ($requestMethod === 'POST') {
        $cartController = new CartController();
        $cartController->addProduct();
    } else {
        echo "HTTPS метод $requestMethod не поддерживается";
    }
} elseif ($requestUri === '/cart'){
   if ($requestMethod === 'GET'){
       $product = new UserProductController();
       $product->showCart();
    } else {
        echo "HTTPS метод $requestMethod не поддерживается";
    }
} elseif ($_SERVER['REQUEST_URI'] === '/add-to-cart' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartController = new CartController();
    $cartController->addProduct();
} elseif ($requestUri === '/increase-product' && $requestMethod === 'POST') {
    $cartController = new CartController();
    $cartController->increaseProductQuantity();
} elseif ($requestUri === '/decrease-product' && $requestMethod === 'POST') {
    $cartController = new CartController();
    $cartController->decreaseProductQuantity();
} elseif ($requestUri === '/update-cart' && $requestMethod === 'POST') {
    $cartController = new CartController();
    $cartController->updateCart();
} elseif ($requestUri === '/delete-product' && $requestMethod === 'POST') {
    $cartController = new CartController();
    $cartController->deleteProduct();
} else {
    require_once './404.php';
}


