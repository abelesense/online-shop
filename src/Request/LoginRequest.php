<?php

namespace Request;

class LoginRequest extends Request
{
    public function validate(): array
    {
        $errors = [];
        if ($this->getUsername() !== null) {
            $email = $this->getUsername();
        } else {
            $errors["username"] = "Username is required";
        }
        if ($this->getPassword() !== null) {
            $password = $this->getPassword();
        } else {
            $errors["password"] = "Password is required";
        }
        return $errors;
    }

    public function getUsername(): string
    {
        return $this->getData()["username"];
    }

    public function getPassword(): string
    {
        return $this->getData()["password"];
    }
}