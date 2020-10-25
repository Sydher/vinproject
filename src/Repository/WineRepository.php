<?php

namespace App\Repository;

use App\Entity\SearchProduct;
use App\Entity\Wine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Wine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wine[]    findAll()
 * @method Wine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WineRepository extends ServiceEntityRepository {

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator) {
        parent::__construct($registry, Wine::class);
        $this->paginator = $paginator;
    }

    /**
     * @return Query
     */
    public function findAllOrderByLastUpdateQuery(): Query {
        return $this->createQueryBuilder('w')
            ->orderBy('w.updatedAt', 'DESC')
            ->getQuery();
    }

    /**
     * @param SearchProduct $search
     * @return PaginationInterface
     */
    public function findSearch(SearchProduct $search): PaginationInterface {
        $results = $this->getSearchQuery($search)->getQuery();
        return $this->paginator->paginate($results, $search->page, 10);
    }

    /**
     * Récupère le prix minimum et maximum correspondant à la recherche.
     * @param SearchProduct $search
     * @return integer[]
     */
    public function findMinMax(SearchProduct $search): array {
        $results = $this->getSearchQuery($search, true)
            ->select('MIN(w.price) as min', 'MAX(w.price) as max')
            ->getQuery()
            ->getScalarResult();
        return [(int)$results[0]['min'], (int)$results[0]['max']];
    }

    /**
     * @param SearchProduct $search
     * @param bool $ignorePrice
     * @return QueryBuilder
     */
    private function getSearchQuery(SearchProduct $search, $ignorePrice = false): QueryBuilder {
        $query = $this->createQueryBuilder('w')
            ->select('w, a, p')
            ->join('w.appellation', 'a')
            ->join('w.productor', 'p');

        if (!empty($search->name)) {
            $query = $query->andWhere('w.name LIKE :name')
                ->setParameter('name', "%{$search->name}%");
        }

        if (!empty($search->min) && !$ignorePrice) {
            $query = $query->andWhere('w.price >= :min')
                ->setParameter('min', $search->min);
        }

        if (!empty($search->max) && !$ignorePrice) {
            $query = $query->andWhere('w.price <= :max')
                ->setParameter('max', $search->max);
        }

        if (!empty($search->appellation) && count($search->appellation) > 0) {
            $query = $query->andWhere('a.id IN (:app)')
                ->setParameter('app', $search->appellation);
        }

        if (!empty($search->productor) && count($search->productor) > 0) {
            $query = $query->andWhere('p.id IN (:prod)')
                ->setParameter('prod', $search->productor);
        }

        return $query;
    }

}
