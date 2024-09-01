<?php
namespace Controller;

use Repository\ProductRepository;
use Repository\UserProductRepository;
use Service\AuthenticationInterface;
use PDO;

class UserProductController
{
    private UserProductRepository $userProductRepository;
    private AuthenticationInterface $authenticationService;
    private PDO $pdo;
    private ProductRepository $productRepository;

    public function __construct(UserProductRepository $userProductModel, AuthenticationInterface $authenticationService, PDO $pdo, ProductRepository $productRepository)
    {
        $this->userProductRepository = $userProductModel;
        $this->authenticationService = $authenticationService;
        $this->pdo = $pdo;
        $this->productRepository = $productRepository;
    }

    public function showCart()
    {
        // Проверка авторизован ли пользователь
        if ($this->authenticationService->check()) {
            $user = $this->authenticationService->getUser();
            $userId = $user->getId();

            // Получение данных о продуктах с количеством в корзине
            $products = $this->productRepository->leftJoinUserProducts($userId);

            // Передача данных в шаблон
            require_once '../View/cart.php';
        } else {
            http_response_code(403);
            echo "Доступ запрещен.";
        }
    }
}
