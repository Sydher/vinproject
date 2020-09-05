<?php

namespace App\Service\Shop;

use App\Entity\Region;
use App\Repository\RegionRepository;

class RegionService {

    /**
     * @var RegionRepository
     */
    private $regionRepository;

    public function __construct(RegionRepository $regionRepository) {
        $this->regionRepository = $regionRepository;
    }

    /**
     * @return Region[]
     */
    public function getAllRegions(): array {
        return $this->regionRepository->findAllOrderByName();
    }

}