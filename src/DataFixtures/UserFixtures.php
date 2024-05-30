<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\CreateUserAction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_NAME = 'admin';
    public const ADMIN_PASSWORD = '111';
    public const REGULAR_USER_NAME = 'joe';
    public const REGULAR_USER_PASSWORD = '222';

    public function __construct(
        private CreateUserAction $createUserAction,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $userData) {
            $this->createUserAction->create(
                $userData['username'],
                $userData['password'],
                $userData['roles'],
            );
        }
    }

    public function getData(): \Generator
    {
        yield [
            'username' => self::ADMIN_USER_NAME,
            'password' => self::ADMIN_PASSWORD,
            'roles' => [User::ROLE_USER, User::ROLE_ADMIN],
        ];

        yield [
            'username' => self::REGULAR_USER_NAME,
            'password' => self::REGULAR_USER_PASSWORD,
            'roles' => [User::ROLE_USER],
        ];
    }
}
