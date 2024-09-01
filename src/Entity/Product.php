<?php

namespace Entity;

class Product
{
    private int $id;
    private string $name;
    private string $description;
    private string $price;
    private string $image;

    private int $countInCart;

    public function __construct(int $id, string $name, string $description, string $price, string $image, int $count)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->image = $image;
        $this->count = $count;

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}