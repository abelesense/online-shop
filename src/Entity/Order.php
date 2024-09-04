<?php

namespace Entity;

class Order
{
    private int $id;
    private string $city;
    private string $street;
    private string $number;
    private int $userId;
    private int $totalAmount;

    /**
     * @param int $id
     * @param string $city
     * @param string $street
     * @param string $number
     * @param int $totalAmount
     * @param int $userId
     */
    public function __construct(int $id, string $city, string $street, string $number, int $userId, int $totalAmount)
    {
        $this->id = $id;
        $this->city = $city;
        $this->street = $street;
        $this->number = $number;
        $this->userId = $userId;
        $this->totalAmount = $totalAmount;
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
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }




}