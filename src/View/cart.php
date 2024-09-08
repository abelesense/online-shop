<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<div class="container">
    <h3>Cart</h3>
    <div class="card-deck">
        <?php if (!empty($products)): ?>
            <?php /** @var $product \Entity\Product */ ?>
            <?php foreach ($products as $product): ?>
                <?php if ($product->getCount() > 0) : ?>
                    <div class="card text-center" id="product-card-<?php echo $product->getId(); ?>">
                        <a href="#">
                            <div class="card-header">Hit!</div>
                            <img class="card-img-top" src="<?php echo $product->getImage(); ?>" alt="Card image">
                            <div class="card-body">
                                <p class="card-text text-muted"><?php echo $product->getDescription(); ?></p>
                                <a href="#"><h5 class="card-title"><?php echo $product->getName(); ?></h5></a>
                                <div class="card-footer">
                                    <p>Price: <?php echo $product->getPrice(); ?></p>
                                    <p>Count:</p>
                                    <!-- Добавляем форму для каждого продукта -->
                                    <form id="update-cart-<?php echo $product->getId(); ?>" method="POST" action="/update-cart">
                                        <button type="button" onclick="changeCartQuantity('<?php echo $product->getId(); ?>', -1)">-</button>
                                        <input type="number" id="cart-quantity-<?php echo $product->getId(); ?>" name="quantity" value="<?php echo $product->getCount(); ?>" min="0" readonly>
                                        <button type="button" onclick="changeCartQuantity('<?php echo $product->getId(); ?>', 1)">+</button>
                                        <!-- Скрытые поля для отправки данных -->
                                        <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                                        <input type="hidden" id="hidden-cart-quantity-<?php echo $product->getId(); ?>" name="hidden_quantity" value="<?php echo $product->getCount(); ?>">
                                    </form>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <?php if (!empty($products)): ?>
        <div class="text-right mt-3">
            <a href="/checkout" class="btn btn-primary">Checkout</a>
            <?php foreach ($products as $product): ?>
                <input type="hidden" name="products[<?php echo $product->getId(); ?>][name]" value="<?php echo $product->getName(); ?>">
                <input type="hidden" name="products[<?php echo $product->getId(); ?>][price]" value="<?php echo $product->getPrice(); ?>">
                <input type="hidden" name="products[<?php echo $product->getId(); ?>][count]" value="<?php echo $product->getCount(); ?>">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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

    .card-footer {
        font-weight: bold;
        font-size: 18px;
        background-color: white;
    }

    button {
        font-size: 18px;
        padding: 5px 10px;
        margin: 0 5px;
        cursor: pointer;
    }

    input[type="number"] {
        width: 50px;
        text-align: center;
        border: none;
        background: none;
        font-size: 18px;
    }
</style>

<script>
    function changeCartQuantity(productId, change) {
        var quantityInput = document.getElementById('cart-quantity-' + productId);
        var hiddenQuantityInput = document.getElementById('hidden-cart-quantity-' + productId);
        var currentQuantity = parseInt(quantityInput.value);
        var newQuantity = currentQuantity + change;

        if (newQuantity < 1) {
            newQuantity = 0;
        }

        quantityInput.value = newQuantity;
        hiddenQuantityInput.value = newQuantity;

        // Автоматически отправляем форму для обновления корзины
        document.getElementById('update-cart-' + productId).submit();
    }
</script>
