<?php

namespace Service;

use Entity\User;

class AuthenticationService
{
    public function getUser(): ?User
    {
        session_start();
        if (!isset($_SESSION['userId'])) {
            return null;
        }

        $userId = $_SESSION['userId'];
        // получить пользователя из бд и вернуть
    }

    public function check(): bool
    {
        session_start();

        return isset($_SESSION['userId']);
    }

    public function login()
    {

    }

    public function logout()
    {

    }
}