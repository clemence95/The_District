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
        $panier = $this->getPanier();
    
        // Recherchez le plat dans le panier
        foreach ($panier as $key => $platItem) {
            // dd($panier);
            
            if ($platItem['id'] == $platId) {
                // Vérifiez si la quantité est supérieure à 1
                if ($panier[$key]['quantite'] > 1) {
                    // Si oui, décrémentez la quantité
                    $panier[$key]['quantite']--;
                    // dd($panier[$key]);
                } else {
                   
                    // Sinon, supprimez le plat du panier
                    unset($panier[$key]);
                }
    
                break; // Sortez de la boucle dès que le plat est trouvé
            }
        }
    
        // Mettez à jour le panier dans la session
        $this->requestStack->getSession()->set('panier', $panier);
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
        // Recherchez un plat par ID depuis la base de données
        $plat = $this->entityManager->getRepository(Plat::class)->find($platId);

        // Vérifiez si le plat existe
        if (!$plat) {
            throw new \Exception('Plat non trouvé avec l\'ID ' . $platId);
        }

        return $plat;
    }

    // Ajoutez d'autres méthodes de gestion du panier au besoin
}
