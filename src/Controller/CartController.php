<?php
namespace Controller;

use Model\UserProduct;

class CartController{

    private UserProduct $userProductModel;
    // Конструктор инициализирует модели UserProduct и Product
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
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

        if ($existingProduct) {
            $this->userProductModel->decreaseProductCount($existingProduct, $userId, $productId);
        }
        $count = $this->userProductModel->countOfUserProducts($userId, $productId);
        $result = ['count' => $count];
        echo json_encode($result);


    }

    public function updateCart()
    {
            session_start();
            if (!isset($_SESSION['userId'])) {
                http_response_code(403);
            }
            $userId = $_SESSION['userId'];
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            if ($quantity > 0) {
                if ($this->userProductModel->updateCart($userId, $productId, $quantity)) {
                    header('Location: /cart');
                } else {
                    echo "Ошибка при обновлении корзины.";
                }
            } else {
                if ($this->userProductModel->deleteProduct($userId, $productId)) {
                    header('Location: /cart');
                } else {
                    echo "Ошибка при удалении товара из корзины.";
                }
            }
    }


}
