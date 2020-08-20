<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Service\Security\Securizer;
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
            $this->postRepository->findAllActiveQuery(),
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
     * @param Securizer $securizer
     * @param string $slug
     * @param string $id
     * @return Response
     */
    public function show(Securizer $securizer, string $slug, string $id): Response {
        $post = $this->postRepository->find($id);

        // Si le post est invisible et que l'utilisateur n'est pas un admin
        // Alors il n'est pas autorisé à consulter l'article
        $isAdmin = $securizer->isGranted($this->getUser(), 'ROLE_ADMIN');
        if (!$post->getIsVisible() && !$isAdmin) {
            return $this->redirectToRoute('blog_list');
        }

        return $this->render('blog/show.html.twig', [
            'post' => $post,
            'menu' => 'blog'
        ]);
    }

}
