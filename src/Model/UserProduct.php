<?php
namespace Model;
//require_once 'Model.php';
use PDO;

class UserProduct extends Model
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

    public function TakeUserProducts(): array{
        $userId = $_SESSION['userId'];
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }




}