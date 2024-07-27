<?php
namespace Model;
//require_once "../Model/Model.php";
use PDO;

class User extends Model
{

    public function insert(string $name, string $email, string $passwordHash)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $passwordHash]);
    }

    public function getUserByEmail(string $email): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function select(string $email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
    }

    public function validateEmail(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}
?>
