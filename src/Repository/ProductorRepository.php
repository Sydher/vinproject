<?php

namespace App\Repository;

use App\Entity\Productor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Productor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Productor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Productor[]    findAll()
 * @method Productor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductorRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Productor::class);
    }

    /**
     * @return Query
     */
    public function findAllOrderByNameQuery(): Query {
        return $this->createQueryBuilder('p')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery();
    }

}
