<?php
namespace Controller;
//require_once __DIR__ . '/../Model/Product.php';
use Model\Product;

class ProductController
{
    private $productModel;

    public function __construct(){
        $this->productModel = new Product();
    }

    //Метод для отображения каталога продуктов
    public function showCatalog()

    {

        session_start();
        //Проверка авторизован ли пользователь
        if(isset($_SESSION['userId'])) {
            //Получение списка продуктов из модели
            $products = $this->productModel->getAllProducts();

            require_once  '../View/catalog.php';

        } else {
            http_response_code(403);
        }

    }

}
