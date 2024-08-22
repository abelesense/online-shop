<?php

namespace Service;
use \Entity\User;
use Repository\UserRepository;

class AuthenticationService
{
    public function getUser(): ?User
    {
        session_start();
        if (!isset($_SESSION['userId'])) {
            return null;
        }
        $userId = $_SESSION['userId'];
        $user = new UserRepository();
        return $user->getUserById($userId);
    }

    public function check(): bool
    {
        session_start();
        return isset($_SESSION['userId']);
    }

    public function login(string $email, string $password): bool
    {

        $userModel = new UserRepository();
        $user = $userModel->getUserByEmail($email);

        //Если нет ошибок, выполняем подключение к БД и проверку пользователя
        if (!empty($user)) {
            $passwordHash = $user->getPassword();
            if (password_verify($password, $passwordHash)) {
                session_start();
                $_SESSION['userId'] = $user->getId();
                $_SESSION['userName'] = $user->getName();
                exit();

            }
        }
        return false;
    }

}