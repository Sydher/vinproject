<?php

namespace App\DataFixtures;

use App\Entity\Wine;
use App\Enum\WineColors;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Generator;

/**
 * Class WineFixtures
 * @package App\DataFixtures
 */
class WineFixtures extends Fixture implements DependentFixtureInterface {

    public function load(ObjectManager $manager) {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 25; $i++) {
            $fakeWine = $this->getFakeWine($faker);
            $manager->persist($fakeWine);
        }

        $manager->flush();
    }

    private function getFakeWine(Generator $faker): Wine {
        $fakeWine = new Wine();
        $fakeWine->setName($faker->text($this->getRandomSize())
            . $faker->randomLetter . $faker->randomNumber());
        $fakeWine->setRegion($this->getReference($this->getRandomRegion()));
        $fakeWine->setAppellation($this->getReference($this->getRandomAppellation()));
        $fakeWine->setProductor($this->getReference($this->getRandomProductor()));
        $fakeWine->setColor($this->getRandomColor());
        $fakeWine->setYear($faker->year('now'));
        $fakeWine->setFormat('Bouteille (' . $faker->numberBetween(25, 100) . ' cl)');
        $fakeWine->setPrice($faker->randomFloat(2, 8, 200));
        $fakeWine->setStock($faker->numberBetween(2, 20));
        $fakeWine->setDegree("4%");
        return $fakeWine;
    }

    private function getRandomRegion() {
        return 'region_' . rand(1, 4);
    }

    private function getRandomAppellation() {
        return 'appellation_' . rand(1, 15);
    }

    private function getRandomProductor() {
        return 'productor_' . rand(1, 15);
    }

    private function getRandomColor() {
        return WineColors::getColors()[rand(0, 2)];
    }

    private function getRandomSize(): int {
        return rand(12, 20);
    }

    public function getDependencies() {
        return [RegionFixtures::class, AppellationFixtures::class, ProductorFixtures::class];
    }

}
