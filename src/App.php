<?php

use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;

class App
{
    // Ассоциативный массив для маршрутов и методов HTTP
    private $routes = [];

    // Метод для обработки входящих запросов
    public function handle()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $route = $this->getRoute($requestUri, $requestMethod);

        if ($route) {
            $class = $route['class'];
            $method = $route['method'];

            $controller = new $class();
            $request = new \Request\Request($requestUri, $requestMethod, $_POST);
            $controller->$method($request);
        } else {
            http_response_code(404);
            require_once 'View/404.php';
        }
    }
    private function getRoute(string $uri, string $method): ?array
    {
        return $this->routes[$uri][$method] ?? null;
    }
    // Метод для добавления маршрута
    public function addGetRoute(string $route, string $class, string $method)
    {
        $this->routes[$route]['GET'] = [
            'class' => $class,
            'method' => $method
        ];
    }

    public function addPostRoute(string $route, string $class, string $method)
    {
        $this->routes[$route]['POST'] = [
            'class' => $class,
            'method' => $method
        ];
    }

}