<?php

namespace App\Tests\API\PrivateActions;

use App\Entity\User;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;

class ApiResourceUserNotLoggedTest extends ApiTestCase
{
    const COLLECTION_URL = '/api/users';
    const SINGLE_URL = '/api/users/1';
    
    public function testGetCollection(): void
    {
        $this->expectException(ClientException::class);
        $data = static::createClient()->request('GET', self::COLLECTION_URL)->getContent();
        $this->assertEquals(401, $data['code']);
    }
    // public function testPostNew(): void
    // {
    //     $this->expectException(ClientException::class);
    //     $data = static::createClient()->request(
    //         'POST', 
    //         '/api/users',             
    //         // [
    //         //     'json' => [
    //         //         '@context' => '/api/contexts/User',
    //         //         '@id' => '/api/users',
    //         //         '@type'=> 'User',                
    //         //         'username' => 'new_joe',
    //         //         'password' => 'some_password',
    //         //         'roles' => [User::ROLE_USER]
    //         //     ]
    //         // ]
    //         )
    //         ->getContent();
    //     $this->assertEquals(401, $data['code']);
    // }

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
