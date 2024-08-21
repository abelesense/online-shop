<?php
namespace Controller;

use Repository\UserRepository;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Request\Request;

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
        $userModel = new UserRepository();
        $data = $request->getData();
        $user = $userModel->getUserByEmail($request->getUsername());

        //Если нет ошибок, выполняем подключение к БД и проверку пользователя
        if (!empty($user)) {
            $password = $request->getPassword();
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