<?php

// PanierController.php

namespace App\Controller;

use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Plat;

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
        $this->panierService->ajouterAuPanier($plat);

        $this->addFlash('success', 'Plat ajouté au panier avec succès!');

        return $this->redirectToRoute('afficher_panier');
    }

    /**
     * @Route("/retirer-du-panier/{platId}", name="retirer_du_panier")
     * @Security("is_granted('ROLE_USER')")
     * @ParamConverter("plat", class="App\Entity\Plat", options={"id" = "platId"})
     */
    public function retirerDuPanier(Plat $plat): Response
    {
        // Utilisez $plat dans votre logique pour retirer du panier

        $this->panierService->retirerDuPanier($plat->getId());

        $this->addFlash('success', 'Plat retiré du panier avec succès!');

        return $this->redirectToRoute('afficher_panier');
    }
}
