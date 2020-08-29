<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Generator;

/**
 * Class PostFixtures
 * @package App\DataFixtures
 */
class PostFixtures extends Fixture implements DependentFixtureInterface {

    public function load(ObjectManager $manager) {
        $faker = Faker\Factory::create('fr_FR');

        // Articles classiques
        for ($i = 0; $i < 25; $i++) {
            $fakePost = $this->getFakePostWithImage($faker);
            $manager->persist($fakePost);
        }

        // Articles spéciaux
        $fakePostNoImage1 = $this->getFakePost($faker);
        $manager->persist($fakePostNoImage1);
        $fakePostNoImage2 = $this->getFakePost($faker);
        $manager->persist($fakePostNoImage2);
        $fakePostNoImageInactif = $this->getFakePost($faker);
        $fakePostNoImageInactif->setIsVisible(false);
        $manager->persist($fakePostNoImageInactif);
        $fakePostInactif = $this->getFakePostWithImage($faker);
        $fakePostInactif->setIsVisible(false);
        $manager->persist($fakePostInactif);

        $manager->flush();
    }

    private function getFakePost(Generator $faker): Post {
        $fakePost = new Post();
        $fakePost->setTitle($faker->text($this->getRandomSize())
            . $faker->randomLetter . $faker->randomNumber());
        $fakePost->setDescription($faker->sentence(20));
        $fakePost->setAuthor($this->getReference($this->getRandomRef()));
        $fakePost->setContent($faker->sentence(500));
        $fakePost->setCreatedAt($faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now'));
        $fakePost->setUpdatedAt($faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now'));
        $fakePost->setIsVisible(true);
        return $fakePost;
    }

    private function getFakePostWithImage(Generator $faker): Post {
        $fakePost = $this->getFakePost($faker);
        $fakePost->setImageName($faker->image('public/storage/images/post',
            400, 300, null, false));
        return $fakePost;
    }

    private function getRandomRef(): string {
        return 'user_' . rand(1, 3);
    }

    private function getRandomSize(): int {
        return rand(10, 20);
    }

    public function getDependencies() {
        return [UserFixtures::class];
    }

}
