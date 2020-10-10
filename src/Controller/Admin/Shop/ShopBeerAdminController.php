<?php

namespace App\Controller\Admin\Shop;

use App\Controller\AbstractController;
use App\Entity\Appellation;
use App\Entity\Beer;
use App\Entity\Productor;
use App\Entity\Region;
use App\Entity\Wine;
use App\Form\Admin\Shop\AppellationFormType;
use App\Form\Admin\Shop\BeerFormType;
use App\Form\Admin\Shop\ProductorFormType;
use App\Form\Admin\Shop\RegionFormType;
use App\Form\Admin\Shop\WineFormType;
use App\Form\ConfirmDeleteFormType;
use App\Repository\AppellationRepository;
use App\Repository\BeerRepository;
use App\Repository\ProductorRepository;
use App\Repository\RegionRepository;
use App\Repository\WineRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class ShopBeerAdminController extends AbstractController {

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var BeerRepository
     */
    private $beerRepository;

    public function __construct(PaginatorInterface $paginator,
                                CacheInterface $cache,
                                BeerRepository $beerRepository) {
        $this->paginator = $paginator;
        $this->cache = $cache;
        $this->beerRepository = $beerRepository;
    }

    /**
     * @Route("/admin/boutique/bieres", name="admin_shop_list_beer")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function listBeer(PaginatorInterface $paginator, Request $request): Response {
        $allBeer = $paginator->paginate(
            $this->beerRepository->findAllOrderByLastUpdateQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/shop/beer/list.html.twig', [
            'allBeer' => $allBeer,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/bieres/creer", name="admin_shop_add_beer")
     * @param Request $request
     * @return Response
     */
    public function createBeer(Request $request): Response {
        $beer = new Beer();
        $form = $this->createForm(BeerFormType::class, $beer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($beer);
            $entityManager->flush();
            $this->flashSuccess("Created");
            $entityManager->refresh($beer);
            return $this->redirectToRoute('admin_shop_list_beer');
        }

        return $this->render('admin/shop/beer/create.html.twig', [
            'form' => $form->createView(),
            'beer' => $beer,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/bieres/modifier/{id}", name="admin_shop_edit_beer")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function editBeer(Request $request, string $id): Response {
        $beer = $this->beerRepository->find($id);
        $form = $this->createForm(BeerFormType::class, $beer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($beer);
            $entityManager->flush();
            $this->flashSuccess("Updated");
            $entityManager->refresh($beer);
            return $this->redirectToRoute('admin_shop_list_beer');
        }

        return $this->render('admin/shop/beer/edit.html.twig', [
            'form' => $form->createView(),
            'beer' => $beer,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/bieres/supprimer/{id}", name="admin_shop_delete_beer")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function deleteBeer(Request $request, string $id): Response {
        $beer = $this->beerRepository->find($id);
        $form = $this->createForm(ConfirmDeleteFormType::class, $beer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($beer);
            $entityManager->flush();
            $this->flashInfo("Deleted");
            return $this->redirectToRoute('admin_shop_list_beer');
        }

        return $this->render('admin/shop/beer/delete.html.twig', [
            'form' => $form->createView(),
            'beer' => $beer,
            'menu' => 'boutique'
        ]);
    }

}
