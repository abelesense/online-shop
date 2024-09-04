<?php
namespace Controller;

use Repository\UserProductRepository;
use Request\Request;
use Service\AuthenticationInterface;
use Service\CookieAuthenticationService;

class CartController
{

    private UserProductRepository $userProductModel;
    private AuthenticationInterface  $authenticationService;
    // Конструктор инициализирует модели UserProductRepository и ProductRepository
    public function __construct(UserProductRepository $userProductRepository, AuthenticationInterface $authenticationService)
    {
        $this->userProductModel = $userProductRepository;
        $this->authenticationService = $authenticationService;
    }
    public function getAddProductForm()
    {
        require_once "../View/add-product.php";
    }

    public function increaseProductQuantity(Request $request)
    {
        $data = $request->getData();

        if ($this->authenticationService->check()) {
            http_response_code(403);
            require_once '../View/404.php';
            return;
        }

        $user = $this->authenticationService->getUser();
        $productId = $data['productId'];

        $existingProduct = $this->userProductModel->getOneByUserIdAndProductId($user->getId(), $productId);

        if ($existingProduct) {
            $this->userProductModel->increaseProductCount($user->getId(), $productId);
        } else {
            $this->userProductModel->addProductToCart($user->getId(), $productId, 1);
        }
        $count = $this->userProductModel->countOfUserProducts($user->getId(), $productId);
        $result = ['count' => $count];

        echo json_encode($result);

    }

    public function decreaseProductQuantity(Request $request)
    {
        $data = $request->getData();

        if ($this->authenticationService->check()) {
            http_response_code(403);
            require_once '../View/403.php';
            return;
        }

        $user = $this->authenticationService->getUser();
        $productId = $data['productId'];

        $existingProduct = $this->userProductModel->getOneByUserIdAndProductId($user->getId(), $productId);

        if ($existingProduct) {
            $this->userProductModel->decreaseProductCount($existingProduct, $user->getId(), $productId);
        }
        $count = $this->userProductModel->countOfUserProducts($user->getId(), $productId);
        $result = ['count' => $count];
        echo json_encode($result);


    }

    public function updateCart(Request $request)
    {
            $data = $request->getData();
            if ($this->authenticationService->check()) {
                http_response_code(403);
            }
            $user = $this->authenticationService->getUser();
            $productId = $data['product_id'];
            $quantity = $data['quantity'];

            if ($quantity > 0) {
                if ($this->userProductModel->updateCart($user->getId(), $productId, $quantity)) {
                    header('Location: /cart');
                } else {
                    echo "Ошибка при обновлении корзины.";
                }
            } else {
                if ($this->userProductModel->deleteProduct($user->getId(), $productId)) {
                    header('Location: /cart');
                } else {
                    echo "Ошибка при удалении товара из корзины.";
                }
            }
    }


}
