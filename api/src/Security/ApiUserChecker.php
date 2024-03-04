<?php

namespace App\Security;

use App\Entity\ApiUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiUserChecker implements UserCheckerInterface
{

    /**
     * @inheritDoc
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof ApiUser) {
            return;
        }
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof ApiUser) {
            return;
        }

        if (true !== $user->getIsActive()) {
            throw new CustomUserMessageAccountStatusException('Account not activated yet. Please wait.');
        }
    }
}
