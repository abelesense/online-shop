<?php
namespace Repository;
use PDO;

class ProductRepository extends Repository
{

    /**
     * @return \Entity\Product[]
     */
    public function getAllProducts(): array
    {
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


    public function getUserProducts(array $productIds): array
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

    public function getPrice(int $productId): int
    {
        $stmt = $this->pdo->prepare("SELECT price FROM products WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['price'];
    }
}