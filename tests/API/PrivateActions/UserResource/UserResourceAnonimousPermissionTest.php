<?php

namespace App\Tests\API\PrivateActions\UserResource;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Symfony\Component\HttpClient\Exception\ClientException;

class UserResourceAnonimousPermissionTest extends ApiTestCase
{
    public const COLLECTION_URL = '/api/users';
    public const SINGLE_URL = '/api/users/1';

    public function testGetCollection(): void
    {
        $this->expectException(ClientException::class);
        $data = static::createClient()->request('GET', self::COLLECTION_URL)->getContent();
        $this->assertEquals(401, $data['code']);
    }

    public function testPostNew(): void
    {
        $this->expectException(ClientException::class);
        $data = static::createClient()->request(
            'POST',
            '/api/users',
            [
                'headers' => ['Content-Type' => 'application/ld+json'],
                'json' => [
                    'username' => 'new_joe',
                    'password' => 'some_password',
                    'roles' => [User::ROLE_USER],
                ],
            ]
        )
            ->getContent();
        $this->assertEquals(401, $data['code']);
    }

    public function testGetSingle(): void
    {
        $this->expectException(ClientException::class);
        $data = static::createClient()->request('GET', self::SINGLE_URL)->getContent();
        $this->assertEquals(401, $data['code']);
    }

    public function testDeleteSingle(): void
    {
        $this->expectException(ClientException::class);
        $data = static::createClient()->request('DELETE', self::SINGLE_URL)->getContent();
        $this->assertEquals(401, $data['code']);
    }

    public function testPostSingle(): void
    {
        $this->expectException(ClientException::class);
        $data = static::createClient()->request('PATCH', self::SINGLE_URL)->getContent();
        $this->assertEquals(401, $data['code']);
    }
}
