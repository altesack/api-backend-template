<?php

namespace App\Tests\API;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

class UserResourceRegisterNewTest extends ApiTestCase
{
    public const URL = '/api/register';

    public function testRegisterUser()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::URL,
            [
                'headers' => ['Content-Type' => 'application/ld+json'],
                'json' => [
                    'username' => 'joe2',
                    'password' => 'new_password',
                ],
            ]
        );
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();

        $data = json_decode($response, true);
        $this->assertEquals('/api/contexts/User', $data['@context']);
        $this->assertEquals('User', $data['@type']);
        $this->assertEquals('joe2', $data['username']);
        $this->assertEquals(false, $data['admin']);
        $this->assertEquals([User::ROLE_USER], $data['roles']);
    }

    public function testRegisterUserHacker()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::URL,
            [
                'headers' => ['Content-Type' => 'application/ld+json'],
                'json' => [
                    'username' => 'joe2',
                    'password' => 'new_password',
                    'roles' => [User::ROLE_USER, User::ROLE_ADMIN],
                ],
            ]
        );
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();

        $data = json_decode($response, true);
        $this->assertEquals('/api/contexts/User', $data['@context']);
        $this->assertEquals('User', $data['@type']);
        $this->assertEquals('joe2', $data['username']);
        $this->assertEquals(false, $data['admin']);
        $this->assertEquals([User::ROLE_USER], $data['roles']);
    }
}
