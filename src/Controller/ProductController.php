<?php
namespace Controller;

use Repository\ProductRepository;
use Service\AuthenticationInterface;

class ProductController
{
    private ProductRepository $productRepository;
    private AuthenticationInterface  $authenticationService;
    public function __construct(ProductRepository $productRepository, AuthenticationInterface $authenticationService)
    {
        $this->productRepository = $productRepository;
        $this->authenticationService = $authenticationService;
    }
    //Метод для отображения каталога продуктов
    public function showCatalog(): void
    {
        $user = $this->authenticationService->getUser();
        //Проверка авторизован ли пользователь
        if($this->authenticationService->check()) {
            $products = $this->productRepository->getAllWithCount($user->getId());

            // Передача данных в представление
            require_once __DIR__ . '/../View/catalog.php';
        } else {
            http_response_code(403);
            require_once __DIR__ . '/../View/404.php';
        }
    }

}
