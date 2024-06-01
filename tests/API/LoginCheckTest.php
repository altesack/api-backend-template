<?php

namespace App\Tests\API;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;

class LoginCheckTest extends ApiTestCase
{
    public const URL = '/api/login_check';

    public function testWrongLogin(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::URL,
            [
                'json' => [
                    'username' => 'joe',
                    'password' => 'wrong_password',
                ],
            ]
        );
        $this->expectException(ClientException::class);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(401, $data['code']);
        $this->assertEquals('Invalid credentials.', $data['message']);
    }

    public function testGoodLogin(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::URL,
            [
                'json' => [
                    'username' => 'admin',
                    'password' => '111',
                ],
            ]
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);
    }
}
