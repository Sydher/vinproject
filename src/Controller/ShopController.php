<?php

namespace App\Controller;

use App\Repository\AppellationRepository;
use App\Repository\ProductorRepository;
use App\Repository\RegionRepository;
use App\Repository\WineRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController {

    /**
     * @var RegionRepository
     */
    private $regionRepository;

    /**
     * @var AppellationRepository
     */
    private $appellationRepository;

    /**
     * @var ProductorRepository
     */
    private $productorRepository;

    /**
     * @var WineRepository
     */
    private $wineRepository;

    public function __construct(RegionRepository $regionRepository,
                                AppellationRepository $appellationRepository,
                                ProductorRepository $productorRepository,
                                WineRepository $wineRepository) {
        $this->regionRepository = $regionRepository;
        $this->appellationRepository = $appellationRepository;
        $this->productorRepository = $productorRepository;
        $this->wineRepository = $wineRepository;
    }

    /**
     * @Route("/boutique", name="shop_home")
     * @return Response
     */
    public function index(): Response {
        $wines = $this->wineRepository->findAll();
        return $this->render('shop/index.html.twig', [
            'wines' => $wines,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route(
     *     "/boutique/region/{id}-{slug}",
     *     name="shop_region",
     *     requirements={"id": "[0-9]*"}
     * )
     * @param string $id
     * @return Response
     */
    public function viewByRegion(string $id): Response {
        $region = $this->regionRepository->find($id);
        return $this->render('shop/region.html.twig', [
            'region' => $region,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route(
     *     "/boutique/appellation/{id}-{slug}",
     *     name="shop_appellation",
     *     requirements={"id": "[0-9]*"}
     * )
     * @param string $id
     * @return Response
     */
    public function viewByAppellation(string $id): Response {
        $appellation = $this->appellationRepository->find($id);
        return $this->render('shop/appellation.html.twig', [
            'appellation' => $appellation,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route(
     *     "/boutique/producteur/{id}-{slug}",
     *     name="shop_productor",
     *     requirements={"id": "[0-9]*"}
     * )
     * @param string $id
     * @return Response
     */
    public function viewByProductor(string $id): Response {
        $productor = $this->productorRepository->find($id);
        return $this->render('shop/productor.html.twig', [
            'productor' => $productor,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route(
     *     "/boutique/vin/{id}-{slug}",
     *     name="shop_wine",
     *     requirements={"id": "[0-9]*"}
     * )
     * @param string $id
     * @return Response
     */
    public function showWine(string $id): Response {
        $wine = $this->wineRepository->find($id);
        return $this->render('shop/wine.html.twig', [
            'wine' => $wine,
            'menu' => 'boutique'
        ]);
    }

}
