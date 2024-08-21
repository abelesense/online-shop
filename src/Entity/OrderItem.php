<?php

namespace Entity;

class OrderItem
{
    private $id;
    private $orderId;
    private $productId;
    private $count;
    private $price;

    /**
     * @param $id
     * @param $orderId
     * @param $productId
     * @param $price
     * @param $count
     */
    public function __construct($id, $orderId, $productId, $price, $count)
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->price = $price;
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }




}