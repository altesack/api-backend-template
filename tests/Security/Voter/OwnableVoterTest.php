<?php

namespace App\Tests\Entity;

use App\Entity\OwnableInterface;
use App\Entity\User;
use App\Security\Voter\OwnableVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class OwnableVoterTest extends TestCase
{
    public function testNoUser()
    {
        $token = $this->createMock(TokenInterface::class);
        $voter = new OwnableVoter();
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, $this->createMock(OwnableInterface::class), [OwnableVoter::IS_OWNER_OR_ADMIN]));
    }

    public function testOwnerUser()
    {
        $user = $this->createMock(User::class);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $ownable = $this->createMock(OwnableInterface::class);
        $ownable->method('getOwner')->willReturn($user);

        $voter = new OwnableVoter();
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, $ownable, [OwnableVoter::IS_OWNER_OR_ADMIN]));
    }

    public function testNotOwnerUser()
    {
        $user = $this->createMock(User::class);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $ownable = $this->createMock(OwnableInterface::class);
        $ownable->method('getOwner')->willReturn($this->createMock(User::class));

        $voter = new OwnableVoter();
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, $ownable, [OwnableVoter::IS_OWNER_OR_ADMIN]));
    }

    public function testAdminUser()
    {
        $user = $this->createMock(User::class);
        $user->method('isAdmin')->willReturn(true);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $ownable = $this->createMock(OwnableInterface::class);
        $ownable->method('getOwner')->willReturn($this->createMock(User::class));

        $voter = new OwnableVoter();
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, $ownable, [OwnableVoter::IS_OWNER_OR_ADMIN]));
    }
}
