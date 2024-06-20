<?php

namespace App\Tests\API\PostResource;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;

class PostResourceAnonimousPermissionTest extends ApiTestCase
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
            self::COLLECTION_URL,
            [
                'headers' => ['Content-Type' => 'application/ld+json'],
                'json' => [
                    'title' => 'new title',
                    'content' => 'new content',
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
