<?php

namespace App\Controller\User;

use App\Controller\AbstractController;
use App\Entity\User;
use App\Form\User\DeleteProfileFormType;
use App\Form\User\EditPasswordFormType;
use App\Form\User\EditProfileFormType;
use App\Service\Security\EmailVerifier;
use App\Service\Security\LoginFormAuthenticator;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController {

    /** @var EmailVerifier */
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier) {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/utilisateur/profile/modifier", name="user_edit_profile")
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function editProfile(Request $request): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(EditProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                // Vérification modification email
                $isEmailUpdated = $this->emailIsUpdated($entityManager, $user);

                // Enregistrement en base
                $entityManager->flush();

                // Revalidation en cas de changement d'email
                if ($isEmailUpdated) {
                    $this->validateEmail($entityManager, $user);
                }

                $this->flashSuccess("Updated");
                return $this->redirectToRoute("home");
            }

            $entityManager->refresh($user);
        }

        return $this->render('user/edit_profile.html.twig', [
            'editProfileForm' => $form->createView(),
            'menu' => 'session'
        ]);
    }

    /**
     * @Route("/utilisateur/motdepasse/modifier", name="user_edit_password")
     * @param Request $request
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function editPassword(Request $request,
                                 LoginFormAuthenticator $loginFormAuthenticator,
                                 UserPasswordEncoderInterface $passwordEncoder): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(EditPasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $credentials = ["password" => $form->get('oldPassword')->getData()];
            if ($loginFormAuthenticator->checkCredentials($credentials, $user)) {
                // Encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager->flush();
                $this->flashSuccess("Password updated");
                return $this->redirectToRoute("home");
            } else {
                $this->flashError("Wrong old password");
            }

            $entityManager->refresh($user);
        }

        return $this->render('user/edit_password.html.twig', [
            'editPasswordForm' => $form->createView(),
            'menu' => 'session'
        ]);
    }

    /**
     * @Route("/utilisateur/profile/supprimer", name="user_delete_profile")
     * @param Request $request
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @param MailerInterface $mailer
     * @return Response
     */
    public function deleteProfile(Request $request,
                                  LoginFormAuthenticator $loginFormAuthenticator,
                                  MailerInterface $mailer): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(DeleteProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $credentials = ["password" => $form->get('plainPassword')->getData()];
            if ($loginFormAuthenticator->checkCredentials($credentials, $user)) {
                $email = $user->getEmail();
                $entityManager->remove($user);
                $entityManager->flush();
                $this->flashInfo("AccountDeleted");
                $this->sendEmail($mailer, $email);
                $session = new Session();
                $session->invalidate();
                return $this->redirectToRoute('home');
            } else {
                $this->flashWarning("Invalid credentials.");
                $entityManager->refresh($user);
            }
        }

        return $this->render('user/delete_profile.html.twig', [
            'deleteProfileForm' => $form->createView(),
            'menu' => 'session'
        ]);
    }

    /**
     * Vérifie si l'adresse mail a été mise à jour.
     * @param ObjectManager $entityManager
     * @param User $user
     * @return bool
     */
    private function emailIsUpdated(ObjectManager $entityManager, User $user): bool {
        $uow = $entityManager->getUnitOfWork();
        $uow->computeChangeSets();
        $changeSet = $uow->getEntityChangeSet($user);
        return isset($changeSet['email']);
    }

    /**
     * Envoie un email de confirmation.
     * @param ObjectManager $entityManager
     * @param User $user
     * @throws TransportExceptionInterface
     */
    private function validateEmail(ObjectManager $entityManager, User $user) {
        $entityManager->refresh($user);
        $user->setIsVerified(false);
        $entityManager->flush();
        $this->emailVerifier->sendEmailConfirmationFR($user);
    }

    private function sendEmail(MailerInterface $mailer, string $email) {
        $email = (new TemplatedEmail())
            ->from(new Address('noreply@vinproject.fr', 'VinProject'))// TODO nom
            ->to($email)
            ->subject('Compte Supprimer')
            ->htmlTemplate('email/user_delete_profile_email.twig');
        $mailer->send($email);
    }

}
