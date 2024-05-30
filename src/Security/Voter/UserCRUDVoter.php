<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserCRUDVoter extends Voter
{
    public const USER_GET = 'USER_GET';
    public const USER_UPDATE = 'USER_UPDATE';
    public const USER_DELETE = 'USER_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::USER_GET, self::USER_UPDATE, self::USER_DELETE])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $user->isAdmin() || $subject === $user;
    }
}
