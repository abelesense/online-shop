<?php

use Controller\OrderController;
use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;
use Controller\UserProductController;
use Repository\OrderItemRepository;
use Repository\OrderRepository;
use Repository\ProductRepository;
use Repository\UserProductRepository;
use Service\AuthenticationInterface;

class App
{
    // Ассоциативный массив для маршрутов и методов HTTP
    private array $routes = [];

    // Метод для обработки входящих запросов
    public function handle()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $route = $this->getRoute($requestUri, $requestMethod);

        if ($route) {
            $class = $route['class'];
            $method = $route['method'];
            $requestClass = $route['request'];

            $orderRepository = new OrderRepository();
            $orderItemRepository = new OrderItemRepository();
            $productRepository = new ProductRepository();
            $userProductRepository = new UserProductRepository();
            $authenticationService = new \Service\SessionAuthenticationService();

            if ($class === CartController::class) {
                $controller = new CartController($userProductRepository, $authenticationService);
            } else if ($class === OrderController::class) {
                $controller = new \Controller\OrderController
                (
                    $userProductRepository,
                    $productRepository,
                    $orderRepository,
                    $orderItemRepository,
                    $authenticationService
                );
            } else if ($class === ProductController::class) {
                $controller = new ProductController(
                    $productRepository,
                    $userProductRepository,
                    $authenticationService
                );
            } else if ($class === UserController::class) {
                $controller = new UserController(
                    $authenticationService
                );
            } else if ($class === \Controller\UserProductController::class) {
                $controller = new UserProductController(
                    $userProductRepository,
                    $authenticationService
                );
            }

            $request = new $requestClass($requestUri, $requestMethod, $_POST);

            // Вызываем метод контроллера с объектом запроса
            $controller->$method($request);

        } else {
            http_response_code(404);
            require_once 'View/404.php';
        }
    }

    // Метод для поиска маршрута
    private function getRoute(string $uri, string $method): ?array
    {
        return $this->routes[$uri][$method] ?? null;
    }

    // Метод для добавления GET-маршрута
    public function addGetRoute(string $route, string $class, string $method, string $requestClass = \Request\Request::class)
    {
        $this->routes[$route]['GET'] = [
            'class' => $class,
            'method' => $method,
            'request' => $requestClass
        ];
    }

    // Метод для добавления POST-маршрута
    public function addPostRoute(string $route, string $class, string $method, string $requestClass = \Request\Request::class)
    {
        $this->routes[$route]['POST'] = [
            'class' => $class,
            'method' => $method,
            'request' => $requestClass
        ];
    }

}
