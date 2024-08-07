<?php
namespace Controller;
//require_once __DIR__ . '/../Model/Product.php';
use Model\Product;
use Model\UserProduct;

class ProductController
{
    private $productModel;
    private $userProduct;
    public function __construct(){
        $this->productModel = new Product();
        $this->userProduct = new UserProduct();
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

            foreach ($products as &$product) {
                $userProduct = $this->userProduct->getOneByUserIdAndProductId($userId, $product['id']);
                $product['count'] = $userProduct['count'] ?? 0;
            }
            unset($product);
            // Передача данных в представление
            require_once __DIR__ . '/../View/catalog.php';
        } else {
            http_response_code(403);
            require_once __DIR__ . '/../View/403.php';
        }
    }

}
