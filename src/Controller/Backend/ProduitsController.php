<?php

namespace App\Controller\Backend;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/admin/produits')]
class ProduitsController extends AbstractController
{
    public function __construct(
        private ProduitsRepository $repoProduits
    ) {
    }

    #[Route('', name: 'produits.home')]
    public function index(): Response
    {
        $produits = $this->repoProduits->findAll();

        return $this->render('Backend/Produits/home.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/create', name: 'produits.create')]
    public function createProduits(Request $request): Response|RedirectResponse
    {
        $produit = new Produits();

        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->repoProduits->save($produit, true);
            $this->addFlash('success', 'Produits crée avec succès !');
            return $this->redirectToRoute('produits.home');
        }

        return $this->renderForm('Backend/Produits/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'produits.edit', methods: 'GET|POST')]
    public function editProduits(Request $request, ?Produits $produits): Response|RedirectResponse
    {
        if (!$produits instanceof Produits) {
            $this->addFlash('error', 'Produits non trouvé');

            return $this->redirectToRoute('produits.home');
        }

        $form = $this->createForm(ProduitsType::class, $produits);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->repoProduits->save($produits, true);
            $this->addFlash('success', 'Produits modifiés avec succès !');

            return $this->redirectToRoute('produits.home');
        }

        return $this->renderForm('Backend/Produits/edit.html.twig', [
            'form' => $form,
            'produits' => $produits
        ]);
    }

    #[Route('/delete/{id}', name: 'produits.delete', methods: 'POST|DELETE')]
    public function deleteProduits(Request $request, ?Produits $produits): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produits->getId(), $request->request->get('_token'))) {
            $this->repoProduits->remove($produits, true);

            $this->addFlash('success', 'Produits deleted succès !');

            return $this->redirectToRoute('produits.home');
        }

        $this->addFlash('error', 'Token invalide !');

        return $this->redirectToRoute('produits.home', [], Response::HTTP_SEE_OTHER);
    }
}
