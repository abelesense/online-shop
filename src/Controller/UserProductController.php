<?php
namespace Controller;
use Repository\UserProductRepository;
use Repository\ProductRepository;

class UserProductController
{
    private UserProductRepository $userProductModel;

    public function __construct()
    {
        $this->userProductModel = new UserProductRepository();
    }
    public function showCart()
    {
        session_start();
        // Проверка авторизован ли пользователь
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            // Получение списка продуктов из модели
            $userProducts = $this->userProductModel->getUserProducts($userId);
            $productCounts = [];
            foreach ($userProducts as $userProduct) {
                $productCounts[$userProduct->getProductId()] = $userProduct->getCount();
            }

            $obj = new ProductRepository();
            $productIds = array_keys($productCounts);
            $products = $obj->getUserProducts($productIds);

            // Создаем новый массив с добавленным количеством
            $updatedProducts = [];
            foreach ($products as $product) {
                if (isset($productCounts[$product->getId()])) {
                    $product->setCountInCart($productCounts[$product->getId()]);
                } else {
                    $product->setCountInCart(0); // или какое-то другое значение по умолчанию
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