<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\Admin\Blog\EditPostFormType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogAdminController extends AdminAbstractController {

    protected $entity = Post::class;
    protected $templatePath = 'blog';
    protected $menu = 'blog';
    protected $formClassName = EditPostFormType::class;
    protected $indexRouteName = 'admin_blog_index';
    protected $addRouteName = 'admin_blog_add';
    protected $editRouteName = 'admin_blog_edit';
    protected $deleteRouteName = 'admin_blog_delete';

    /**
     * @Route("/admin/blog/liste", name="admin_blog_index")
     * @return Response
     */
    public function index(): Response {
        return $this->abstractIndex();
    }

    /**
     * @Route("/admin/blog/creer", name="admin_blog_add")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        return $this->abstractNew(new Post());
    }

    /**
     * @Route("/admin/blog/modifier/{id}", name="admin_blog_edit")
     * @param string $id identifiant de l'article à modifier
     * @return Response
     */
    public function edit(string $id): Response {
        return $this->abstractEdit($id);
    }

    /**
     * @Route("/admin/blog/supprimer/{id}", name="admin_blog_delete")
     * @param string $id identifiant de l'article à supprimer
     * @return Response
     */
    public function delete(string $id): Response {
        return $this->abstractDelete($id);
    }

    /**
     * @Route("/admin/blog/visible/{id}", name="admin_blog_set_visible_post")
     * @param PostRepository $postRepository
     * @param string $id identifiant de l'article à rendre visible
     * @return Response
     */
    public function setVisible(PostRepository $postRepository, string $id): Response {
        return $this->changeVisibility($postRepository, $id, true);
    }

    /**
     * @Route("/admin/blog/invisible/{id}", name="admin_blog_set_invisible_post")
     * @param PostRepository $postRepository
     * @param string $id identifiant de l'article à rendre invisible
     * @return Response
     */
    public function setInvisible(PostRepository $postRepository, string $id): Response {
        return $this->changeVisibility($postRepository, $id, false);
    }

    private function changeVisibility(PostRepository $postRepository,
                                      string $id,
                                      bool $state): Response {
        $post = $postRepository->find($id);
        $post->setIsVisible($state);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();
        $this->flashSuccess("Updated");
        $entityManager->refresh($post);
        return $this->redirectToRoute($this->indexRouteName);
    }

    /**
     * @inheritDoc
     */
    protected function applyNew($entity): void {
        $entity->setAuthor($this->getUser());
    }

    /**
     * @inheritDoc
     */
    protected function applyEdit($entity): void {
        // Empty
    }

}
