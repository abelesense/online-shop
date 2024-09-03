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
    private $pdo;

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
           try{
               $this->pdo->beginTransaction();
               $data = $request->getData();
               $user = $this->authenticationService->getUser();
               $street = $request->getStreet();
               $city = $request->getCity();
               $phone = $request->getPhone();
               $totalAmount = $request->getTotalPrice();

               $order = $this->orderRepository->create($city, $street, $phone, $user->getId(), $totalAmount);

               $userProducts = $this->userProductRepository->getUserProducts($user->getId());

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

               $userProduct = $this->userProductRepository->delete($user->getId());
               $this->pdo->commit();
           } catch (\Throwable $exception) {

               $this->pdo->rollBack();

           }
        }


        require_once __DIR__ . '/../View/get_checkout.php';

    }


}