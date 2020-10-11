<?php

namespace App\Controller\Admin\Shop;

use App\Controller\AbstractController;
use App\Entity\Food;
use App\Form\Admin\Shop\FoodFormType;
use App\Form\ConfirmDeleteFormType;
use App\Repository\FoodRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class ShopFoodAdminController extends AbstractController {

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var FoodRepository
     */
    private $foodRepository;

    public function __construct(PaginatorInterface $paginator,
                                CacheInterface $cache,
                                FoodRepository $foodRepository) {
        $this->paginator = $paginator;
        $this->cache = $cache;
        $this->foodRepository = $foodRepository;
    }

    /**
     * @Route("/admin/boutique/alimentations", name="admin_shop_list_food")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response {
        $allFood = $paginator->paginate(
            $this->foodRepository->findAllOrderByLastUpdateQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/shop/food/list.html.twig', [
            'allFood' => $allFood,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/alimentations/creer", name="admin_shop_add_food")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response {
        $food = new Food();
        $form = $this->createForm(FoodFormType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($food);
            $entityManager->flush();
            $this->flashSuccess("Created");
            $entityManager->refresh($food);
            return $this->redirectToRoute('admin_shop_list_food');
        }

        return $this->render('admin/shop/food/create.html.twig', [
            'form' => $form->createView(),
            'food' => $food,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/alimentations/modifier/{id}", name="admin_shop_edit_food")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function edit(Request $request, string $id): Response {
        $food = $this->foodRepository->find($id);
        $form = $this->createForm(FoodFormType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($food);
            $entityManager->flush();
            $this->flashSuccess("Updated");
            $entityManager->refresh($food);
            return $this->redirectToRoute('admin_shop_list_food');
        }

        return $this->render('admin/shop/food/edit.html.twig', [
            'form' => $form->createView(),
            'food' => $food,
            'menu' => 'boutique'
        ]);
    }

    /**
     * @Route("/admin/boutique/alimentations/supprimer/{id}", name="admin_shop_delete_food")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function delete(Request $request, string $id): Response {
        $food = $this->foodRepository->find($id);
        $form = $this->createForm(ConfirmDeleteFormType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($food);
            $entityManager->flush();
            $this->flashInfo("Deleted");
            return $this->redirectToRoute('admin_shop_list_food');
        }

        return $this->render('admin/shop/food/delete.html.twig', [
            'form' => $form->createView(),
            'food' => $food,
            'menu' => 'boutique'
        ]);
    }

}
