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

            // Ajoutez le plat au panier
            $panier[] = $plat;

            // Enregistrez le panier dans la session
            $session->set('panier', $panier);
            // dd($session);
        }
    }

    private function getPlatById($platId)
    {
        $session = $this->requestStack->getSession();
        // Récupérez le plat depuis la base de données en utilisant l'ID
        $plat = $this->entityManager->getRepository(Plat::class)->find($platId);

        // Retournez le plat ou null si le plat n'est pas trouvé
        return $plat;
    }

    // Ajoutez d'autres méthodes de gestion du panier au besoin
}




