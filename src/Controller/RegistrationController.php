<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // Crée une nouvelle instance de l'entité Utilisateur
        $user = new Utilisateur();

        // Crée un formulaire d'inscription associé à l'utilisateur
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Traite la requête pour vérifier si le formulaire a été soumis
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe en clair
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // Attribue le rôle "ROLE_USER"
            $user->setRoles(['ROLE_USER']);

            // Persiste l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoie un email de confirmation à l'utilisateur
            $email = (new Email())
                ->from('your-email@example.com')
                ->to($user->getEmail())
                ->subject('Confirmation d\'inscription')
                ->html('<p>Veuillez confirmer votre adresse email en cliquant sur le lien suivant : ...</p>');
            $mailer->send($email);

            // Redirige l'utilisateur vers la page d'accueil après une inscription réussie
            return $this->redirectToRoute('app_home');
        }

        // Affiche le formulaire d'inscription s'il n'est pas valide
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

