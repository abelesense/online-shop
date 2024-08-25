<?php

namespace Controller;

use Repository\OrderItemRepository;
use Repository\OrderRepository;
use Repository\ProductRepository;
use Repository\UserProductRepository;
use Request\OrderRequest;
use Request\Request;
use Service\AuthenticationInterface;
use Service\CookieAuthenticationService;

class OrderController
{
    private OrderRepository $orderRepository;
    private OrderItemRepository $orderItemRepository;
    private ProductRepository $productRepository;
    private UserProductRepository $userProductRepository;
    private AuthenticationInterface  $authenticationService;
    public function __construct(
        UserProductRepository $userProductRepository,
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        AuthenticationInterface $authenticationService
    )
    {
        $this->userProductRepository = $userProductRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->authenticationService = $authenticationService;
    }

    public function getOrder()
    {
        $user = $this->authenticationService->getUser();
        //Проверка авторизован ли пользователь
        if($this->authenticationService->check()) {
            //Получение списка продуктов из модели
            $orders = $this->orderRepository->getAllByUserId($user->getId());
            $orderIds = [];

            foreach($orders as $order) {
                $orderIds[] = $order->getId();
            }
            $orderData = [];

            foreach($orderIds as $orderId) {
                $orderData = $this->orderItemRepository->getAllByOrderId($orderId);
            }

            // Передача данных в представление
            require_once "../View/order.php";;
        } else {
            http_response_code(403);
            require_once __DIR__ . '/../View/404.php';
        }
    }

    public function getCheckOut()
    {
        if ($this->authenticationService->check()) {
            $user = $this->authenticationService->getUser();
            $userProducts = $this->uuserProductRepository->getUserProducts($user->getId());
            $productCounts = [];
            foreach ($userProducts as $userProduct) {
                $productCounts[$userProduct->getProductId()] = $userProduct->getCount();
            }

            $obj = $this->productRepository;
            $productIds = array_keys($productCounts);
            $products = $this->productRepository->getUserProducts($productIds);

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
            $products = $updatedProducts;
            require_once "../View/get_checkout.php";
        } else {
            http_response_code(403);
        }
    }

    public function registrateOrder(OrderRequest $request)
    {
        $errors = $request->validateOrderForm();
        if (empty($errors)) {
            $data = $request->getData();
            $user = $this->authenticationService->getUser();
            $street = $request->getStreet();
            $city = $request->getCity();
            $phone = $request->getPhone();
            $totalAmount = $request->getTotalPrice();

            $order = $this->orderRepository->create($city, $street, $phone, $user->getId(), $totalAmount);

            $userProducts = $this->uuserProductRepository->getUserProducts($user->getId());

            $productIds = [];
            foreach ($userProducts as $userProduct) {
                $productIds[] = $userProduct->getProductId();
            }

            $products = $this->productRepository->getUserProducts($productIds);
            foreach ($userProducts as $userProduct) {
                foreach ($products as $product) {
                    if ($userProduct->getProductId() === $product->getId()) {
                        $product->setCountInCart($userProduct->getCount());
                    }
                }
            }

            foreach ($products as $product) {
                $this->orderItemRepository->insert(
                    $order->getId(),
                    $product->getId(),
                    $product->getCount(),
                    $product->getCount() * $product->getPrice()
                );
            }

            $userProduct = $this->uuserProductRepository->delete($user->getId());
        }


        require_once __DIR__ . '/../View/get_checkout.php';

    }

}