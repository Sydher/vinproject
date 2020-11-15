<?php

namespace App\Controller;

use App\Repository\WineRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class HomeController extends AbstractController {

    /**
     * @var WineRepository
     */
    private $wineRepository;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(WineRepository $wineRepository, CacheInterface $cache) {
        $this->wineRepository = $wineRepository;
        $this->cache = $cache;
    }

    /**
     * @Route("/", name="home")
     * @return Response
     * @throws InvalidArgumentException
     */
    public function index(): Response {
        $newWines = $this->cache->get('last3Created', function() {
            return $this->wineRepository->findLastCreated(3);
        });
        $best1 = $this->cache->get('bestWine1', function() {
            return $this->wineRepository->findByIdJoined(2);
        });
        $best2 = $this->cache->get('bestWine2', function() {
            return $this->wineRepository->findByIdJoined(12);
        });
        $best3 = $this->cache->get('bestWine3', function() {
            return $this->wineRepository->findByIdJoined(6);
        });
        return $this->render('pages/index.html.twig', [
            'controller_name' => 'HomeController',
            'menu' => 'accueil',
            'newWines' => $newWines,
            'best1' => $best1,
            'best2' => $best2,
            'best3' => $best3
        ]);
    }

}
