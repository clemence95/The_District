<?php

// src/Controller/AccueilController.php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    private $platRepo;
    private $categorieRepo;

    public function __construct(PlatRepository $platRepo, CategorieRepository $categorieRepo)
    {
        $this->platRepo = $platRepo;
        $this->categorieRepo = $categorieRepo;
    }

    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        $categories = $this->categorieRepo->findBy([], [], 3); // Récupérer les trois premières catégories
        $plats = $this->platRepo->findBy([], [], 5); // Récupérer les cinq premiers plats
    
        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'plats' => $plats,
        ]);
    }
        
    #[Route('/plats', name: 'app_listePlats')]
    public function plats(): Response
    {
        $plats = $this->platRepo->findAll();

        return $this->render('accueil/plats.html.twig', [
            'plats' => $plats,
        ]);
    }

    #[Route('/categories', name: 'app_listeCategories')]
    public function categories(): Response
    {
        $categories = $this->categorieRepo->findAll();

        return $this->render('accueil/categories.html.twig', [
            'categories' => $categories,
        ]);
    }
}
