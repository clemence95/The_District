<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Plat;
use App\Entity\Commande;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        include 'theDistrict.php';
        $categorieRepo = $manager->getRepository(Categorie::class);

        // Chargez les catégories
        foreach ($categorie as $data) {
            $category = new Categorie();
            $category
                ->setId($data['id'])
                ->setLibelle($data['libelle'])
                ->setImage($data['image'])
                ->setActive($data['active']);
            // dd($categorie);
            $manager->persist($category);
            // empêcher l'auto incrément
            $metadata = $manager->getClassMetaData(Categorie::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        }
        $manager->flush();

        // Chargez les plats
        foreach ($plat as $data) {
            $dish = new Plat();
            $dish
                ->setId($data['id'])
                ->setLibelle($data['libelle'])
                ->setDescription($data['description'])
                ->setPrix($data['prix'])
                ->setImage($data['image'])
                ->setActive($data['active']);


            // Assurez-vous que $data['id_categorie'] correspond à une catégorie existante
            $cat = $categorieRepo->find($data['id_categorie']);
            $dish->setCategorie($cat);
            // dd($cat);

            $manager->persist($dish);
        }
        // Créez les utilisateurs
        $user1 = new Utilisateur();
        $user1
            ->setEmail("test1@test.com")
            ->setPassword("123456")
            ->setNom("test")
            ->setPrenom("test2")
            ->setTelephone("0123456789")
            ->setAdresse("3 rue des cailloux")
            ->setCp("80000")
            ->setVille("Amiens")
            ->setRoles('ROLE_USER');
        $manager->persist($user1);

        $user2 = new Utilisateur();
        $user2
            ->setEmail("test2@test.com")
            ->setPassword("123456")
            ->setNom("test222")
            ->setPrenom("test2")
            ->setTelephone("0123456789")
            ->setAdresse("3 rue des cailloux")
            ->setCp("80000")
            ->setVille("Amiens")
            ->setRoles('ROLE_USER');
        $manager->persist($user2);

        // Créez les commandes
        $commande1 = new Commande();
        $commande1
            ->setDateCommande(new \DateTime('2023-11-08'))
            ->setTotal("30.00")
            ->setEtat(3)
            ->setUtilisateur($user1);
        $manager->persist($commande1);

        $commande2 = new Commande();
        $commande2
            ->setDateCommande(new \DateTime('2023-11-08'))
            ->setTotal("45.00")
            ->setEtat(1)
            ->setUtilisateur($user2);
        $manager->persist($commande2);

        $commande3 = new Commande();
        $commande3
            ->setDateCommande(new \DateTime('2023-11-08'))
            ->setTotal("30.00")
            ->setEtat(3)
            ->setUtilisateur($user1);
        $manager->persist($commande3);

        $manager->flush();
    }
}
