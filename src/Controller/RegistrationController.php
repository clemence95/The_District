<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
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

            // Persiste l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Génère une URL signée et l'envoie par email à l'utilisateur
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('mailer@your-domain.com', 'District'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmer votre email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // Faites tout autre traitement nécessaire ici, comme l'envoi d'un email

            // Redirige l'utilisateur vers la page d'accueil après une inscription réussie
            return $this->redirectToRoute('app_accueil');
        }

        // Affiche le formulaire d'inscription s'il n'est pas valide
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        // N'accorde l'accès que si l'utilisateur est complètement authentifié
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Valide le lien de confirmation d'email, défini User::isVerified=true et persiste
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            // En cas d'erreur, redirige vers la page d'inscription avec un message d'erreur
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }

        // @TODO Changez la redirection en cas de succès et gérez ou supprimez le message flash dans vos modèles
        $this->addFlash('success', 'Votre adresse email a été vérifiée.');

        return $this->redirectToRoute('app_register');
    }
}


