<?php
namespace Repository;
use Entity\Product;
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
            $products[] = $this->hydrate($product);
        }

        return $products;
    }

    public function getAllCatalogWithCount($userId): array
    {
        $stmt = $this->pdo->prepare("SELECT p.*, COALESCE(up.count, 0)
                    FROM products p
                    LEFT JOIN user_products up ON p.id = up.product_id AND up.user_id = :user_id
            ");
        $stmt->execute(['userId' => $userId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $OrderProductObjects = [];
        foreach ($result as $product) {
            $OrderProductObjects[] = $this->hydrate($product);
        }
        return $OrderProductObjects;
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
            $products[] = $this->hydrate($product);
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

    public function getAllWithCount($userId): array
    {
        $stmt = $this->pdo->prepare("SELECT p.id, 
                           p.name, 
                           p.description, 
                           p.price, 
                           p.image_url,
                           COALESCE(up.count, 0)
                    FROM products p
                    INNER JOIN user_products up ON p.id = up.product_id AND up.user_id = :userId");
        $stmt->execute(['userId' => $userId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $OrderProductObjects = [];
        foreach ($result as $product) {
            $OrderProductObjects[] = $this->hydrate($product);
        }
        return $OrderProductObjects;
    }
    public function hydrate(array $data): Product
    {
        $OrderProductObject = new \Entity\Product(
            $data['id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image_url'],
            $data['count']
        );
        return $OrderProductObject;
    }
}