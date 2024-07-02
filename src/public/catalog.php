<?php
function getDatabaseConnection() {
    try {
        $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbname", "dbuser", "pwd");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die ("Error" . $e->getMessage());
    }
}

//Подключение к базе данных
$pdo = getDatabaseConnection();

//Извлечение данных о продуктах
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
?>

<div class="container">
    <h3>Catalog</h3>
    <div class="card-deck">
        <?php foreach ($products as $product) : ?>
        <div class="card text-center">
            <a href="#">
                <div class="card-header">
                    Hit!
                </div>
                <img class="card-img-top" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Card image">
                <div class="card-body">
                    <p class="card-text text-muted"><?php echo htmlspecialchars($product["description"]); ?></p>
                    <a href="#"><h5 class="card-title"><?php echo htmlspecialchars($product["name"]); ?></h5></a>
                    <div class="card-footer">
                        <?php echo htmlspecialchars($product["price"]); ?>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    body {
        font-style: sans-serif;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: none;
    }

    h3 {
        line-height: 3em;
    }

    .card {
        max-width: 16rem;
    }

    .card:hover {
        box-shadow: 1px 2px 10px lightgray;
        transition: 0.2s;
    }

    .card-header {
        font-size: 13px;
        color: gray;
        background-color: white;
    }

    .text-muted {
        font-size: 11px;
    }

    .card-footer{
        font-weight: bold;
        font-size: 18px;
        background-color: white;
    }
</style>
