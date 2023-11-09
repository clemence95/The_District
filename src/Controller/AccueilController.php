<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;

class AccueilController extends AbstractController
{
    private $categorieRepository;
    private $platRepository;

    public function __construct(CategorieRepository $categorieRepository, PlatRepository $platRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->platRepository = $platRepository;
    }

    #[Route('/accueil', name: 'app_accueil')]
    public function index(): Response
    {
         // Utilise les repositories pour récupérer les données
         $plats = $this->platRepository->findAll();
         $categories = $this->categorieRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'plats' => $plats,
            'categories' => $categories,
        ]);
    }
    
}
