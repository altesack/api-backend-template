<?php

namespace App\Security\Voter;

use App\Entity\OwnableInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OwnableVoter extends Voter
{
    public const IS_OWNER_OR_ADMIN = 'IS_OWNER_OR_ADMIN';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::IS_OWNER_OR_ADMIN === $attribute && $subject instanceof OwnableInterface;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (!$subject instanceof OwnableInterface) {
            return false;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $user->isAdmin() || $subject->getOwner() === $user;
    }
}
