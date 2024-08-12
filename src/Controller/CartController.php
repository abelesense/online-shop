<?php
namespace Controller;

use Model\Cart;
use Model\UserProduct;

class CartController{

    private UserProduct $userProductModel;
    private Cart $cartModel;

    // Конструктор инициализирует модели UserProduct и Product
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->cartModel = new Cart();
    }
    public function getAddProductForm()
    {
        require_once "../View/add-product.php";
    }

    public function increaseProductQuantity()
    {
        session_start();

        if (!isset($_SESSION['userId'])) {
            http_response_code(403);
            require_once '../View/404.php';
            return;
        }

        $userId = $_SESSION['userId'];
        $productId = $_POST['productId'];

        $existingProduct = $this->userProductModel->getOneByUserIdAndProductId($userId, $productId);

        if ($existingProduct) {
            $this->userProductModel->increaseProductCount($userId, $productId);
        } else {
            $this->userProductModel->addProductToCart($userId, $productId, 1);
        }
        $count = $this->userProductModel->countOfUserProducts($userId, $productId);
        $result = ['count' => $count];

        echo json_encode($result);

    }

    public function decreaseProductQuantity()
    {
        session_start();

        if (!isset($_SESSION['userId'])) {
            http_response_code(403);
            require_once '../View/403.php';
            return;
        }

        $userId = $_SESSION['userId'];
        $productId = $_POST['productId'];


        $existingProduct = $this->userProductModel->getOneByUserIdAndProductId($userId, $productId);

        if ($existingProduct && $existingProduct->getCount() > 1) {
            $this->userProductModel->decreaseProductCount($userId, $productId);
            $_SESSION['success'] = "Количество продукта уменьшено на 1.";
        } elseif ($existingProduct && $existingProduct->getCount() === 1) {
            $this->userProductModel->delete($userId, $productId);
            $_SESSION['success'] = "Продукт удален из корзины.";
        } else {
            $_SESSION['errors'][] = "Количество продукта не может быть меньше 0.";
        }

        $count = $this->userProductModel->countOfUserProducts($userId, $productId);
        $result = ['count' => $count];
        echo json_encode($result);


    }

    public function updateCart()
    {
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
