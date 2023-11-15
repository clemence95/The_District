<?php

// src/Controller/AccueilController.php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    private $platRepo;
    private $categorieRepo;
    private $em;

    public function __construct(PlatRepository $platRepo, CategorieRepository $categorieRepo, EntityManagerInterface $em)
    {
        $this->platRepo = $platRepo;
        $this->categorieRepo = $categorieRepo;
        $this->em = $em;
    }
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        $plats = $this->platRepo->findBy([],[],5);
        $categories = $this->categorieRepo->findBy([],[],3);
        //   dd($categories);

        // ...

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'plats' => $plats,
        ]);
    }

    #[Route('/plats', name: 'app_plats')]
    public function plats(): Response
    {
        $plats = $this->platRepo->findAll();

        return $this->render('accueil/plats.html.twig', [
            'plats' => $plats,
        ]);
    }

    #[Route('/categories', name: 'app_categories')]
    public function categories(): Response
    {
        $categories = $this->categorieRepo->findAll();

        return $this->render('accueil/categories.html.twig', [
            'categories' => $categories,
        ]);
    }
    
}
