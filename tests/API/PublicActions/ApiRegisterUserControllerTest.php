<?php

namespace App\Tests\API\PublicActions;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class ApiRegisterUserControllerTest extends ApiTestCase
{
    public const URL = '/api/register';

    public function testRegisterUser()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::URL,
            [
                'json' => [
                    'username' => 'joe2',
                    'password' => 'new_password',
                ],
            ]
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertCount(0, $data);
    }
}
