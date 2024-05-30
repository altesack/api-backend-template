<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testIsAdmin(array $roles, bool $expected)
    {
        $user = new User();
        $user->setRoles($roles);

        $this->assertEquals($expected, $user->isAdmin());
    }

    public static function dataProvider(): \Generator
    {
        yield [
            'roles' => [User::ROLE_ADMIN],
            'expected' => true,
        ];
        yield [
            'roles' => [User::ROLE_ADMIN, User::ROLE_USER],
            'expected' => true,
        ];
        yield [
            'roles' => [User::ROLE_USER],
            'expected' => false,
        ];
        yield [
            'roles' => [],
            'expected' => false,
        ];
        yield [
            'roles' => ['something else'],
            'expected' => false,
        ];
    }
}
