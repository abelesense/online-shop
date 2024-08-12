<?php

namespace Model;
use PDO;

class Cart extends Model {

    public function addProduct($productId, $quantity) {
        session_start();
        $userId = $_SESSION['userId'];

        $stmt = $this->pdo->prepare("SELECT count FROM user_products WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute([':userId' => $userId, ':productId' => $productId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $newQuantity = $row['count'] + $quantity;
            $stmt = $this->pdo->prepare("UPDATE user_products SET count = :quantity WHERE user_id = :userId AND product_id = :productId");
            $stmt->execute([':quantity' => $newQuantity, ':userId' => $userId, ':productId' => $productId]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, count) VALUES (:userId, :productId, :quantity)");
            $stmt->execute([':userId' => $userId, ':productId' => $productId, ':quantity' => $quantity]);
        }

    }

    public function increaseProductQuantity($productId) {
        session_start();
        $userId = $_SESSION['userId'];

        $stmt = $this->pdo->prepare("UPDATE user_products SET count = count + 1 WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute([':userId' => $userId, ':productId' => $productId]);

    }

    public function decreaseProductQuantity($productId) {
        session_start();
        $userId = $_SESSION['userId'];

        $stmt = $this->pdo->prepare("SELECT count FROM user_products WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute([':userId' => $userId, ':productId' => $productId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $row['count'] > 1) {
            $stmt = $this->pdo->prepare("UPDATE user_products SET count = count - 1 WHERE user_id = :userId AND product_id = :productId");
            $stmt->execute([':userId' => $userId, ':productId' => $productId]);
        } else {
            $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :userId AND product_id = :productId");
            $stmt->execute([':userId' => $userId, ':productId' => $productId]);
        }

    }

    public function updateCart($productId, $quantity) {
        session_start();
        $userId = $_SESSION['userId'];

        if ($quantity > 0) {
            $stmt = $this->pdo->prepare("UPDATE user_products SET count = :quantity WHERE user_id = :userId AND product_id = :productId");
            $stmt->execute([':quantity' => $quantity, ':userId' => $userId, ':productId' => $productId]);
        } else {
            $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :userId AND product_id = :productId");
            $stmt->execute([':userId' => $userId, ':productId' => $productId]);
        }

    }

    public function deleteProduct($productId) {
        session_start();
        $userId = $_SESSION['userId'];

        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute([':userId' => $userId, ':productId' => $productId]);

    }


}


