<?php

use Controller\CartController;
use Controller\OrderController;
use Controller\ProductController;
use Controller\UserController;
use Controller\UserProductController;
use Repository\OrderItemRepository;
use Repository\OrderRepository;
use Repository\ProductRepository;
use Repository\UserProductRepository;


class Container
{
    private array $services = [];
    public function get(string $class): object
    {
        if (isset($this->services[$class])) {
            $callback = $this->services[$class];
            $obj = $callback();
        } else {
            $obj = new $class();
        }
        return $obj;
    }

    public function set(string $class, callable $callback)
    {
        $this->services[$class] = $callback;
    }

}