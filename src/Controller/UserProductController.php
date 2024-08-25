<?php
namespace Controller;
use Repository\UserProductRepository;
use Repository\ProductRepository;
use Service\AuthenticationInterface;
use Service\CookieAuthenticationService;

class UserProductController
{
    private UserProductRepository $userProductRepository;
    private AuthenticationInterface $authenticationService;

    public function __construct(UserProductRepository $userProductModel, AuthenticationInterface $authenticationService)
    {
        $this->userProductRepository = $userProductModel;
        $this->authenticationService = $authenticationService;
    }
    public function showCart()
    {
        // Проверка авторизован ли пользователь
        if ($this->authenticationService->check()) {
            $user = $this->authenticationService->getUser();
            // Получение списка продуктов из модели
            $userProducts = $this->userProductRepository->getUserProducts($user->getId());
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