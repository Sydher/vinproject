<?php

namespace App\Repository;

use App\Entity\About;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method About|null find($id, $lockMode = null, $lockVersion = null)
 * @method About|null findOneBy(array $criteria, array $orderBy = null)
 * @method About[]    findAll()
 * @method About[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AboutRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, About::class);
    }

    /**
     * @return Query
     */
    public function findAllOrderByLastUpdateQuery(): Query {
        return $this->createQueryBuilder('a')
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery();
    }

    public function findValue(String $name) {
        return $this->findOneBy(array('name' => $name));
    }

}
