<?php

namespace App\Tests\Unit\Utils\Generator;

use PHPUnit\Framework\TestCase;
use App\Utils\Generator\PasswordGenerator;

/**
 * @group unit
 */
class PasswordGeneratorTest extends TestCase
{
    public function testGeneratePassword(): void
    {
        $password = PasswordGenerator::generatePassword(8);

        self::assertSame(8, strlen($password));
    }
}
