<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController {

    /**
     * @var CartService
     */
    private $cartService;

    public function __construct(CartService $cartService) {
        $this->cartService = $cartService;
    }

    /**
     * @Route("/panier", name="cart_home")
     * @return Response
     */
    public function index(): Response {
        $cart = $this->cartService->getCart();
        return $this->render('cart/index.html.twig', [
            'items' => $cart['items'],
            'total' => $cart['total'],
            'menu' => 'panier'
        ]);
    }

    /**
     * @Route("/panier/ajouter/{id}", name="cart_add")
     * @param string $id
     * @return Response
     */
    public function add(string $id): Response {
        $quantity = $this->cartService->add($id);
        return $this->json([
            'code' => 200,
            'message' => $id.' : +1 dans le panier',
            'quantity' => $quantity
        ], 200);
    }

    /**
     * @Route("/panier/enlever/{id}", name="cart_remove")
     * @param string $id
     * @return Response
     */
    public function remove(string $id): Response {
        $quantity = $this->cartService->remove($id);
        return $this->json([
            'code' => 200,
            'message' => $id.' : -1 dans le panier',
            'quantity' => $quantity
        ], 200);
    }

    /**
     * @Route("/panier/ajouter-au-panier/{id}", name="cart_add_redirect")
     * @param string $id
     * @return Response
     */
    public function addToCart(string $id): Response {
        $this->cartService->add($id);
        return $this->redirectToRoute('cart_home');
    }

    /**
     * @Route("/panier/enlever-du-panier/{id}", name="cart_remove_redirect")
     * @param string $id
     * @return Response
     */
    public function removeToCart(string $id): Response {
        $this->cartService->remove($id);
        return $this->redirectToRoute('cart_home');
    }

    /**
     * @Route("/panier/supprimer/{id}", name="cart_delete")
     * @param string $id
     * @return Response
     */
    public function delete(string $id): Response {
        $this->cartService->delete($id);
        return $this->redirectToRoute('cart_home');
    }

}