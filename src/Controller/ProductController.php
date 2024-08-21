<?php
namespace Controller;

use Repository\ProductRepository;
use Repository\UserProductRepository;

class ProductController
{
    private ProductRepository $productModel;
    private UserProductRepository $userProduct;

    public function __construct()
    {
        $this->productModel = new ProductRepository();
        $this->userProduct = new UserProductRepository();
    }

    //Метод для отображения каталога продуктов
    public function showCatalog()
    {

        session_start();
        $userId = $_SESSION['userId'];
        //Проверка авторизован ли пользователь
        if(isset($_SESSION['userId'])) {
            //Получение списка продуктов из модели
            $products = $this->productModel->getAllProducts();

            foreach ($products as $product) {
                $userProduct = $this->userProduct->getOneByUserIdAndProductId($userId, $product->getId());
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
