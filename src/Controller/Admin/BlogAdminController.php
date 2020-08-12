<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Entity\Post;
use App\Form\Admin\Blog\EditPostFormType;
use App\Repository\PostRepository;
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
     * @return Response
     */
    public function liste(): Response {
        $post = $this->postRepository->findAll();
        return $this->render('admin/blog/list.html.twig', [
            'allPosts' => $post,
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
     * @param string $id identifiant de l'article à supprimer
     * @return Response
     */
    public function delete(string $id): Response {
        $post = $this->postRepository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();
        $this->flashInfo("PostDeleted");
        return $this->redirectToRoute('admin_blog_list');
    }

}
