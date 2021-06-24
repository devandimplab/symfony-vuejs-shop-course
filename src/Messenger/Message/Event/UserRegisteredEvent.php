<?php

namespace App\Messenger\Message\Event;

use App\Entity\User;

class UserRegisteredEvent
{
    private $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
