<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use joshtronic\LoremIpsum;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADMIN_POST_TITLE = 'Post of Admin';
    public const USER_POST_TITLE = 'Post of regular user';

    public function load(ObjectManager $manager): void
    {
        $admin = $this->getReference('user_'.UserFixtures::ADMIN_USER_NAME);
        $regularUser = $this->getReference('user_'.UserFixtures::REGULAR_USER_NAME);

        $manager->persist($this->createPost(title: self::ADMIN_POST_TITLE, owner: $admin));
        $manager->persist($this->createPost(title: self::USER_POST_TITLE, owner: $regularUser));

        for ($i = 0; $i < 10; ++$i) {
            $post = $this->createPost(owner: ($i % 2) == 0 ? $admin : $regularUser);
            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    private function createPost(User $owner, ?string $title = null): Post
    {
        $lipsum = new LoremIpsum();

        return (new Post())
            ->setTitle($title ?? $lipsum->words(5))
            ->setContent($lipsum->paragraphs(5))
            ->setOwner($owner);
    }
}
