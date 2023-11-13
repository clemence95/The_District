<?php

// src/Controller/AccueilController.php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    private $platRepo;
    private $categorieRepo;
    private $em;
  
    public function __construct(PlatRepository $platRepo, CategorieRepository $categorieRepo, EntityManagerInterface $em){

        $this->platRepo = $platRepo;
        $this->categorieRepo = $categorieRepo;
        $this->em = $em;
    }
    #[Route('/accueil', name: 'app_accueil')]
public function index(): Response
    {
  $plats = $this->platRepo->findAll();
  $categories = $this->categorieRepo->findAll();
//   dd($categories);

        // ...

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'plats' => $plats,
        ]);
    }
}

