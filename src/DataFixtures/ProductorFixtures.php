<?php

namespace App\DataFixtures;

use App\Entity\Productor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Generator;

/**
 * Class ProductorFixtures
 * @package App\DataFixtures
 */
class ProductorFixtures extends Fixture implements DependentFixtureInterface {

    /** @var int */
    private $cpt;

    public function load(ObjectManager $manager) {
        $faker = Faker\Factory::create('fr_FR');
        $this->cpt = 1;

        for ($i = 0; $i < 15; $i++) {
            $fakeWine = $this->getFakeProductor($faker);
            $manager->persist($fakeWine);
        }

        $manager->flush();
    }

    private function getFakeProductor(Generator $faker): Productor {
        $fakeProductor = new Productor();
        $fakeProductor->setName($faker->text($this->getRandomSize())
            . $faker->randomLetter . $faker->randomNumber());
        $max = $this->getRandomNumber();
        for ($i = 1; $i <= $max; $i++) {
            $fakeProductor->addAppellation($this->getReference($this->getRandomAppellation()));
        }
        $fakeProductor->setDescription($faker->sentence(20));
        $this->addReference('productor_' . $this->cpt, $fakeProductor);
        $this->cpt++;
        return $fakeProductor;
    }

    private function getRandomAppellation() {
        return 'appellation_' . rand(1, 15);
    }

    private function getRandomNumber() {
        return rand(2, 4);
    }

    private function getRandomSize(): int {
        return rand(12, 20);
    }

    public function getDependencies() {
        return [AppellationFixtures::class];
    }

}
