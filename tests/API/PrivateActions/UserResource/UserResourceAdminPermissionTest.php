<?php

namespace App\Tests\API\PrivateActions\UserResource;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Tests\API\PrivateActions\AbstractAuthenticatedApiTestCase;

class UserResourceAdminPermissionTest extends AbstractAuthenticatedApiTestCase
{
    public const USER_NAME = UserFixtures::ADMIN_USER_NAME;
    public const PASSWORD = UserFixtures::ADMIN_PASSWORD;
    public const COLLECTION_URL = '/api/users';
    public const SINGLE_URL = '/api/users/2';

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
        $response = $this->createClientWithCredentials()->request('GET', self::COLLECTION_URL)->getContent();
        $this->assertResponseIsSuccessful();
        $data = json_decode($response);
        $this->assertEquals('/api/contexts/User', $data->{'@context'});
        $this->assertEquals('/api/users', $data->{'@id'});
        $this->assertEquals('hydra:Collection', $data->{'@type'});
        $member = $data->{'hydra:member'};
        $this->assertGreaterThan(0, count($member));
    }

    public function testPostNew(): void
    {
        $response = $this->createClientWithCredentials()->request(
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
        $this->assertResponseIsSuccessful();
        $data = json_decode($response);

        $this->assertEquals('/api/contexts/User', $data->{'@context'});
        $this->assertEquals('User', $data->{'@type'});
        $this->assertEquals('new_joe', $data->username);
    }

    public function testGetSingle(): void
    {
        $response = $this->createClientWithCredentials()->request('GET', self::SINGLE_URL)->getContent();

        $this->assertResponseIsSuccessful();
        $data = json_decode($response);
        $this->assertEquals('/api/contexts/User', $data->{'@context'});
        $this->assertEquals(self::SINGLE_URL, $data->{'@id'});
        $this->assertEquals('User', $data->{'@type'});
        $this->assertEquals('joe', $data->username);
    }

    public function testDeleteSingle(): void
    {
        $response = $this->createClientWithCredentials()->request('DELETE', self::SINGLE_URL)->getContent();
        $this->assertResponseStatusCodeSame(204);
        $this->assertEmpty($response);
    }

    public function testPatchSingle(): void
    {
        $response = $this->createClientWithCredentials()->request(
            'PATCH',
            self::SINGLE_URL, [
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
        $this->assertEquals(self::SINGLE_URL, $data->{'@id'});
        $this->assertEquals('User', $data->{'@type'});
        $this->assertEquals('joe2', $data->username);
        $this->assertNotEquals('some_password', $data->password);
    }
}
