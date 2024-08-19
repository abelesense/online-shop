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

    public function getUserOrderItems(int $orderId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM order_items WHERE order_id = :orderId");
        $stmt->execute([':orderId' => $orderId]);
        $result = $stmt->fetchAll();
        $OrderProductObjects = [];
        foreach ($result as $product) {
            $OrderProductObjects[] = new \Entity\OrderItems(
                $product['id'],
                $product['order_id'],
                $product['product_id'],
                $product['count'],
                $product['price']
            );
        }

        return $OrderProductObjects;

    }
}