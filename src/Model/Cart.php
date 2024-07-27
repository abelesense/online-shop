<?php

namespace Model;
use PDO;

class Cart extends Model {

    public function addProduct($productId, $quantity) {
        // Логика добавления товара в корзину
        $stmt = $this->pdo->prepare("INSERT INTO user_products (product_id, count) VALUES (:productId, :quantity)");
        $stmt->execute([':productId' => $productId, ':quantity' => $quantity]);
        return $stmt;
    }
}

