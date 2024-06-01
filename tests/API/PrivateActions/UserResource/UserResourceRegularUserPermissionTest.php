<?php

namespace App\Tests\API\PrivateActions\UserResource;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Tests\API\PrivateActions\AbstractAuthenticatedApiTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;

class UserResourceRegularUserPermissionTest extends AbstractAuthenticatedApiTestCase
{
    public const USER_NAME = UserFixtures::REGULAR_USER_NAME;
    public const PASSWORD = UserFixtures::REGULAR_USER_PASSWORD;
    public const COLLECTION_URL = '/api/users';
    public const SINGLE_URL_NOT_ME = '/api/users/1';
    public const SINGLE_URL_ME = '/api/users/2';

    protected function getUsername(): string
    {
        return self::USER_NAME;
    }

    protected function getPassword(): string
    {
        return self::PASSWORD;
    }

    public function testGetCollection(): void
    {
        $this->expectException(ClientException::class);
        $data = $this->createClientWithCredentials()->request('GET', self::COLLECTION_URL)->getContent();
        $this->assertEquals(401, $data['code']);
    }

    public function testPostNew(): void
    {
        $this->expectException(ClientException::class);
        $data = $this->createClientWithCredentials()->request(
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
        $response = $this->createClientWithCredentials()->request('GET', self::SINGLE_URL_ME)->getContent();

        $this->assertResponseIsSuccessful();
        $data = json_decode($response);
        $this->assertEquals('/api/contexts/User', $data->{'@context'});
        $this->assertEquals(self::SINGLE_URL_ME, $data->{'@id'});
        $this->assertEquals('User', $data->{'@type'});
        $this->assertEquals(UserFixtures::REGULAR_USER_NAME, $data->username);
    }

    public function testDeleteSingle(): void
    {
        $response = $this->createClientWithCredentials()->request('DELETE', self::SINGLE_URL_ME)->getContent();
        $this->assertResponseStatusCodeSame(204);
        $this->assertEmpty($response);
    }

    public function testPatchSingle(): void
    {
        $response = $this->createClientWithCredentials()->request(
            'PATCH',
            self::SINGLE_URL_ME, [
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
                'json' => [
                    'username' => 'joe2',
                    'password' => 'some_password',
                    'roles' => [User::ROLE_USER],
                ],
            ]
        )->getContent();

        $this->assertResponseIsSuccessful();
        $data = json_decode($response);
        $this->assertEquals('/api/contexts/User', $data->{'@context'});
        $this->assertEquals(self::SINGLE_URL_ME, $data->{'@id'});
        $this->assertEquals('User', $data->{'@type'});
        $this->assertEquals('joe2', $data->username);
        $this->assertNotEquals('some_password', $data->password);
    }

    public function testGetSingleNotMe(): void
    {
        $this->expectException(ClientException::class);
        $data = $this->createClientWithCredentials()->request('GET', self::SINGLE_URL_NOT_ME)->getContent();
        $this->assertEquals(401, $data['code']);
    }

    public function testDeleteSingleNotMe(): void
    {
        $this->expectException(ClientException::class);

        $data = $this->createClientWithCredentials()->request('DELETE', self::SINGLE_URL_NOT_ME)->getContent();
        $this->assertEquals(401, $data['code']);
    }

    public function testPatchSingleNotMe(): void
    {
        $this->expectException(ClientException::class);

        $data = $this->createClientWithCredentials()->request(
            'PATCH',
            self::SINGLE_URL_NOT_ME,
            [
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
                'json' => [
                    'username' => 'admin2',
                    'password' => 'some_password',
                    'roles' => [User::ROLE_USER],
                ],
            ]
        )->getContent();
        $this->assertEquals(401, $data['code']);
    }
}
