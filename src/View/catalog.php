<div class="container">
    <h3>Catalog</h3>
    <div class="card-deck">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>"/>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <form action="/add-to-cart" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <label for="quantity">Количество:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1">
                    <button type="submit">Добавить в корзину</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 90%;
        margin: auto;
        overflow: hidden;
    }

    h3 {
        text-align: center;
        color: #333;
        padding: 1rem 0;
        border-bottom: 2px solid #e1e1e1;
        margin-bottom: 2rem;
    }

    .card-deck {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 1rem;
    }

    .card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        width: 100%;
        max-width: 300px;
        margin: auto;
    }

    .card-header {
        background-color: #f8f8f8;
        padding: 1rem;
        text-align: center;
        border-bottom: 1px solid #e1e1e1;
    }

    .card-img {
        width: 100%;
        height: auto;
    }

    .card-body {
        padding: 1rem;
    }

    .card-footer {
        background-color: #f8f8f8;
        padding: 1rem;
        text-align: center;
        border-top: 1px solid #e1e1e1;
    }

    .card-footer .btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
    }

    .card-footer .btn:hover {
        background-color: #0056b3;
    }

    label {
        margin-right: 0.5rem;
    }

    input[type="number"] {
        width: 60px;
        padding: 0.25rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right: 0.5rem;
    }
</style>
