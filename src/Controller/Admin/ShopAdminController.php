<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Entity\Appellation;
use App\Entity\Region;
use App\Entity\Wine;
use App\Form\Admin\Shop\AppellationFormType;
use App\Form\Admin\Shop\RegionFormType;
use App\Form\Admin\Shop\WineFormType;
use App\Form\ConfirmDeleteFormType;
use App\Repository\AppellationRepository;
use App\Repository\RegionRepository;
use App\Repository\WineRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopAdminController extends AbstractController {

    /** @var PaginatorInterface */
    private $paginator;

    /** @var RegionRepository */
    private $regionRepository;

    /** @var AppellationRepository */
    private $appellationRepository;

    /** @var WineRepository */
    private $wineRepository;

    public function __construct(PaginatorInterface $paginator,
                                RegionRepository $regionRepository,
                                AppellationRepository $appellationRepository,
                                WineRepository $wineRepository) {
        $this->paginator = $paginator;
        $this->regionRepository = $regionRepository;
        $this->appellationRepository = $appellationRepository;
        $this->wineRepository = $wineRepository;
    }

    /* ********************************************************* */
    /* ****************** Gestion des Régions ****************** */
    /* ********************************************************* */

    /**
     * @Route("/admin/boutique/regions", name="admin_shop_list_region")
     * @return Response
     */
    public function listRegions(): Response {
        $regions = $this->regionRepository->findAllOrderByName();
        return $this->render('admin/shop/region/list.html.twig', [
            'regions' => $regions,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/regions/creer", name="admin_shop_add_region")
     * @param Request $request
     * @return Response
     */
    public function createRegion(Request $request): Response {
        $region = new Region();
        $form = $this->createForm(RegionFormType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($region);
            $entityManager->flush();
            $this->flashSuccess("Created");
            $entityManager->refresh($region);
            return $this->redirectToRoute('admin_shop_list_region');
        }

        return $this->render('admin/shop/region/create.html.twig', [
            'form' => $form->createView(),
            'region' => $region,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/regions/modifier/{id}", name="admin_shop_edit_region")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function editRegion(Request $request, string $id): Response {
        $region = $this->regionRepository->find($id);
        $form = $this->createForm(RegionFormType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($region);
            $entityManager->flush();
            $this->flashSuccess("Updated");
            $entityManager->refresh($region);
            return $this->redirectToRoute('admin_shop_list_region');
        }

        return $this->render('admin/shop/region/edit.html.twig', [
            'form' => $form->createView(),
            'region' => $region,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/regions/supprimer/{id}", name="admin_shop_delete_region")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function deleteRegion(Request $request, string $id): Response {
        $region = $this->regionRepository->find($id);
        $form = $this->createForm(ConfirmDeleteFormType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($region);
            $entityManager->flush();
            $this->flashInfo("Deleted");
            return $this->redirectToRoute('admin_shop_list_region');
        }

        return $this->render('admin/shop/region/delete.html.twig', [
            'form' => $form->createView(),
            'region' => $region,
            'menu' => 'boutique'
        ]);
    }

    /* ******************************************************** */
    /* *************** Gestion des Appellations *************** */
    /* ******************************************************** */

    /**
     * @Route("/admin/boutique/appellations", name="admin_shop_list_appellation")
     * @param Request $request
     * @return Response
     */
    public function listAppellations(Request $request): Response {
        $appellations = $this->paginator->paginate(
            $this->appellationRepository->findAllOrderByNameAndRegionQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/shop/appellation/list.html.twig', [
            'appellations' => $appellations,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/appellations/creer", name="admin_shop_add_appellation")
     * @param Request $request
     * @return Response
     */
    public function createAppellation(Request $request): Response {
        $appellation = new Appellation();
        $form = $this->createForm(AppellationFormType::class, $appellation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appellation);
            $entityManager->flush();
            $this->flashSuccess("Created");
            $entityManager->refresh($appellation);
            return $this->redirectToRoute('admin_shop_list_appellation');
        }

        return $this->render('admin/shop/appellation/create.html.twig', [
            'form' => $form->createView(),
            'appellation' => $appellation,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/appellations/modifier/{id}", name="admin_shop_edit_appellation")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function editAppellation(Request $request, string $id): Response {
        $appellation = $this->regionRepository->find($id);
        $form = $this->createForm(AppellationFormType::class, $appellation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appellation);
            $entityManager->flush();
            $this->flashSuccess("Updated");
            $entityManager->refresh($appellation);
            return $this->redirectToRoute('admin_shop_list_appellation');
        }

        return $this->render('admin/shop/appellation/edit.html.twig', [
            'form' => $form->createView(),
            'appellation' => $appellation,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/appellations/supprimer/{id}", name="admin_shop_delete_appellation")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function deleteAppellation(Request $request, string $id): Response {
        $appellation = $this->regionRepository->find($id);
        $form = $this->createForm(ConfirmDeleteFormType::class, $appellation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($appellation);
            $entityManager->flush();
            $this->flashInfo("Deleted");
            return $this->redirectToRoute('admin_shop_list_appellation');
        }

        return $this->render('admin/shop/appellation/delete.html.twig', [
            'form' => $form->createView(),
            'appellation' => $appellation,
            'menu' => 'boutique'
        ]);
    }

    /* ********************************************************** */
    /* ******************** Gestion des Vins ******************** */
    /* ********************************************************** */

    /**
     * @Route("/admin/boutique/vins", name="admin_shop_list_wine")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function listWine(PaginatorInterface $paginator, Request $request): Response {
        $allWine = $paginator->paginate(
            $this->wineRepository->findAllOrderByLastUpdateQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/shop/wine/list.html.twig', [
            'allWine' => $allWine,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/vins/creer", name="admin_shop_add_wine")
     * @param Request $request
     * @return Response
     */
    public function createWine(Request $request): Response {
        $wine = new Wine();
        $form = $this->createForm(WineFormType::class, $wine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wine);
            $entityManager->flush();
            $this->flashSuccess("Created");
            $entityManager->refresh($wine);
            return $this->redirectToRoute('admin_shop_list_wine');
        }

        return $this->render('admin/shop/wine/create.html.twig', [
            'form' => $form->createView(),
            'wine' => $wine,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/vins/modifier/{id}", name="admin_shop_edit_wine")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function editWine(Request $request, string $id): Response {
        $wine = $this->wineRepository->find($id);
        $form = $this->createForm(WineFormType::class, $wine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wine);
            $entityManager->flush();
            $this->flashSuccess("Updated");
            $entityManager->refresh($wine);
            return $this->redirectToRoute('admin_shop_list_wine');
        }

        return $this->render('admin/shop/wine/edit.html.twig', [
            'form' => $form->createView(),
            'wine' => $wine,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/vins/supprimer/{id}", name="admin_shop_delete_wine")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function deleteWine(Request $request, string $id): Response {
        $wine = $this->wineRepository->find($id);
        $form = $this->createForm(ConfirmDeleteFormType::class, $wine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wine);
            $entityManager->flush();
            $this->flashInfo("Deleted");
            return $this->redirectToRoute('admin_shop_list_wine');
        }

        return $this->render('admin/shop/wine/delete.html.twig', [
            'form' => $form->createView(),
            'wine' => $wine,
            'menu' => 'boutique'
        ]);
    }

}
