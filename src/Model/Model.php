<?php
namespace Model;
use PDO;

class Model
{
    protected PDO $pdo;

    public function __construct()
    {
        $dbname = getenv("DB_NAME");
        $dbuser = getenv("DB_USER");
        $dbpwd = getenv("DB_PASSWORD");

        $this->pdo = new PDO("pgsql:host=db;port=5432;dbname=$dbname", $dbuser, $dbpwd);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

}