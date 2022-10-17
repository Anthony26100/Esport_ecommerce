<?php

namespace App\Controller\Backend;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories')]
class CategoriesController extends AbstractController
{
    public function __construct(
        private CategoriesRepository $repoCategories
    ) {
    }

    #[Route('', name: 'categories.home', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        $categories = $this->repoCategories->findAll();

        return $this->render('Categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/create', name: 'categories.create', methods: ['GET', 'POST'])]
    public function createCategories(Request $request): Response|RedirectResponse
    {
        $category = new Categories();

        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->repoCategories->save($category, true);
            $this->addFlash('success', 'Catégories crée avec succès !');

            return $this->redirectToRoute('categories.home');
        }

        return $this->renderForm('Categories/create.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categories_show', methods: ['GET'])]
    public function show(Categories $category): Response
    {
        return $this->render('Categories/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_categories_edit', methods: ['GET', 'POST'])]
    public function editCategories(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriesRepository->save($category, true);

            return $this->redirectToRoute('categories.home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Categories/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_categories_delete', methods: ['POST'])]
    public function deleteCategories(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categoriesRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
    }

    // Switch Visibility Categories 
    #[Route('/switch/{id}', name: 'produits.switchvisibility', methods: 'GET')]
    public function switchVisibilityProduits(?Categories $categories)
    {
        if (!$categories instanceof Categories) {

            return new Response('Produits non trouvé', 404);
        }

        if ($categories) {
            $categories->isActive() ? $categories->setActive(false) : $categories->setActive(true);
            $this->categoriesRepository->save($categories, true);

            return new Response('Visibility changée !', 200);
        }
    }
}
