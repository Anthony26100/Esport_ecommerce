<?php

namespace App\Controller\Backend;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasesListController extends AbstractController
{
    #[Route('/purchases', name: 'purchases_index')]
    #[IsGranted('ROLE_USER', message: "Vous devez être connecté pour accéder à vos commandes")]
    public function index(): Response
    {
        // 1. S'assurer que la personne est connecté sinon redirection vers la page d'accueil
        /**
         * @var User
         */
        $user = $this->getUser();

        // 2. Nous voulons savoir qui est connecté 

        // 3. Nous voulons passer l'utilisateur connecté à twig afin d'afficher ses commandes 
        return $this->render('Backend/purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }
}
