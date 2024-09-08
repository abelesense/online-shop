<?php

namespace Repository;

class OrderRepository extends Repository
{

    public function create(string $city, string $street, string $phone, int $userId, int $totalAmount): ?\Entity\Order
    {
        $stmt = $this->pdo->prepare("INSERT INTO orders (city, street, number, user_id, total_amount) VALUES (:city, :street, :phone, :userId, :totalAmount) RETURNING id");
        $stmt->execute([':city' => $city, ':street' => $street, ':phone' => $phone, ':userId' => $userId, ':totalAmount' => $totalAmount]);

        $data = $stmt->fetch();
        if(empty($data)){
            return null;
        }
        $orderId = $data['id'];
        return new \Entity\Order($orderId, $city, $street, $phone, $userId, $totalAmount);
    }
    public function getAllByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = :userId");
        $stmt->execute([':userId' => $userId]);
        $result = $stmt->fetchAll();
        $orderProductObjects = [];
        foreach ($result as $product) {
            $orderProductObjects[] = new \Entity\Order(
                $product['id'],
                $product['city'],
                $product['street'],
                $product['number'],
                $product['user_id'],
                $product['total_amount']
            );
        }

        return $orderProductObjects;
    }

}