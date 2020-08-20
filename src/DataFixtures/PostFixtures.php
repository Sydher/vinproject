<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

/**
 * Class PostFixtures
 * @package App\DataFixtures
 */
class PostFixtures extends Fixture implements DependentFixtureInterface {

    public function load(ObjectManager $manager) {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 25; $i++) {
            $fakePost = new Post();
            $fakePost->setTitle($faker->realText(20));
            $fakePost->setTags($faker->colorName . ', ' . $faker->jobTitle . ', ' . $faker->city);
            $fakePost->setAuthor($this->getReference($this->getRandomRef()));
            $fakePost->setContent($faker->realText(500));
            $fakePost->setCreatedAt($faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now'));
            $fakePost->setUpdatedAt($faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now'));
            $fakePost->setImageName($faker->image('public/storage/images/post',
                400, 300, null, false));
            $manager->persist($fakePost);
        }

        $manager->flush();
    }

    private function getRandomRef() {
        return 'user_'.rand(1, 3);
    }

    public function getDependencies() {
        return [UserFixtures::class];
    }

}
