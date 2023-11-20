<?php

// src/Controller/PanierController.php

namespace App\Controller;

use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PanierController extends AbstractController
{
    private $panierService;

    public function __construct(PanierService $panierService)
    {
        $this->panierService = $panierService;
    }

    /**
     * @Route("/panier", name="page_panier")
     * @Security("is_granted('ROLE_USER')")
     */
    public function ajouterAuPanier($plat): Response
    {
        // Vous pouvez ajouter une logique supplémentaire ici, par exemple, récupérer les détails du plat depuis la base de données
        // puis ajouter le plat au panier

        $this->panierService->ajouterAuPanier($plat);

        $this->addFlash('success', 'Plat ajouté au panier avec succès!');

        return $this->redirectToRoute('liste_plats');
    }
}

