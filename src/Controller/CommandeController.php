<?php

// src/Controller/CommandeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use App\Entity\Detail;
use App\Repository\PlatRepository; // Assurez-vous que cette ligne est présente

class CommandeController extends AbstractController
{
    #[Route('/valider-commande', name: 'valider_commande', methods: ['POST'])]
    public function validerCommande(Request $request, PlatRepository $platRepository, EntityManagerInterface $entityManager): Response
    {
        // Obtenez les éléments du panier depuis votre logique existante
        $panier = []; 

        // Initialisez le montant total du panier
        $montantTotalPanier = 0;

        // Créez une nouvelle instance de la classe Commande
        $nouvelleCommande = new Commande();

        // Parcourez chaque élément du panier
        foreach ($panier as $element) {
            // Récupérez le plat associé à l'élément du panier depuis le repository
            $plat = $platRepository->find($element['plat_id']);

            // Ajoutez le montant du plat multiplié par la quantité au total du panier
            $montantTotalPanier += $plat->getPrix() * $element['quantity'];

            // Créez une nouvelle instance de la classe Detail
            $detail = new Detail();

            // Configurez les propriétés du détail (relation entre Commande et Plats)
            $detail->setPlat($plat);
            $detail->setQuantite($element['quantity']);

            // Ajoutez le détail à la commande
            $nouvelleCommande->addDetail($detail);
        }

        // Configurez les propriétés de la commande
        $nouvelleCommande->setDateCommande(new \DateTime());
        $nouvelleCommande->setTotal($montantTotalPanier);
        $nouvelleCommande->setEtat(Commande::ETAT_EN_COURS_PREPARATION);

        $utilisateur = $this->getUser();
        if ($utilisateur) {
            $nouvelleCommande->setUtilisateur($utilisateur);
        }

        // Persistez la nouvelle commande dans la base de données
        $entityManager->persist($nouvelleCommande);
        $entityManager->flush();

        // Redirigez vers la page de succès
        return $this->redirectToRoute('app_home');
    }
}

