<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserLoggedInViaSocialNetworkEvent extends Event
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var string
     */
    private $plainPassword;

    public function __construct(User $user, string $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}