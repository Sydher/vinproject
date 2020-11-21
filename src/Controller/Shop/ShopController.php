<?php

namespace App\Controller\Shop;

use App\Controller\AbstractController;
use App\Repository\BeerRepository;
use App\Repository\FoodRepository;
use App\Repository\WineRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController {

    /**
     * @Route("/boutique", name="shop_home")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param WineRepository $wineRepository
     * @param BeerRepository $beerRepository
     * @param FoodRepository $foodRepository
     * @return Response
     */
    public function index(Request $request,
                          PaginatorInterface $paginator,
                          WineRepository $wineRepository,
                          BeerRepository $beerRepository,
                          FoodRepository $foodRepository): Response {
        $wines = $paginator->paginate(
            $wineRepository->findAllQuery(),
            $request->query->getInt('page', 1),
            10
        );
        $beers = $paginator->paginate(
            $beerRepository->findAllQuery(),
            $request->query->getInt('page', 1),
            10
        );
        $foods = $paginator->paginate(
            $foodRepository->findAllQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('shop/index.html.twig', [
            'menu' => 'boutique',
            'wines' => $wines,
            'foods' => $foods,
            'beers' => $beers
        ]);
    }

}
