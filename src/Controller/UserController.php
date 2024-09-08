<?php
namespace Controller;

use Repository\UserRepository;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Service\AuthenticationInterface;

class UserController
{

    private AuthenticationInterface  $authenticationService;

    public function __construct(AuthenticationInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function getMyProfile(): void
    {
        require_once '../View/get_profile.php';
    }

    public function getLogin(): void
    {
        require_once '../View/get_login.php';
    }

    public function getRegistration(): void
    {
        require_once '../View/get_registration.php';
    }

    public function registrate(RegistrateRequest $request): void
    {
        $errors = $request->validate();
        if (empty($errors)) {
            $data = $request->getData();
            $name = $request->getName();
            $email = $request->getEmail();
            $password = $request->getPassword();
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $user = new UserRepository();
            $user->insert($name, $email, $passwordHash);

            header('Location: /login');
        }

        require_once __DIR__ . '/../View/get_registration.php';
    }

    public function login(LoginRequest $request): void
    {
        $errors = $request->validate();
        //Если нет ошибок, выполняем подключение к БД и проверку пользователя
        if ($this->authenticationService->login($request->getUsername(), $request->getPassword())){
                header('Location: /catalog');
        } else {
            $errors["username"] = "Username or password is incorrect";
        }
        require_once __DIR__ . '/../View/get_login.php';
    }

    public function logout(): void
    {
        // Завершите сессию
        $this->authenticationService->logout();
        // Перенаправьте пользователя на страницу входа или главную страницу
        header('Location: /login');
    }


}