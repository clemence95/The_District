<?php
// src/Controller/CommandeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PanierService;
use App\Form\CommandeType;

class CommandeController extends AbstractController
{
    #[Route('/valider-commande', name: 'commande_valider', methods: ['POST'])]
    public function validerCommande(Request $request, PanierService $panierService): Response
    {
        // Créez une instance du formulaire
        $form = $this->createForm(CommandeType::class);

        // Traitez le formulaire s'il a été soumis
        $form->handleRequest($request);

        // Votre logique pour valider la commande

        return $this->render('commande/index.html.twig', [
            'panier' => $panierService->getPanier(),
            'form' => $form->createView(), // Transmettez le formulaire au modèle
        ]);
    }
    // ... autres actions de votre contrôleur
}







