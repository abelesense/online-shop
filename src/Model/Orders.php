<?php

namespace Model;

class Orders extends Model
{

    public function insert(string $city, string $street, string $phone, int $userId, int $totalAmount)
    {
        $stmt = $this->pdo->prepare("INSERT INTO orders (city, street, number, user_id, total_amount) VALUES (:city, :street, :phone, :userId, :totalAmount)");
        $stmt->execute([':city' => $city, ':street' => $street, ':phone' => $phone, ':userId' => $userId, ':totalAmount' => $totalAmount]);
    }

    public function selectUserOrder(int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = :userId");
        $stmt->execute([':userId' => $userId]);
        $result = $stmt->fetchAll();
        $OrderProductObjects = [];
        foreach ($result as $product) {
            $OrderProductObjects[] = new \Entity\Orders(
                $product['id'],
                $product['city'],
                $product['street'],
                $product['number'],
                $product['user_id'],
                $product['total_amount']
            );
        }

        return $OrderProductObjects;
    }

}