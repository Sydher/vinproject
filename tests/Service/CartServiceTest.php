<?php

namespace App\Tests\Service;

use App\Repository\BeerRepository;
use App\Repository\FoodRepository;
use App\Repository\WineRepository;
use App\Service\CartService;
use App\Tests\AbstractTest;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartServiceTest extends AbstractTest {

    /* ***** Mocks ***** */

    /**
     * @var WineRepository|MockObject
     */
    private $mockWineRepository;

    /**
     * @var BeerRepository|MockObject
     */
    private $mockBeerRepository;

    /**
     * @var FoodRepository|MockObject
     */
    private $mockFoodRepository;

    /* ***** Initialisation ***** */

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var CartService
     */
    private $cartService;

    protected function setUp() {
        parent::setUp();
        $this->session = new Session(new MockArraySessionStorage());
        $this->mockWineRepository = $this->createMock(WineRepository::class);
        $this->mockBeerRepository = $this->createMock(BeerRepository::class);
        $this->mockFoodRepository = $this->createMock(FoodRepository::class);

        $this->cartService = new CartService($this->session,
            $this->mockWineRepository,
            $this->mockBeerRepository,
            $this->mockFoodRepository);
    }

    /* ***** Tests ***** */

    /**
     * @test add
     * @case le panier n'existe pas, on y ajoute un produit
     */
    public function addDansPanierNullTest() {
        // given
        $this->session->set('panier', null);

        // when
        $resultat = $this->cartService->add("wine-3");

        // then
        $this->assertEquals(1, $resultat);
        $this->assertEquals(["wine-3" => 1], $this->session->get('panier'));
    }

    /**
     * @test add
     * @case le panier est vide, on y ajoute un produit
     */
    public function addDansPanierVideTest() {
        // given
        $this->session->set('panier', []);

        // when
        $resultat = $this->cartService->add("wine-3");

        // then
        $this->assertEquals(1, $resultat);
        $this->assertEquals(["wine-3" => 1], $this->session->get('panier'));
    }

    /**
     * @test add
     * @case le panier contient des produits mais pas celui qu'on ajoute
     */
    public function addDansPanierAutreProduitTest() {
        // given
        $this->session->set('panier', ["food-3" => 4, "wine-1" => 2]);

        // when
        $resultat = $this->cartService->add("wine-3");

        // then
        $this->assertEquals(1, $resultat);
        $this->assertEquals(["food-3" => 4, "wine-1" => 2, "wine-3" => 1], $this->session->get('panier'));
    }

    /**
     * @test add
     * @case le panier contient des produits dont celui qu'on ajoute
     */
    public function addDansPanierDejaPresentTest() {
        // given
        $this->session->set('panier', ["food-2" => 4, "wine-7" => 2]);

        // when
        $resultat = $this->cartService->add("wine-7");

        // then
        $this->assertEquals(3, $resultat);
        $this->assertEquals(["food-2" => 4, "wine-7" => 3], $this->session->get('panier'));
    }

    /**
     * @test remove
     * @case le panier n'existe pas, on y retire un produit
     */
    public function removeDansPanierNullTest() {
        // given
        $this->session->set('panier', null);

        // when
        $resultat = $this->cartService->remove("wine-3");

        // then
        $this->assertEquals(0, $resultat);
        $this->assertEquals(null, $this->session->get('panier'));
    }

    /**
     * @test remove
     * @case le panier est vide, on y retire un produit
     */
    public function removeDansPanierVideTest() {
        // given
        $this->session->set('panier', []);

        // when
        $resultat = $this->cartService->remove("wine-3");

        // then
        $this->assertEquals(0, $resultat);
        $this->assertEquals([], $this->session->get('panier'));
    }

    /**
     * @test remove
     * @case le panier possède plusieurs produits mais pas celui qu'on retire
     */
    public function removeDansPanierAvecAutreProduitTest() {
        // given
        $this->session->set('panier', ["food-3" => 4, "wine-1" => 2]);

        // when
        $resultat = $this->cartService->remove("wine-3");

        // then
        $this->assertEquals(0, $resultat);
        $this->assertEquals(["food-3" => 4, "wine-1" => 2], $this->session->get('panier'));
    }

    /**
     * @test remove
     * @case le panier possède plusieurs produits dont celui qu'on retire
     */
    public function removeDansPanierDejaPresentTest() {
        // given
        $this->session->set('panier', ["food-3" => 4, "wine-3" => 2]);

        // when
        $resultat = $this->cartService->remove("wine-3");

        // then
        $this->assertEquals(1, $resultat);
        $this->assertEquals(["food-3" => 4, "wine-3" => 1], $this->session->get('panier'));
    }

    /**
     * @test remove
     * @case on retire la dernière unité d'un produit
     */
    public function removePassageAZeroTest() {
        // given
        $this->session->set('panier', ["food-3" => 4, "wine-3" => 1]);

        // when
        $resultat = $this->cartService->remove("wine-3");

        // then
        $this->assertEquals(0, $resultat);
        $this->assertEquals(["food-3" => 4], $this->session->get('panier'));
    }

}