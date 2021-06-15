<?php

namespace App\Utils\Manager;

use App\Entity\Cart;
use Doctrine\Persistence\ObjectRepository;

class CartManager extends AbstractBaseManager
{
    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Cart::class);
    }
}
