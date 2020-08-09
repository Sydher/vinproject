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

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager) {
        $superAdmin = new User();
        $superAdmin->setUsername('hugotest');
        $superAdmin->setEmail('hugotest@yopmail.com');
        $superAdmin->setPassword($this->encoder->encodePassword($superAdmin, 'test'));
        $superAdmin->setIsVerified(true);
        $superAdmin->setRoles(["ROLE_SUPER_ADMIN"]);
        $manager->persist($superAdmin);

        $simpleAdmin = new User();
        $simpleAdmin->setUsername('thierry');
        $simpleAdmin->setEmail('thierry@yopmail.com');
        $simpleAdmin->setPassword($this->encoder->encodePassword($simpleAdmin, 'foot'));
        $simpleAdmin->setIsVerified(true);
        $simpleAdmin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($simpleAdmin);

        $simpleUser = new User();
        $simpleUser->setUsername('michel');
        $simpleUser->setEmail('michel@yopmail.com');
        $simpleUser->setPassword($this->encoder->encodePassword($simpleUser, 'michelmichel'));
        $manager->persist($simpleUser);

        $manager->flush();
    }

}
