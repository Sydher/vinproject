<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

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
        $this->addReference('user_1', $superAdmin);

        $simpleAdmin = new User();
        $simpleAdmin->setUsername('titi');
        $simpleAdmin->setEmail('thierry@yopmail.com');
        $simpleAdmin->setPassword($this->encoder->encodePassword($simpleAdmin, 'foot'));
        $simpleAdmin->setIsVerified(true);
        $simpleAdmin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($simpleAdmin);
        $this->addReference('user_2', $simpleAdmin);

        $otherAdmin = new User();
        $otherAdmin->setUsername('CarClems');
        $otherAdmin->setEmail('carole@yopmail.com');
        $otherAdmin->setPassword($this->encoder->encodePassword($otherAdmin, 'hp'));
        $otherAdmin->setIsVerified(true);
        $otherAdmin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($otherAdmin);
        $this->addReference('user_3', $otherAdmin);

        $simpleUser = new User();
        $simpleUser->setUsername('michel');
        $simpleUser->setEmail('michel@yopmail.com');
        $simpleUser->setPassword($this->encoder->encodePassword($simpleUser, 'michelmichel'));
        $simpleUser->setIsVerified(false);
        $manager->persist($simpleUser);

        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i++) {
            $fakeUser = new User();
            $fakeUser->setUsername($faker->userName);
            $fakeUser->setEmail($faker->email);
            $fakeUser->setPassword($this->encoder->encodePassword($superAdmin, $faker->password));
            $fakeUser->setIsVerified(true);
            $manager->persist($fakeUser);
        }

        $manager->flush();
    }

}
