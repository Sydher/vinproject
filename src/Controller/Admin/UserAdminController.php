<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Entity\User;
use App\Form\Admin\User\EditFormType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAdminController extends AbstractController {

    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin/utilisateur")
     * @Route("/admin/utilisateur/liste", name="admin_user_list")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function liste(PaginatorInterface $paginator, Request $request): Response {
        $users = $paginator->paginate(
            $this->userRepository->findAllQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/user/list.html.twig', [
            'allUsers' => $users,
            'menu' => 'utilisateurs'
        ]);
    }

    /**
     * @Route("/admin/utilisateur/modifier/{id}", name="admin_user_edit")
     * @param Request $request
     * @param string $id identifiant de l'utilisateur à modifier
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function edit(Request $request,
                         string $id,
                         UserPasswordEncoderInterface $passwordEncoder): Response {
        /** @var User $user */
        $user = $this->userRepository->find($id);
        $form = $this->createForm(EditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Modification du mot de passe si renseigné
            $password = $form->get('plainPassword')->getData();
            if ($password != null && $password != "") {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $entityManager->flush();
            $this->flashSuccess("Updated");
            $entityManager->refresh($user);
            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'editForm' => $form->createView(),
            'user' => $user,
            'menu' => 'utilisateurs'
        ]);
    }

}
