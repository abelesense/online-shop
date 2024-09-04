<?php

namespace Request;

use Repository\UserRepository;

class RegistrateRequest extends Request
{
    public function validate (): array
    {
        $errors = [];
        $name = $this->getName();
        if (empty($name)) {
            $errors['name'] = "Name cannot be empty";
        } elseif (strlen($name) < 2) {
            $errors['name'] = "Name cannot be less than 2 characters";
        } elseif ($this->containsNumbers($name)) {
            $errors['name'] = "Name cannot contain numbers";
        }

// Валидация почты
        $email = $this->getEmail();
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
        $user = new UserRepository();
        $count = $user->countWithEmail($email);
        if ($count) {
            $errors['email'] = "Email already exists";
        }

// Валидация пароля
        $password = $this->getPassword();
        if (empty($password)) {
            $errors['password'] = "Password cannot be empty";
        } elseif (strlen($password) < 2) {
            $errors['password'] = "Password cannot be less than 2 characters";
        } elseif ($this->getPassword() != $this->getPasswordRepeat()) {
            $errors['password'] = "Passwords do not match";
        }
        return $errors;

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

    public function getName(): string
    {
        return $this->getData()['name'];
    }

    public function getEmail(): string
    {
        return $this->getData()['email'];
    }

    public function getPassword(): string
    {
        return $this->getData()['psw'];
    }

    public function getPasswordRepeat(): string
    {
        return $this->getData()['psw-repeat'];
    }

}