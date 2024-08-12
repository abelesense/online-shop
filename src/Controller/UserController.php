<?php
namespace Controller;
//require_once '../Model/User.php';
use Model\User;

class UserController {

    public function getMyProfile()
    {
        require_once '../View/get_profile.php';
    }

    public function getLogin()
    {
        require_once '../View/get_login.php';
    }

    public function getRegistration()
    {
        require_once '../View/get_registration.php';
    }

    private function containsNumbers(string $string):bool  {
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
    public function validateRegistration (array $data): array{
        $errors = [];
        $name = $data['name'];
        if (empty($name)) {
            $errors['name'] = "Name cannot be empty";
        } elseif (strlen($name) < 2) {
            $errors['name'] = "Name cannot be less than 2 characters";
        } elseif ($this->containsNumbers($name)) {
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
        $user = new User();
        $count= $user->validateEmail($email);
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
    public function registrate()
    {
        $errors = $this->validateRegistration($_POST);
        if (empty($errors)) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['psw'];
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $user = new User();
            $user->insert($name, $email, $passwordHash);

            $user->select($email);
            header('Location: /login');


        }


        require_once __DIR__ . '/../View/get_registration.php';


    }

    public function validateLogin(array $data): array
    {
        $errors = [];
        if (isset($data["username"])) {
            $email = $data["username"];
        } else {
            $errors["username"] = "Username is required";
        }
        if (isset($data["password"])) {
            $password = $data["password"];
        } else {
            $errors["password"] = "Password is required";
        }
        return $errors;
    }

    public function login()
    {
        $this->validateLogin($_POST);
        $userModel = new User();
        $user = $userModel->getUserByEmail($_POST["username"]);

        //Если нет ошибок, выполняем подключение к БД и проверку пользователя
        if (!empty($user)) {
            $password = $_POST['password'];
            $passwordHash = $user->getPassword();


            if (password_verify($password, $passwordHash)) {
                session_start();
                $_SESSION['userId']= $user->getId();
                $_SESSION['userName'] = $user->getName();
                header('Location: /catalog');

            } else {
                $errors["username"] = "Username or password is incorrect";
            }
        } else {
            $errors["username"] = "Username or password is incorrect";

        }
        require_once __DIR__ . '/../View/get_login.php';
    }

    public function logout()
    {
        // Завершите сессию пользователя
        session_unset();
        session_destroy();

        // Перенаправьте пользователя на страницу входа или главную страницу
        header('Location: /login');
    }


}