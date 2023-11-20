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
     * @Route("/panier", name="afficher_panier")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(): Response
    {
        $panier = $this->panierService->getPanier();

        return $this->render('panier/index.html.twig', ['panier' => $panier]);
    }

    /**
     * @Route("/ajouter-au-panier/{plat}", name="ajouter_au_panier")
     * @Security("is_granted('ROLE_USER')")
     */
    public function ajouterAuPanier($plat): Response
    {
        // Vous pouvez ajouter une logique supplémentaire ici, par exemple, récupérer les détails du plat depuis la base de données
        // puis ajouter le plat au panier

        $this->panierService->ajouterAuPanier($plat);

        $this->addFlash('success', 'Plat ajouté au panier avec succès!');

        return $this->redirectToRoute('afficher_panier'); // Redirigez où vous le souhaitez après l'ajout au panier
    }
}


