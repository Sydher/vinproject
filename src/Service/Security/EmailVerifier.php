<?php

namespace App\Service\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier {

    /** @var VerifyEmailHelperInterface */
    private $verifyEmailHelper;

    /** @var MailerInterface */
    private $mailer;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager) {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    /**
     * Envoie un mail de validation (avec un lien généré).
     * @param User $user
     * @throws TransportExceptionInterface
     */
    public function sendEmailConfirmationFR(User $user): void {
        $this->sendEmailConfirmation('user_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('no-reply@vinproject.fr', 'Vin Project'))// TODO nom projet
                ->to($user->getEmail())
                ->subject('Confirmer votre adresse e-mail')
                ->htmlTemplate('email/user_verify_email.html.twig')
        );
    }

    /**
     * Envoie un mail de validation (avec un lien généré).
     * @param string $verifyEmailRouteName
     * @param User $user
     * @param TemplatedEmail $email
     * @throws TransportExceptionInterface
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail()
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAt'] = $signatureComponents->getExpiresAt();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * Gère la validation d'un email.
     * @param Request $request
     * @param User $user
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, User $user): void {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}
