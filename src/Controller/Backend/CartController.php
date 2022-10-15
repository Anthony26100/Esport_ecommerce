<?php

namespace App\Controller\Backend;

use App\Services\Cart\CartService;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    public function __construct(
        private ProduitsRepository $productRepository,
        private CartService $cartService
    ) {
    }

    #[Route('/cart/add/{id}', name: 'cart_add', requirements: ['id' => '\d+'])]
    public function add(int $id, Request $request): Response
    {
        $product = $this->productRepository->find($id);

        // Sécurisation : est-ce que le produit existe ?
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

        $this->cartService->add($id);

        $this->addFlash('success', 'Le produit a bien été ajouté au panier');

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }

        return $this->redirectToRoute('product_show', [
            'slug' => $product->getSlug(),
        ]);
    }

    #[Route('/cart/delete/{id}', name: 'cart_delete', requirements: ['id' => '\d+'])]
    public function delete($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                "Le produit $id n'existe pas et ne peut pas être supprimé !"
            );
        }

        $this->cartService->remove($id);

        $this->addFlash('success', 'Le produit a bien été supprimé du panier');

        return $this->redirectToRoute('cart_show');
    }

    #[Route('/cart/decrement/{id}', name: 'cart_decrement', requirements: ['id' => '\d+'])]
    public function decrement($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut pas être retiré !");
        }

        $this->cartService->decrement($id);

        $this->addFlash('success', 'Le produit a bien été décrémenté du panier');

        return $this->redirectToRoute('cart_show');
    }
}
