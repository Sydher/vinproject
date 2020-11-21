<?php

namespace App\Repository;

use App\Entity\Beer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Beer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beer[]    findAll()
 * @method Beer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeerRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Beer::class);
    }

    /**
     * @return Query
     */
    public function findAllQuery(): Query {
        return $this->createQueryBuilder('b')
            ->orderBy('b.name', 'ASC')
            ->getQuery();
    }

    /**
     * @return Query
     */
    public function findAllOrderByLastUpdateQuery(): Query {
        return $this->createQueryBuilder('b')
            ->orderBy('b.updatedAt', 'DESC')
            ->getQuery();
    }

}
