<?php

namespace App\Tests\API\PostResource;

use App\DataFixtures\PostFixtures;
use App\DataFixtures\UserFixtures;
use App\Tests\API\AbstractAuthenticatedApiTestCase;

class PostResourceAdminPermissionTest extends AbstractAuthenticatedApiTestCase
{
    public const USER_NAME = UserFixtures::ADMIN_USER_NAME;
    public const PASSWORD = UserFixtures::ADMIN_PASSWORD;
    public const COLLECTION_URL = '/api/posts';
    public const SINGLE_URL = '/api/posts/2';

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
        $data = json_decode($response, true);
        $this->assertEquals('/api/contexts/Post', $data['@context']);
        $this->assertEquals('/api/posts', $data['@id']);
        $this->assertEquals('hydra:Collection', $data['@type']);
        $member = $data['hydra:member'];
        $this->assertGreaterThan(0, count($member));
    }

    public function testPostNew(): void
    {
        $response = $this->createClientWithCredentials()->request(
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
        $this->assertResponseIsSuccessful();
        $data = json_decode($response, true);

        $this->assertEquals('/api/contexts/Post', $data['@context']);
        $this->assertEquals('Post', $data['@type']);
        $this->assertEquals('new title', $data['title']);
    }

    public function testGetSingle(): void
    {
        $response = $this->createClientWithCredentials()->request('GET', self::SINGLE_URL)->getContent();

        $this->assertResponseIsSuccessful();
        $data = json_decode($response, true);
        $this->assertEquals('/api/contexts/Post', $data['@context']);
        $this->assertEquals(self::SINGLE_URL, $data['@id']);
        $this->assertEquals('Post', $data['@type']);
        $this->assertEquals(PostFixtures::USER_POST_TITLE, $data['title']);
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
                    'title' => 'new title',
                    'content' => 'new content',
                ],
            ]
        )->getContent();

        $this->assertResponseIsSuccessful();
        $data = json_decode($response, true);
        $this->assertEquals('/api/contexts/Post', $data['@context']);
        $this->assertEquals(self::SINGLE_URL, $data['@id']);
        $this->assertEquals('Post', $data['@type']);
        $this->assertEquals('new title', $data['title']);
    }
}
