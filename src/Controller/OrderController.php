<?php

namespace Controller;

use OrderService;
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
    private OrderService $orderService;
    private \PDO $pdo;

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
        $this->orderService = $orderService;
        $this->pdo = $pdo;
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

            $products = $this->productRepository->getAllWithCount($user->getId());
            require_once "../View/get_checkout.php";
        } else {
            http_response_code(403);
        }
    }

    public function registrateOrder(OrderRequest $request)
    {
        $errors = $request->validateOrderForm();
        if (empty($errors)) {
            $this->pdo->beginTransaction();
           try{
               $data = $request->getData();
               $user = $this->authenticationService->getUser();
               $street = $request->getStreet();
               $city = $request->getCity();
               $phone = $request->getPhone();
               $totalAmount = $request->getTotalPrice();
               $this->orderService->createOrder(
                   $user->getId(),
                   $city,
                   $street,
                   $phone,
                   $totalAmount
               );
               $this->pdo->commit();
           } catch (\Throwable $exception) {

               $this->pdo->rollBack();

           }
        }


        require_once __DIR__ . '/../View/get_checkout.php';

    }


}