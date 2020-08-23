<?php

namespace App\Controller;

use App\Repository\RegionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController {

    /** @var RegionRepository */
    private $regionRepository;

    public function __construct(RegionRepository $regionRepository) {
        $this->regionRepository = $regionRepository;
    }

    /**
     * @Route("/boutique", name="shop_home")
     * @return Response
     */
    public function index(): Response {
        $regions = $this->regionRepository->findAll();
        return $this->render('shop/index.html.twig', [
            'regions' => $regions,
            'menu' => 'boutique'
        ]);
    }

    // TODO vue par région

    // TODO vue toutes les bouteilles

//
//    /**
//     * @Route("/blog/{slug}-{id}", name="blog_show_post", requirements={"slug": "[a-z0-9\-]*"})
//     * @param Securizer $securizer
//     * @param string $slug
//     * @param string $id
//     * @return Response
//     */
//    public function show(Securizer $securizer, string $slug, string $id): Response {
//        $post = $this->postRepository->find($id);
//
//        if ($post->getIsVisible()
//            || $securizer->isGranted($this->getUser(), 'ROLE_ADMIN')) {
//
//            return $this->render('blog/show.html.twig', [
//                'post' => $post,
//                'menu' => 'blog'
//            ]);
//        } else {
//            return $this->redirectToRoute('blog_list');
//        }
//    }

}
