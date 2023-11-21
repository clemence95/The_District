<?php

// src/Service/PanierService.php

namespace App\Service;

use App\Entity\Plat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PanierService
{

    private $requestStack;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public function getPanier(): array
    {
        $session = $this->requestStack->getSession();
        // Récupérez le panier depuis la session
        $panier = $session->get('panier', []);

        return $panier;
    }

    public function ajouterAuPanier($platId): void
    {
        $session = $this->requestStack->getSession();
        // Obtenez le plat depuis la base de données ou d'où vous stockez vos plats
        $plat = $this->getPlatById($platId);

        // Vérifiez si le plat existe et ajoutez-le au panier
        if ($plat) {
            $panier = $this->getPanier();

            // Vérifiez si le plat est déjà dans le panier
            $platIndex = $this->getPlatIndexInPanier($platId);

            if ($platIndex !== false) {
                // Si le plat est déjà dans le panier, augmentez la quantité
                $panier[$platIndex]['quantite']++;
            } else {
                // Sinon, ajoutez le plat avec une quantité initiale de 1
                $panier[] = [
                    'id' => $platId,
                    'libelle' => $plat->getLibelle(),
                    'prix' => $plat->getPrix(),
                    'image' => $plat->getImage(),
                    'quantite' => 1, // Quantité initiale
                ];
            }

            // Enregistrez le panier dans la session
            $session->set('panier', $panier);
        }
    }

    public function retirerDuPanier($platId): void
    {
        $session = $this->requestStack->getSession();
        $panier = $this->getPanier();

        // Recherchez le plat dans le panier
        foreach ($panier as $key => $platItem) {
            if ($platItem['id'] === $platId) {
                // Décrémentez la quantité du plat dans le panier
                $panier[$key]['quantite']--;

                // Si la quantité atteint zéro, retirez le plat du panier
                if ($panier[$key]['quantite'] <= 0) {
                    unset($panier[$key]);
                }

                break; // Sortez de la boucle dès que le plat est trouvé
            }
        }

        // Mettez à jour le panier dans la session
        $session->set('panier', $panier);
    }


    private function getPlatIndexInPanier($platId)
    {
        $panier = $this->getPanier();

        // Recherchez l'index du plat dans le panier
        foreach ($panier as $index => $platItem) {
            if ($platItem['id'] === $platId) {
                return $index;
            }
        }

        return false;
    }

    private function getPlatById($platId)
    {
        // Implémentez la logique pour récupérer un plat par ID depuis la base de données
        // ...

        return $this->entityManager->getRepository(Plat::class)->find($platId);
    }

    // Ajoutez d'autres méthodes de gestion du panier au besoin
}
