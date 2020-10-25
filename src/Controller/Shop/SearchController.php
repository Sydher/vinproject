<?php

namespace App\Controller\Shop;

use App\Controller\AbstractController;
use App\Entity\SearchProduct;
use App\Form\SearchProductForm;
use App\Repository\WineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController {

    /**
     * @var WineRepository
     */
    private $wineRepository;

    public function __construct(WineRepository $wineRepository) {
        $this->wineRepository = $wineRepository;
    }

    /**
     * @Route("/boutique/rechercher", name="shop_search")
     * @param Request $request la requête de recherche
     * @return Response
     */
    public function index(Request $request): Response {
        $search = new SearchProduct();
        $search->page = $request->get('page', 1);

        $form = $this->createForm(SearchProductForm::class, $search);
        $form->handleRequest($request);

        $wines = $this->wineRepository->findSearch($search);
        [$min, $max] = $this->wineRepository->findMinMax($search);

        return $this->render('shop/search/index.html.twig', [
            'menu' => 'boutique',
            'form' => $form->createView(),
            'wines' => $wines,
            'min' => $min,
            'max' => $max
        ]);
    }

}
