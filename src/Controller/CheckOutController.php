<?php

namespace Controller;

use Model\OrderItems;
use Model\Orders;
use Model\Product;
use Model\UserProduct;

class CheckOutController
{
    private UserProduct $userProductModel;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
    }

    public function getCheckOut()
    {
        session_start();
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $userProducts = $this->userProductModel->takeUserProducts($userId);
            $productCounts = [];
            foreach ($userProducts as $userProduct) {
                $productCounts[$userProduct->getProductId()] = $userProduct->getCount();
            }

            $obj = new Product();
            $productIds = array_keys($productCounts);
            $products = $obj->getUserProducts($productIds);

            // Создаем новый массив с добавленным количеством
            $updatedProducts = [];
            foreach ($products as $product) {
                if (isset($productCounts[$product->getId()])) {
                    $product->setCountInCart($productCounts[$product->getId()]);
                } else {
                    $product->setCountInCart(0); // или какое-то другое значение по умолчанию
                }
                $updatedProducts[] = $product;
            }

            // Передача массива $updatedProducts в шаблон
            $products = $updatedProducts;
            require_once "../View/get_checkout.php";
        } else {
            http_response_code(403);
        }
    }

    public function validateOrderForm(array $data): array
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

    public function registrateOrder()
    {
        $errors = $this->validateOrderForm($_POST);
        if (empty($errors)) {
            session_start();
            $userId = $_SESSION['userId'];
            $street = $_POST['house_address'];
            $city = $_POST['city'];
            $phone = $_POST['phone'];
            $totalAmount = $_POST['total_amount'];

            $order = new Orders();
            $order->insert($city, $street, $phone, $userId, $totalAmount);
            $orderInfo = $order->selectUserOrder($userId);
            $orderData = [
                'ids' => [],
//                'user_ids' => [],
                'product_id'=>[],
                'count' => [],
                'price' => [],
            ];

            foreach ($orderInfo as $order) {
                $orderData['ids'][] = $order->getId();
//                $orderData['user_ids'][] = $order->getUserId();
            }
            $userProduct = new UserProduct();
            $orderDataFromUserProducts = $userProduct->takeUserProducts($userId);

            foreach ($orderDataFromUserProducts as $orderDataFromUserProduct) {
                $orderData['product_id'][] = $orderDataFromUserProduct->getProductId();
            }

            foreach ($orderDataFromUserProducts as $orderDataFromUserProduct) {
                $orderData['count'][] = $orderDataFromUserProduct->getCount();
            }
            $products = new Product();
            $orderDataFromProduct = $products->getAllProducts();
            foreach ($orderDataFromProduct as $orderDataPrice) {
                $orderData['price'][] = $orderDataPrice->getPrice();
            }

            $orderItemsModel = new OrderItems();

            foreach ($orderData['product_id'] as $index => $productId) {
                $orderId = end($orderData['ids']); // Получаем последний добавленный order_id
                $count = $orderData['count'][$index];
                $price = $orderData['price'][$index];

                $orderItemsModel->insert($orderId, $productId, $count, $price);
            }

            $userProduct = $this->userProductModel->delete($userId);
        }


        require_once __DIR__ . '/../View/get_checkout.php';

    }
}