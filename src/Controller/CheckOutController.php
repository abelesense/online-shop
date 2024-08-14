<?php

namespace Controller;

use Model\Order;

class CheckOutController
{
    public function getCheckOut()
    {
        session_start();
        if (isset($_SESSION['user_id'])) {

        }

        $userId = $_SESSION['user_id'];

        $products = [];

        require_once "../View/get_checkout.php";
    }

    public function registrateOrder()
    {
        $errors = $this->validateOrderForm($_POST);
        if (empty($errors)) {
            session_start();
            $userId = $_SESSION['user_id'];
            $street = $_POST['house_address'];
            $city = $_POST['city'];
            $phone = $_POST['phone'];
            $totalAmount = $_POST['total_amount'];

//            $userId = ;

            $order = new Order();
            $order->insert($city, $street, $phone, $totalAmount, $userId);



            header('Location: /checkout');


        }


        require_once __DIR__ . '/../View/get_checkout.php';

    }

    private function validateOrderForm(array $data): array
    {
        $errors = [];
        $street = $data['house_address'];
        if (empty($street)) {
            $errors['street'] = "Street cannot be empty";
        } elseif (strlen($street) < 2) {
            $errors['street'] = "Name cannot be less than 2 characters";
        }

        // Валидация города
        $city = $data['city'];
        $firstChar = substr($city, 0, 1);
        if (empty($city)) {
            $errors['city'] = "City cannot be empty";
        } elseif (strlen($city) < 2) {
            $errors['city'] = "City cannot be less than 2 characters";
        } elseif ($firstChar !== strtoupper($firstChar)) {
            $errors['city'] = 'City must be starts with a capital letter';

        }

        // Валидация телефона
        $phone = $data['phone'];
        if (empty($phone)) {
            $errors['phone'] = "Phone cannot be empty";
        } elseif (!ctype_digit($phone)) {
            $errors['phone'] = 'Phone must be digits';
        }
        return $errors;
    }

}