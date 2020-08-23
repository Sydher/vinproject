<?php

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PostFixtures
 * @package App\DataFixtures
 */
class RegionFixtures extends Fixture {

    public function load(ObjectManager $manager) {
        $region1 = new Region();
        $region1->setName("Bordeaux");
        $manager->persist($region1);
        $region2 = new Region();
        $region2->setName("Bourgogne");
        $manager->persist($region2);
        $region3 = new Region();
        $region3->setName("Vallée du Rhône");
        $manager->persist($region3);
        $region4 = new Region();
        $region4->setName("Languedoc-Roussillon");
        $manager->persist($region4);

        $manager->flush();
    }

}
