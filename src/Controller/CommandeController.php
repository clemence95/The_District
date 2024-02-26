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
use App\Entity\Plat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class CommandeController extends AbstractController
{
    #[Route('/valider-commande', name: 'commande_valider', methods: ['POST'])]
    public function validerCommande(
        Request $request,
        PanierService $panierService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ): Response {
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
            if (isset($panierItem['id'], $panierItem['quantite'])) {
                $prix = floatval($panierItem['prix']); // ou (float)$panierItem['plat']['prix']
                $quantite = $panierItem['quantite'];
                $totalPrixPlats += $prix * $quantite;
            } else {
                // Gérez le cas où une clé est manquante
            }
        }

        // Votre logique pour valider la commande
        if ($form->isSubmitted() && $form->isValid()) {
            // ...

            // Créez une nouvelle commande
            $commande = new Commande();
            $commande->setDateCommande(new \DateTime());
            $commande->setTotal($totalPrixPlats);
            $commande->setEtat(0); // état initial de la commande
            $commande->setUtilisateur($this->getUser()); // Assurez-vous que vous avez un utilisateur connecté

            $entityManager->persist($commande);
            $entityManager->flush();

            // Utilisez l'ID de la commande pour lier les détails de la commande
            foreach ($panier as $panierItem) {
                // Assurez-vous que les clés 'plat' et 'quantite' existent dans $panierItem
                if (isset($panierItem['id'], $panierItem['quantite'])) {
                    $plat = $entityManager->getRepository(Plat::class)->find($panierItem['id']);
                    $detailsCommande = new Detail();
                    $detailsCommande->setCommande($commande);
                    $detailsCommande->setPlat($plat);
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

            // EventService confirmation commande
            $event = new \App\Events\OrderConfirmedEvent($commande);
            $eventDispatcher->dispatch($event, \App\Events\OrderConfirmedEvent::class);
            
            //Redirection message flash page d'accueil
            $this->addFlash('commande-ok', 'Commande enregistrée avec succès');

            return $this->redirectToRoute('app_home');
        }
            
        //Retourne le panier, le formulaire et le totale des prix sur la page commande
        return $this->render('commande/index.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
            'totalPrixPlats' => $totalPrixPlats,
        ]);
    }

    // ... autres actions de votre contrôleur
}
