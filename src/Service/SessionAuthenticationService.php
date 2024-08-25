<?php

namespace Service;

use Entity\User;
use Repository\UserRepository;

class SessionAuthenticationService implements AuthenticationInterface
{
    private UserRepository $userRepository;
    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }
    public function getUser(): ?User
    {
        session_start();
        if (!isset($_SESSION['userId'])) {
            return null;
        }
        $userId = $_SESSION['userId'];

        return $this->userRepository->getUserById($userId);
    }

    public function check(): bool
    {
        session_start();
        return isset($_SESSION['userId']);
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->userRepository->getUserByEmail($email);

        //Если нет ошибок, выполняем подключение к БД и проверку пользователя
        if (!empty($user)) {
            $passwordHash = $user->getPassword();
            if (password_verify($password, $passwordHash)) {
                session_start();
                $_SESSION['userId'] = $user->getId();
                $_SESSION['userName'] = $user->getName();
                return true;

            }
        }
        return false;
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
    }
}