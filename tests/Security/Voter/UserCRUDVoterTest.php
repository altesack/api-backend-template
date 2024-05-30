<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Security\Voter\UserCRUDVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class UserCRUDVoterTest extends TestCase
{
    public function testNoUser()
    {
        $token = $this->createMock(TokenInterface::class);
        $voter = new UserCRUDVoter();
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, $this->createMock(User::class), [UserCRUDVoter::USER_GET]));
    }

    public function testOwnerUser()
    {
        $user = $this->createMock(User::class);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $voter = new UserCRUDVoter();
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, $user, [UserCRUDVoter::USER_GET]));
    }

    public function testNotOwnerUser()
    {
        $user = $this->createMock(User::class);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $voter = new UserCRUDVoter();
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, $this->createMock(User::class), [UserCRUDVoter::USER_GET]));
    }

    public function testAdminUser()
    {
        $user = $this->createMock(User::class);
        $user->method('isAdmin')->willReturn(true);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $voter = new UserCRUDVoter();
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, $this->createMock(User::class), [UserCRUDVoter::USER_GET]));
    }
}
