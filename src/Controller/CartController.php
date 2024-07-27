<?php
namespace Controller;

use Model\Cart;

class CartController
{
    public function getAddProductForm()
    {
        require_once '../View/add-product.php';
    }

    private $cartModel;

    public function __construct() {
        $this->cartModel = new Cart();
    }

    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            if ($this->cartModel->addProduct($productId, $quantity)) {
                // Перенаправить пользователя или вывести сообщение об успехе
                header('Location: /cart');
            } else {
                // Вывести сообщение об ошибке
                echo "Ошибка при добавлении товара в корзину.";
            }
        } else {
            // Если метод не POST, перенаправить пользователя на страницу каталога
            header('Location: /catalog');
        }
    }

}