<?php

namespace Service;

use Repository\UserProductRepository;

class CartService
{
    private UserProductRepository $userProductRepository;
    public function __construct(UserProductRepository $userProductRepository)
    {
        $this->userProductRepository = $userProductRepository;
    }

    public function increaseProduct(object $user, int $productId): array
    {
        $existingProduct = $this->userProductRepository->getOneByUserIdAndProductId($user->getId(), $productId);

        if ($existingProduct) {
            $this->userProductRepository->increaseProductCount($user->getId(), $productId);
        } else {
            $this->userProductRepository->addProductToCart($user->getId(), $productId, 1);
        }
        $count = $this->userProductRepository->countOfUserProducts($user->getId(), $productId);
        return ['count' => $count];
    }

    public function decreaseProduct(object $user, int $productId): array
    {
        $existingProduct = $this->userProductRepository->getOneByUserIdAndProductId($user->getId(), $productId);

        if ($existingProduct) {
            $this->userProductRepository->decreaseProductCount($existingProduct, $user->getId(), $productId);
        }
        $count = $this->userProductRepository->countOfUserProducts($user->getId(), $productId);
        return ['count' => $count];
    }

}