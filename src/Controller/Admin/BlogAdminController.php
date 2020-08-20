<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Entity\Post;
use App\Form\Admin\Blog\EditPostFormType;
use App\Form\ConfirmDeleteFormType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogAdminController extends AbstractController {

    /** @var PostRepository */
    private $postRepository;

    public function __construct(PostRepository $postRepository) {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/admin/blog/liste", name="admin_blog_list")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function liste(PaginatorInterface $paginator, Request $request): Response {
        $posts = $paginator->paginate(
            $this->postRepository->findAllByLastUpdateQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/blog/list.html.twig', [
            'allPosts' => $posts,
            'menu' => 'blog'
        ]);
    }

    /**
     * @Route("/admin/blog/creer", name="admin_blog_add_post")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response {
        $post = new Post();
        $form = $this->createForm(EditPostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $post->setAuthor($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();
            $this->flashSuccess("PostCreated");
            return $this->redirectToRoute('admin_blog_list');
        }

        return $this->render('admin/blog/create.html.twig', [
            'createForm' => $form->createView(),
            'menu' => 'blog'
        ]);
    }

    /**
     * @Route("/admin/blog/modifier/{id}", name="admin_blog_edit_post")
     * @param Request $request
     * @param string $id identifiant de l'article à modifier
     * @return Response
     */
    public function edit(Request $request, string $id): Response {
        $post = $this->postRepository->find($id);
        $form = $this->createForm(EditPostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            $this->flashSuccess("Updated");
            $entityManager->refresh($post);
            return $this->redirectToRoute('admin_blog_list');
        }

        return $this->render('admin/blog/edit.html.twig', [
            'editForm' => $form->createView(),
            'post' => $post,
            'menu' => 'blog'
        ]);
    }

    /**
     * @Route("/admin/blog/supprimer/{id}", name="admin_blog_delete_post")
     * @param Request $request
     * @param string $id identifiant de l'article à supprimer
     * @return Response
     */
    public function delete(Request $request, string $id): Response {
        $post = $this->postRepository->find($id);
        $form = $this->createForm(ConfirmDeleteFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
            $this->flashInfo("PostDeleted");
            return $this->redirectToRoute('admin_blog_list');
        }

        return $this->render('admin/blog/delete.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
            'menu' => 'blog'
        ]);
    }

    /**
     * @Route("/admin/blog/visible/{id}", name="admin_blog_set_visible_post")
     * @param string $id identifiant de l'article à rendre visible
     * @return Response
     */
    public function setVisible(string $id): Response {
        return $this->changeVisibility($id, true);
    }

    /**
     * @Route("/admin/blog/invisible/{id}", name="admin_blog_set_invisible_post")
     * @param string $id identifiant de l'article à rendre invisible
     * @return Response
     */
    public function setInvisible(string $id): Response {
        return $this->changeVisibility($id, false);
    }

    private function changeVisibility(string $id, bool $state): Response {
        $post = $this->postRepository->find($id);
        $post->setIsVisible($state);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();
        $this->flashSuccess("Updated");
        $entityManager->refresh($post);
        return $this->redirectToRoute('admin_blog_list');
    }

}
