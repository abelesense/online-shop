<?php
namespace Controller;

use Repository\UserRepository;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Request\Request;
use Service\AuthenticationInterface;
use Service\CookieAuthenticationService;
use Service\SessionAuthenticationService;

class UserController
{

    private AuthenticationInterface  $authenticationService;

    public function __construct(AuthenticationInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

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

    public function registrate(RegistrateRequest $request)
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

    public function login(LoginRequest $request)
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

    public function logout()
    {
        // Завершите сессию
        $this->authenticationService->logout();
        // Перенаправьте пользователя на страницу входа или главную страницу
        header('Location: /login');
    }


}