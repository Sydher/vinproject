<?php

namespace App\DataFixtures;

use App\Entity\Food;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

/**
 * Class FoodFixtures
 * @package App\DataFixtures
 */
class FoodFixtures extends Fixture {

    public function load(ObjectManager $manager) {
        $faker = Faker\Factory::create('fr_FR');

        $food1 = new Food();
        $food1->setName("Foie Gras d'Oie Sudreau 500g");
        $food1->setDescription($faker->sentence(20));
        $food1->setPrice("18.27");
        $food1->setStock(8);
        $manager->persist($food1);

        $food2 = new Food();
        $food2->setName("Foie Gras de Canard Sudreau 500g");
        $food2->setDescription($faker->sentence(20));
        $food2->setPrice("20.27");
        $food2->setStock(12);
        $manager->persist($food2);

        $food3 = new Food();
        $food3->setName("Foie Gras Sudreau 250g");
        $food3->setDescription($faker->sentence(20));
        $food3->setPrice("14.27");
        $food3->setStock(20);
        $manager->persist($food3);

        $manager->flush();
    }

}
