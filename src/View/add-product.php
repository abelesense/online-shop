<a href="/catalog">Catalog</a>
<a href="/logout">Logout</a>
<a href="/cart">Cart</a>

<form action="/add-product" method="POST">
    <div class="container">
        <h1>Add product</h1>
        <p>Please fill in this form to create an account.</p>
        <hr>
        <label for="name"><b>Product-id</b></label>
        <?php if (isset($errors['name'])): ?>
            <label><?php echo $errors['name']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter Product Id" name="product-id" id="product-id" required>

        <label for="count"><b>Count</b></label>
        <?php if (isset($errors['email'])): ?>
            <label><?php echo $errors['email']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter Count" name="count" id="count" required>


        <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
        <button type="submit" class="registerbtn">Add product</button>
    </div>

    <div class="container signin">
        <p>Already have an account? <a href="#">Sign in</a>.</p>
    </div>
</form>
<style>
    * {box-sizing: border-box}

    /* Add padding to containers */
    .container {
        padding: 16px;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
    .registerbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .registerbtn:hover {
        opacity:1;
    }

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
        background-color: #f1f1f1;
        text-align: center;
    }
</style>
