<?php
namespace Repository;
//require_once "../Repository/Repository.php";
use PDO;

class UserRepository extends Repository
{

    public function insert(string $name, string $email, string $passwordHash)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $passwordHash]);
    }

    public function getUserByEmail(string $email): \Entity\User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $obj = new \Entity\User($user['id'], $user['name'], $user['password'], $user['email']);
        return $obj;
    }

    public function countWithEmail(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public function getUserById(int $userId): \Entity\User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :userId");
        $stmt->execute([':userId' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return new \Entity\User($user['id'], $user['name'], $user['password'], $user['email']);
    }
}

