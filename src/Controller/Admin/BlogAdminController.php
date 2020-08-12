<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Entity\Post;
use App\Form\Admin\Blog\CreatePostFormType;
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
     * @param Request $request
     * @return Response
     */
    public function liste(Request $request): Response {
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
        $form = $this->createForm(CreatePostFormType::class, $post);
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

}
