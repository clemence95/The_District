<?php
// src/Service/OrderConfirmationMailService.php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\Commande;

class OrderConfirmationMailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendOrderConfirmationEmail(Commande $order): void
    {
        $user = $order->getUtilisateur();
        $userEmail = $user->getEmail();

        $email = (new TemplatedEmail())
            ->from('your_email@example.com') // Remplacez par votre adresse e-mail
            ->to($userEmail)
            ->subject('Confirmation de commande')
            ->htmlTemplate('emails/order_confirmation_mail.html.twig')
            ->context([
                'order' => $order,
            ]);

        $this->mailer->send($email);
    }
}
