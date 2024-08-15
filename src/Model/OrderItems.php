<?php

namespace Model;

class OrderItems
{
    public function insert(int $orderId, int $productId, int $count, int $price)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (order_id, product_id, count, price) VALUES (:orderId, :productId, :count, :price)");
        $stmt->execute([':orderId' => $orderId, ':productId' => $productId, ':count' => $count, ':price' => $price]);
    }


}