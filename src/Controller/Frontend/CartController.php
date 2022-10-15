<?php

namespace App\Controller\Frontend;

use App\Form\CartConfirmationType;
use App\Services\Cart\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_show')]
    public function show(CartService $cartService): Response
    {
        $form = $this->createForm(CartConfirmationType::class);

        $detailedCart = $cartService->getDetailedCartItems();

        $total = $cartService->getTotal();

        return $this->render('Frontend/cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total,
            'confirmationForm' => $form->createView(),
        ]);
    }
}
