<?php

namespace Model;

class Order extends Model
{

    public function insert(string $city, string $street, string $phone, int $totalAmount, int $userId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (city, street, number, user_id, total_amount) VALUES (:city, :street, :phone, :userId, :totalAmount)");
        $stmt->execute([':city' => $city, ':street' => $street, ':number' => $phone, ':userId' => $userId, ':totalAmount' => $totalAmount]);
    }

}