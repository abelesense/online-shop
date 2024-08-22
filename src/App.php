<?php

use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;

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

            $controller = new $class();
            $request = new $requestClass();

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
