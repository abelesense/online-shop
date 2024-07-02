<?php
class User
{

    public function insert(string $name, string $email, string $password)
    {
        $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbname", "dbuser", "pwd");
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $passwordHash]);
    }

    public function getUserByEmail(string $email): array
    {
        $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbname", "dbuser", "pwd");
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return $user;
    }

    public function select(string $email)
    {
        $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbname", "dbuser", "pwd");
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
    }
    public function validateEmail(string $email)
    {
        $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbname", "dbuser", "pwd");
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $count = $stmt->fetchColumn();
        return $count;
    }
}