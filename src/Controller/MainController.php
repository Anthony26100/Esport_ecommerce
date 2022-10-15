<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProduitsRepository $repoProduits): Response
    {
        $produits = $repoProduits->findAll();


        return $this->render('Home/index.html.twig', [
            'produits' => $produits,
        ]);
    }
}
