<?php

namespace Entity;

class User
{
    private int $id;
    private string $name;
    private string $password;
    private string $email;

    public function __construct(int $id, string $name, string $password, string $email){
        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getId(): int
    {
        return $this->id;
    }


}