<?php

// src/Controller/CommandeController.php

namespace App\Controller;

use App\Repository\PlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use App\Entity\Detail;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'commande')]
    public function passerCommande(Request $request, PlatsRepository $platsRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupère les éléments du panier depuis le service PanierService
        $panier = []; // Remplacez par la logique de récupération du panier

        $dateCommande = new \DateTime();
        $montantTotalPanier = 0;

        $nouvelleCommande = new Commande();

        foreach ($panier as $element) {
            $plat = $platsRepository->find($element['plat_id']);

            $montantTotalPanier += $plat->getPrix() * $element['quantity'];

            $detail = new Detail();
            $detail->setPlat($plat);
            $detail->setQuantite($element['quantity']);

            $nouvelleCommande->addDetail($detail);
        }

        $nouvelleCommande->setDateCommande($dateCommande);
        $nouvelleCommande->setTotal($montantTotalPanier);
        $nouvelleCommande->setEtat('En attente');

        $utilisateur = $this->getUser();
        if ($utilisateur) {
            $nouvelleCommande->setUtilisateur($utilisateur);
        }

        $entityManager->persist($nouvelleCommande);
        $entityManager->flush();

        // Redirige vers une page de confirmation ou une autre page appropriée
        return $this->redirectToRoute('confirmation_commande', ['id' => $nouvelleCommande->getId()]);
    }

    #[Route('/confirmation-commande/{id}', name: 'confirmation_commande')]
    public function confirmationCommande(Commande $commande): Response
    {
        // Affiche la page de confirmation avec les détails de la commande
        return $this->render('commande/confirmation.html.twig', ['commande' => $commande]);
    }
}



