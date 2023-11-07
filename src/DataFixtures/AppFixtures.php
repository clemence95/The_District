<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Plat;
use App\Entity\Commande;
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

        // Chargez les commandes
        // foreach ($commande as $data) {
        //     $order = new Commande();
        //     $order
        //         ->setId($data['id'])
        //         ->setId($data['id_plat'])
        //         ->setTotal($data['total'])
        //         ->setDateCommande(new \DateTime($data['date_commande']))
        //         ->setEtat($data['etat'])
        //         ->setUtilisateur($data['nom_client'])
        //         ->setTelephoneClient($data['telephone_client'])
        //         ->setEmailClient($data['email_client'])
        //         ->setAdresseClient($data['adresse_client']);

        //     // Assurez-vous que $data['id_plat'] correspond à un plat existant
        //     $dish = $manager->getRepository(Plat::class)->find($data['id_plat']);
        //     $order->setPlat($dish);

        //     $manager->persist($order);
        // }

        $manager->flush();
    }
}

