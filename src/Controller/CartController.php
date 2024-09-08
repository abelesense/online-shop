<?php
namespace Controller;

use Repository\UserProductRepository;
use Request\Request;
use Service\AuthenticationInterface;
use Service\CartService;

class CartController
{
    private UserProductRepository $userProductRepository;
    private AuthenticationInterface  $authenticationService;
    private CartService $cartService;

    public function __construct(UserProductRepository $userProductRepository, AuthenticationInterface $authenticationService, CartService $cartService)
    {
        $this->userProductRepository = $userProductRepository;
        $this->authenticationService = $authenticationService;
        $this->cartService = $cartService;
    }
    public function getAddProductForm(): void
    {
        require_once "../View/add-product.php";
    }

    public function increaseProductQuantity(Request $request): void
    {
        $data = $request->getData();

        if (!$this->authenticationService->check()) {
            http_response_code(403);
            require_once '../View/404.php';
            return;
        }

        $user = $this->authenticationService->getUser();
        $productId = $data['productId'];
        $result = $this->cartService->increaseProduct($user, $productId);

        echo json_encode($result);
    }

    public function decreaseProductQuantity(Request $request): void
    {
        $data = $request->getData();

        if (!$this->authenticationService->check()) {
            http_response_code(403);
            require_once '../View/403.php';
            return;
        }

        $user = $this->authenticationService->getUser();
        $productId = $data['productId'];
        $result = $this->cartService->decreaseProduct($user, $productId);

        echo json_encode($result);
    }

    public function updateCart(Request $request): void
    {
            $data = $request->getData();
            if (!$this->authenticationService->check()) {
                http_response_code(403);
            }
            $user = $this->authenticationService->getUser();
            $productId = $data['product_id'];
            $quantity = $data['quantity'];

            if ($quantity > 0) {
                if ($this->userProductRepository->updateCart($user->getId(), $productId, $quantity)) {
                    header('Location: /cart');
                } else {
                    echo "Ошибка при обновлении корзины.";
                }
            } else {
                if ($this->userProductRepository->deleteProduct($user->getId(), $productId)) {
                    header('Location: /cart');
                } else {
                    echo "Ошибка при удалении товара из корзины.";
                }
            }
    }
}
