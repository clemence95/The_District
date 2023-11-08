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
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérez les données du formulaire
            $data = $form->getData();

            // Créez un e-mail avec la classe TemplatedEmail
            $email = (new TemplatedEmail())
                ->from('votre@email.com')
                ->to('destinataire@email.com')
                ->subject('Tututoto')
                ->htmlTemplate('mailer/index.html.twig')
                ->context([
                    'user_email' => $data->getEmail(), // Accédez à la propriété 'user_email' de l'objet Contact
                    'subject' => $data->getObjet(),
                    'message' => $data->getMessage(), // Accédez à la propriété 'message' de l'objet Contact
                  
                ]);

            // Envoyez l'e-mail
            $mailer->send($email);

            // Enregistrez le message dans la base de données
            $message = new Contact();
            $message->setEmail($data->getEmail()); // Utilisez le setter pour la propriété 'user_email'
            $message->setObjet($data->getObjet()); // Utilisez le setter pour la propriété 'subject'
            $message->setMessage($data->getMessage()); // Utilisez le setter pour la propriété 'message'
            $entityManager->persist($message);
            $entityManager->flush();

            // Redirection vers la page d'accueil
            return $this->redirectToRoute('app_accueil');
            
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
