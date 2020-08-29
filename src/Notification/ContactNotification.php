<?php

namespace App\Notification;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ContactNotification {

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    /**
     * @param Contact $contact
     * @throws TransportExceptionInterface
     */
    public function notify(Contact $contact) {
        $email = (new TemplatedEmail())
            ->from(new Address('noreply@vinproject.fr', 'VinProject'))// TODO nom
            ->to('contact@vinproject.fr')// TODO nom
            ->subject('[Contact] Message de ' . $contact->getEmail())
            ->htmlTemplate('email/contact_email.html.twig')
            ->context([
                'contact' => $contact
            ]);
        $this->mailer->send($email);
    }

}