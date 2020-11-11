<?php

namespace App\Repository\User;

use App\Entity\User\BannedDomain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BannedDomain|null find($id, $lockMode = null, $lockVersion = null)
 * @method BannedDomain|null findOneBy(array $criteria, array $orderBy = null)
 * @method BannedDomain[]    findAll()
 * @method BannedDomain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannedDomainRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, BannedDomain::class);
    }

}
