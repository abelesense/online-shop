<?php
namespace Repository;
use PDO;

class Repository
{
    protected PDO $pdo;

    public function __construct()
    {
        $dbName = getenv('DB_NAME');
        $dbUser = getenv('DB_USER');
        $dbPassword = getenv('DB_PASSWORD');
        $this->pdo = new PDO("pgsql:host=db;port=5432;dbname=$dbName", "$dbUser", "$dbPassword");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

}