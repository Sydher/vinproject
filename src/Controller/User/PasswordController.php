<?php

namespace App\Controller\User;

use App\Controller\AbstractController;
use App\Entity\User;
use App\Form\EditPasswordFormType;
use App\Service\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordController extends AbstractController {

    /**
     * @Route("/utilisateur/motdepasse/modifier", name="edit_password")
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
            } else {
                $this->flashError("Wrong old password");
            }

            $entityManager->refresh($user);
        }

        return $this->render('user/edit_password.html.twig', [
            'editPasswordForm' => $form->createView(),
        ]);
    }

}
