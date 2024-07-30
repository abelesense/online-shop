<?php
namespace Controller;

use Model\Cart;
use Model\Product;
use Model\UserProduct;

class CartController{
    public function getAddProductForm()
    {
        require_once '../View/add-product.php';
    }

    private $userProduct;
    private $cartModel;

    // Конструктор инициализирует модели UserProduct и Product
    public function __construct()
    {
        $this->userProduct = new UserProduct();
        $this->cartModel = new Cart();
    }

    public function increaseProductQuantity() {
        session_start();

        if (!isset($_SESSION['userId'])) {
            http_response_code(403);
            require_once '../View/404.php';
            return;
        }

        $userId = $_SESSION['userId'];
        $productId = $_POST['productId'];

        $existingProduct = $this->userProduct->getOneByUserIdAndProductId($userId, $productId);

        if ($existingProduct) {
            $this->userProduct->increaseProductCount($userId, $productId);
        } else {
            $this->userProduct->addProductToCart($userId, $productId, 1);
        }

        $_SESSION['success'] = "Количество продукта увеличено на 1.";
        header('Location: /catalog');


    }


    public function decreaseProductQuantity() {
        session_start();

        if (!isset($_SESSION['userId'])) {
            http_response_code(403);
            require_once '../View/403.php';
            return;
        }

        $userId = $_SESSION['userId'];
        $productId = $_POST['productId'];


        $existingProduct = $this->userProduct->getOneByUserIdAndProductId($userId, $productId);

        if ($existingProduct && $existingProduct['count'] > 1) {
            $this->userProduct->decreaseProductCount($userId, $productId);
            $_SESSION['success'] = "Количество продукта уменьшено на 1.";
        } elseif ($existingProduct && $existingProduct['count'] === 1) {
            $this->userProduct->delete($userId, $productId);
            $_SESSION['success'] = "Продукт удален из корзины.";
        } else {
            $_SESSION['errors'][] = "Количество продукта не может быть меньше 0.";
        }

        header('Location: /catalog');


    }


    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            if ($quantity > 0) {
                if ($this->cartModel->updateCart($productId, $quantity)) {
                    header('Location: /cart');
                } else {
                    echo "Ошибка при обновлении корзины.";
                }
            } else {
                if ($this->cartModel->deleteProduct($productId)) {
                    header('Location: /cart');
                } else {
                    echo "Ошибка при удалении товара из корзины.";
                }
            }
        }
    }


}
