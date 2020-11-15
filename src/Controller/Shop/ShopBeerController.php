<?php

namespace App\Controller\Shop;

use App\Controller\AbstractController;
use App\Repository\BeerRepository;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopBeerController extends AbstractController {

    /**
     * @var BeerRepository
     */
    private $beerRepository;

    /**
     * @var CartService
     */
    private $cartService;

    public function __construct(BeerRepository $beerRepository,
                                CartService $cartService) {
        $this->beerRepository = $beerRepository;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/boutique/bieres", name="shop_beer_list")
     * @return Response
     */
    public function index(): Response {
        $allBeer = $this->beerRepository->findAll();
        return $this->render('shop/beer/index.html.twig', [
            'allBeer' => $allBeer,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route(
     *     "/boutique/biere/{id}",
     *     requirements={"id": "[0-9]*"}
     * )
     * @Route(
     *     "/boutique/biere/{id}-{slug}",
     *     name="shop_beer",
     *     requirements={"id": "[0-9]*"}
     * )
     * @param string $id
     * @return Response
     */
    public function showBeer(string $id): Response {
        $beer = $this->beerRepository->find($id);
        $quantity = $this->cartService->getItem("beer-" . $id);
        return $this->render('shop/beer/show.html.twig', [
            'beer' => $beer,
            'quantity' => $quantity,
            'menu' => 'boutique'
        ]);
    }

}
