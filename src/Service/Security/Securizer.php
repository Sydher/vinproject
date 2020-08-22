<?php

namespace App\Service\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class Securizer {

    /** @var AccessDecisionManagerInterface */
    private $accessDecisionManager;

    public function __construct(AccessDecisionManagerInterface $accessDecisionManager) {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    /**
     * @param User|null $user
     * @param $attribute
     * @param null $object
     * @return bool
     */
    public function isGranted(?User $user, $attribute, $object = null) {
        if (!$user) {
            return false;
        }

        $token = new UsernamePasswordToken($user, 'none', 'none', $user->getRoles());
        return ($this->accessDecisionManager->decide($token, [$attribute], $object));
    }

}