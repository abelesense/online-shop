<?php
namespace Controller;

use Repository\ProductRepository;
use Repository\UserProductRepository;
use Service\AuthenticationInterface;
use Service\CookieAuthenticationService;

class ProductController
{
    private ProductRepository $productRepository;
    private UserProductRepository $userProduct;
    private AuthenticationInterface  $authenticationService;
    public function __construct(ProductRepository $productRepository, UserProductRepository $userProduct, AuthenticationInterface $authenticationService)
    {
        $this->productRepository = $productRepository;
        $this->userProduct = $userProduct;
        $this->authenticationService = $authenticationService;
    }
    //Метод для отображения каталога продуктов
    public function showCatalog()
    {
        $user = $this->authenticationService->getUser();
        //Проверка авторизован ли пользователь
        if($this->authenticationService->check()) {
            //Получение списка продуктов из модели
            $products = $this->productRepository->getAllProducts();

            foreach ($products as $product) {
                $userProduct = $this->userProduct->getOneByUserIdAndProductId($user->getId(), $product->getId());
                if ($userProduct !== null) {
                    $product->setCountInCart($userProduct->getCount());
                } else {
                    $product->setCountInCart(0); // Значение по умолчанию
                }
            }
            unset($product);
            // Передача данных в представление
            require_once __DIR__ . '/../View/catalog.php';
        } else {
            http_response_code(403);
            require_once __DIR__ . '/../View/404.php';
        }
    }

}
