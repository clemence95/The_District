<?php
// src/Controller/CommandeController.php

namespace App\Controller;

use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CommandeController extends AbstractController
{
    #[Route('/commande/afficher-formulaire', name: 'commande_afficher_formulaire')]
    public function afficherFormulaire(PanierService $panierService): Response
    {
        // Récupérez le panier depuis votre service PanierService
        $panier = $panierService->getPanier();

        return $this->render('commande/index.html.twig', ['panier' => $panier]);
    }

    // ... autres actions de votre contrôleur
}







