<?php

namespace App\DataFixtures;

use App\Entity\Beer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class BeerFixtures
 * @package App\DataFixtures
 */
class BeerFixtures extends Fixture {

    public function load(ObjectManager $manager) {
        $beer1 = new Beer();
        $beer1->setName("Ratz Blonde 75cl");
        $beer1->setDegree("4%");
        $beer1->setIngredients("Eau, malt d’orge, houblon et levure.");
        $beer1->setDescription("Légère et rafraîchissante, cette bière artisanale vous étonnera par sa large gamme d'arômes en harmonie avec cette douce amertume. À travers son élégante robe dorée, se dévoile un pétillant naturel issu de sa fermentation haute. Brassée par l'infusion de pur malt légèrement touraillé et de houblon rigoureusement sélectionné pour ses arômes, cette blonde artisanale vous fera découvrir une autre vision de la bière.");
        $beer1->setPrice("3.5");
        $beer1->setStock(10);
        $manager->persist($beer1);

        $beer2 = new Beer();
        $beer2->setName("Ratz Brune 75cl");
        $beer2->setDegree("6%");
        $beer2->setIngredients("Eau, malt d’orge, houblon et levure.");
        $beer2->setDescription("Mystérieuse et originale, cette bière artisanale vous surprendra par sa large gamme d'arômes aux notes subtiles de café. Sous sa robe si sombre se dévoile un pétillant naturel issu de sa fermentation haute. Brassée par l'infusion de pur malt hautement torréfié et de houblon aromatique, cette brune artisanale vous fera découvrir un autre univers de la bière.");
        $beer2->setPrice("5.5");
        $beer2->setStock(8);
        $manager->persist($beer2);

        $beer3 = new Beer();
        $beer3->setName("Ratz Bière de Noël 75cl");
        $beer3->setDegree("4%");
        $beer3->setIngredients("Eau, malt d’orge, houblon, épices et levure.");
        $beer3->setDescription("Spécialement brassée pour les fêtes de fin d’année, la Bière de Noël permet de passer de grands moments de convivialité... \"En été, brasse qui peut ; en hiver, brasse qui veut.\" La Bière de Noël : une tradition brassicole ancestrale, rythmée par les saisons et les moissons. Autrefois, les températures plus fraîches de l'automne devenaient favorables au premier brassage des récoltes d'orge de l'été. Cette bière pouvait mûrir en cave pendant plusieurs semaines pour acquérir ses arômes si particuliers qui en font la Bière de Noël. Surprenante par sa robe cuivrée et ses notes épicées, la Bière de Noël saura vous enchanter !");
        $beer3->setPrice("5.3");
        $beer3->setStock(80);
        $manager->persist($beer3);

        $beer4 = new Beer();
        $beer4->setName("Ratz Pepita de lemon 33cl");
        $beer4->setDegree("5%");
        $beer4->setIngredients("Eau, malt d’orge, houblon, levure et jus de citron naturel.");
        $beer4->setDescription("Pepita de lemon est brassée spécialement pour répondre à un nouveau public à la recherche d’une bière originale et fruitée pour se désaltérer toute l’année. Blonde aux reflets dorés, agrémentée de jus de citron naturel, Pepita de lemon vous surprendra agréablement par ce subtil mélange rafraîchissant et acidulé.");
        $beer4->setPrice("4");
        $beer4->setStock(3);
        $manager->persist($beer4);

        $manager->flush();
    }

}
