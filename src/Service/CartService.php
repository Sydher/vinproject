<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductTrait;
use App\Entity\Wine;
use App\Repository\BeerRepository;
use App\Repository\FoodRepository;
use App\Repository\WineRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var WineRepository
     */
    private $wineRepository;

    /**
     * @var BeerRepository
     */
    private $beerRepository;

    /**
     * @var FoodRepository
     */
    private $foodRepository;

    public function __construct(SessionInterface $session,
                                WineRepository $wineRepository,
                                BeerRepository $beerRepository,
                                FoodRepository $foodRepository) {
        $this->session = $session;
        $this->wineRepository = $wineRepository;
        $this->beerRepository = $beerRepository;
        $this->foodRepository = $foodRepository;
    }

    /**
     * Récupère le panier (avec le total).
     * @return array [[Produit, Quantité], total]
     */
    public function getCart(): array {
        $panier = $this->session->get('panier', []);
        $items = [];
        $total = 0;

        foreach ($panier as $id => $quantity) {
            list($product, $type) = $this->getProductWithId($id);
            $total += $product->getPrice() * $quantity;
            $items[] = [
                'product' => $product,
                'type' => $type,
                'quantity' => $quantity
            ];
        }
        return [
            'items' => $items,
            'total' => $total
        ];
    }

    public function getItem($id) {
        $panier = $this->session->get('panier', []);
        if (isset($panier[$id])) {
            return $panier[$id];
        }
        return 0;
    }

    /**
     * Ajoute un produit au panier.
     * @param $id l'identifiant du produit à ajouter au panier
     */
    public function add($id): void {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $this->session->set('panier', $panier);
    }

    /**
     * Retire un produit du panier.
     * @param $id l'identifiant du produit à retirer du panier
     */
    public function remove($id): void {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }
        $this->session->set('panier', $panier);
    }

    /**
     * Supprime un produit du panier.
     * @param $id l'identifiant du produit à supprimer du panier
     */
    public function delete($id): void {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);
    }

    /**
     * @param $idProduct
     * @return array|null[]
     */
    private function getProductWithId($idProduct) {
        $productWithId = explode("-", $idProduct);
        switch ($productWithId[0]) {
            case "wine":
                return [
                    $this->wineRepository->find($productWithId[1]),
                    "wine"
                ];
                break;
            case "beer":
                return [
                    $this->beerRepository->find($productWithId[1]),
                    "beer"
                ];
                break;
            case "food":
                return [
                    $this->foodRepository->find($productWithId[1]),
                    "food"
                ];
                break;
        }
        return [null, null];
    }

    private function updateStock($idProduct) {
        // TODO
        $productWithId = explode("-", $idProduct);
        switch ($productWithId[0]) {
            case "wine":
                return $this->wineRepository->find($productWithId[1]);
                break;
            case "bear":
                return null;
                break;
        }
        return null;
    }

}
