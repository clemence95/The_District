<?php
// src/Service/PanierService.php
namespace App\Service;

class PanierService
{
    private $panier;

    public function __construct()
    {
        // Initialisez le panier (peut-être à partir de la session ou d'une base de données)
        $this->panier = [];
    }

    public function getPanier(): array
    {
        return $this->panier;
    }

    public function ajouterAuPanier(string $plat): void
    {
        // Ajoutez le plat au panier
        $this->panier[] = $plat;
    }

    public function retirerDuPanier(string $plat): void
    {
        // Retirez le plat du panier s'il existe
        $index = array_search($plat, $this->panier);
        if ($index !== false) {
            unset($this->panier[$index]);
        }
    }
}

