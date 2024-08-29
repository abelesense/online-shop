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

$container = new Container();

$container->set(CartController::class, function () {
    $userProductRepository = new UserProductRepository();
    $userRepository = new UserRepository();
    $authenticationService = new \Service\CookieAuthenticationService($userRepository);
    $controller = new CartController($userProductRepository, $authenticationService);
    return $controller;
});
$container->set(OrderController::class, function () {
    $orderRepository = new OrderRepository();
    $orderItemRepository = new OrderItemRepository();
    $productRepository = new ProductRepository();
    $userProductRepository = new UserProductRepository();
    $userRepository = new UserRepository();
    $authenticationService = new \Service\CookieAuthenticationService($userRepository);

    $controller = new \Controller\OrderController
    (
        $userProductRepository,
        $productRepository,
        $orderRepository,
        $orderItemRepository,
        $authenticationService
    );
    return $controller;
});
$container->set(ProductController::class, function () {
    $productRepository = new ProductRepository();
    $userProductRepository = new UserProductRepository();
    $userRepository = new UserRepository();
    $authenticationService = new \Service\CookieAuthenticationService($userRepository);
    $controller = new ProductController(
        $productRepository,
        $userProductRepository,
        $authenticationService
    );
    return $controller;
});
$container->set(UserController::class, function () {
    $userRepository = new UserRepository();
    $authenticationService = new \Service\CookieAuthenticationService($userRepository);
    $controller = new UserController(
        $authenticationService
    );
    return $controller;
});
$container->set(UserProductController::class, function (Container $container) {
    $userProductRepository = new UserProductRepository();
    $authenticationService = $container->get(\Service\CookieAuthenticationService::class);
    $controller = new UserProductController(
        $userProductRepository,
        $authenticationService
    );
    return $controller;
});
$container->set(\Service\CookieAuthenticationService::class, function () {
    $userRepository = new UserRepository();
    $controller = new \Service\CookieAuthenticationService(
        $userRepository
    );
    return $controller;
});
$container->set(\Service\SessionAuthenticationService::class, function () {
    $userRepository = new UserRepository();
    $controller = new \Service\CookieAuthenticationService(
        $userRepository
    );
    return $controller;
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



