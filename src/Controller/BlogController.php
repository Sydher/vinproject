<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController {

    /** @var PostRepository */
    private $postRepository;

    public function __construct(PostRepository $postRepository) {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/blog/liste", name="blog_list")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function liste(PaginatorInterface $paginator, Request $request): Response {
        $posts = $paginator->paginate(
            $this->postRepository->findAllQuery(),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('blog/list.html.twig', [
            'allPosts' => $posts,
            'menu' => 'blog'
        ]);
    }

    /**
     * @Route("/blog/{slug}-{id}", name="blog_show_post", requirements={"slug": "[a-z0-9\-]*"})
     * @param string $slug
     * @param string $id
     * @return Response
     */
    public function show(string $slug, string $id): Response {
        $post = $this->postRepository->find($id);
        return $this->render('blog/show.html.twig', [
            'post' => $post,
            'menu' => 'blog'
        ]);
    }

}
