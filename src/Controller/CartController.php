<?php
namespace Controller;

use Repository\UserProductRepository;
use Request\Request;

class CartController{

    private UserProductRepository $userProductModel;
    // Конструктор инициализирует модели UserProductRepository и ProductRepository
    public function __construct()
    {
        $this->userProductModel = new UserProductRepository();
    }
    public function getAddProductForm()
    {
        require_once "../View/add-product.php";
    }

    public function increaseProductQuantity(Request $request)
    {
        session_start();

        $data = $request->getData();

        if (!isset($_SESSION['userId'])) {
            http_response_code(403);
            require_once '../View/404.php';
            return;
        }

        $userId = $_SESSION['userId'];
        $productId = $data['productId'];

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

    public function decreaseProductQuantity(Request $request)
    {
        session_start();
        $data = $request->getData();

        if (!isset($_SESSION['userId'])) {
            http_response_code(403);
            require_once '../View/403.php';
            return;
        }

        $userId = $_SESSION['userId'];
        $productId = $data['productId'];

        $existingProduct = $this->userProductModel->getOneByUserIdAndProductId($userId, $productId);

        if ($existingProduct) {
            $this->userProductModel->decreaseProductCount($existingProduct, $userId, $productId);
        }
        $count = $this->userProductModel->countOfUserProducts($userId, $productId);
        $result = ['count' => $count];
        echo json_encode($result);


    }

    public function updateCart(Request $request)
    {
            session_start();
            $data = $request->getData();
            if (!isset($_SESSION['userId'])) {
                http_response_code(403);
            }
            $userId = $_SESSION['userId'];
            $productId = $data['product_id'];
            $quantity = $data['quantity'];

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
