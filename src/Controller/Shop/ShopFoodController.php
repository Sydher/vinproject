<?php

namespace App\Controller\Shop;

use App\Controller\AbstractController;
use App\Repository\FoodRepository;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopFoodController extends AbstractController {

    /**
     * @var FoodRepository
     */
    private $foodRepository;

    /**
     * @var CartService
     */
    private $cartService;

    public function __construct(FoodRepository $foodRepository,
                                CartService $cartService) {
        $this->foodRepository = $foodRepository;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/boutique/alimentations", name="shop_food_list")
     * @return Response
     */
    public function index(): Response {
        $allFood = $this->foodRepository->findAll();
        return $this->render('shop/food/index.html.twig', [
            'allFood' => $allFood,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route(
     *     "/boutique/alimentation/{id}",
     *     requirements={"id": "[0-9]*"}
     * )
     * @Route(
     *     "/boutique/alimentation/{id}-{slug}",
     *     name="shop_food",
     *     requirements={"id": "[0-9]*"}
     * )
     * @param string $id
     * @return Response
     */
    public function show(string $id): Response {
        $food = $this->foodRepository->find($id);
        $quantity = $this->cartService->getItem("food-" . $id);
        return $this->render('shop/food/show.html.twig', [
            'food' => $food,
            'quantity' => $quantity,
            'menu' => 'boutique'
        ]);
    }

}
