<?php

class ProductModel{
    private $pdo;

    public function __construct(){
        $this->pdo = new PDO("pgsql:host=db;port=5432;dbname=dbname", "dbuser", "pwd");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getAllProducts(): array{
        $stmt = $this->pdo->prepare("SELECT * FROM products");
        return $stmt->fetchAll();
    }
}