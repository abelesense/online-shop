<?php

namespace Controller;

use Repository\ProductRepository;
use Request\OrderRequest;
use Service\AuthenticationInterface;
use Service\OrderService;

class OrderController
{
    private ProductRepository $productRepository;
    private AuthenticationInterface  $authenticationService;
    private OrderService $orderService;

    public function __construct(
        ProductRepository $productRepository,
        AuthenticationInterface $authenticationService,
        OrderService $orderService
    )
    {
        $this->productRepository = $productRepository;
        $this->authenticationService = $authenticationService;
        $this->orderService = $orderService;
    }

    public function getOrder(): void
    {
        $user = $this->authenticationService->getUser();
        //Проверка авторизован ли пользователь
        if($this->authenticationService->check()) {
            //Получение списка продуктов из модели
            $orderData = $this->orderService->getProducts($user);

            // Передача данных в представление
            require_once "../View/order.php";;
        } else {
            http_response_code(403);
            require_once __DIR__ . '/../View/404.php';
        }
    }

    public function getCheckOut(): void
    {
        if ($this->authenticationService->check()) {
            $user = $this->authenticationService->getUser();

            $products = $this->productRepository->getAllWithCount($user->getId());
            require_once "../View/get_checkout.php";
        } else {
            http_response_code(403);
        }
    }

    public function registrateOrder(OrderRequest $request): void
    {
        $errors = $request->validateOrderForm();
        if (empty($errors)) {
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

        }


        require_once __DIR__ . '/../View/get_checkout.php';

    }


}