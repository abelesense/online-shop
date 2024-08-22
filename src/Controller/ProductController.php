<?php
namespace Controller;

use Repository\ProductRepository;
use Repository\UserProductRepository;
use Service\AuthenticationService;

class ProductController
{
    private ProductRepository $productModel;
    private UserProductRepository $userProduct;

    private AuthenticationService  $authenticationService;

    public function __construct()
    {
        $this->productModel = new ProductRepository();
        $this->userProduct = new UserProductRepository();
        $this->authenticationService = new AuthenticationService();
    }

    //Метод для отображения каталога продуктов
    public function showCatalog()
    {

        session_start();
        $user = $this->authenticationService->getUser();
        //Проверка авторизован ли пользователь
        if($this->authenticationService->check()) {
            //Получение списка продуктов из модели
            $products = $this->productModel->getAllProducts();

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
