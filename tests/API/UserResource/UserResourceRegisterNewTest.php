<?php

namespace App\Tests\API;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

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
    }
}
