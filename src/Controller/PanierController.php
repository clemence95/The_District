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
    public function afficherPanier(): Response
    {
        $panier = $this->panierService->getPanier();

        return $this->render('panier/afficher_panier.html.twig', ['panier' => $panier]);
    }
}

