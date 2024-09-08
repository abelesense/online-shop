<?php
namespace Repository;
use Entity\Product;
use PDO;

class ProductRepository extends Repository
{

    /**
     * @return \Entity\Product[]
     */
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
    public function getAllWithCount($userId): array
    {
        $stmt = $this->pdo->prepare("SELECT p.id, 
                           p.name, 
                           p.description, 
                           p.price, 
                           p.image_url,
                           up.count
                    FROM products p
                    LEFT JOIN user_products up ON p.id = up.product_id AND up.user_id = :userId");
        $stmt->execute(['userId' => $userId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $OrderProductObjects = [];
        foreach ($result as $product) {
            $OrderProductObjects[] = $this->hydrate($product);
        }
        return $OrderProductObjects;
    }
    private function hydrate(array $data): Product
    {
        if(isset($data['count'])){
            $OrderProductObject = new \Entity\Product(
                $data['id'],
                $data['name'],
                $data['description'],
                $data['price'],
                $data['image_url'],
                $data['count']
            );

        } else {
            $OrderProductObject = new \Entity\Product(
                $data['id'],
                $data['name'],
                $data['description'],
                $data['price'],
                $data['image_url']
            );
        }
        return $OrderProductObject;
    }
}