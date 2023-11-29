<?php
// src/Controller/CommandeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PanierService;
use App\Form\CommandeType;
use App\Entity\Detail;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;

// src/Controller/CommandeController.php


class CommandeController extends AbstractController
{
    #[Route('/valider-commande', name: 'commande_valider', methods: ['POST'])]
    public function validerCommande(Request $request, PanierService $panierService, EntityManagerInterface $entityManager): Response
    {
        // Créez une instance du formulaire
        $form = $this->createForm(CommandeType::class);

        // Traitez le formulaire s'il a été soumis
        $form->handleRequest($request);

        // Récupérez le panier
        $panier = $panierService->getPanier();

        // Calculez le total du prix des plats dans le panier
        $totalPrixPlats = 0;
        foreach ($panier as $panierItem) {
            // Assurez-vous que les clés 'plat' et 'quantite' existent dans $panierItem
            if (isset($panierItem['plat'], $panierItem['quantite'])) {
                $prix = floatval($panierItem['plat']['prix']); // ou (float)$panierItem['plat']['prix']
                $quantite = $panierItem['quantite'];
                $totalPrixPlats += $prix * $quantite;
            } else {
                // Gérez le cas où une clé est manquante
            }
        }
        
        // Votre logique pour valider la commande
        if ($form->isSubmitted() && $form->isValid()) {
            // Créez une nouvelle commande
            $commande = new Commande();
            $commande->setDateCommande(new \DateTime());
            $commande->setTotal($totalPrixPlats);
            $commande->setEtat(0); // Mettez l'état initial de la commande
            $commande->setUtilisateur($this->getUser()); // Assurez-vous que vous avez un utilisateur connecté

            $entityManager->persist($commande);
            $entityManager->flush();

            // Utilisez l'ID de la commande pour lier les détails de la commande
            foreach ($panier as $panierItem) {
                // Assurez-vous que les clés 'plat' et 'quantite' existent dans $panierItem
                if (isset($panierItem['plat'], $panierItem['quantite'])) {
                    $detailsCommande = new Detail();
                    $detailsCommande->setCommande($commande);
                    $detailsCommande->setPlat($panierItem['plat']);
                    $detailsCommande->setQuantite($panierItem['quantite']);
                    // ... autres propriétés des détails de la commande

                    $entityManager->persist($detailsCommande);
                } else {
                    // Gérez le cas où une clé est manquante
                    // Log ou affichez un message d'erreur selon votre besoin
                    // Vous pouvez également ajouter une redirection vers une page d'erreur
                }
            }
            
            $entityManager->flush();

            // Effacez le panier après la validation de la commande
            $panierService->viderPanier();

            // Redirigez l'utilisateur vers une page de confirmation ou une autre page appropriée
            return $this->redirectToRoute('app_home');
        }

        return $this->render('commande/index.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
            'totalPrixPlats' => $totalPrixPlats,
        ]);
    }

    // ... autres actions de votre contrôleur
}
