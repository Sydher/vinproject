<?php

namespace App\Controller\Shop;

use App\Controller\AbstractController;
use App\Repository\AppellationRepository;
use App\Repository\ProductorRepository;
use App\Repository\RegionRepository;
use App\Repository\WineRepository;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopWineController extends AbstractController {

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

    /**
     * @var CartService
     */
    private $cartService;

    public function __construct(RegionRepository $regionRepository,
                                AppellationRepository $appellationRepository,
                                ProductorRepository $productorRepository,
                                WineRepository $wineRepository,
                                CartService $cartService) {
        $this->regionRepository = $regionRepository;
        $this->appellationRepository = $appellationRepository;
        $this->productorRepository = $productorRepository;
        $this->wineRepository = $wineRepository;
        $this->cartService = $cartService;
    }

    /**
     * @Route(
     *     "/boutique/region/{id}-{slug}",
     *     name="shop_region",
     *     requirements={"id": "[0-9]*"}
     * )
     * @Route(
     *     "/boutique/region/{id}",
     *     requirements={"id": "[0-9]*"}
     * )
     * @param string $id
     * @return Response
     */
    public function viewByRegion(string $id): Response {
        $region = $this->regionRepository->find($id);
        return $this->render('shop/wine/region.html.twig', [
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
     * @Route(
     *     "/boutique/appellation/{id}",
     *     requirements={"id": "[0-9]*"}
     * )
     * @param string $id
     * @return Response
     */
    public function viewByAppellation(string $id): Response {
        $appellation = $this->appellationRepository->find($id);
        return $this->render('shop/wine/appellation.html.twig', [
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
        return $this->render('shop/wine/productor.html.twig', [
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
        $quantity = $this->cartService->getItem("wine-" . $id);
        return $this->render('shop/wine/show.html.twig', [
            'wine' => $wine,
            'quantity' => $quantity,
            'menu' => 'boutique'
        ]);
    }

}
