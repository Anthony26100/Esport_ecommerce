<?php

namespace App\Controller\Frontend;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoriesRepository;
use App\Repository\ProductRepository;
use App\Repository\ProduitsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProduitsController extends AbstractController
{
  #[Route('/{slug}', name: 'product_category', priority: -1)]
  public function category(string $slug, CategoriesRepository $categoryRepository)
  {
    $category = $categoryRepository->findOneBy([
      'slug' => $slug
    ]);

    if (!$category) {
      throw $this->createNotFoundException("La catégorie demandée n'existe pas");
    }

    return $this->render('Frontend/product/category.html.twig', [
      'slug' => $slug,
      'category' => $category
    ]);
  }


  #[Route('/product/{slug}', name: 'product_show')]
  public function show(string $slug, ProduitsRepository $productRepository): Response
  {
    $produits = $productRepository->findOneBy([
      'slug' => $slug
    ]);

    if (!$produits) {
      throw $this->createNotFoundException("Le produit demandé n'existe pas");
    }

    return $this->render('Frontend/product/show.html.twig', [
      'slug' => $slug,
      'product' => $produits
    ]);
  }
}
