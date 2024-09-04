<?php

use Repository\OrderItemRepository;
use Repository\OrderRepository;
use Repository\ProductRepository;
use Repository\UserProductRepository;
use Service\AuthenticationInterface;

class OrderService
{
    private OrderRepository $orderRepository;
    private OrderItemRepository $orderItemRepository;
    private ProductRepository $productRepository;
    private UserProductRepository $userProductRepository;

    public function __construct(
        $orderRepository,
        $orderItemRepository,
        $userProductRepository,
        $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->userProductRepository = $userProductRepository;
        $this->productRepository = $productRepository;
    }

    public function createOrder($userId, $city, $street, $phone, $totalAmount)
    {
        $order = $this->orderRepository->create($city, $street, $phone, $userId, $totalAmount);

        $userProducts = $this->userProductRepository->getUserProducts($userId);

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

        $this->userProductRepository->delete($userId);

        return $order;
    }
}