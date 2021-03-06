<?php

namespace App\Controller\User;

use App\Controller\AbstractController;
use App\Entity\User;
use App\Form\User\RegistrationFormType;
use App\Repository\User\BannedDomainRepository;
use App\Service\Security\EmailVerifier;
use App\Service\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class SecurityController extends AbstractController {

    /** @var EmailVerifier */
    private $emailVerifier;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(EmailVerifier $emailVerifier, TranslatorInterface $translator) {
        $this->emailVerifier = $emailVerifier;
        $this->translator = $translator;
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $errMsg = $this->translator->trans($error->getMessageKey(), [], 'security');
            $this->flashError($errMsg);
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', ['last_username' => $lastUsername, 'error' => null]);
    }

    /**
     * @Route("/deconnexion")
     * @Route("/se-deconnecter")
     * @Route("/logout", name="logout")
     */
    public function logout() {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register")
     * @Route("/inscription", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @param BannedDomainRepository $bannedDomainRepository
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler,
                             LoginFormAuthenticator $authenticator,
                             BannedDomainRepository $bannedDomainRepository): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->canRegister($bannedDomainRepository, $user)) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmationFR($user);

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            } else {
                $this->flashError("RegisterDomainForbidden");
            }
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/utilisateur/verification/email", name="user_verify_email")
     * @param Request $request
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('user_verify_email_error', $exception->getReason());

            return $this->redirectToRoute('register');
        }

        $this->flashSuccess('Email verified');
        return $this->redirectToRoute('home');
    }

    /**
     * Vérifie si l'utilisteur peut s'inscrire.
     * @param BannedDomainRepository $bannedDomainRepository le repository
     * @param User $user l'utilisateur qui souhaite s'inscrire
     * @return bool true si l'utilisateur peut s'inscrire, false sinon
     */
    private function canRegister(BannedDomainRepository $bannedDomainRepository,
                                 User $user): bool {
        $domaine = explode('@', $user->getEmail());
        $result = $bannedDomainRepository->findBy(['name' => $domaine]);
        return empty($result);
    }

}
