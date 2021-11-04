<?php

namespace App\Utils\Generator;

class TokenGenerator
{
    /**
     * @return string
     */
    public static function generateToken(): string
    {
        $token = openssl_random_pseudo_bytes(16);
        return bin2hex($token);
    }
}
