<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier {

    private $verifyEmailHelper;
    private $mailer;
    private $entityManager;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager) {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    /**
     * Envoie un mail de validation (avec un lien généré).
     * @param User $user
     */
    public function sendEmailConfirmationFR(User $user): void {
        $this->sendEmailConfirmation('verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('no-reply@vinproject.fr', 'Vin Project'))// TODO nom projet
                ->to($user->getEmail())
                ->subject('Confirmer votre adresse e-mail')
                ->htmlTemplate('security/confirmation_email.html.twig')
        );
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void {
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
     * @param Request $request
     * @param UserInterface $user
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}
