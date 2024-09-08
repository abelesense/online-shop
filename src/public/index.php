<?php
require_once __DIR__ . '/../Autoloader/Autoloader.php';
Autoloader::autoload();

use Controller\CheckOutController;
use Controller\OrderController;
use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;
use Controller\UserProductController;
use Repository\OrderItemRepository;
use Repository\OrderRepository;
use Repository\ProductRepository;
use Repository\UserProductRepository;
use Repository\UserRepository;
use Service\CartService;
use Service\OrderService;

$container = new Container();

$container->set(CartController::class, function (Container $container) {
    $userProductRepository = new UserProductRepository();
    $authenticationService = $container->get(\Service\AuthenticationInterface::class);
    $cartService = $container->get(CartService::class);
    $controller = new CartController($userProductRepository, $authenticationService, $cartService);
    return $controller;
});
$container->set(OrderController::class, function (Container $container) {
    $productRepository = new ProductRepository();
    $authenticationService = $container->get(\Service\AuthenticationInterface::class);
    $orderService = $container->get(OrderService::class);

    $controller = new \Controller\OrderController
    (
        $productRepository,
        $authenticationService,
        $orderService
    );
    return $controller;
});
$container->set(ProductController::class, function (Container $container) {
    $productRepository = new ProductRepository();
    $authenticationService = $container->get(\Service\AuthenticationInterface::class);
    $controller = new ProductController(
        $productRepository,
        $authenticationService
    );
    return $controller;
});
$container->set(UserController::class, function (Container $container) {
    $authenticationService = $container->get(\Service\AuthenticationInterface::class);
    $controller = new UserController(
        $authenticationService
    );
    return $controller;
});
$container->set(UserProductController::class, function (Container $container) {
    $productRepository = new ProductRepository();
    $authenticationService = $container->get(\Service\AuthenticationInterface::class);
    $controller = new UserProductController(
        $authenticationService,
        $productRepository
    );
    return $controller;
});
$container->set(\Service\AuthenticationInterface::class, function () {
    $userRepository = new UserRepository();
    $controller = new \Service\CookieAuthenticationService(
        $userRepository
    );
    return $controller;
});
$container->set(OrderService::class, function(Container $container) {
    $orderRepository = new OrderRepository();
    $orderItemRepository = new OrderItemRepository();
    $userProductRepository = new UserProductRepository();
    $productRepository = new ProductRepository();
    $pdo = $container->get(PDO::class);
    $controller = new OrderService(
        $orderRepository,
        $orderItemRepository,
        $productRepository,
        $userProductRepository,
        $pdo
    );
    return $controller;
});
$container->set(PDO::class, function(){
    $dbName = getenv('DB_NAME');
    $dbUser = getenv('DB_USER');
    $dbPassword = getenv('DB_PASSWORD');
    $pdo = new PDO("pgsql:host=db;port=5432;dbname=$dbName", "$dbUser", "$dbPassword");
    return $pdo;
});
$container->set(CartService::class, function(){
    $userProductRepository = new UserProductRepository();
    return new CartService($userProductRepository);
});
$app = new \App($container);

$app->addGetRoute('/registration', UserController::class, 'getRegistration');
$app->addPostRoute('/registration', UserController::class, 'registrate', \Request\RegistrateRequest::class);
$app->addGetRoute('/login', UserController::class, 'getLogin');
$app->addPostRoute('/login', UserController::class, 'login', \Request\LoginRequest::class);
$app->addGetRoute('/my_profile', UserController::class, 'showProfile');
$app->addGetRoute('/catalog', ProductController::class, 'showCatalog');
$app->addGetRoute('/add-product', CartController::class, 'getAddProductForm');
$app->addPostRoute('/increase-product', CartController::class, 'increaseProductQuantity');
$app->addPostRoute('/decrease-product', CartController::class, 'decreaseProductQuantity');
$app->addPostRoute('/remove-product', CartController::class, 'removeProduct');
$app->addGetRoute('/cart', UserProductController::class, 'showCart');
$app->addGetRoute('/logout', UserController::class, 'logout');
$app->addPostRoute('/update-cart', CartController::class, 'updateCart');
$app->addGetRoute('/checkout', OrderController::class, 'getCheckOut');
$app->addPostRoute('/checkout', OrderController::class, 'registrateOrder', \Request\OrderRequest::class);
$app->addGetRoute('/my_orders', OrderController::class, 'getOrder');
$app->handle();
