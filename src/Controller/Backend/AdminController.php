<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin.home')]
    public function index(): Response
    {


        return $this->render('Backend/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
