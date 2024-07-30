<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog</title>
    <style>
        body {
            font-family: sans-serif;
        }

        a {
            text-decoration: none;
            margin: 0 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        h3 {
            line-height: 3em;
        }

        .card {
            max-width: 16rem;
            margin: 10px;
            display: inline-block;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
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

        .card-footer {
            font-weight: bold;
            font-size: 18px;
            background-color: white;
        }

        .card-img-top {
            max-width: 100px;
            margin: 0 auto;
            display: block;
        }

        .container {
            text-align: center;
        }

        .card-deck {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
    </style>
</head>
<body>
<a href="/my_profile">My profile</a>
<a href="/cart">Cart</a>
<a href="/add-product">Add product</a>
<a href="/logout">Logout</a>

<div class="container">
    <h3>Catalog</h3>
    <div class="card-deck">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="card text-center">
                    <a href="#">
                        <div class="card-header">
                            Hit!
                        </div>
                        <?php
                        $img_url = is_array($product['image_url']) ? $product['image_url'][0] : $product['image_url'];
                        $image_url = htmlspecialchars($img_url ?? 'default_image.jpg');
                        ?>
                        <img class="card-img-top" src="<?= $image_url; ?>" alt="Card image">
                        <div class="card-body">
                            <p class="card-text text-muted"><?= htmlspecialchars($product['description'] ?? ''); ?></p>
                            <a href="#"><h5 class="card-title"><?= htmlspecialchars($product['name'] ?? ''); ?></h5></a>
                            <div class="card-footer">
                                Цена: <?= htmlspecialchars($product['price'] ?? '0'); ?>$
                                <br>
                                <?php
                                $count = is_array($product['count']) ? $product['count']['count'] : $product['count'];
                                ?>
                                Количество: <?= htmlspecialchars((string)($count ?? '0')); ?>

                                <form action="/increase-product" method="POST" style="display: inline;">
                                    <input type="hidden" name="productId" value="<?= htmlspecialchars((string)($product['id'] ?? '')); ?>">
                                    <button type="submit">Увеличить на 1</button>
                                </form>
                                <form action="/decrease-product" method="POST" style="display: inline;">
                                    <input type="hidden" name="productId" value="<?= htmlspecialchars((string)($product['id'] ?? '')); ?>">
                                    <button type="submit">Уменьшить на 1</button>
                                </form>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>В каталоге нет товаров.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>