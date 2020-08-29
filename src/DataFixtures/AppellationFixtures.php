<?php

namespace App\DataFixtures;

use App\Entity\Appellation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Generator;

/**
 * Class AppellationFixtures
 * @package App\DataFixtures
 */
class AppellationFixtures extends Fixture implements DependentFixtureInterface {

    /** @var int */
    private $cpt;

    public function load(ObjectManager $manager) {
        gc_collect_cycles();
        $faker = Faker\Factory::create('fr_FR');
        $this->cpt = 1;

        for ($i = 0; $i < 15; $i++) {
            $fakeWine = $this->getFakeAppellation($faker);
            $manager->persist($fakeWine);
        }

        $manager->flush();
    }

    private function getFakeAppellation(Generator $faker): Appellation {
        $fakeAppellation = new Appellation();
        $fakeAppellation->setName($faker->text($this->getRandomSize())
            . $faker->randomLetter . $faker->randomNumber());
        $fakeAppellation->setRegion($this->getReference($this->getRandomRegion()));
        $fakeAppellation->setDescription($faker->sentence(20));
        $this->addReference('appellation_' . $this->cpt, $fakeAppellation);
        $this->cpt++;
        return $fakeAppellation;
    }

    private function getRandomRegion() {
        return 'region_' . rand(1, 4);
    }

    private function getRandomSize(): int {
        return rand(12, 20);
    }

    public function getDependencies() {
        return [RegionFixtures::class];
    }

}
