<?php
// src/Service/MailService.php

// src/Service/MailService.php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendContactEmail(string $from, string $to, string $subject, string $message): void
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate('emails/contact_mail.html.twig') // Le template Twig à utiliser
            ->context([
                'user_email' => $from,
                'subject' => $subject,
                'message' => $message,
            ]);

        $this->mailer->send($email);
    }
}
