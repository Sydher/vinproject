<?php

namespace App\Service\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface {

    public function checkPreAuth(UserInterface $user) {
        if (!$user instanceof User) {
            return;
        }

        if ($user->isBanned()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('YouAreBanned');
        }
    }

    public function checkPostAuth(UserInterface $user) {
        if (!$user instanceof User) {
            return;
        }
    }

}