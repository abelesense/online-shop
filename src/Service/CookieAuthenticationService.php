<?php

namespace Service;

use Entity\User;
use Repository\UserRepository;


class CookieAuthenticationService implements AuthenticationInterface
{
    public function getUser(): ?User
    {
        if (!isset($_COOKIE['userId'])) {
            return null;
        }
        $userId = $_COOKIE['userId'];
        $userRepo = new UserRepository();
        return $userRepo->getUserById($userId);
    }

    public function check(): bool
    {
        return isset($_COOKIE['userId']);
    }

    public function login(string $email, string $password): bool
    {
        $userRepo = new UserRepository();
        $user = $userRepo->getUserByEmail($email);

        if (!empty($user)) {
            $passwordHash = $user->getPassword();
            if (password_verify($password, $passwordHash)) {
                // Установка cookies с безопасными флагами
                setcookie('userId', $user->getId(), time() + 3600, "/", "", true, true);
                setcookie('userName', $user->getName(), time() + 3600, "/", "", true, true);
                return true;
            }
        }
        return false;
    }

    public function logout():void
    {
        // Удаление cookies
        setcookie('userId', '', time() - 3600, "/");
        setcookie('userName', '', time() - 3600, "/");
    }
}