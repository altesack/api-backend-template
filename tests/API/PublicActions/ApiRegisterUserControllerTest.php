<?php

namespace App\Tests\API\PublicActions;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;

class ApiRegisterUserControllerTest extends ApiTestCase {
    const URL = '/api/register';

    public function testRegisterUser()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::URL,
            [
                'json' => [
                    'username' => 'joe',
                    'password' => 'new_password',
                ]
            ]
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertCount(0, $data);
    }
}