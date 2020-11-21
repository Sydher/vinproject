<?php

namespace App\Notification;

use App\Entity\Contact;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ContactNotification {

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger) {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function notify(Contact $contact, $htmlBody) {
        $email = (new TemplatedEmail())
            ->from(new Address('noreply@o-melchior.fr', 'Ô Melchior'))
            ->to('contact@o-melchior.fr')
            ->subject('[Contact] Message de ' . $contact->getEmail())
            ->html($htmlBody)
            ->context([
                'contact' => $contact
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error("Erreur lors de l'envoi du mail de contact");
        }
    }

}