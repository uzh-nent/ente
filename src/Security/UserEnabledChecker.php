<?php

namespace App\Security;

use App\Entity\User;
use App\Security\Exceptions\AccountDisabledException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEnabledChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User || !$user->isEnabled()) {
            throw new AccountDisabledException();
        }
    }

    public function checkPostAuth(UserInterface $user, ?TokenInterface $token = null): void
    {
        // intentionally left blanc
    }
}
