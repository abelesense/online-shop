<div class="container">
    <div class="title">
        <h2>Product Order Form</h2>
    </div>
    <?php if (!empty($_POST['products'])): ?>
        <div class="d-flex">
            <form action="/checkout" method="POST">
                <label>
                    <span>Street Address <span class="required">*</span></span>
                    <?php if(isset($errors['house_address'])): ?>
                    <label><?php echo $errors['house_address']; ?><label>
                            <?php endif; ?>
                    <input type="text" name="house_address" placeholder="House number and street name" required>
                </label>
                <label>
                    <span>City <span class="required">*</span></span>
                    <?php if(isset($errors['city'])): ?>
                    <label><?php echo $errors['city']; ?><label>
                            <?php endif; ?>
                    <input type="text" name="city" required>
                </label>
                <label>
                    <span>Phone <span class="required">*</span></span>
                    <?php if(isset($errors['phone'])): ?>
                    <label><?php echo $errors['phone']; ?><label>
                            <?php endif; ?>
                    <input type="tel" name="phone" required>
                </label>

                <div class="Yorder">
                    <table>
                        <tr>
                            <th colspan="2">Your order</th>
                        </tr>
                        <?php
                        $totalAmount = 0; // Инициализация переменной для общей суммы

                        foreach ($_POST['products'] as $productId => $productData):
                            $productTotal = $productData['count'] * $productData['price'];
                            $totalAmount += $productTotal; // Добавляем стоимость товара к общей сумме
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($productData['name']) . ' x ' . htmlspecialchars($productData['count']); ?></td>
                                <td><?php echo htmlspecialchars($productTotal); ?></td>
                            </tr>
                            <input type="hidden" name="products[<?php echo htmlspecialchars($productId); ?>][name]" value="<?php echo htmlspecialchars($productData['name']); ?>">
                            <input type="hidden" name="products[<?php echo htmlspecialchars($productId); ?>][price]" value="<?php echo htmlspecialchars($productData['price']); ?>">
                            <input type="hidden" name="products[<?php echo htmlspecialchars($productId); ?>][count]" value="<?php echo htmlspecialchars($productData['count']); ?>">
                        <?php endforeach; ?>
                        <tr>
                            <td>Shipping</td>
                            <td>Free shipping</td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <th><?php echo htmlspecialchars($totalAmount); ?></th>
                        </tr>
                    </table><br>
                    <!-- Скрытое поле для передачи общей суммы -->
                    <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($totalAmount); ?>">

                    <div>
                        <input type="radio" name="payment_method" value="dbt" checked> Direct Bank Transfer
                    </div>
                    <div>
                        <input type="radio" name="payment_method" value="cod"> Cash on Delivery
                    </div>
                    <div>
                        <input type="radio" name="payment_method" value="paypal"> Paypal
                        <span>
                            <img src="https://www.logolynx.com/images/logolynx/c3/c36093ca9fb6c250f74d319550acac4d.jpeg" alt="PayPal" width="50">
                        </span>
                    </div>
                    <button type="submit">Place Order</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>
</div>




<style>
    @import url('https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700');

    body{
        background: url('http://all4desktop.com/data_images/original/4236532-background-images.jpg');
        font-family: 'Roboto Condensed', sans-serif;
        color: #262626;
        margin: 5% 0;
    }
    .container{
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }
    @media (min-width: 1200px)
    {
        .container{
            max-width: 1140px;
        }
    }
    .d-flex{
        display: flex;
        flex-direction: row;
        background: #f6f6f6;
        border-radius: 0 0 5px 5px;
        padding: 25px;
    }
    form{
        flex: 4;
    }
    .Yorder{
        flex: 2;
    }
    .title{
        background: -webkit-gradient(linear, left top, right bottom, color-stop(0, #5195A8), color-stop(100, #70EAFF));
        background: -moz-linear-gradient(top left, #5195A8 0%, #70EAFF 100%);
        background: -ms-linear-gradient(top left, #5195A8 0%, #70EAFF 100%);
        background: -o-linear-gradient(top left, #5195A8 0%, #70EAFF 100%);
        background: linear-gradient(to bottom right, #5195A8 0%, #70EAFF 100%);
        border-radius:5px 5px 0 0 ;
        padding: 20px;
        color: #f6f6f6;
    }
    h2{
        margin: 0;
        padding-left: 15px;
    }
    .required{
        color: red;
    }
    label, table{
        display: block;
        margin: 15px;
    }
    label>span{
        float: left;
        width: 25%;
        margin-top: 12px;
        padding-right: 10px;
    }
    input[type="text"], input[type="tel"], input[type="email"], select
    {
        width: 70%;
        height: 30px;
        padding: 5px 10px;
        margin-bottom: 10px;
        border: 1px solid #dadada;
        color: #888;
    }
    select{
        width: 72%;
        height: 45px;
        padding: 5px 10px;
        margin-bottom: 10px;
    }
    .Yorder{
        margin-top: 15px;
        height: 600px;
        padding: 20px;
        border: 1px solid #dadada;
    }
    table{
        margin: 0;
        padding: 0;
    }
    th{
        border-bottom: 1px solid #dadada;
        padding: 10px 0;
    }
    tr>td:nth-child(1){
        text-align: left;
        color: #2d2d2a;
    }
    tr>td:nth-child(2){
        text-align: right;
        color: #52ad9c;
    }
    td{
        border-bottom: 1px solid #dadada;
        padding: 25px 25px 25px 0;
    }

    p{
        display: block;
        color: #888;
        margin: 0;
        padding-left: 25px;
    }
    .Yorder>div{
        padding: 15px 0;
    }

    button{
        width: 100%;
        margin-top: 10px;
        padding: 10px;
        border: none;
        border-radius: 30px;
        background: #52ad9c;
        color: #fff;
        font-size: 15px;
        font-weight: bold;
    }
    button:hover{
        cursor: pointer;
        background: #428a7d;
    }
</style>