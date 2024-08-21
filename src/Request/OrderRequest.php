<?php

namespace Request;

class OrderRequest extends Request
{
    public function validateOrderForm(): array
    {
        $errors = [];
        $street = $this->getStreet();
        if (empty($street)) {
            $errors['street'] = "Street cannot be empty";
        } elseif (strlen($street) < 2) {
            $errors['street'] = "Name cannot be less than 2 characters";
        }

        // Валидация города
        $city = $this->getCity();
        $firstChar = substr($city, 0, 1);
        if (empty($city)) {
            $errors['city'] = "City cannot be empty";
        } elseif (strlen($city) < 2) {
            $errors['city'] = "City cannot be less than 2 characters";
        } elseif ($firstChar !== strtoupper($firstChar)) {
            $errors['city'] = 'City must be starts with a capital letter';

        }

        // Валидация телефона
        $phone = $this->getPhone();
        if (empty($phone)) {
            $errors['phone'] = "Phone cannot be empty";
        } elseif (!ctype_digit($phone)) {
            $errors['phone'] = 'Phone must be digits';
        }
        return $errors;
    }

    public function getStreet(): string
    {
        return $this->getData()['house_address'];
    }

    public function getCity(): string
    {
        return $this->getData()['city'];
    }

    public function getPhone(): string
    {
        return $this->getData()['phone'];
    }

    public function getTotalPrice()
    {
        return $this->getData()['total_amount'];
    }

}