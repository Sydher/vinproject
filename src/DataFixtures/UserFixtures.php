<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture {

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager) {
        $user1 = new User();
        $user1->setUsername('hugotest');
        $user1->setEmail('hugotest@yopmail.com');
        $user1->setPassword($this->encoder->encodePassword($user1, 'test'));
        $user1->setIsVerified(true);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('michel');
        $user2->setEmail('michel@yopmail.com');
        $user2->setPassword($this->encoder->encodePassword($user2, 'michel'));
        $manager->persist($user1);

        $manager->flush();
    }

}
