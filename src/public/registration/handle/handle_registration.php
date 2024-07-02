<?php
function containsNumbers($string) {
    // Перебираем каждый символ в строке
    for ($i = 0; $i < strlen($string); $i++) {
        // Если текущий символ является числом, возвращаем true
        if (is_numeric($string[$i]) && $string[$i] != ' ') {
            return true;
        }
    }
    // Если ни один символ не является числом, возвращаем false
    return false;
}
function validate (array $data){
     $errors = [];

// Валидация имени
    $name = $data['name'];
    if (empty($name)) {
        $errors['name'] = "Name cannot be empty";
    } elseif (strlen($name) < 2) {
        $errors['name'] = "Name cannot be less than 2 characters";
    } elseif (containsNumbers($name)) {
        $errors['name'] = "Name cannot contain numbers";
    }

// Валидация почты
    $email = $data['email'];
    if (empty($email)) {
        $errors['email'] = "Email cannot be empty";
    } elseif (strlen($email) < 2) {
        $errors['email'] = "Email cannot be less than 2 characters";
    }
    $flag = 0;
    for($i = 0; $i < strlen($email); $i++) {
        if ($email[$i] == '@') {
            $flag = 1;
        }
    }
    if ($flag == 0) {
        $errors['email'] = "Email is not a valid email address";
    }
    $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbname", "dbuser", "pwd");
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        $errors['email'] = "Email already exists";
    }

// Валидация пароля
    $password = $data['psw'];
    if (empty($password)) {
        $errors['password'] = "Password cannot be empty";
    } elseif (strlen($password) < 2) {
        $errors['password'] = "Password cannot be less than 2 characters";
    } elseif ($data['psw'] != $data['psw-repeat']) {
        $errors['password'] = "Passwords do not match";
    }
    return $errors;
}
$errors = validate($_POST);

//Выполнение SQL-запроса
if (empty($errors)) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['psw'];

    $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbname", "dbuser", "pwd");
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->execute([':name' => $name, ':email' => $email, ':password' => $passwordHash]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);


}


require_once __DIR__ . '/../form/get_registration.php';
?>

