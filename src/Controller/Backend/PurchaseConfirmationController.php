<?php

namespace App\Controller\Backend;

use DateTime;
use App\Entity\Purchase;
use App\Services\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private EntityManagerInterface $em,
        private PurchasePersister $persister
    ) {
    }

    #[Route("/purchase/confirm", name: 'purchase_confirm')]
    #[IsGranted('ROLE_USER', message: "Vous devez être connecté pour confirmer une commande")]
    public function confirm(Request $request)
    {
        // 1. Nous voulons lire les données du formulaire 
        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);

        // 2. Si le form n'a pas été soumis: redirection
        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');

            return $this->redirectToRoute('cart_show');
        }

        // 3. Si il n'y a pas de produits dans mon panier: redirection (CartService)
        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash('warning', 'Vous ne pouvez pas confirmer une commande avec un panier vide');

            return $this->redirectToRoute('cart_show');
        }

        // 4. Nous allons créer une purchase
        /** @var Purchase */
        $purchase = $form->getData();

        $this->persister->storePurchase($purchase);

        return $this->redirectToRoute('purchase_payment_form', [
            'id' => $purchase->getId()
        ]);
    }
}
