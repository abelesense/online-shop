<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Responsive Login Page</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<div class="login-container">
    <form id="loginForm" action="/login" method="post">
        <h2>Login</h2>
        <div class="input-group">
            <label for="username">Email</label>
            <?php if(isset($errors['username'])): ?>
            <label><?php echo $errors['username']; ?><label>
                    <?php endif; ?>
            <input type="email" id="username" name="username" required />
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <?php if(isset($errors['password'])): ?>
            <label><?php echo $errors['password']; ?><label>
                    <?php endif; ?>
            <input type="password" id="password" name="password" required />

        </div>
        <button type="submit">Login</button>
        <p id="errorMessage" class="error-message"></p>
    </form>
</div>
<script src="script.js"></script>
</body>
</html>

<style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background: linear-gradient(to right, #ff7e5f, #03c03c);
    }

    .login-container {
        background: linear-gradient(to right, #43cea2, #185a9d);
        padding: 2rem;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        max-width: 400px;
        width: 100%;
    }

    h2 {
        margin-bottom: 1.5rem;
        color: #000000;
        text-align: center;
    }

    .input-group {
        margin-bottom: 1rem;
    }

    .input-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #000000;
        font-family: Arial, Helvetica, sans-serif;
    }

    .input-group input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        width: 100%;
        padding: 0.75rem;
        border: none;
        background-color: #007bff;
        color: white;
        font-size: 1rem;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    .error-message {
        color: red;
        text-align: center;
        margin-top: 1rem;
        display: none;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .login-container {
            padding: 1.5rem;
        }
    }

</style>