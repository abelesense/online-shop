<div class="container">
    <h3>Cart</h3>
    <div class="card-deck">
        <?php foreach ($products as $product) : ?>
            <?php if ($product['count'] > 0) : ?>
                <div class="card text-center" id="product-card-<?php echo htmlspecialchars($product['id']); ?>">
                    <a href="#">
                        <div class="card-header">
                            Hit!
                        </div>
                        <img class="card-img-top" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Card image">
                        <div class="card-body">
                            <p class="card-text text-muted"><?php echo htmlspecialchars($product["description"]); ?></p>
                            <a href="#"><h5 class="card-title"><?php echo htmlspecialchars($product["name"]); ?></h5></a>
                            <div class="card-footer">
                                <p>Price: <?php echo htmlspecialchars($product["price"]); ?></p>
                                <p>Count:
                                    <button type="button" onclick="changeCartQuantity('<?php echo htmlspecialchars($product['id']); ?>', -1)">-</button>
                                    <input type="number" id="cart-quantity-<?php echo htmlspecialchars($product['id']); ?>" value="<?php echo htmlspecialchars($product['count']); ?>" min="0" readonly>
                                    <button type="button" onclick="changeCartQuantity('<?php echo htmlspecialchars($product['id']); ?>', 1)">+</button>
                                </p>
                                <form action="/update-cart" method="POST" style="display: none;">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                    <input type="hidden" id="hidden-cart-quantity-<?php echo htmlspecialchars($product['id']); ?>" name="quantity" value="<?php echo htmlspecialchars($product['count']); ?>">
                                    <button type="submit" id="update-cart-<?php echo htmlspecialchars($product['id']); ?>">Update Cart</button>
                                </form>
                                <form action="/delete-product" method="POST" style="display: none;">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                    <button type="submit" id="delete-product-<?php echo htmlspecialchars($product['id']); ?>">Delete Product</button>
                                </form>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
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

        // Automatically submit the form to update the cart
        document.getElementById('update-cart-' + productId).click();
    }
</script>