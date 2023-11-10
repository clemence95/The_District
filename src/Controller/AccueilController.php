<?php

// src/Controller/AccueilController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AccueilService;

// src/Controller/AccueilController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AccueilService;

class AccueilController extends AbstractController
{
    private AccueilService $accueilService;

    public function __construct(AccueilService $accueilService)
    {
        $this->accueilService = $accueilService;
    }

    #[Route('/accueil', name: 'app_accueil')]
    public function index(): Response
    {
        $categories = $this->accueilService->getSomeCategorie('NomCategorie');
        $plats = $this->accueilService->getSomePlat('NomPlat');

        // ...

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'plats' => $plats,
        ]);
    }
}
