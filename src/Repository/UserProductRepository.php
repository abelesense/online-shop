<?php
namespace Repository;
//require_once 'Repository.php';
use PDO;

class UserProductRepository extends Repository
{
    public function addProductToCart(int $userId, int $productId, int $count): bool
    {
        // Проверка наличия записи с заданными user_id и product_id
        $stmt = $this->pdo->prepare("SELECT count FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);
        $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingProduct) {
            // Если запись существует, обновляем поле count
            $newCount = $existingProduct['count'] + $count;
            $stmt = $this->pdo->prepare("UPDATE user_products SET count = :count WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->execute([':count' => $newCount, ':user_id' => $userId, ':product_id' => $productId]);
        } else {
            // Если записи нет, вставляем новую запись
            $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, count) VALUES (:user_id, :product_id, :count)");
            $stmt->execute([':user_id' => $userId, ':product_id' => $productId, ':count' => $count]);
        }

        return true;
    }

    /**
     * @param int $userId
     * @return \Entity\UserProduct[]
     */
    public function getUserProducts(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $userProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userProductObjects = [];
        foreach ($userProducts as $product) {
            $userProductObjects[] = new \Entity\UserProduct(
                $product['id'],
                $product['user_id'],
                $product['product_id'],
                $product['count']
            );
        }

        return $userProductObjects;
    }

    public function getOneByUserIdAndProductId(int $userId, int $productId): ?\Entity\UserProduct
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }

        // Создаем объект только если запрос вернул данные
        $obj = new \Entity\UserProduct($result['id'], $result['user_id'], $result['product_id'], $result['count']);

        return $obj;
    }

    public function increaseProductCount(int $userId, int $productId): bool
    {
        $stmt = $this->pdo->prepare("UPDATE user_products SET count = count + 1 WHERE user_id = :user_id AND product_id = :product_id");
        return $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    public function decreaseProductCount($existingProduct, int $userId, int $productId): bool
    {

        if ($existingProduct->getCount() > 1) {
            $stmt = $this->pdo->prepare("UPDATE user_products SET count = count - 1 WHERE user_id = :user_id AND product_id = :product_id");
            return $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        } elseif ($existingProduct->getCount() == 1) {
            $this->deleteProduct($userId, $productId);
            return true;
        }
        return false;
    }

    public function delete(int $userId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return true;
    }

    public function countOfUserProducts(int $userId, int $productId): int
    {
        $stmt = $this->pdo->prepare("SELECT count FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $result = $stmt->fetchColumn();
        if(empty($result)){
            return 0;
        }
        return $result;
    }

    public function updateCart(int $userId, int $productId, int $quantity): bool {

        if ($quantity > 0) {
            $stmt = $this->pdo->prepare("UPDATE user_products SET count = :quantity WHERE user_id = :userId AND product_id = :productId");
            $stmt->execute([':quantity' => $quantity, ':userId' => $userId, ':productId' => $productId]);
        } else {
            $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :userId AND product_id = :productId");
            $stmt->execute([':userId' => $userId, ':productId' => $productId]);
        }
        return true;

    }

    public function deleteProduct(int $userId, int $productId): bool {

        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute([':userId' => $userId, ':productId' => $productId]);
        return true;

    }


}