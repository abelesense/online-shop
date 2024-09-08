<?php

namespace Entity;

class UserProduct
{
    private int $id;
    private int $userId;
    private int $productId;
    private int $count;

    public function __construct(int $id, int $userId, int $productId, int $count){
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
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
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}