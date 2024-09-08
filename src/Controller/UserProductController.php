<?php
namespace Controller;

use Repository\ProductRepository;
use Service\AuthenticationInterface;

class UserProductController
{
    private AuthenticationInterface $authenticationService;
    private ProductRepository $productRepository;

    public function __construct(AuthenticationInterface $authenticationService, ProductRepository $productRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->productRepository = $productRepository;
    }

    public function showCart(): void
    {
        // Проверка авторизован ли пользователь
        if ($this->authenticationService->check()) {
            $user = $this->authenticationService->getUser();
            $userId = $user->getId();

            // Получение данных о продуктах с количеством в корзине
            $products = $this->productRepository->getAllWithCount($userId);

            // Передача данных в шаблон
            require_once '../View/cart.php';
        } else {
            http_response_code(403);
            echo "Доступ запрещен.";
        }
    }
}
