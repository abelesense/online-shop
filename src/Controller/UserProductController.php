<?php
namespace Controller;
use Model\UserProduct;
use Model\Product;
//require_once '../Model/Product.php';
//require_once '../Model/UserProduct.php';

class UserProductController extends UserProduct
{
    private $UserProductModel;

    public function __construct()
    {
        $this->UserProductModel = new UserProduct();
    }
    public function showCart()
    {
        session_start();
        // Проверка авторизован ли пользователь
        if (isset($_SESSION['userId'])) {
            // Получение списка продуктов из модели
            $userProducts = $this->UserProductModel->TakeUserProducts();
            $productCounts = [];
            foreach ($userProducts as $userProduct) {
                $productCounts[$userProduct['product_id']] = $userProduct['count'];
            }

            $obj = new Product();
            $productIds = array_keys($productCounts);
            $products = $obj->GetUserProducts($productIds);

            // Создаем новый массив с добавленным количеством
            $updatedProducts = [];
            foreach ($products as $product) {
                if (isset($productCounts[$product['id']])) {
                    $product['count'] = $productCounts[$product['id']];
                } else {
                    $product['count'] = 0; // или какое-то другое значение по умолчанию
                }
                $updatedProducts[] = $product;
            }

            // Передача массива $updatedProducts в шаблон
            $products = $updatedProducts; // Для совместимости с текущим шаблоном
            require_once '../View/cart.php';
        } else {
            http_response_code(403);
        }
    }

    


}