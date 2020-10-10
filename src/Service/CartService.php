<?php

namespace App\Service;

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

    public function __construct(SessionInterface $session,
                                WineRepository $wineRepository) {
        $this->session = $session;
        $this->wineRepository = $wineRepository;
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
            $product = $this->getProductWithId($id);
            $total += $product->getPrice() * $quantity;
            $items[] = [
                'product' => $product,
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

    private function getProductWithId($idProduct) {
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
