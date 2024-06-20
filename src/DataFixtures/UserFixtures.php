<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\CreateUserAction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_NAME = 'admin';
    public const ADMIN_PASSWORD = 'some_password';
    public const REGULAR_USER_NAME = 'joe';
    public const REGULAR_USER_PASSWORD = 'some_password';

    public const NEW_USER_NAME = 'jey';
    public const NEW_USER_PASSWORD = 'some_password';

    public function __construct(
        private CreateUserAction $createUserAction,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $userData) {
            $user = $this->createUserAction->create(
                $userData['username'],
                $userData['password'],
                $userData['roles'],
            );

            $this->addReference('user_'.$userData['username'], $user);
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

        yield [
            'username' => self::NEW_USER_NAME,
            'password' => self::NEW_USER_PASSWORD,
            'roles' => [User::ROLE_USER],
        ];
    }
}
