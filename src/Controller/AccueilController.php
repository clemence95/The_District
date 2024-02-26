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
    private $platRepository;
    private $categorieRepo;
    private $em;

    #[Route('/accueil', name: 'app_accueil')]
    public function __construct(PlatRepository $platRepo, CategorieRepository $categorieRepo)
    {
        
    
    
        $entityManager = $this->getDoctrine()->getManager();
        $platRepository = $entityManager->getRepository('App\Entity\Plat');
        
        $categories = $platRepository->getAllCategories('NomCategorie');
        $plats = $platRepository->getAllPlats();

        // ...

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'plats' => $plats,
        ]);
    }
}

