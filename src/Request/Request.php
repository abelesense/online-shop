<?php

namespace Request;

class Request
{
    private string $route;
    private string $method;
    private array $data;
    public function __construct(string $route, string $method, array $data = [])
    {
        $this->route = $route;
        $this->method = $method;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

}