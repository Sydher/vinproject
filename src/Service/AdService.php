<?php

namespace App\Service;

use App\Repository\AboutRepository;
use Psr\Cache\InvalidArgumentException as InvalidArgumentExceptionAlias;
use Symfony\Contracts\Cache\CacheInterface;

class AdService {

    /**
     * @var AboutRepository
     */
    private $aboutRepository;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(AboutRepository $aboutRepository,
                                CacheInterface $cache) {
        $this->aboutRepository = $aboutRepository;
        $this->cache = $cache;
    }

    /**
     * Vérifie si l'annonce spéciale est activée.
     * @return bool
     * @throws InvalidArgumentExceptionAlias
     */
    public function isSpecialAdOn(): bool {
        return $this->cache->get('specialAdOn', function() {
            $data = $this->aboutRepository->findValue('specialAdOn');
            return $data != null && $data->getValue() == "ON";
        });
    }

    /**
     * Récupère le message de l'annonce spéciale.
     * @return string
     * @throws InvalidArgumentExceptionAlias
     */
    public function getSpecialAdMessage(): string {
        return $this->cache->get('specialAdMessage', function() {
            return $this->aboutRepository->findValue('specialAdMessage')->getValue();
        });
    }

}