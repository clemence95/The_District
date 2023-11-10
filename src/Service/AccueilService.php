<?php
// src/Service/AccueilService.php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class AccueilService
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function getAllCategories()
    {
        try {
            $query = $this->entityManager->createQuery(
                'SELECT c
                FROM App\Entity\Categorie c'
            );

            return $query->getResult();
        } catch (\Exception $e) {
            // Gérer l'exception, par exemple, en la loguant
            $this->logger->error('Une erreur s\'est produite lors de la récupération des catégories: ' . $e->getMessage());

            // Vous pouvez également renvoyer une réponse d'erreur appropriée
            throw new \Exception('Une erreur s\'est produite lors de la récupération des catégories.');
        }
    }


    public function getAllPlats()
    {
        try {
            $query = $this->entityManager->createQuery(
                'SELECT p
                FROM App\Entity\Plat p'
            );

            return $query->getResult();
        } catch (\Exception $e) {
            // Gérer l'exception, par exemple, en la loguant
            $this->logger->error('Une erreur s\'est produite lors de la récupération des plats: ' . $e->getMessage());

            // Vous pouvez également renvoyer une réponse d'erreur appropriée
            throw new \Exception('Une erreur s\'est produite lors de la récupération des plats.');
        }
    }
}
