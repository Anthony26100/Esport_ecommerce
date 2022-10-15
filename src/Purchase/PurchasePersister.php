<?php

namespace App\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Services\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePersister extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private EntityManagerInterface $em,
    ) {
    }

    public function storePurchase(Purchase $purchase)
    {
        // 1. Nous allons la lier la purchase avec l'utilisateur actuellement connecté (Security)
        $purchase->setUser($this->getUser())
            ->setPurchasedAt(new DateTime())
            ->setTotal($this->cartService->getTotal());

        $this->em->persist($purchase);

        // 2. Nous allons la lier avec les produits qui sont dans le panier (CartService)
        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
                ->setProduits($cartItem->product)
                ->setProduitsName($cartItem->product->getTitre())
                ->setQuantity($cartItem->quantity)
                ->setProduitsPrice($cartItem->product->getPrix())
                ->setTotal($cartItem->getTotal());

            $this->em->persist($purchaseItem);
        }

        // 3. Nous allons enregistrer la commande
        $this->em->flush();
    }
}
