<?php
// src/Controller/CommandeController.php

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

        // Récupérez le panier
        $panier = $panierService->getPanier();

        // Calculez le total du prix des plats dans le panier
        $totalPrixPlats = 0;
        foreach ($panier as $plat) {
            $totalPrixPlats += $plat['prix'] * $plat['quantite'];
        }

        // Votre logique pour valider la commande

        return $this->render('commande/index.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
            'totalPrixPlats' => $totalPrixPlats, // Transmettez le total du prix des plats au modèle
        ]);
    }

    // ... autres actions de votre contrôleur
}







