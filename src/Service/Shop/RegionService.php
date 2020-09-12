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

    public function __construct(RegionRepository $regionRepository, CacheInterface $cache) {
        $this->regionRepository = $regionRepository;
        $this->cache = $cache;
    }

    /**
     * @return Region[]
     * @throws InvalidArgumentException
     */
    public function getAllRegions(): array {
        return $this->cache->get('allRegion', function() {
            return $this->regionRepository->findAllOrderByName();
        });
    }

    /**
     * @return Region[]
     */
    public function getAllRegionsWithoutCache(): array {
        return $this->regionRepository->findAllOrderByName();
    }

}