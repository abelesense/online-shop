<?php

use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;
use Controller\UserProductController;

require_once './../Autoloader.php';

Autoloader::registrate();

$app = new \App();

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
<<<<<<< Updated upstream
=======
$app->addGetRoute('/checkout', CheckOutController::class, 'getCheckOut');
$app->addPostRoute('/checkout', CheckOutController::class, 'registrateOrder');
>>>>>>> Stashed changes
$app->handle();



