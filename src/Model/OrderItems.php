<?php

namespace Model;

class OrderItems extends Model
{
    public function insert(int $orderId, int $productId, int $count, float $price)
    {
        $stmt = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, count, price) VALUES (:orderId, :productId, :count, :price)");
        $stmt->execute([
            ':orderId' => $orderId,
            ':productId' => $productId,
            ':count' => $count,
            ':price' => $price,
        ]);
    }
}