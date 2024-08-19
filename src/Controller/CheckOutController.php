<?php

namespace Controller;

use Model\OrderItems;
use Model\Orders;
use Model\Product;
use Model\UserProduct;

class CheckOutController
{
    private UserProduct $userProductModel;
    private Product $productModel;
    private Orders $orderModel;
    private OrderItems $orderItemModel;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->orderModel = new Orders();
        $this->orderItemModel = new OrderItems();
    }

    public function getCheckOut()
    {
        session_start();
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $userProducts = $this->userProductModel->getUserProducts($userId);
            $productCounts = [];
            foreach ($userProducts as $userProduct) {
                $productCounts[$userProduct->getProductId()] = $userProduct->getCount();
            }

            $obj = $this->productModel;
            $productIds = array_keys($productCounts);
            $products = $this->productModel->getUserProducts($productIds);

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

            $order = $this->orderModel->create($city, $street, $phone, $userId, $totalAmount);

            $userProducts = $this->userProductModel->getUserProducts($userId);

            $productIds = [];
            foreach ($userProducts as $userProduct) {
                $productIds[] = $userProduct->getProductId();
            }

            $products = $this->productModel->getUserProducts($productIds);
            foreach ($userProducts as $userProduct) {
                foreach ($products as $product) {
                    if ($userProduct->getProductId() === $product->getId()) {
                        $product->setCountInCart($userProduct->getCount());
                    }
                }
            }

            foreach ($products as $product) {
                $this->orderItemModel->insert(
                    $order->getId(),
                    $product->getId(),
                    $product->getCount(),
                    $product->getCount() * $product->getPrice()
                );
            }

            $userProduct = $this->userProductModel->delete($userId);
        }


        require_once __DIR__ . '/../View/get_checkout.php';

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
}