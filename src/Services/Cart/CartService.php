<?php

namespace App\Services\Cart;

use App\Services\Cart\CartItem;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartService extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private ProduitsRepository $productRepository,
    ) {
    }

    public function empty()
    {
        $this->requestStack->getSession()->remove('cart');
    }

    public function add(int $id)
    {
        // 1. Retrouver le panier dans la session sous forme de tableau
        // 2. Si il n'existe pas encore, alors prendre un tableau vide 
        $cart = $this->requestStack->getSession()->get('cart', []);

        // 3. Voir si le produit ($id) existe déjà dans le tableau
        // 4. Si il existe, augmenter la quantité
        // 5. Sinon, ajouter le produit avec la quantité 1 
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }

        $cart[$id]++;

        // 6. Enregistrer le produit mis à jour dans la session
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function remove(int $id)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);

        unset($cart[$id]);

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function decrement(int $id)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);

        if (!array_key_exists($id, $cart)) {
            return;
        }

        // Si le produit est à 1, alors il faut le supprimé
        if ($cart[$id] === 1) {
            return $this->remove($id);
        }

        // Si le produit est à plus de 1, alors il faut le décrémenter
        $cart[$id]--;

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $quantity) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += $product->getPrix() * $quantity;
        }

        return $total;
    }

    /** 
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];

        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $quantity) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $detailedCart[] = new CartItem($product, $quantity);
        }

        return $detailedCart;
    }
}
