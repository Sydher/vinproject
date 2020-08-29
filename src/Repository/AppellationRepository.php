<?php

namespace App\Repository;

use App\Entity\Appellation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Appellation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appellation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appellation[]    findAll()
 * @method Appellation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppellationRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Appellation::class);
    }

    /**
     * @return Query
     */
    public function findAllOrderByNameAndRegionQuery(): Query {
        return $this->createQueryBuilder('a')
            ->addOrderBy('a.region', 'ASC')
            ->addOrderBy('a.name', 'ASC')
            ->getQuery();
    }

}
