<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Entity\Region;
use App\Form\Admin\RegionFormType;
use App\Form\ConfirmDeleteFormType;
use App\Repository\RegionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopAdminController extends AbstractController {

    /** @var RegionRepository */
    private $regionRepository;

    public function __construct(RegionRepository $regionRepository) {
        $this->regionRepository = $regionRepository;
    }

    /**
     * @Route("/admin/boutique/regions", name="admin_shop_list_region")
     * @return Response
     */
    public function listRegions(): Response {
        $regions = $this->regionRepository->findAllOrderByName();
        return $this->render('admin/shop/list.html.twig', [
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

        return $this->render('admin/shop/create.html.twig', [
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

        return $this->render('admin/shop/edit.html.twig', [
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

        return $this->render('admin/shop/delete.html.twig', [
            'form' => $form->createView(),
            'region' => $region,
            'menu' => 'boutique'
        ]);
    }

}
