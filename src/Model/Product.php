<?php
namespace Model;
use PDO;

class Product extends Model{

    public function getAllProducts(): array {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $products = [];

        foreach ($result as $product) {
            $products[] = new \Entity\Product(
                $product['id'],
                $product['name'],
                $product['description'],
                $product['price'],
                $product['image_url']
            );
        }

        return $products;
    }


    public function GetUserProducts(array $productIds)
    {
        if (empty($productIds)) {
            return [];
        }

        // Формируем плейсхолдеры для подстановки в запрос
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        // Готовим SQL-запрос
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");

        // Выполняем запрос с массивом идентификаторов продуктов
        $stmt->execute($productIds);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}