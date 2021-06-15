<?php

namespace App\Utils\Manager;

use App\Entity\User;
use Doctrine\Persistence\ObjectRepository;

class UserManager extends AbstractBaseManager
{
    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
    }

    /**
     * @param User $user
     */
    public function remove(object $user)
    {
        $user->setIsDeleted(true);
        $this->save($user);
    }
}
