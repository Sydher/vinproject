<?php

namespace App\Service\Shop;

use App\Entity\Region;
use App\Repository\RegionRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;

class RegionService {

    /**
     * @var RegionRepository
     */
    private $regionRepository;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(RegionRepository $regionRepository,
                                CacheInterface $cache) {
        $this->regionRepository = $regionRepository;
        $this->cache = $cache;
    }

    /**
     * Récupère la liste des régions (chargée depuis le cache).
     * @return Region[]
     * @throws InvalidArgumentException
     */
    public function getAll(): array {
        return $this->cache->get('allRegion', function() {
            return $this->regionRepository->findAllOrderByName();
        });
    }

}
