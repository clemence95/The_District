<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\DemoFormType;
use App\Form\ContactFormType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, MailService $mailService): Response
    {
        $form = $this->createForm(ContactFormType::class);
        // dd($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérez les données du formulaire
            $data = $form->getData();

            // dd($form);

            // Enregistrez le message dans la base de données
            $message = new Contact();
            $message->setEmail($data->getEmail()); // Utilisez le setter pour la propriété 'user_email'
            $message->setObjet($data->getObjet()); // Utilisez le setter pour la propriété 'subject'
            $message->setMessage($data->getMessage()); // Utilisez le setter pour la propriété 'message'
            $entityManager->persist($message);
            $entityManager->flush();

            // Créez un e-mail avec la classe TemplatedEmail
             // Utilisez le service MailService pour envoyer l'e-mail
             $mailService->sendContactEmail(
                $data->getEmail(),
                'destinataire@email.com',
                $data->getObjet(),
                $data->getMessage()
            );


            // Redirection vers la page d'accueil
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('contact/index.html.twig', [
            // 'form' => $form->createView(),
            'form' => $form
        ]);
    }
}
