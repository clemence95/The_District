<?php

// src/Controller/PanierController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        // Ajoutez votre logique pour afficher le contenu du panier
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    #[Route('/panier/ajout/{id}', name: 'app_panier_ajout')]
    public function ajout(int $id): Response
    {
        // Ajoutez votre logique pour ajouter un plat au panier
        return $this->redirectToRoute('app_panier');
    }
}

