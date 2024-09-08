<?php
namespace Service;

use PDO;
use Repository\OrderItemRepository;
use Repository\OrderRepository;
use Repository\ProductRepository;
use Repository\UserProductRepository;
use Service\AuthenticationInterface;
use Service\LoggerService;
use Throwable;

class OrderService
{
    private OrderRepository $orderRepository;
    private OrderItemRepository $orderItemRepository;
    private ProductRepository $productRepository;
    private UserProductRepository $userProductRepository;
    private PDO $pdo;

    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        ProductRepository $productRepository,
        UserProductRepository $userProductRepository,
        PDO $pdo
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->productRepository = $productRepository;
        $this->userProductRepository = $userProductRepository;
        $this->pdo = $pdo;
    }

    public function createOrder($userId, $city, $street, $phone, $totalAmount): \Entity\Order|null
    {
        $this->pdo->beginTransaction();
        try{
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
            $this->pdo->commit();

            return $order;
        } catch(Throwable $exception) {
            $this->pdo->rollBack();
            $loggerSrv = new LoggerService();
            $loggerSrv->error($exception);
        }
        return null;

    }
    public function getProducts(object $user): array
    {
        $orders = $this->orderRepository->getAllByUserId($user->getId());
        $orderIds = [];

        foreach($orders as $order) {
            $orderIds[] = $order->getId();
        }
        $orderData = [];

        foreach($orderIds as $orderId) {
            $orderData = $this->orderItemRepository->getAllByOrderId($orderId);
        }
        return $orderData;
    }
}