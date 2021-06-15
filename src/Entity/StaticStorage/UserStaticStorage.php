<?php

namespace App\Entity\StaticStorage;

class UserStaticStorage
{
    public const USER_ROLE_USER = 'ROLE_USER';
    public const USER_ROLE_ADMIN = 'ROLE_ADMIN';
    public const USER_ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public static function getUserRolesChoices(): array
    {
        return [
            self::USER_ROLE_USER => 'User',
            self::USER_ROLE_ADMIN => 'Admin',
            self::USER_ROLE_SUPER_ADMIN => 'Super Admin'
        ];
    }
}
