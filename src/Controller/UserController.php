<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileFormType;
use App\Security\EmailVerifier;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController {

    /**
     * @var EmailVerifier
     */
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier) {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/user/edit/profile", name="edit_profile")
     * @param Request $request
     * @return Response
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

                $this->flashSuccess("Les modifications ont étaient réalisées avec succès !");
            }

            $entityManager->refresh($user);
        }

        return $this->render('user/edit_profile.html.twig', [
            'editProfileForm' => $form->createView(),
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
     */
    private function validateEmail(ObjectManager $entityManager, User $user) {
        $entityManager->refresh($user);
        $user->setIsVerified(false);
        $entityManager->flush();
        $this->emailVerifier->sendEmailConfirmationFR($user);
    }

}