<?php

// src/Service/PanierService.php

namespace App\Service;

use App\Entity\Plat;
use Doctrine\ORM\EntityManagerInterface;

class PanierService
{
    private $panier;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->panier = [];
        $this->entityManager = $entityManager;
    }

    public function getPanier(): array
    {
        return $this->panier;
    }

    public function ajouterAuPanier($platId): void
    {
        // Obtenez le plat depuis la base de données
        $plat = $this->entityManager->getRepository(Plat::class)->find($platId);

        // Vérifiez si le plat existe et ajoutez-le au panier
        if ($plat) {
            $this->panier[] = $plat;
        }
    }

    public function retirerDuPanier(string $platId): void
    {
        // Obtenez le plat depuis la base de données
        $plat = $this->entityManager->getRepository(Plat::class)->find($platId);

        // Retirez le plat du panier s'il existe
        $index = array_search($plat, $this->panier);
        if ($index !== false) {
            unset($this->panier[$index]);
        }
    }
}


