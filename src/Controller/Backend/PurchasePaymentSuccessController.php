<?php

namespace App\Controller\Backend;

use App\Entity\Purchase;
use App\Services\Cart\CartService;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentSuccessController extends AbstractController
{
    #[Route('/purchase/terminate/{id}', name: 'purchase_payment_success')]
    #[IsGranted('ROLE_USER')]
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService)
    {
        $purchase = $purchaseRepository->find($id);

        if (
            !$purchase ||
            ($purchase && $purchase->getUser() !== $this->getUser()) ||
            ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)
        ) {
            $this->addFlash("warning", "La commande n'existe pas");

            return $this->redirectToRoute('purchases_index');
        }

        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();

        $cartService->empty();

        $this->addFlash("success", "La commande à été payée et confimée");

        return $this->redirectToRoute('purchases_index');
    }
}
