<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Entity\About;
use App\Form\Admin\AboutFormType;
use App\Form\ConfirmDeleteFormType;
use App\Repository\AboutRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class AboutAdminController extends AbstractController {

    /**
     * @var AboutRepository
     */
    private $aboutRepository;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(AboutRepository $aboutRepository, CacheInterface $cache) {
        $this->aboutRepository = $aboutRepository;
        $this->cache = $cache;
    }

    /**
     * @Route("/admin/divers", name="admin_about")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator) {
        $allAbout = $paginator->paginate(
            $this->aboutRepository->findAllOrderByLastUpdateQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/about/index.html.twig', [
            'menu' => 'about',
            'allAbout' => $allAbout
        ]);
    }

    /**
     * @Route("/admin/divers/creer", name="admin_about_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response {
        $about = new About();
        $form = $this->createForm(AboutFormType::class, $about);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($about);
            $entityManager->flush();
            $this->flashSuccess("Created");
            $entityManager->refresh($about);
            return $this->redirectToRoute('admin_about');
        }

        return $this->render('admin/about/create.html.twig', [
            'menu' => 'about',
            'form' => $form->createView(),
            'about' => $about
        ]);
    }

    /**
     * @Route("/admin/divers/modifier/{id}", name="admin_about_edit")
     * @param Request $request
     * @param string $id
     * @return Response
     * @throws InvalidArgumentException
     */
    public function edit(Request $request, string $id): Response {
        $about = $this->aboutRepository->find($id);
        $form = $this->createForm(AboutFormType::class, $about);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($about);
            $entityManager->flush();
            $this->flashSuccess("Updated");
            $entityManager->refresh($about);
            $this->cache->delete($about->getName());
            return $this->redirectToRoute('admin_about');
        }

        return $this->render('admin/about/edit.html.twig', [
            'menu' => 'about',
            'form' => $form->createView(),
            'about' => $about
        ]);
    }

    /**
     * @Route("/admin/divers/supprimer/{id}", name="admin_about_delete")
     * @param Request $request
     * @param string $id
     * @return Response
     * @throws InvalidArgumentException
     */
    public function delete(Request $request, string $id): Response {
        $about = $this->aboutRepository->find($id);
        $form = $this->createForm(ConfirmDeleteFormType::class, $about);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->cache->delete($about->getName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($about);
            $entityManager->flush();
            $this->flashInfo("Deleted");
            return $this->redirectToRoute('admin_about');
        }

        return $this->render('admin/about/delete.html.twig', [
            'menu' => 'about',
            'form' => $form->createView(),
            'about' => $about
        ]);
    }

}
